// Funcionalidad de sedes - Acordeón
// Optimización: ejecutar inmediatamente si el DOM ya está listo
(function () {
    // Variables globales para el scope de la función
    let cursadasContainer = null;
    let loadingIndicator = null;
    // Construir URL de forma más robusta
    const buscarDescuentoUrl = (function () {
        try {
            const baseUrl = window.location.origin;
            return baseUrl + '/buscar-descuento';
        } catch (e) {
            return window.inscripcionConfig.buscarDescuentoUrl;
        }
    })();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || window.inscripcionConfig.csrfToken;

    // Función para mostrar notificaciones personalizadas
    function mostrarNotificacion(mensaje, tipo = 'error') {
        const container = document.getElementById('notificaciones-container');
        if (!container) return;

        const notificacion = document.createElement('div');
        notificacion.className = `cursada-notificacion cursada-notificacion-${tipo}`;

        const icono = tipo === 'success'
            ? '<svg class="cursada-notificacion-icono" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            : '<svg class="cursada-notificacion-icono" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';

        notificacion.innerHTML = `
                    ${icono}
                    <span class="cursada-notificacion-texto">${mensaje}</span>
                `;

        container.appendChild(notificacion);

        // Trigger animation
        setTimeout(() => {
            notificacion.classList.add('cursada-notificacion-visible');
        }, 10);

        // Auto-remover después de 4 segundos
        setTimeout(() => {
            notificacion.classList.remove('cursada-notificacion-visible');
            setTimeout(() => {
                if (notificacion.parentNode) {
                    notificacion.parentNode.removeChild(notificacion);
                }
            }, 300);
        }, 4000);
    }

    // Función Helper para reCAPTCHA
    function executeRecaptcha() {
        return new Promise((resolve) => {
            const siteKey = window.inscripcionConfig.recaptchaSiteKey;
            if (!siteKey || typeof grecaptcha === 'undefined') {
                console.warn('reCAPTCHA no configurado o no cargado');
                resolve(null); // Si no hay key o script, continuar sin token
                return;
            }
            try {
                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, { action: 'submit' }).then(function (token) {
                        resolve(token);
                    }).catch(err => {
                        console.error('reCAPTCHA Error:', err);
                        resolve(null);
                    });
                });
            } catch (e) {
                console.error('reCAPTCHA Exception:', e);
                resolve(null);
            }
        });
    }

    // Función para inicializar la carga de cursadas
    function init() {
        // Cargar cursadas vía AJAX
        cursadasContainer = document.getElementById('cursadas-container');
        loadingIndicator = document.getElementById('cursadas-loading');
        const cursoId = window.inscripcionConfig.cursoId;

        if (cursadasContainer && loadingIndicator) {
            // Iniciar fetch inmediatamente
            fetch(`/api/inscripcion/${cursoId}/cursadas`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.cursadas) {
                        loadingIndicator.remove();
                        // renderCursadas llamará a initializeFiltering cuando termine de renderizar todas las cursadas
                        renderCursadas(data.cursadas, data.promoBadge);
                    } else {
                        loadingIndicator.textContent = 'Error al cargar las cursadas';
                    }
                })
                .catch(error => {
                    loadingIndicator.textContent = 'Error al cargar las cursadas';
                });
        }
    }

    // Ejecutar inmediatamente si el DOM ya está listo, sino esperar
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Variable global para promoBadgeInfo
    let globalPromoBadgeInfo = null;

    // Constante para sessionStorage
    const STORAGE_KEY = 'inscripcion_form_data';

    // Funciones necesarias para restaurar el estado - definidas temprano
    function cargarDatosFormulario(cursadaId, intentos = 0) {
        try {
            const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
            if (!datosGuardados) {
                return;
            }

            const datos = JSON.parse(datosGuardados);

            // Buscar formulario, primero en el modal si está abierto, luego en el DOM normal
            const modalOverlay = document.getElementById('cursada-modal-overlay');
            const modalBody = document.getElementById('cursada-modal-body');
            const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

            let formulario = null;
            if (isInModal) {
                formulario = modalBody.querySelector('#formulario-' + cursadaId) || modalBody.querySelector('form.cursada-formulario');
            }
            if (!formulario) {
                formulario = document.getElementById('formulario-' + cursadaId);
            }

            if (!formulario) {
                // Si el formulario no existe aún, intentar de nuevo (máximo 5 intentos)
                if (intentos < 5) {
                    setTimeout(() => cargarDatosFormulario(cursadaId, intentos + 1), 100);
                }
                return;
            }

            // Verificar si el formulario ya está deshabilitado (ya se envió)
            // Pero permitir cargar datos incluso si está readonly, ya que todos los formularios
            // quedan readonly después de completar uno
            const inputs = formulario.querySelectorAll('input, select');
            // No verificar si está disabled, solo cargar los datos

            // Buscar inputs por name primero (más confiable cuando se clona), luego por ID como fallback
            let nombre = formulario.querySelector('input[name="nombre"]');
            let apellido = formulario.querySelector('input[name="apellido"]');
            let dni = formulario.querySelector('input[name="dni"]');
            let correo = formulario.querySelector('input[name="correo"]');
            let telefono = formulario.querySelector('input[name="telefono"]');
            let telefonoPrefijo = formulario.querySelector('select[name="telefono_prefijo"]');

            // Si no se encuentran por name, intentar por ID (para compatibilidad)
            if (!nombre) nombre = formulario.querySelector('#nombre-' + cursadaId);
            if (!apellido) apellido = formulario.querySelector('#apellido-' + cursadaId);
            if (!dni) dni = formulario.querySelector('#dni-' + cursadaId);
            if (!correo) correo = formulario.querySelector('#correo-' + cursadaId);
            if (!telefono) telefono = formulario.querySelector('#telefono-' + cursadaId);
            if (!telefonoPrefijo) telefonoPrefijo = formulario.querySelector('#telefono-prefijo-' + cursadaId);

            // Cargar los datos guardados en los campos (siempre, incluso si ya tienen valor)
            // Esto asegura que los datos se repliquen en todos los formularios
            let datosCargados = false;
            if (nombre && datos.nombre) {
                nombre.value = datos.nombre;
                datosCargados = true;
            }
            if (apellido && datos.apellido) {
                apellido.value = datos.apellido;
                datosCargados = true;
            }
            if (dni && datos.dni) {
                dni.value = datos.dni;
                datosCargados = true;
            }
            if (correo && datos.correo) {
                correo.value = datos.correo;
                datosCargados = true;
            }
            if (telefono && datos.telefono) {
                telefono.value = datos.telefono;
                datosCargados = true;
            }
            if (telefonoPrefijo && datos.telefonoPrefijo) {
                telefonoPrefijo.value = datos.telefonoPrefijo;
                datosCargados = true;
            }

            // Restaurar leadId en el panel si existe
            if (datos.leadId) {
                let panel = document.getElementById('panel-' + cursadaId);
                if (!panel) {
                    if (cursadaId.includes('cursada-')) {
                        panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                    } else {
                        panel = document.getElementById('panel-cursada-' + cursadaId);
                    }
                }
                if (panel) {
                    panel.setAttribute('data-lead-id', datos.leadId);
                }
            }


            // Actualizar el estado del botón después de cargar
            // Pero solo si el formulario no está completado (no está readonly)
            const isDisabled = Array.from(inputs).some(input => input.hasAttribute('readonly'));
            if (!isDisabled) {
                // actualizarEstadoBotonContinuar se definirá más tarde, pero no es crítico aquí
                if (typeof actualizarEstadoBotonContinuar === 'function') {
                    actualizarEstadoBotonContinuar(cursadaId);
                }
            }
        } catch (error) {
        }
    }

    // Función para guardar datos del formulario en localStorage (definida temprano para que esté disponible en el modal)
    function guardarDatosFormulario(cursadaId) {
        try {
            // Buscar formulario, primero en el modal si está abierto, luego en el DOM normal
            const modalOverlay = document.getElementById('cursada-modal-overlay');
            const modalBody = document.getElementById('cursada-modal-body');
            const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

            let formulario = null;
            if (isInModal) {
                formulario = modalBody.querySelector('#formulario-' + cursadaId) || modalBody.querySelector('form.cursada-formulario');
            }
            if (!formulario) {
                formulario = document.getElementById('formulario-' + cursadaId);
            }

            if (!formulario) {
                return;
            }

            // Buscar inputs por name para evitar problemas con IDs duplicados
            const nombre = formulario.querySelector('input[name="nombre"]');
            const apellido = formulario.querySelector('input[name="apellido"]');
            const dni = formulario.querySelector('input[name="dni"]');
            const correo = formulario.querySelector('input[name="correo"]');
            const telefono = formulario.querySelector('input[name="telefono"]');
            const telefonoPrefijo = formulario.querySelector('select[name="telefono_prefijo"]');

            // Obtener leadId del panel si existe
            let panel = document.getElementById('panel-' + cursadaId);
            if (!panel) {
                if (cursadaId.includes('cursada-')) {
                    panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                } else {
                    panel = document.getElementById('panel-cursada-' + cursadaId);
                }
            }

            // Prioridad: 1. Panel, 2. SessionStorage
            let leadId = panel ? panel.getAttribute('data-lead-id') : null;

            if (!leadId) {
                try {
                    const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
                    if (datosGuardados) {
                        const datos = JSON.parse(datosGuardados);
                        if (datos.leadId) {
                            leadId = datos.leadId;
                        }
                    }
                } catch (e) { }
            }

            const datos = {
                nombre: nombre?.value?.trim() || '',
                apellido: apellido?.value?.trim() || '',
                dni: dni?.value?.trim() || '',
                correo: correo?.value?.trim() || '',
                telefono: telefono?.value?.trim() || '',
                telefonoPrefijo: telefonoPrefijo?.value || '+54',
                leadId: leadId
            };

            // Guardar los datos en sessionStorage
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(datos));
        } catch (error) {
            console.error('guardarDatosFormulario: Error', error);
        }
    }

    function mostrarValoresEnFormulario(formCursadaId) {
        // Verificar si estamos dentro del modal
        const modalOverlay = document.getElementById('cursada-modal-overlay');
        const modalBody = document.getElementById('cursada-modal-body');
        const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

        // Buscar elementos, primero en el modal si estamos ahí, luego en el DOM normal
        let formCuotaInfo = null;
        let formPanel = null;

        if (isInModal) {
            formCuotaInfo = modalBody.querySelector('#cuota-info-' + formCursadaId);
            formPanel = modalBody.querySelector('[data-promo-mat-logo]');
        }

        // Si no se encontraron en el modal, buscar en el DOM normal
        if (!formCuotaInfo) formCuotaInfo = document.getElementById('cuota-info-' + formCursadaId);
        if (!formPanel) formPanel = document.getElementById('panel-' + formCursadaId);

        if (!formCuotaInfo) return;

        // Mostrar sección de información de cuota (valores que estaban ocultos)
        formCuotaInfo.style.display = 'block';
        formCuotaInfo.style.visibility = 'visible';
        formCuotaInfo.style.opacity = '1';
        formCuotaInfo.style.height = 'auto';
        formCuotaInfo.removeAttribute('hidden');
        formCuotaInfo.classList.remove('hidden');
        formCuotaInfo.classList.add('visible');

        // Buscar y habilitar elementos, primero en el modal si estamos ahí
        let linkCodigo = null;
        let checkboxTerminos = null;
        let labelCheckbox = null;
        let linkVer = null;

        if (isInModal) {
            linkCodigo = modalBody.querySelector('#link-codigo-modal-' + formCursadaId) || modalBody.querySelector('#link-codigo-' + formCursadaId);
            checkboxTerminos = modalBody.querySelector('#acepto-terminos-modal-' + formCursadaId) || modalBody.querySelector('#acepto-terminos-' + formCursadaId);
            labelCheckbox = modalBody.querySelector('label[for="acepto-terminos-modal-' + formCursadaId + '"]') || modalBody.querySelector('label[for="acepto-terminos-' + formCursadaId + '"]');
            linkVer = modalBody.querySelector('#link-ver-modal-' + formCursadaId) || modalBody.querySelector('#link-ver-' + formCursadaId);
        }

        // Si no se encontraron en el modal, buscar en el DOM normal
        if (!linkCodigo) linkCodigo = document.getElementById('link-codigo-modal-' + formCursadaId) || document.getElementById('link-codigo-' + formCursadaId);
        if (!checkboxTerminos) checkboxTerminos = document.getElementById('acepto-terminos-modal-' + formCursadaId) || document.getElementById('acepto-terminos-' + formCursadaId);
        if (!labelCheckbox) labelCheckbox = document.querySelector('label[for="acepto-terminos-modal-' + formCursadaId + '"]') || document.querySelector('label[for="acepto-terminos-' + formCursadaId + '"]');
        if (!linkVer) linkVer = document.getElementById('link-ver-modal-' + formCursadaId) || document.getElementById('link-ver-' + formCursadaId);

        // Habilitar elementos que estaban deshabilitados
        if (linkCodigo) {
            linkCodigo.classList.remove('cursada-link-disabled');
        }
        if (checkboxTerminos) {
            checkboxTerminos.disabled = false;
        }
        if (labelCheckbox) {
            labelCheckbox.classList.remove('cursada-checkbox-disabled');
        }
        if (linkVer) {
            linkVer.classList.remove('cursada-link-disabled');
        }

        // Obtener valores de los data attributes del cuotaInfo
        const matricBase = parseFloat(formCuotaInfo.getAttribute('data-matric-base') || 0);
        const sinIvaMat = parseFloat(formCuotaInfo.getAttribute('data-sin-iva-mat') || 0);

        // Buscar elementos para actualizar valores, primero en el modal si estamos ahí
        let valorMatricula = null;
        let precioTotalMatricula = null;

        if (isInModal) {
            valorMatricula = modalBody.querySelector('#valor-matricula-' + formCursadaId);
            precioTotalMatricula = modalBody.querySelector('#precio-total-matricula-' + formCursadaId);
        }

        // Si no se encontraron en el modal, buscar en el DOM normal
        if (!valorMatricula) valorMatricula = document.getElementById('valor-matricula-' + formCursadaId);
        if (!precioTotalMatricula) precioTotalMatricula = document.getElementById('precio-total-matricula-' + formCursadaId);

        // Actualizar valor de matrícula
        if (valorMatricula) {
            if (matricBase > 0) {
                const valorFormateado = matricBase.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                valorMatricula.textContent = '$' + valorFormateado;
                // Guardar el valor numérico en un data attribute para uso posterior
                valorMatricula.setAttribute('data-valor-numerico', matricBase);
            } else {
                valorMatricula.textContent = 'n/d';
                valorMatricula.removeAttribute('data-valor-numerico');
            }
        }

        // Actualizar precio total de matrícula sin impuestos
        if (precioTotalMatricula) {
            if (sinIvaMat > 0) {
                const precioFormateado = sinIvaMat.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                precioTotalMatricula.textContent = '$' + precioFormateado;
            } else {
                precioTotalMatricula.textContent = 'n/d';
            }
        }

        // Actualizar descuento y total directamente
        // Función auxiliar para actualizar valores de descuento y total
        const actualizarDescuentoYTotal = () => {
            // Buscar elementos, primero en el modal si estamos ahí
            let descuentoAplicado = null;
            let totalAplicado = null;

            if (isInModal) {
                descuentoAplicado = modalBody.querySelector('#descuento-aplicado-' + formCursadaId);
                totalAplicado = modalBody.querySelector('#total-aplicado-' + formCursadaId);
            }

            // Si no se encontraron en el modal, buscar en el DOM normal
            if (!descuentoAplicado) descuentoAplicado = document.getElementById('descuento-aplicado-' + formCursadaId);
            if (!totalAplicado) totalAplicado = document.getElementById('total-aplicado-' + formCursadaId);

            const valorDescuento = descuentoAplicado ? descuentoAplicado.querySelector('.cursada-descuento-valor') : null;
            const valorTotal = totalAplicado ? totalAplicado.querySelector('.cursada-total-valor') : null;

            // Verificar si hay un descuento aplicado (si el descuento no es "n/d" ni "$0,00" y comienza con "-")
            const descuentoText = valorDescuento ? valorDescuento.textContent.trim() : '';
            const tieneDescuentoAplicado = descuentoText && descuentoText !== 'n/d' && descuentoText !== '$0,00' && descuentoText.startsWith('-');

            // Si no hay descuento aplicado, establecer valores iniciales
            if (!tieneDescuentoAplicado) {
                // Establecer descuento en $0,00
                if (valorDescuento) {
                    valorDescuento.textContent = '$0,00';
                }

                // Establecer total igual a matrícula base (con impuestos)
                if (valorTotal && matricBase > 0) {
                    const totalFormateado = matricBase.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    valorTotal.textContent = '$' + totalFormateado;
                } else if (valorTotal) {
                    valorTotal.textContent = 'n/d';
                }
            } else {
                // Si hay descuento aplicado, usar la función de inicialización para recalcular
                if (typeof inicializarValoresDescuento === 'function') {
                    inicializarValoresDescuento(formCursadaId);
                }
            }
        };

        // Intentar actualizar inmediatamente
        actualizarDescuentoYTotal();

        // Si los elementos no estaban disponibles, intentar de nuevo después de un breve delay
        setTimeout(() => {
            const valorDescuento = document.querySelector('#descuento-aplicado-' + formCursadaId + ' .cursada-descuento-valor');
            const valorTotal = document.querySelector('#total-aplicado-' + formCursadaId + ' .cursada-total-valor');

            // Si aún muestran "n/d" y tenemos el valor de matrícula base, actualizar
            if (valorDescuento && valorDescuento.textContent.trim() === 'n/d' && matricBase > 0) {
                actualizarDescuentoYTotal();
            }
            if (valorTotal && valorTotal.textContent.trim() === 'n/d' && matricBase > 0) {
                actualizarDescuentoYTotal();
            }
        }, 200);
    }

    // Función para verificar si hay datos completos guardados y restaurar el estado
    // Definida temprano para que esté disponible cuando se necesite
    function verificarYRestaurarEstadoCompletado() {
        try {
            const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
            if (!datosGuardados) {
                return false;
            }

            const datos = JSON.parse(datosGuardados);

            // Verificar si todos los campos tienen datos (formulario completo)
            const formularioCompleto = datos.nombre && datos.apellido && datos.dni &&
                datos.correo && datos.telefono;

            if (!formularioCompleto) {
                return false;
            }

            // Si el formulario está completo, restaurar el estado
            // Buscar formularios en DOM normal y también en modal
            const modalBody = document.getElementById('cursada-modal-body');
            const todosLosFormularios = [];

            // Agregar formularios del DOM normal
            document.querySelectorAll('.cursada-formulario').forEach(form => {
                todosLosFormularios.push(form);
            });

            // Agregar formularios del modal si existe
            if (modalBody) {
                modalBody.querySelectorAll('.cursada-formulario').forEach(form => {
                    if (!todosLosFormularios.includes(form)) {
                        todosLosFormularios.push(form);
                    }
                });
            }

            if (todosLosFormularios.length === 0) {
                return false;
            }

            todosLosFormularios.forEach(formulario => {
                const formularioId = formulario.getAttribute('id');
                if (formularioId) {
                    const cursadaId = formularioId.replace('formulario-', '');

                    // Desactivar todos los formularios
                    const inputs = formulario.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.setAttribute('readonly', 'readonly');
                        input.setAttribute('disabled', 'disabled');
                        input.style.pointerEvents = 'none';
                        input.style.opacity = '0.6';
                        input.style.cursor = 'not-allowed';
                    });

                    // Desactivar todos los botones continuar (buscar en modal y DOM normal)
                    let botonesContinuar = [];
                    if (modalBody) {
                        // Buscar en el modal primero
                        modalBody.querySelectorAll('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]').forEach(b => {
                            if (!botonesContinuar.includes(b)) botonesContinuar.push(b);
                        });
                        // Si no se encontró por data-cursada-id, buscar cualquier botón continuar en el modal
                        if (botonesContinuar.length === 0) {
                            modalBody.querySelectorAll('.cursada-btn-continuar').forEach(b => {
                                if (!botonesContinuar.includes(b)) botonesContinuar.push(b);
                            });
                        }
                    }
                    // Buscar en el DOM normal
                    document.querySelectorAll('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]').forEach(b => {
                        if (!botonesContinuar.includes(b)) botonesContinuar.push(b);
                    });

                    botonesContinuar.forEach(botonContinuar => {
                        botonContinuar.classList.remove('activo');
                        botonContinuar.disabled = true;
                        botonContinuar.style.opacity = '0.6';
                        botonContinuar.style.cursor = 'not-allowed';
                    });

                    // Mostrar botón editar
                    const btnEditar = document.getElementById('editar-form-' + cursadaId);
                    if (btnEditar) {
                        btnEditar.style.display = 'inline';
                    }
                }
            });

            // Cargar datos y mostrar valores inmediatamente (las funciones ya están definidas)
            todosLosFormularios.forEach(formulario => {
                const formularioId = formulario.getAttribute('id');
                if (formularioId) {
                    const cursadaId = formularioId.replace('formulario-', '');
                    cargarDatosFormulario(cursadaId);
                    mostrarValoresEnFormulario(cursadaId);
                }
            });

            return true;
        } catch (error) {
            return false;
        }
    }
    // Variable global para almacenar las cursadas originales
    let globalCursadas = null;
    // Variable para rastrear si el botón de ordenar ya fue inicializado
    let ordenarBtnInicializado = false;
    // Variable para rastrear el estado del ordenamiento: true = descendente (mayor primero), false = ascendente (menor primero)
    let ordenDescendente = true;
    // Variable para rastrear si los event listeners de formularios ya fueron inicializados
    let formsEventListenersInicializados = false;
    // Función para cerrar todos los paneles excepto uno
    function cerrarTodosLosPaneles(excluirCursadaId = null) {
        document.querySelectorAll('.cursada-valores-panel').forEach(panel => {
            const panelId = panel.getAttribute('id');
            const cursadaId = panelId.replace('panel-', '');

            if (cursadaId !== excluirCursadaId) {
                panel.classList.remove('panel-visible');
                panel.classList.add('panel-hidden');

                // Remover estado desplegado del botón (usar querySelectorAll para encontrar todos los botones)
                document.querySelectorAll('.cursada-btn-ver-valores[data-cursada-id="' + cursadaId + '"]').forEach(botonVerValores => {
                    botonVerValores.classList.remove('panel-desplegado');
                });

                const infoTexto = document.getElementById('info-' + cursadaId);
                if (infoTexto) {
                    infoTexto.classList.remove('panel-visible');
                    infoTexto.classList.add('panel-hidden');
                }

                const formulario = document.getElementById('formulario-' + cursadaId);
                if (formulario) {
                    // Solo actualizar tabindex, NO borrar los datos
                    // Los datos se mantienen y se cargarán desde sessionStorage cuando se vuelva a abrir
                    const inputs = formulario.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.setAttribute('tabindex', '-1');
                        // NO borrar el valor - se mantiene para reutilización
                    });
                }

                // Limpiar errores de validación
                const errorElements = document.querySelectorAll('#formulario-' + cursadaId + ' .cursada-formulario-error');
                const inputElements = document.querySelectorAll('#formulario-' + cursadaId + ' .cursada-formulario-input');
                errorElements.forEach(error => {
                    error.classList.remove('show');
                    error.textContent = '';
                });
                inputElements.forEach(input => {
                    input.classList.remove('error');
                });
            }
        });
    }

    // Función para inicializar el panel después del scroll
    function inicializarPanelDespuesDeScroll(cursadaId, panel, infoTexto, formulario, boton) {
        // Inicializar valores de descuento cuando se abre el panel
        // Asegurar que muestren "n/d" si el formulario no está validado
        setTimeout(() => {
            if (typeof inicializarValoresDescuento === 'function') {
                inicializarValoresDescuento(cursadaId);
            }
        }, 0);
        // Habilitar tabindex de los inputs cuando el panel se muestra
        if (formulario) {
            const nombre = formulario.querySelector('#nombre-' + cursadaId);
            const apellido = formulario.querySelector('#apellido-' + cursadaId);
            const dni = formulario.querySelector('#dni-' + cursadaId);
            const correo = formulario.querySelector('#correo-' + cursadaId);
            const telefono = formulario.querySelector('#telefono-' + cursadaId);

            if (nombre) nombre.setAttribute('tabindex', '1');
            if (apellido) apellido.setAttribute('tabindex', '2');
            if (dni) dni.setAttribute('tabindex', '3');
            if (correo) correo.setAttribute('tabindex', '4');
            const telefonoPrefijo = formulario.querySelector('#telefono-prefijo-' + cursadaId);
            if (telefonoPrefijo) telefonoPrefijo.setAttribute('tabindex', '5');
            if (telefono) telefono.setAttribute('tabindex', '6');

            // Cargar datos guardados cuando se abre el panel
            // Usar múltiples intentos para asegurar que se carguen los datos
            setTimeout(() => {
                if (typeof cargarDatosFormulario === 'function') {
                    cargarDatosFormulario(cursadaId);
                }
            }, 100);
            // Segundo intento por si acaso
            setTimeout(() => {
                if (typeof cargarDatosFormulario === 'function') {
                    cargarDatosFormulario(cursadaId);
                }
            }, 300);
        }
    }

    // Registrar event listener para botones "ver valores" UNA SOLA VEZ al inicio
    // Esto funciona con delegación de eventos, así que funcionará con todos los botones, incluso los creados dinámicamente
    if (!formsEventListenersInicializados) {
        document.addEventListener('click', function (e) {
            // Verificar si el click fue en un botón "ver valores" o en su contenido
            // Usar closest para encontrar el botón incluso si el click fue en un elemento hijo
            let boton = null;

            // Primero intentar con closest (más confiable)
            if (e.target && e.target.closest) {
                boton = e.target.closest('.cursada-btn-ver-valores');
            }

            // Si no se encontró con closest, verificar si el target mismo es el botón
            if (!boton && e.target && e.target.classList && e.target.classList.contains('cursada-btn-ver-valores')) {
                boton = e.target;
            }

            // Si aún no se encontró, buscar en el path del evento
            if (!boton && e.composedPath) {
                const path = e.composedPath();
                boton = path.find(el => el && el.classList && el.classList.contains('cursada-btn-ver-valores'));
            }

            // Si no es un botón "ver valores", salir
            if (!boton) return;

            // Prevenir click si el botón está deshabilitado (sin vacantes)
            if (boton.classList.contains('sin-vacantes') || boton.disabled) {
                return;
            }

            // Prevenir propagación para evitar conflictos con otros listeners
            e.stopPropagation();

            // Obtener el cursadaId del botón
            let cursadaId = boton.getAttribute('data-cursada-id');

            // Si no tiene data-cursada-id, intentar obtenerlo del elemento padre
            if (!cursadaId || cursadaId === 'TEMPLATE_ID') {
                const cursadaItem = boton.closest('.cursada-item');
                if (cursadaItem) {
                    // Buscar el panel dentro del mismo item
                    const panelEnItem = cursadaItem.querySelector('.cursada-valores-panel');
                    if (panelEnItem) {
                        const panelId = panelEnItem.id;
                        cursadaId = panelId.replace('panel-', '');
                        // Actualizar el data-cursada-id del botón
                        boton.setAttribute('data-cursada-id', cursadaId);
                    }
                }
            }

            // Verificar que se encontró el cursadaId
            if (!cursadaId || cursadaId === 'TEMPLATE_ID') {
                return;
            }

            let panel = document.getElementById('panel-' + cursadaId);
            let infoTexto = document.getElementById('info-' + cursadaId);
            let formulario = document.getElementById('formulario-' + cursadaId);

            // Si no se encuentra el panel, intentar encontrarlo dentro del mismo item
            if (!panel) {
                const cursadaItem = boton.closest('.cursada-item');
                if (cursadaItem) {
                    panel = cursadaItem.querySelector('.cursada-valores-panel');
                    if (panel) {
                        const panelId = panel.id;
                        cursadaId = panelId.replace('panel-', '');
                        infoTexto = document.getElementById('info-' + cursadaId);
                        formulario = document.getElementById('formulario-' + cursadaId);
                    } else {
                        return;
                    }
                } else {
                    return;
                }
            }

            // Comportamiento diferente en mobile vs desktop
            const esMobile = window.innerWidth < 600;

            if (esMobile) {
                // Comportamiento en mobile: abrir modal
                const cursadaItem = boton.closest('.cursada-item');
                const modalOverlay = document.getElementById('cursada-modal-overlay');
                const modalHeader = document.getElementById('cursada-modal-header-content');
                const modalBody = document.getElementById('cursada-modal-body');
                const modalClose = document.getElementById('cursada-modal-close');

                // Verificar si el modal ya está abierto
                const modalAbierto = modalOverlay && modalOverlay.classList.contains('cursada-modal-open');

                // Función para cerrar el modal
                const cerrarModal = () => {
                    if (modalOverlay) {
                        modalOverlay.classList.remove('cursada-modal-open');
                        document.body.style.overflow = '';
                    }

                    // Restaurar tabindex de elementos del fondo
                    const todosLosElementos = document.querySelectorAll('[data-original-tabindex]');
                    todosLosElementos.forEach(el => {
                        if (el.dataset.originalTabindex !== undefined) {
                            if (el.dataset.originalTabindex === 'null') {
                                el.removeAttribute('tabindex');
                            } else {
                                el.setAttribute('tabindex', el.dataset.originalTabindex);
                            }
                            delete el.dataset.originalTabindex;
                        }
                    });

                    // Limpiar contenido
                    if (modalHeader) modalHeader.innerHTML = '';
                    if (modalBody) modalBody.innerHTML = '';
                    // Remover estado desplegado del botón
                    boton.classList.remove('panel-desplegado');

                    // Asegurar que el panel original esté oculto en mobile
                    if (panel) {
                        panel.classList.remove('panel-visible');
                        panel.classList.add('panel-hidden');
                        panel.style.display = 'none';
                    }
                    if (infoTexto) {
                        infoTexto.classList.remove('panel-visible');
                        infoTexto.classList.add('panel-hidden');
                        infoTexto.style.display = 'none';
                    }
                };

                // Si el modal ya está abierto, cerrarlo
                if (modalAbierto) {
                    cerrarModal();
                    return;
                }

                // Función para abrir el modal
                const abrirModal = () => {
                    if (!modalOverlay || !modalHeader || !modalBody || !cursadaItem || !panel) return;

                    // Obtener el ID_Curso del elemento original ANTES de clonar y guardarlo en el modal
                    const idCursoOriginal = cursadaItem.getAttribute('data-id-curso');
                    if (idCursoOriginal && modalBody) {
                        modalBody.setAttribute('data-id-curso', idCursoOriginal);
                    }

                    // Ocultar el panel original en mobile (solo mostrar el modal)
                    if (panel) {
                        panel.classList.remove('panel-visible');
                        panel.classList.add('panel-hidden');
                        panel.style.display = 'none';
                    }
                    if (infoTexto) {
                        infoTexto.classList.remove('panel-visible');
                        infoTexto.classList.add('panel-hidden');
                        infoTexto.style.display = 'none';
                    }

                    // Marcar botón como desplegado
                    boton.classList.add('panel-desplegado');

                    // Copiar el header del item (día, turno, modalidad, etc.)
                    const itemFila1 = cursadaItem.querySelector('.cursada-item-fila-1');
                    const itemFila2 = cursadaItem.querySelector('.cursada-item-fila-2');

                    if (itemFila1 && itemFila2) {
                        modalHeader.innerHTML = '';
                        const headerClone1 = itemFila1.cloneNode(true);
                        const headerClone2 = itemFila2.cloneNode(true);
                        modalHeader.appendChild(headerClone1);
                        modalHeader.appendChild(headerClone2);
                    }

                    // Copiar el contenido del panel
                    const panelContent = panel.querySelector('.cursada-valores-grid');
                    if (panelContent) {
                        modalBody.innerHTML = '';
                        const contentClone = panelContent.cloneNode(true);

                        // Actualizar IDs ANTES de agregar al DOM para evitar duplicados
                        const tempFormularioClone = contentClone.querySelector('form.cursada-formulario');
                        if (tempFormularioClone) {
                            tempFormularioClone.id = 'formulario-' + cursadaId;

                            // Actualizar IDs de inputs
                            const nombreInputClone = tempFormularioClone.querySelector('input[name="nombre"]');
                            const apellidoInputClone = tempFormularioClone.querySelector('input[name="apellido"]');
                            const dniInputClone = tempFormularioClone.querySelector('input[name="dni"]');
                            const correoInputClone = tempFormularioClone.querySelector('input[name="correo"]');
                            const telefonoInputClone = tempFormularioClone.querySelector('input[name="telefono"]');
                            const telefonoPrefijoInputClone = tempFormularioClone.querySelector('select[name="telefono_prefijo"]');

                            if (nombreInputClone) nombreInputClone.id = 'nombre-' + cursadaId;
                            if (apellidoInputClone) apellidoInputClone.id = 'apellido-' + cursadaId;
                            if (dniInputClone) dniInputClone.id = 'dni-' + cursadaId;
                            if (correoInputClone) correoInputClone.id = 'correo-' + cursadaId;
                            if (telefonoInputClone) telefonoInputClone.id = 'telefono-' + cursadaId;
                            if (telefonoPrefijoInputClone) telefonoPrefijoInputClone.id = 'telefono-prefijo-' + cursadaId;

                            // Actualizar IDs de errores
                            const errorNombreClone = tempFormularioClone.querySelector('span[id^="error-nombre-"]');
                            const errorApellidoClone = tempFormularioClone.querySelector('span[id^="error-apellido-"]');
                            const errorDniClone = tempFormularioClone.querySelector('span[id^="error-dni-"]');
                            const errorCorreoClone = tempFormularioClone.querySelector('span[id^="error-correo-"]');
                            const errorTelefonoClone = tempFormularioClone.querySelector('span[id^="error-telefono-"]');

                            if (errorNombreClone) errorNombreClone.id = 'error-nombre-' + cursadaId;
                            if (errorApellidoClone) errorApellidoClone.id = 'error-apellido-' + cursadaId;
                            if (errorDniClone) errorDniClone.id = 'error-dni-' + cursadaId;
                            if (errorCorreoClone) errorCorreoClone.id = 'error-correo-' + cursadaId;
                            if (errorTelefonoClone) errorTelefonoClone.id = 'error-telefono-' + cursadaId;
                        }

                        // Actualizar IDs de elementos fuera del formulario
                        const codigoInputFieldClone = contentClone.querySelector('#codigo-input-field-' + cursadaId);
                        if (codigoInputFieldClone) {
                            codigoInputFieldClone.id = 'codigo-input-field-modal-' + cursadaId;
                        }

                        const codigoInputContainerClone = contentClone.querySelector('#codigo-input-' + cursadaId);
                        if (codigoInputContainerClone) {
                            codigoInputContainerClone.id = 'codigo-input-modal-' + cursadaId;
                        }

                        const linkCodigoClone = contentClone.querySelector('#link-codigo-' + cursadaId);
                        if (linkCodigoClone) {
                            linkCodigoClone.id = 'link-codigo-modal-' + cursadaId;
                        }

                        const linkVerClone = contentClone.querySelector('#link-ver-' + cursadaId);
                        if (linkVerClone) {
                            linkVerClone.id = 'link-ver-modal-' + cursadaId;
                        }

                        const checkboxTerminosClone = contentClone.querySelector('#acepto-terminos-' + cursadaId);
                        if (checkboxTerminosClone) {
                            checkboxTerminosClone.id = 'acepto-terminos-modal-' + cursadaId;
                            checkboxTerminosClone.setAttribute('name', 'acepto-terminos-' + cursadaId);
                            const labelCheckboxClone = contentClone.querySelector('label[for^="acepto-terminos-"]');
                            if (labelCheckboxClone) {
                                labelCheckboxClone.setAttribute('for', 'acepto-terminos-modal-' + cursadaId);
                            }
                        }

                        modalBody.appendChild(contentClone);

                        // Inicializar el formulario dentro del modal replicando exactamente la lógica de desktop
                        setTimeout(() => {
                            const tempFormulario = modalBody.querySelector('form.cursada-formulario');
                            if (!tempFormulario) return;

                            // Asegurar que tenga el ID correcto (siempre actualizar, incluso si ya tiene uno)
                            tempFormulario.id = 'formulario-' + cursadaId;

                            // Cargar datos guardados ANTES de actualizar IDs (usa name, así que funciona)
                            // También cargar el leadId desde sessionStorage si existe para asegurar que se usa en futuras ediciones
                            if (typeof cargarDatosFormulario === 'function') {
                                cargarDatosFormulario(cursadaId);
                            }

                            // Restaurar leadId en el panel desde sessionStorage si existe
                            try {
                                const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
                                if (datosGuardados) {
                                    const datos = JSON.parse(datosGuardados);
                                    if (datos.leadId) {
                                        // Intentar encontrar el panel para setear el atributo
                                        const formCursadaId = cursadaId.replace('cursada-', '');
                                        let panel = document.getElementById('panel-' + formCursadaId);
                                        if (!panel) {
                                            if (cursadaId.includes('cursada-')) {
                                                panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                                            } else {
                                                panel = document.getElementById('panel-cursada-' + cursadaId);
                                            }
                                        }
                                        if (panel) {
                                            panel.setAttribute('data-lead-id', datos.leadId);
                                        }
                                    }
                                }
                            } catch (e) { }

                            // Restaurar estado del checkbox de términos si ya se aceptaron
                            const checkboxTerminosModal = modalBody.querySelector('#acepto-terminos-modal-' + cursadaId) || modalBody.querySelector('#acepto-terminos-' + cursadaId);
                            const btnReservar = modalBody.querySelector('.cursada-btn-reservar');

                            // Verificar si ya se aceptaron los términos para este lead
                            // Esto se puede inferir si el botón reservar ya está habilitado o si hay algún indicador
                            // Por ahora, nos aseguramos de que el checkbox funcione correctamente
                            if (checkboxTerminosModal) {
                                checkboxTerminosModal.disabled = false; // Habilitar checkbox

                                checkboxTerminosModal.addEventListener('change', function (e) {
                                    if (this.checked) {
                                        // Obtener el ID del lead desde el panel o atributo guardado
                                        // Usar la misma lógica robusta que usamos para guardar
                                        let leadId = null;
                                        const formCursadaId = cursadaId.replace('cursada-', '');

                                        // 1. Panel
                                        let panel = document.getElementById('panel-' + formCursadaId);
                                        if (!panel) {
                                            if (cursadaId.includes('cursada-')) {
                                                panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                                            } else {
                                                panel = document.getElementById('panel-cursada-' + cursadaId);
                                            }
                                        }
                                        if (panel) leadId = panel.getAttribute('data-lead-id');

                                        // 2. SessionStorage
                                        if (!leadId) {
                                            try {
                                                const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
                                                if (datosGuardados) {
                                                    const datos = JSON.parse(datosGuardados);
                                                    leadId = datos.leadId;
                                                }
                                            } catch (e) { }
                                        }

                                        if (leadId) {
                                            fetch('/leads/' + leadId + '/terms', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                                }
                                            })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        console.log('Términos aceptados guardados para lead (mobile):', leadId);
                                                        if (btnReservar) {
                                                            btnReservar.disabled = false;
                                                        }
                                                    }
                                                })
                                                .catch(error => console.error('Error al guardar términos (mobile):', error));
                                        } else {
                                            console.error('No se encontró leadId para actualizar términos (mobile)');
                                        }
                                    } else {
                                        // Si se desmarca, deshabilitar botón reservar
                                        if (btnReservar) {
                                            btnReservar.disabled = true;
                                        }
                                    }
                                });
                            }

                            // Funciones helper locales (definidas antes de usarlas)
                            const mostrarErrorLocal = (input, errorElement, mensaje) => {
                                if (input) input.classList.add('error');
                                if (errorElement) {
                                    errorElement.classList.add('show');
                                    errorElement.textContent = mensaje;
                                }
                            };

                            const ocultarErrorLocal = (input, errorElement) => {
                                if (input) input.classList.remove('error');
                                if (errorElement) {
                                    errorElement.classList.remove('show');
                                    errorElement.textContent = '';
                                }
                            };

                            const normalizarTelefonoLocal = (telefonoInput) => {
                                if (!telefonoInput) return '';
                                return telefonoInput.value.trim().replace(/\D/g, '').slice(0, 14);
                            };

                            // Función helper local para validar el formulario
                            const validarFormularioLocal = () => {
                                const formParaValidar = modalBody.querySelector('#formulario-' + cursadaId) || modalBody.querySelector('form.cursada-formulario');
                                if (!formParaValidar) return false;

                                const nombre = formParaValidar.querySelector('input[name="nombre"]');
                                const apellido = formParaValidar.querySelector('input[name="apellido"]');
                                const dni = formParaValidar.querySelector('input[name="dni"]');
                                const correo = formParaValidar.querySelector('input[name="correo"]');
                                const telefono = formParaValidar.querySelector('input[name="telefono"]');

                                const nombreVal = nombre?.value?.trim() || '';
                                const apellidoVal = apellido?.value?.trim() || '';
                                const dniVal = dni?.value?.trim() || '';
                                const correoVal = correo?.value?.trim() || '';
                                const telefonoVal = telefono?.value?.trim() || '';

                                const validNombre = nombreVal.length > 0;
                                const validApellido = apellidoVal.length > 0;
                                const validDni = dniVal && /^[0-9]{7,8}$/.test(dniVal);
                                const validCorreo = correoVal && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoVal);
                                const validTelefono = telefonoVal && /^[0-9]{8,14}$/.test(telefonoVal.replace(/\D/g, ''));

                                return validNombre && validApellido && validDni && validCorreo && validTelefono;
                            };

                            // Función helper local para actualizar el botón (definida antes de los event listeners)
                            const actualizarBotonLocal = () => {
                                const botonParaActualizar = modalBody.querySelector('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]') || modalBody.querySelector('.cursada-btn-continuar');
                                if (!botonParaActualizar) return;

                                const esValido = validarFormularioLocal();

                                // Actualizar botón
                                if (esValido) {
                                    botonParaActualizar.classList.add('activo');
                                    botonParaActualizar.disabled = false;
                                    botonParaActualizar.style.opacity = '1';
                                    botonParaActualizar.style.cursor = 'pointer';
                                } else {
                                    botonParaActualizar.classList.remove('activo');
                                    botonParaActualizar.disabled = true;
                                    botonParaActualizar.style.opacity = '0.6';
                                    botonParaActualizar.style.cursor = 'not-allowed';
                                }
                            };

                            // Los IDs ya fueron actualizados antes de agregar al DOM, solo verificar
                            const nombreInput = tempFormulario.querySelector('input[name="nombre"]');
                            const apellidoInput = tempFormulario.querySelector('input[name="apellido"]');
                            const dniInput = tempFormulario.querySelector('input[name="dni"]');
                            const correoInput = tempFormulario.querySelector('input[name="correo"]');
                            const telefonoInput = tempFormulario.querySelector('input[name="telefono"]');
                            const telefonoPrefijoInput = tempFormulario.querySelector('select[name="telefono_prefijo"]');

                            // Asegurar que los IDs estén correctos (por si acaso)
                            if (nombreInput && !nombreInput.id) nombreInput.id = 'nombre-' + cursadaId;
                            if (apellidoInput && !apellidoInput.id) apellidoInput.id = 'apellido-' + cursadaId;
                            if (dniInput && !dniInput.id) dniInput.id = 'dni-' + cursadaId;
                            if (correoInput && !correoInput.id) correoInput.id = 'correo-' + cursadaId;
                            if (telefonoInput && !telefonoInput.id) telefonoInput.id = 'telefono-' + cursadaId;
                            if (telefonoPrefijoInput && !telefonoPrefijoInput.id) telefonoPrefijoInput.id = 'telefono-prefijo-' + cursadaId;

                            // Cargar datos DESPUÉS de actualizar IDs (para asegurar que los IDs estén correctos)
                            // Usar un pequeño delay para asegurar que el DOM esté completamente actualizado
                            setTimeout(() => {
                                // Verificar primero si el formulario ya está completo (readonly)
                                let yaEstaReadonly = false;
                                if (typeof verificarYRestaurarEstadoCompletado === 'function') {
                                    yaEstaReadonly = verificarYRestaurarEstadoCompletado();
                                }

                                // Cargar datos del formulario
                                if (typeof cargarDatosFormulario === 'function') {
                                    cargarDatosFormulario(cursadaId);
                                }

                                // Si ya estaba readonly, asegurar que el botón esté deshabilitado
                                if (yaEstaReadonly) {
                                    const botonContinuarReadonly = modalBody.querySelector('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]') || modalBody.querySelector('.cursada-btn-continuar');
                                    if (botonContinuarReadonly) {
                                        botonContinuarReadonly.classList.remove('activo');
                                        botonContinuarReadonly.disabled = true;
                                        botonContinuarReadonly.style.opacity = '0.6';
                                        botonContinuarReadonly.style.cursor = 'not-allowed';
                                    }
                                }
                            }, 50);


                            // Obtener elementos del formulario (buscando dentro del formulario del modal para evitar IDs duplicados)
                            const nombre = nombreInput;
                            const apellido = apellidoInput;
                            const dni = dniInput;
                            const correo = correoInput;
                            const telefono = telefonoInput;

                            // Obtener elementos de error
                            const errorNombre = tempFormulario.querySelector('#error-nombre-' + cursadaId) || modalBody.querySelector('#error-nombre-' + cursadaId);
                            const errorApellido = tempFormulario.querySelector('#error-apellido-' + cursadaId) || modalBody.querySelector('#error-apellido-' + cursadaId);
                            const errorDni = tempFormulario.querySelector('#error-dni-' + cursadaId) || modalBody.querySelector('#error-dni-' + cursadaId);
                            const errorCorreo = tempFormulario.querySelector('#error-correo-' + cursadaId) || modalBody.querySelector('#error-correo-' + cursadaId);
                            const errorTelefono = tempFormulario.querySelector('#error-telefono-' + cursadaId) || modalBody.querySelector('#error-telefono-' + cursadaId);

                            // Función genérica para validar campo de texto (igual que desktop)
                            function setupCampoTexto(input, errorElement, mensaje) {
                                if (!input) return;
                                input.addEventListener('blur', function () {
                                    if (typeof validarCampoTexto === 'function' && !validarCampoTexto(this.value)) {
                                        mostrarErrorLocal(this, errorElement, mensaje);
                                    } else {
                                        ocultarErrorLocal(this, errorElement);
                                    }
                                    // Actualizar botón usando función local
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                });
                                input.addEventListener('input', function () {
                                    if (this.value.trim()) {
                                        ocultarErrorLocal(this, errorElement);
                                    }
                                    // Actualizar botón usando función local
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                });
                            }

                            // Función genérica para validar campo con función personalizada (igual que desktop)
                            function setupCampoValidado(input, errorElement, validarFn, mensajeError) {
                                if (!input) return;
                                input.addEventListener('blur', function () {
                                    if (!validarFn(this)) {
                                        mostrarErrorLocal(this, errorElement, mensajeError);
                                    } else {
                                        ocultarErrorLocal(this, errorElement);
                                    }
                                    // Actualizar botón usando función local
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                });
                                input.addEventListener('input', function () {
                                    if (validarFn(this)) {
                                        ocultarErrorLocal(this, errorElement);
                                    }
                                    // Actualizar botón usando función local
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                });
                            }

                            // Configurar validaciones (igual que desktop)
                            if (typeof validarCampoTexto === 'function') {
                                setupCampoTexto(nombre, errorNombre, 'Este campo es obligatorio');
                                setupCampoTexto(apellido, errorApellido, 'Este campo es obligatorio');
                            }
                            // Validación de DNI con verificación de función
                            if (dni && errorDni) {
                                // Filtrar solo números en tiempo real
                                dni.addEventListener('input', function (e) {
                                    // Remover cualquier carácter que no sea número
                                    this.value = this.value.replace(/\D/g, '');
                                    // Limitar a 8 caracteres
                                    if (this.value.length > 8) {
                                        this.value = this.value.slice(0, 8);
                                    }
                                    // Ocultar error si es válido
                                    const valor = this.value.trim();
                                    if (valor && /^[0-9]{7,8}$/.test(valor)) {
                                        ocultarErrorLocal(this, errorDni);
                                    }
                                    // Actualizar estado del botón usando función local
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    // Actualizar botón usando función local si está disponible
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                });

                                if (typeof validarDni === 'function') {
                                    setupCampoValidado(dni, errorDni, validarDni, 'El DNI debe tener entre 7 y 8 dígitos');
                                } else {
                                    // Si validarDni no está disponible, usar validación inline
                                    dni.addEventListener('blur', function () {
                                        const valor = this.value.trim();
                                        const esValido = valor && /^[0-9]{7,8}$/.test(valor);
                                        if (!esValido) {
                                            mostrarErrorLocal(this, errorDni, 'El DNI debe tener entre 7 y 8 dígitos');
                                        } else {
                                            ocultarErrorLocal(this, errorDni);
                                        }
                                        setTimeout(() => {
                                            if (typeof actualizarEstadoBotonContinuar === 'function') {
                                                actualizarEstadoBotonContinuar(cursadaId);
                                            }
                                        }, 0);
                                    });
                                }
                            }

                            if (correo && errorCorreo) {
                                if (typeof validarCorreo === 'function') {
                                    correo.addEventListener('blur', function () {
                                        if (!validarCorreo(this)) {
                                            mostrarErrorLocal(this, errorCorreo, this.value.trim() ? 'Por favor ingrese un correo electrónico válido' : 'Este campo es obligatorio');
                                        } else {
                                            ocultarErrorLocal(this, errorCorreo);
                                        }
                                        // Actualizar botón usando función local
                                        if (typeof actualizarBotonLocal === 'function') {
                                            actualizarBotonLocal();
                                        }
                                        // También intentar función global
                                        setTimeout(() => {
                                            if (typeof actualizarEstadoBotonContinuar === 'function') {
                                                actualizarEstadoBotonContinuar(cursadaId);
                                            }
                                        }, 0);
                                    });
                                    correo.addEventListener('input', function () {
                                        if (validarCorreo(this)) {
                                            ocultarErrorLocal(this, errorCorreo);
                                        }
                                        // Actualizar botón usando función local
                                        if (typeof actualizarBotonLocal === 'function') {
                                            actualizarBotonLocal();
                                        }
                                        // También intentar función global
                                        setTimeout(() => {
                                            if (typeof actualizarEstadoBotonContinuar === 'function') {
                                                actualizarEstadoBotonContinuar(cursadaId);
                                            }
                                        }, 0);
                                    });
                                } else {
                                    // Si validarCorreo no está disponible, usar validación inline
                                    correo.addEventListener('blur', function () {
                                        const valor = this.value.trim();
                                        const esValido = valor && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor);
                                        if (!esValido) {
                                            mostrarErrorLocal(this, errorCorreo, valor ? 'Por favor ingrese un correo electrónico válido' : 'Este campo es obligatorio');
                                        } else {
                                            ocultarErrorLocal(this, errorCorreo);
                                        }
                                        // Actualizar botón usando función local
                                        if (typeof actualizarBotonLocal === 'function') {
                                            actualizarBotonLocal();
                                        }
                                        // También intentar función global
                                        setTimeout(() => {
                                            if (typeof actualizarEstadoBotonContinuar === 'function') {
                                                actualizarEstadoBotonContinuar(cursadaId);
                                            }
                                        }, 0);
                                    });
                                    correo.addEventListener('input', function () {
                                        const valor = this.value.trim();
                                        if (valor && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) {
                                            ocultarErrorLocal(this, errorCorreo);
                                        }
                                        // Actualizar botón usando función local
                                        if (typeof actualizarBotonLocal === 'function') {
                                            actualizarBotonLocal();
                                        }
                                        // También intentar función global
                                        setTimeout(() => {
                                            if (typeof actualizarEstadoBotonContinuar === 'function') {
                                                actualizarEstadoBotonContinuar(cursadaId);
                                            }
                                        }, 0);
                                    });
                                }
                            }

                            // Validación de teléfono (igual que desktop)
                            if (telefono) {
                                function normalizarYValidarTelefono() {
                                    // Filtrar solo números
                                    const valor = telefono.value.replace(/\D/g, '').slice(0, 14);
                                    telefono.value = valor;
                                    // Actualizar botón usando función local si está disponible
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                }

                                // Filtrar letras en tiempo real
                                telefono.addEventListener('input', normalizarYValidarTelefono);
                                telefono.addEventListener('paste', () => setTimeout(normalizarYValidarTelefono, 0));
                                telefono.addEventListener('blur', function () {
                                    normalizarYValidarTelefono();
                                    // Validar formato
                                    const valorTelefono = telefono.value.replace(/\D/g, '');
                                    const esValido = valorTelefono && /^[0-9]{8,14}$/.test(valorTelefono);

                                    if (!esValido && errorTelefono) {
                                        mostrarErrorLocal(telefono, errorTelefono, 'El teléfono debe tener entre 8 y 14 dígitos');
                                    } else if (esValido && errorTelefono) {
                                        ocultarErrorLocal(telefono, errorTelefono);
                                    }

                                    // Actualizar botón usando función local si está disponible
                                    if (typeof actualizarBotonLocal === 'function') {
                                        actualizarBotonLocal();
                                    }
                                    // También intentar función global
                                    setTimeout(() => {
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 0);
                                });
                            }

                            // Configurar botón editar form (mismo comportamiento que desktop)
                            const btnEditarModal = modalBody.querySelector('#editar-form-' + cursadaId);
                            if (btnEditarModal) {
                                btnEditarModal.addEventListener('click', function (e) {
                                    e.preventDefault();

                                    // Habilitar inputs del formulario actual
                                    const formInputs = tempFormulario.querySelectorAll('input, select');
                                    formInputs.forEach(input => {
                                        input.removeAttribute('readonly');
                                        input.removeAttribute('disabled');
                                        input.style.pointerEvents = 'auto';
                                        input.style.opacity = '1';
                                        input.style.cursor = input.tagName === 'SELECT' ? 'pointer' : 'text';
                                    });

                                    // Habilitar botón continuar correspondiente
                                    const botonContinuar = modalBody.querySelector('.cursada-btn-continuar');
                                    if (botonContinuar) {
                                        botonContinuar.disabled = false;
                                        botonContinuar.style.opacity = '1';
                                        botonContinuar.style.cursor = 'pointer';
                                        // Re-validar para asegurar estado correcto del botón
                                        if (typeof actualizarBotonLocal === 'function') {
                                            actualizarBotonLocal();
                                        }
                                    }

                                    // Ocultar este botón de editar
                                    this.style.display = 'none';

                                    // OCULTAR Y DESHABILITAR PANELES DERECHOS (Modo Edición - Mobile/Modal)

                                    // 1. Ocultar información de cuota y panel derecho
                                    const cuotaInfo = modalBody.querySelector('#cuota-info-' + cursadaId);
                                    if (cuotaInfo) {
                                        cuotaInfo.style.display = 'none';
                                        cuotaInfo.setAttribute('hidden', '');
                                        cuotaInfo.classList.remove('visible');
                                        cuotaInfo.classList.add('hidden');
                                    }

                                    // 2. Deshabilitar checkbox de términos y desmarcarlo
                                    const checkboxTerminos = modalBody.querySelector('#acepto-terminos-modal-' + cursadaId);
                                    if (checkboxTerminos) {
                                        // Primero desmarcar, luego deshabilitar
                                        checkboxTerminos.checked = false;
                                        checkboxTerminos.disabled = true;

                                        // Forzar actualización visual si es necesario (para algunos navegadores móviles)
                                        checkboxTerminos.dispatchEvent(new Event('change', { bubbles: true }));
                                    }

                                    // 3. Deshabilitar botón Reservar
                                    const btnReservar = modalBody.querySelector('.cursada-btn-reservar');
                                    if (btnReservar) {
                                        btnReservar.disabled = true;
                                    }

                                    // 4. Deshabilitar link de código de descuento
                                    const linkCodigo = modalBody.querySelector('#link-codigo-modal-' + cursadaId);
                                    if (linkCodigo) {
                                        linkCodigo.classList.add('cursada-link-disabled');
                                    }

                                    // 5. Ocultar input de código si estaba abierto
                                    const codigoInputContainer = modalBody.querySelector('#codigo-input-modal-' + cursadaId);
                                    if (codigoInputContainer) {
                                        codigoInputContainer.classList.remove('input-visible');
                                        codigoInputContainer.style.display = 'none';
                                    }
                                });
                            }

                            // Configurar botón "Continuar" (igual que desktop)
                            // Buscar el botón continuar en el modal (puede tener cualquier data-cursada-id después del clone)
                            let botonContinuar = modalBody.querySelector('.cursada-btn-continuar');
                            if (botonContinuar) {
                                // Asegurar que tenga el data-cursada-id correcto
                                botonContinuar.setAttribute('data-cursada-id', cursadaId);

                                // Verificar si el formulario está en modo readonly
                                const formReadonly = tempFormulario.querySelector('input[readonly]') || tempFormulario.querySelector('input[disabled]');
                                if (formReadonly) {
                                    // Si está readonly, deshabilitar el botón inmediatamente
                                    botonContinuar.classList.remove('activo');
                                    botonContinuar.disabled = true;
                                    botonContinuar.style.opacity = '0.6';
                                    botonContinuar.style.cursor = 'not-allowed';
                                } else {
                                    // Si no está readonly, inicializar como inactivo y validar
                                    botonContinuar.classList.remove('activo');
                                    botonContinuar.disabled = true;
                                    botonContinuar.style.opacity = '0.6';
                                    botonContinuar.style.cursor = 'not-allowed';

                                    // Actualizar estado inmediatamente después de configurar
                                    setTimeout(() => {
                                        actualizarBotonLocal();
                                        // También intentar llamar a la función global si está disponible
                                        if (typeof actualizarEstadoBotonContinuar === 'function') {
                                            actualizarEstadoBotonContinuar(cursadaId);
                                        }
                                    }, 100);
                                }

                                // Agregar event listener (igual que desktop)
                                botonContinuar.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    // Solo procesar si el botón está activo
                                    if (!this.classList.contains('activo')) {
                                        return;
                                    }

                                    const cursadaIdBtn = this.getAttribute('data-cursada-id');

                                    // Si el botón está activo, el formulario ya está validado
                                    // Usar función local de validación
                                    if (!validarFormularioLocal()) {
                                        return;
                                    }

                                    const nombreModal = tempFormulario.querySelector('#nombre-' + cursadaIdBtn);
                                    const apellidoModal = tempFormulario.querySelector('#apellido-' + cursadaIdBtn);
                                    const dniModal = tempFormulario.querySelector('#dni-' + cursadaIdBtn);
                                    const correoModal = tempFormulario.querySelector('#correo-' + cursadaIdBtn);
                                    const telefonoModal = tempFormulario.querySelector('#telefono-' + cursadaIdBtn);
                                    const telefonoPrefijoModal = tempFormulario.querySelector('#telefono-prefijo-' + cursadaIdBtn);
                                    const checkboxTerminosModal = tempFormulario.querySelector('#acepto-terminos-modal-' + cursadaIdBtn) || tempFormulario.querySelector('#acepto-terminos-' + cursadaIdBtn);

                                    // Limpiar todos los errores antes de enviar (usar función local)
                                    ocultarErrorLocal(nombreModal, errorNombre);
                                    ocultarErrorLocal(apellidoModal, errorApellido);
                                    ocultarErrorLocal(dniModal, errorDni);
                                    ocultarErrorLocal(correoModal, errorCorreo);
                                    ocultarErrorLocal(telefonoModal, errorTelefono);

                                    // Construir número de teléfono completo (usar función local)
                                    const telefonoCompleto = (telefonoPrefijoModal?.value || '+54') + normalizarTelefonoLocal(telefonoModal);

                                    // Obtener el ID_Curso desde el modal body (donde lo guardamos al abrir)
                                    let idCurso = modalBody ? modalBody.getAttribute('data-id-curso') : null;

                                    // Si no está en el modal body, intentar desde el elemento original
                                    if (!idCurso) {
                                        const formCursadaId = cursadaIdBtn.replace('cursada-', '');
                                        const panel = document.getElementById('panel-' + formCursadaId);
                                        if (panel) {
                                            const panelItem = panel.closest('.cursada-item');
                                            if (panelItem) {
                                                idCurso = panelItem.getAttribute('data-id-curso');
                                            }
                                        }
                                    }

                                    // Si aún no se encontró, buscar desde el botón original
                                    if (!idCurso) {
                                        const btnOriginal = document.querySelector('[data-cursada-id="' + cursadaIdBtn + '"]');
                                        if (btnOriginal) {
                                            const cursadaItemOriginal = btnOriginal.closest('.cursada-item');
                                            if (cursadaItemOriginal) {
                                                idCurso = cursadaItemOriginal.getAttribute('data-id-curso');
                                            }
                                        }
                                    }

                                    if (!idCurso) {
                                        mostrarNotificacion('Error: No se pudo obtener el ID del curso. Por favor, recargá la página.', 'error');
                                        return;
                                    }

                                    // Obtener leadId para edición
                                    // 1. Intentar desde el panel original (lugar más confiable)
                                    const formCursadaId = cursadaIdBtn.replace('cursada-', '');
                                    let leadId = null;

                                    // Buscar panel original
                                    let panelOriginal = document.getElementById('panel-' + formCursadaId);
                                    if (!panelOriginal) {
                                        if (cursadaIdBtn.includes('cursada-')) {
                                            panelOriginal = document.getElementById('panel-' + cursadaIdBtn.replace('cursada-', ''));
                                        } else {
                                            panelOriginal = document.getElementById('panel-cursada-' + cursadaIdBtn);
                                        }
                                    }

                                    if (panelOriginal) {
                                        leadId = panelOriginal.getAttribute('data-lead-id');
                                    }

                                    // 2. Si no, intentar desde sessionStorage
                                    if (!leadId) {
                                        const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
                                        if (datosGuardados) {
                                            try {
                                                const datos = JSON.parse(datosGuardados);
                                                if (datos.leadId) {
                                                    leadId = datos.leadId;
                                                }
                                            } catch (e) { }
                                        }
                                    }

                                    // Verificar estado del checkbox
                                    const aceptoTerminos = checkboxTerminosModal ? checkboxTerminosModal.checked : false;

                                    // Guardar lead (igual que desktop)
                                    executeRecaptcha().then(recaptchaToken => {
                                        return fetch(window.inscripcionConfig.leadsStoreUrl, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                            },
                                            body: JSON.stringify({
                                                id: leadId, // Enviar ID si existe para actualizar
                                                nombre: nombreModal.value.trim(),
                                                apellido: apellidoModal.value.trim(),
                                                dni: dniModal.value.trim(),
                                                correo: correoModal.value.trim(),
                                                telefono: telefonoCompleto,
                                                cursada_id: idCurso,
                                                tipo: 'Lead',
                                                acepto_terminos: aceptoTerminos,
                                                'g-recaptcha-response': recaptchaToken
                                            })
                                        });
                                    })
                                        .then(async response => {
                                            // Leer la respuesta una sola vez
                                            const text = await response.text();
                                            const contentType = response.headers.get('content-type');
                                            const isJson = contentType && contentType.includes('application/json');

                                            // Intentar parsear como JSON
                                            let data;
                                            try {
                                                data = JSON.parse(text);
                                            } catch (e) {
                                                // Si no es JSON y hay un error, lanzar error genérico
                                                if (!response.ok) {
                                                    throw new Error('Error del servidor. Por favor, intente nuevamente.');
                                                }
                                                // Si es exitoso pero no es JSON, devolver objeto con el texto
                                                return { success: false, message: text };
                                            }

                                            // Si la respuesta no es OK, lanzar error con el mensaje del servidor
                                            if (!response.ok) {
                                                throw new Error(data.message || 'Error al guardar los datos');
                                            }

                                            // Respuesta exitosa
                                            return data;
                                        })
                                        .then(data => {
                                            // Si data es string, intentar parsearlo
                                            if (typeof data === 'string') {
                                                try {
                                                    data = JSON.parse(data);
                                                } catch (e) {
                                                }
                                            }

                                            if (data && data.success) {
                                                // Guardar el ID del lead en el panel correspondiente para usarlo después
                                                const panelId = 'panel-' + cursadaIdBtn.replace('cursada-', '');
                                                const panel = document.getElementById(panelId);
                                                if (panel && data.lead_id) {
                                                    panel.setAttribute('data-lead-id', data.lead_id);
                                                }

                                                // También guardarlo en sessionStorage para persistencia
                                                if (typeof guardarDatosFormulario === 'function') {
                                                    // Recuperar datos actuales y agregar leadId
                                                    try {
                                                        const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
                                                        if (datosGuardados) {
                                                            const datos = JSON.parse(datosGuardados);
                                                            datos.leadId = data.lead_id;
                                                            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(datos));
                                                        } else {
                                                            // Si no hay datos, crear objeto con leadId (caso raro pero posible)
                                                            const datos = { leadId: data.lead_id };
                                                            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(datos));
                                                        }
                                                        // Forzar actualización inmediata del almacenamiento
                                                        guardarDatosFormulario(cursadaIdBtn);
                                                    } catch (e) { }
                                                }

                                                // Verificar si el formulario ya estaba en modo readonly ANTES de aplicar readonly
                                                const yaEstabaReadonly = tempFormulario.querySelector('input[readonly]') || tempFormulario.querySelector('input[disabled]');

                                                // Guardar los datos del formulario en localStorage
                                                if (typeof guardarDatosFormulario === 'function') {
                                                    guardarDatosFormulario(cursadaIdBtn);
                                                }

                                                // Desactivar TODOS los formularios después de guardar exitosamente
                                                // Buscar en el DOM normal y también en el modal
                                                const modalBodyForReadonly = document.getElementById('cursada-modal-body');
                                                const todosLosFormulariosModal = [];

                                                // Agregar formularios del DOM normal
                                                document.querySelectorAll('.cursada-formulario').forEach(form => {
                                                    todosLosFormulariosModal.push(form);
                                                });

                                                // Agregar formularios del modal si está abierto
                                                if (modalBodyForReadonly) {
                                                    modalBodyForReadonly.querySelectorAll('.cursada-formulario').forEach(form => {
                                                        if (!todosLosFormulariosModal.includes(form)) {
                                                            todosLosFormulariosModal.push(form);
                                                        }
                                                    });
                                                }

                                                todosLosFormulariosModal.forEach(form => {
                                                    const formId = form.getAttribute('id');
                                                    if (formId) {
                                                        const formCursadaId = formId.replace('formulario-', '');
                                                        const formInputs = form.querySelectorAll('input, select');
                                                        formInputs.forEach(input => {
                                                            input.setAttribute('readonly', 'readonly');
                                                            input.setAttribute('disabled', 'disabled');
                                                            input.style.pointerEvents = 'none';
                                                            input.style.opacity = '0.6';
                                                            input.style.cursor = 'not-allowed';
                                                        });

                                                        // Desactivar todos los botones continuar (buscar en modal y DOM normal)
                                                        let botonContinuarFormModal = null;
                                                        if (modalBodyForReadonly) {
                                                            botonContinuarFormModal = modalBodyForReadonly.querySelector('.cursada-btn-continuar[data-cursada-id="' + formCursadaId + '"]') ||
                                                                modalBodyForReadonly.querySelector('.cursada-btn-continuar');
                                                        }
                                                        if (!botonContinuarFormModal) {
                                                            botonContinuarFormModal = document.querySelector('.cursada-btn-continuar[data-cursada-id="' + formCursadaId + '"]');
                                                        }
                                                        if (botonContinuarFormModal) {
                                                            botonContinuarFormModal.classList.remove('activo');
                                                            botonContinuarFormModal.disabled = true;
                                                            botonContinuarFormModal.style.opacity = '0.6';
                                                            botonContinuarFormModal.style.cursor = 'not-allowed';
                                                        }
                                                        // Mostrar botón editar (intentar en modal y DOM normal)
                                                        let btnEditarModal = null;
                                                        if (modalBodyForReadonly) {
                                                            btnEditarModal = modalBodyForReadonly.querySelector('#editar-form-' + formCursadaId);
                                                        }
                                                        if (!btnEditarModal) {
                                                            btnEditarModal = document.getElementById('editar-form-' + formCursadaId);
                                                        }

                                                        if (btnEditarModal) {
                                                            btnEditarModal.style.display = 'inline';
                                                        }
                                                    }
                                                });

                                                // Mostrar valores en TODOS los formularios
                                                todosLosFormulariosModal.forEach(form => {
                                                    const formId = form.getAttribute('id');
                                                    if (formId) {
                                                        const formCursadaId = formId.replace('formulario-', '');
                                                        if (typeof mostrarValoresEnFormulario === 'function') {
                                                            mostrarValoresEnFormulario(formCursadaId);
                                                        }
                                                    }
                                                });

                                                // Cargar los datos en todos los formularios después de guardar
                                                setTimeout(() => {
                                                    if (typeof cargarDatosEnTodosLosFormularios === 'function') {
                                                        cargarDatosEnTodosLosFormularios();
                                                    }
                                                }, 300);

                                                // Verificar y restaurar estado de readonly en todos los formularios
                                                if (typeof verificarYRestaurarEstadoCompletado === 'function') {
                                                    verificarYRestaurarEstadoCompletado();
                                                }

                                                // No mostrar notificación de éxito (solicitado por el usuario)
                                            } else {
                                                if (typeof mostrarNotificacion === 'function') {
                                                    mostrarNotificacion('Error al guardar los datos. Por favor, intente nuevamente.', 'error');
                                                }
                                            }
                                        })
                                        .catch(error => {
                                            if (typeof mostrarNotificacion === 'function') {
                                                mostrarNotificacion(error.message || 'Error al guardar los datos. Por favor, intente nuevamente.', 'error');
                                            }
                                        });
                                });
                            }

                            // Cargar datos guardados y actualizar botón
                            setTimeout(() => {
                                if (typeof cargarDatosFormulario === 'function') {
                                    cargarDatosFormulario(cursadaId);
                                }
                                // Actualizar estado inicial del botón después de cargar datos
                                setTimeout(() => {
                                    if (typeof actualizarEstadoBotonContinuar === 'function') {
                                        actualizarEstadoBotonContinuar(cursadaId);
                                    }
                                }, 100);
                                setTimeout(() => {
                                    if (typeof actualizarEstadoBotonContinuar === 'function') {
                                        actualizarEstadoBotonContinuar(cursadaId);
                                    }
                                }, 300);
                                setTimeout(() => {
                                    if (typeof actualizarEstadoBotonContinuar === 'function') {
                                        actualizarEstadoBotonContinuar(cursadaId);
                                    }
                                }, 500);
                            }, 150);

                            // Inicializar valores de descuento
                            setTimeout(() => {
                                if (typeof inicializarValoresDescuento === 'function') {
                                    inicializarValoresDescuento(cursadaId);
                                }
                            }, 200);
                        }, 100);
                    }

                    // Deshabilitar elementos focusables del fondo (excluyendo el modal)
                    const elementosFondo = document.querySelectorAll('body > *:not(.cursada-modal-overlay)');
                    elementosFondo.forEach(el => {
                        // Excluir el modal y sus elementos
                        if (el.contains(modalOverlay) || modalOverlay.contains(el)) {
                            return;
                        }
                        // Solo procesar elementos que son focusables o contienen elementos focusables
                        const focusables = el.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                        if (focusables.length > 0 || el.matches('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])')) {
                            const currentTabindex = el.getAttribute('tabindex');
                            el.dataset.originalTabindex = currentTabindex || 'null';
                            el.setAttribute('tabindex', '-1');
                        }
                    });

                    // También deshabilitar elementos focusables dentro de los elementos del fondo
                    elementosFondo.forEach(container => {
                        if (container.contains(modalOverlay) || modalOverlay.contains(container)) {
                            return;
                        }
                        const focusablesInternos = container.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                        focusablesInternos.forEach(focusable => {
                            if (!modalOverlay.contains(focusable)) {
                                const currentTabindex = focusable.getAttribute('tabindex');
                                focusable.dataset.originalTabindex = currentTabindex || 'null';
                                focusable.setAttribute('tabindex', '-1');
                            }
                        });
                    });

                    // Mostrar el modal
                    modalOverlay.classList.add('cursada-modal-open');
                    document.body.style.overflow = 'hidden';

                    // Establecer focus en el primer campo del formulario después de que se inicialice
                    setTimeout(() => {
                        const primerInput = modalBody.querySelector('input[name="nombre"]');
                        if (primerInput) {
                            primerInput.focus();
                        }
                    }, 150);

                    // Event listener para cerrar
                    if (modalClose) {
                        modalClose.onclick = cerrarModal;
                    }

                    // Cerrar al hacer click en el overlay (parte oscura)
                    modalOverlay.onclick = (e) => {
                        if (e.target === modalOverlay) {
                            cerrarModal();
                        }
                    };
                };

                abrirModal();
            } else {
                // Comportamiento en desktop: toggle simple sin scroll (comportamiento original)
                cerrarTodosLosPaneles(cursadaId);
                if (panel) {
                    panel.classList.remove('panel-hidden');
                    panel.classList.add('panel-visible');
                }
                if (boton) {
                    boton.classList.add('panel-desplegado');
                }
                if (infoTexto) {
                    infoTexto.classList.remove('panel-hidden');
                    infoTexto.classList.add('panel-visible');
                }
                if (typeof inicializarPanelDespuesDeScroll === 'function') {
                    inicializarPanelDespuesDeScroll(cursadaId, panel, infoTexto, formulario, boton);
                }
            }
        });

        formsEventListenersInicializados = true;
    }

    // Función para ordenar cursadas por Dto_Cuota (alterna entre mayor y menor descuento)
    function ordenarPorDescuento() {
        if (!globalCursadas || !cursadasContainer) return;

        // Obtener todas las cursadas del DOM (incluyendo las ocultas)
        const todasLasCursadasItems = Array.from(cursadasContainer.querySelectorAll('.cursada-item'));

        if (todasLasCursadasItems.length === 0) return;

        // Crear un array con los elementos y sus valores de descuento para ordenar
        const cursadasConDescuento = todasLasCursadasItems.map(item => {
            // Buscar el ID en los elementos hijos que tienen data-cursada-id
            const elementoConId = item.querySelector('[data-cursada-id]');
            if (elementoConId) {
                const cursadaIdStr = elementoConId.getAttribute('data-cursada-id');
                // El formato es 'cursada-{id}', extraer el ID numérico
                const idMatch = cursadaIdStr.match(/cursada-(\d+)/);
                if (idMatch) {
                    const cursadaId = parseInt(idMatch[1]);
                    // Buscar la cursada original por ID
                    const cursada = globalCursadas.find(c => c.id == cursadaId);
                    if (cursada) {
                        const descuento = Math.abs(parseFloat(cursada.Dto_Cuota || 0));
                        return {
                            element: item,
                            descuento: descuento,
                            cursadaId: cursadaId
                        };
                    }
                }
            }
            return null;
        }).filter(item => item !== null);

        // Si no hay cursadas válidas, no hacer nada
        if (cursadasConDescuento.length === 0) return;

        // Ordenar por descuento (alterna entre descendente y ascendente)
        // Si hay empate en descuento, mantener el orden por ID (estable)
        cursadasConDescuento.sort((a, b) => {
            if (b.descuento !== a.descuento) {
                // Alternar entre orden descendente y ascendente
                return ordenDescendente ? (b.descuento - a.descuento) : (a.descuento - b.descuento);
            }
            // Si tienen el mismo descuento, mantener el orden por ID
            return a.cursadaId - b.cursadaId;
        });

        // Guardar el estado de visibilidad de cada elemento antes de reordenar
        const estadosVisibilidad = cursadasConDescuento.map(item => ({
            element: item.element,
            display: item.element.style.display
        }));

        // Reordenar los elementos en el DOM usando DocumentFragment
        const fragment = document.createDocumentFragment();
        cursadasConDescuento.forEach(item => {
            fragment.appendChild(item.element);
        });

        // Limpiar el contenedor y agregar los elementos ordenados
        // Esto asegura que siempre se reordene correctamente, incluso en múltiples clics
        cursadasContainer.innerHTML = '';
        cursadasContainer.appendChild(fragment);

        // Restaurar el estado de visibilidad de cada elemento
        estadosVisibilidad.forEach(estado => {
            if (estado.display) {
                estado.element.style.display = estado.display;
            }
        });

        // Actualizar el texto del botón para indicar el orden que se acaba de aplicar
        // Desktop
        const ordenarBtn = document.querySelector('.inscripcion-filtros-ordenar');
        if (ordenarBtn) {
            const textoDestacado = ordenarBtn.querySelector('.inscripcion-filtros-ordenar-destacado');
            const chevron = ordenarBtn.querySelector('.inscripcion-filtros-ordenar-chevron');
            if (textoDestacado) {
                // Mostrar el orden que se acaba de aplicar (ordenDescendente antes de alternar)
                textoDestacado.textContent = ordenDescendente ? 'Mayor descuento' : 'Menor descuento';
            }
            if (chevron) {
                // Cambiar la dirección del chevron según el orden aplicado
                chevron.textContent = ordenDescendente ? '▼' : '▲';
            }
        }

        // Mobile
        const ordenarBtnMobile = document.querySelector('.inscripcion-mobile-ordenar');
        if (ordenarBtnMobile) {
            const textoDestacadoMobile = ordenarBtnMobile.querySelector('.inscripcion-mobile-ordenar-destacado');
            const chevronMobile = ordenarBtnMobile.querySelector('.inscripcion-mobile-ordenar-chevron');
            if (textoDestacadoMobile) {
                // Mostrar el orden que se acaba de aplicar (ordenDescendente antes de alternar)
                textoDestacadoMobile.textContent = ordenDescendente ? 'Mayor descuento' : 'Menor descuento';
            }
            if (chevronMobile) {
                // Cambiar la dirección del chevron según el orden aplicado
                chevronMobile.textContent = ordenDescendente ? '▼' : '▲';
            }
        }

        // Alternar el estado del ordenamiento para el próximo clic
        ordenDescendente = !ordenDescendente;

        // Re-aplicar los filtros para mantener el estado de visibilidad correcto
        if (typeof filtrarCursadas === 'function') {
            filtrarCursadas();
        }
    }

    // Función para renderizar cursadas desde JSON (optimizada)
    function renderCursadas(cursadas, promoBadgeInfo) {
        // Guardar las cursadas originales
        globalCursadas = cursadas;
        // Guardar promoBadgeInfo globalmente
        globalPromoBadgeInfo = promoBadgeInfo;
        const template = document.getElementById('cursada-template');
        if (!template || !cursadasContainer) return;

        // Usar DocumentFragment para mejor rendimiento
        const fragment = document.createDocumentFragment();
        let processed = 0;
        const batchSize = 10; // Procesar en lotes de 10

        function processBatch() {
            const end = Math.min(processed + batchSize, cursadas.length);

            for (let i = processed; i < end; i++) {
                const cursada = cursadas[i];
                const clone = template.content.cloneNode(true);
                const item = clone.querySelector('.cursada-item');
                const pre = cursada.pre_calculado || {};

                // Actualizar atributos data
                item.setAttribute('data-carrera', cursada.carrera || '');
                item.setAttribute('data-sede', cursada.sede || '');
                item.setAttribute('data-modalidad', cursada.xModalidad || '');
                item.setAttribute('data-regimen', cursada.Régimen || '');
                item.setAttribute('data-turno', cursada.xTurno || '');
                item.setAttribute('data-dia', cursada.xDias || '');
                item.setAttribute('data-promocion', pre.tieneDescuento ? 'con_descuento' : 'sin_descuento');
                item.setAttribute('data-id-curso', cursada.ID_Curso || '');

                const cursadaId = 'cursada-' + cursada.id;

                // Actualizar data attribute de Promo_Mat_logo en el panel
                const panel = clone.querySelector('.cursada-valores-panel');
                if (panel) {
                    panel.setAttribute('data-promo-mat-logo', cursada.Promo_Mat_logo || '');
                }

                // Actualizar todos los IDs y referencias
                clone.querySelectorAll('[id]').forEach(el => {
                    const oldId = el.id;
                    if (oldId.includes('TEMPLATE_ID')) {
                        el.id = oldId.replace(/TEMPLATE_ID/g, cursadaId);
                    }
                });

                clone.querySelectorAll('[data-cursada-id]').forEach(el => {
                    el.setAttribute('data-cursada-id', cursadaId);
                });

                clone.querySelectorAll('[for]').forEach(el => {
                    const oldFor = el.getAttribute('for');
                    if (oldFor && oldFor.includes('TEMPLATE_ID')) {
                        el.setAttribute('for', oldFor.replace(/TEMPLATE_ID/g, cursadaId));
                    }
                });

                // Limpiar clases por defecto del template
                item.classList.remove('sin-vacantes');

                // Aplicar clase sin-vacantes solo si realmente no hay vacantes
                const vacantesCount = pre.vacantes || cursada.Vacantes || 0;
                const sinVacantes = (vacantesCount === 0);
                if (sinVacantes) {
                    item.classList.add('sin-vacantes');
                }

                // Actualizar contenido dinámico (desktop y mobile)
                const diaTurnoContainers = clone.querySelectorAll('.cursada-item-dia-turno');
                diaTurnoContainers.forEach(diaTurnoContainer => {
                    const diaTextos = diaTurnoContainer.querySelectorAll('.cursada-dia-turno-texto');
                    if (diaTextos.length >= 1) {
                        // Primer span: día
                        const diaValue = pre.diaMayusculas || 'N/A';
                        if (diaValue !== 'TEMPLATE_DIA') {
                            diaTextos[0].textContent = diaValue;
                        }
                    }
                    if (diaTextos.length >= 2) {
                        // Segundo span: turno (ya tiene "TURNO " en el HTML, solo reemplazar el valor)
                        const turnoValue = pre.turnoMayusculas || 'N/A';
                        if (turnoValue !== 'TEMPLATE_TURNO') {
                            // El HTML tiene "TURNO TEMPLATE_TURNO", reemplazar solo la parte del valor
                            const currentText = diaTextos[1].textContent;
                            if (currentText.includes('TEMPLATE_TURNO')) {
                                diaTextos[1].textContent = 'TURNO ' + turnoValue;
                            } else {
                                diaTextos[1].textContent = 'TURNO ' + turnoValue;
                            }
                        }
                    }
                });

                // Actualizar horarios (desktop y mobile)
                const horarios = clone.querySelectorAll('.cursada-dia-turno-horario');
                horarios.forEach(horario => {
                    if (pre.horarioFormateado && pre.horarioFormateado !== 'TEMPLATE_HORARIO') {
                        horario.textContent = ' (' + pre.horarioFormateado + ')';
                    } else {
                        horario.remove();
                    }
                });

                // Actualizar valores en columnas originales (desktop)
                const valoresItems = clone.querySelectorAll('.cursada-item-value');
                if (valoresItems.length >= 1) {
                    valoresItems[0].textContent = pre.fechaFormateada || 'N/A';
                }
                if (valoresItems.length >= 2) {
                    const modalidadCompleta = pre.modalidadCompleta || 'N/A';
                    if (modalidadCompleta !== 'TEMPLATE_MODALIDAD') {
                        valoresItems[1].textContent = modalidadCompleta;
                    }
                }
                if (valoresItems.length >= 3) {
                    const sedeSimplificada = pre.sedeSimplificada || 'N/A';
                    if (sedeSimplificada !== 'TEMPLATE_SEDE') {
                        valoresItems[2].textContent = sedeSimplificada;
                    }
                }

                // Actualizar valores en fila 2 móvil
                const valoresFila2 = clone.querySelectorAll('.cursada-item-fila-2-value');
                if (valoresFila2.length >= 1) {
                    valoresFila2[0].textContent = pre.fechaFormateada || 'N/A';
                }
                if (valoresFila2.length >= 2) {
                    const modalidadCompleta = pre.modalidadCompleta || 'N/A';
                    if (modalidadCompleta !== 'TEMPLATE_MODALIDAD') {
                        valoresFila2[1].textContent = modalidadCompleta;
                    }
                }
                if (valoresFila2.length >= 3) {
                    const sedeSimplificada = pre.sedeSimplificada || 'N/A';
                    if (sedeSimplificada !== 'TEMPLATE_SEDE') {
                        valoresFila2[2].textContent = sedeSimplificada;
                    }
                }

                // Actualizar vacantes (desktop y mobile)
                const vacantes = clone.querySelectorAll('.cursada-lugares-texto strong');
                vacantes.forEach(vacante => {
                    vacante.textContent = vacantesCount;
                });

                // Actualizar descuento (desktop y mobile)
                const descuentoWrappers = clone.querySelectorAll('.cursada-descuento-wrapper');
                descuentoWrappers.forEach(descuentoWrapper => {
                    const descuento = Math.abs(pre.dtoCuotaValue || 0);
                    if (descuento > 0.01 && vacantesCount > 0) {
                        const badgeDescuento = descuentoWrapper.querySelector('.cursada-badge-descuento');
                        if (badgeDescuento) badgeDescuento.textContent = Math.round(descuento) + '% OFF';
                        descuentoWrapper.style.display = 'block';
                    } else {
                        descuentoWrapper.style.display = 'none';
                    }
                });

                // Actualizar badge promo (desktop y mobile)
                const promoBadgeImgs = clone.querySelectorAll('.cursada-promo-badge');
                promoBadgeImgs.forEach(promoBadgeImg => {
                    if (promoBadgeImg) {
                        const promoMatLogo = (cursada.Promo_Mat_logo || '').toLowerCase().trim();
                        // Verificar si debe mostrarse el badge: Promo_Mat_logo === 'mostrar' Y existe promoBadge activo
                        if (promoMatLogo === 'mostrar' && promoBadgeInfo && promoBadgeInfo.archivo && promoBadgeInfo.image_path) {
                            promoBadgeImg.src = promoBadgeInfo.image_path;
                            promoBadgeImg.style.display = 'block';
                            promoBadgeImg.alt = 'Promo Mat Logo';
                        } else {
                            // Ocultar el badge si no debe mostrarse (no remover, solo ocultar)
                            promoBadgeImg.style.display = 'none';
                        }
                    }
                });

                // Actualizar botones ver valores (desktop y mobile) - IMPORTANTE: remover clases y disabled por defecto
                const btnVerValores = clone.querySelectorAll('.cursada-btn-ver-valores');
                btnVerValores.forEach(btn => {
                    // Asegurar que el data-cursada-id esté correctamente asignado
                    btn.setAttribute('data-cursada-id', cursadaId);
                    btn.classList.remove('sin-vacantes');
                    btn.disabled = false;
                    // Solo aplicar sin-vacantes si realmente no hay vacantes
                    if (sinVacantes) {
                        btn.classList.add('sin-vacantes');
                        btn.disabled = true;
                    }
                });

                // Actualizar valores de cuota
                const cuotaInfo = clone.querySelector('.cursada-cuota-info');
                if (cuotaInfo) {
                    cuotaInfo.setAttribute('data-cta-web', cursada.Cta_Web || 0);
                    cuotaInfo.setAttribute('data-dto-cuota', cursada.Dto_Cuota || 0);
                    cuotaInfo.setAttribute('data-cuotas', cursada.cuotas || 12);
                    cuotaInfo.setAttribute('data-matric-base', cursada.Matric_Base || 0);
                    cuotaInfo.setAttribute('data-sin-iva-mat', cursada.Sin_iva_Mat || 0);

                    const valorCuota = cuotaInfo.querySelector('.cursada-cuota-valor');
                    if (valorCuota) {
                        const valor = parseFloat(cursada.Cta_Web || 0).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        valorCuota.innerHTML = '$' + valor + '<span class="cursada-cuota-asterisco">*</span>';
                    }

                    const descuentoTexto = cuotaInfo.querySelector('.cursada-cuota-descuento');
                    const dtoCuotaValue = Math.abs(parseFloat(cursada.Dto_Cuota || 0));
                    if (dtoCuotaValue > 0) {
                        if (descuentoTexto) {
                            descuentoTexto.textContent = '¡Descuento del ' + Math.round(dtoCuotaValue) + '% Aplicado! - ';
                        }
                    } else if (descuentoTexto) {
                        descuentoTexto.remove();
                    }

                    const cantidadCuotas = cuotaInfo.querySelector('.cursada-cuota-cantidad-valor');
                    if (cantidadCuotas) cantidadCuotas.textContent = cursada.cuotas || 12;

                    // Actualizar cuotas totales en el texto informativo
                    const cuotasTotales = clone.querySelector('.cursada-cuotas-totales');
                    if (cuotasTotales) {
                        cuotasTotales.textContent = cursada.cuotas || 12;
                    }

                    const precioTotal = cuotaInfo.querySelector('.cursada-cuota-precio-total');
                    if (precioTotal) {
                        const precio = parseFloat(cursada.Sin_IVA_cta || 0).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        precioTotal.textContent = '$' + precio;
                    }
                }

                // Los valores se inicializan como "n/d" y se actualizan cuando el formulario se valida
                // No actualizar aquí, se hará después de validar el formulario

                // Actualizar badge promo en el desplegable (al lado de Descuento)
                const promoBadgeDescuento = clone.querySelector('#promo-badge-descuento-' + cursadaId);
                if (promoBadgeDescuento) {
                    const promoMatLogo = (cursada.Promo_Mat_logo || '').toLowerCase().trim();
                    // Verificar si debe mostrarse el badge: Promo_Mat_logo === 'mostrar' Y existe promoBadge activo
                    // Usar la misma lógica que para el badge del header
                    if (promoMatLogo === 'mostrar' && promoBadgeInfo && promoBadgeInfo.archivo && promoBadgeInfo.image_path) {
                        promoBadgeDescuento.src = promoBadgeInfo.image_path;
                        promoBadgeDescuento.removeAttribute('style');
                        promoBadgeDescuento.style.setProperty('display', 'inline-block', 'important');
                        promoBadgeDescuento.style.setProperty('visibility', 'visible', 'important');
                        promoBadgeDescuento.style.setProperty('opacity', '1', 'important');
                        promoBadgeDescuento.alt = 'Promo Mat Logo';
                        promoBadgeDescuento.classList.add('cursada-promo-badge-descuento-visible');
                    } else {
                        // Ocultar el badge si no debe mostrarse
                        promoBadgeDescuento.style.display = 'none';
                        promoBadgeDescuento.classList.remove('cursada-promo-badge-descuento-visible');
                    }
                }

                // Actualizar link de editar form para que tenga el estilo correcto (inline-style porque es dinámico en el template)
                const linkEditar = clone.querySelector('.cursada-editar-form-link');
                if (linkEditar) {
                    linkEditar.textContent = "(Editar datos)";
                    // Aplicar estilos inline para asegurar consistencia
                    linkEditar.style.fontFamily = "'Montserrat', sans-serif";
                    linkEditar.style.fontWeight = "600";
                    linkEditar.style.textDecoration = "underline";
                    linkEditar.style.color = "#65E09C";
                    linkEditar.style.cursor = "pointer";
                    linkEditar.style.transition = "opacity 0.2s, color 0.2s";
                    linkEditar.style.textAlign = "left";
                    linkEditar.style.flexShrink = "0";
                    linkEditar.style.marginLeft = "10px";
                    // El font-size clamp se mantiene desde el HTML/CSS o se aplica aquí si es necesario
                }

                // Inicializar botón reservar como disabled
                const btnReservar = clone.querySelector('.cursada-btn-reservar');
                if (btnReservar) {
                    btnReservar.disabled = true;
                }

                // Inicializar elementos deshabilitados hasta que el formulario se valide
                const linkCodigo = clone.querySelector('#link-codigo-' + cursadaId);
                if (linkCodigo) {
                    linkCodigo.classList.add('cursada-link-disabled');
                }
                const checkboxTerminos = clone.querySelector('#acepto-terminos-' + cursadaId);
                if (checkboxTerminos) {
                    checkboxTerminos.disabled = true;

                    // Agregar listener para cuando se clickea el checkbox
                    checkboxTerminos.addEventListener('change', function (e) {
                        if (this.checked && !this.disabled) {
                            // Obtener el ID del lead desde el panel o atributo guardado
                            const panel = document.getElementById('panel-' + cursadaId);
                            const leadId = panel ? panel.getAttribute('data-lead-id') : null;

                            if (leadId) {
                                fetch('/leads/' + leadId + '/terms', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                    }
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            console.log('Términos aceptados guardados para lead:', leadId);
                                            // Habilitar botón reservar si existe
                                            const btnReservar = document.querySelector('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');
                                            if (btnReservar) {
                                                btnReservar.disabled = false;
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error al guardar términos:', error));
                            } else {
                                console.error('No se encontró leadId para actualizar términos');
                            }
                        }
                    });
                }
                const linkVer = clone.querySelector('#link-ver-' + cursadaId);
                if (linkVer) {
                    linkVer.classList.add('cursada-link-disabled');
                }

                fragment.appendChild(clone);
            }

            processed = end;

            if (processed < cursadas.length) {
                // Usar requestAnimationFrame para no bloquear el UI
                requestAnimationFrame(processBatch);
            } else {
                // Insertar todo el fragmento de una vez (más eficiente)
                cursadasContainer.appendChild(fragment);
                // Llamar a initializeFiltering DESPUÉS de que todas las cursadas estén en el DOM
                setTimeout(() => {
                    initializeFiltering();
                    initializeOrdenar();
                    // Inicializar formularios después de renderizar
                    if (typeof initializeFormularios === 'function') {
                        initializeFormularios();
                    }
                    // Verificar si hay datos completos guardados y restaurar el estado
                    // Usar múltiples delays para asegurar que todo esté listo
                    setTimeout(() => {
                        if (typeof verificarYRestaurarEstadoCompletado === 'function') {
                            const estadoRestaurado = verificarYRestaurarEstadoCompletado();
                            // Si no se restauró el estado, cargar datos normalmente
                            if (!estadoRestaurado && typeof cargarDatosEnTodosLosFormularios === 'function') {
                                cargarDatosEnTodosLosFormularios();
                            }
                        }
                    }, 500);

                    // Segundo intento por si acaso
                    setTimeout(() => {
                        if (typeof verificarYRestaurarEstadoCompletado === 'function') {
                            verificarYRestaurarEstadoCompletado();
                        }
                    }, 1000);
                }, 0);
            }
        }

        // Iniciar procesamiento por lotes
        processBatch();
    }

    // Función para inicializar el botón de ordenar
    function initializeOrdenar() {
        // Evitar agregar múltiples event listeners
        if (ordenarBtnInicializado) return;

        // Desktop
        const ordenarBtn = document.querySelector('.inscripcion-filtros-ordenar');
        if (ordenarBtn) {
            ordenarBtn.addEventListener('click', function (e) {
                e.preventDefault();
                ordenarPorDescuento();
            });
        }

        // Mobile
        const ordenarBtnMobile = document.querySelector('.inscripcion-mobile-ordenar');
        if (ordenarBtnMobile) {
            ordenarBtnMobile.addEventListener('click', function (e) {
                e.preventDefault();
                ordenarPorDescuento();
            });
        }

        // Dropdown de filtros mobile - Abrir modal
        const filtrosDropdownMobile = document.querySelector('.inscripcion-mobile-filtros-dropdown');
        const filtrosModal = document.getElementById('filtros-modal-mobile');
        const filtrosModalClose = document.getElementById('filtros-modal-close');
        const filtrosModalOverlay = document.querySelector('.filtros-modal-overlay');
        const filtrosModalContent = document.querySelector('.filtros-modal-content');

        // Función para calcular la altura del header
        function calcularAlturaHeader() {
            const header = document.querySelector('.header');
            const stickyBar = document.querySelector('.sticky-bar');
            let alturaTotal = 0;

            if (stickyBar && stickyBar.offsetParent !== null) {
                alturaTotal += stickyBar.offsetHeight;
            }

            if (header && header.offsetParent !== null) {
                alturaTotal += header.offsetHeight;
            }

            return alturaTotal || 80; // Fallback a 80px si no se encuentra
        }

        // Función para abrir el modal
        function abrirModalFiltros() {
            if (filtrosModal) {
                const alturaHeader = calcularAlturaHeader();

                filtrosModal.classList.add('active');
                document.body.classList.add('filtros-modal-open');
                document.body.style.overflow = 'hidden';

                // Ajustar padding-top del contenido para que empiece debajo del header
                // Este valor debe ser dinámico porque depende de la altura real del header
                if (filtrosModalContent) {
                    filtrosModalContent.style.paddingTop = alturaHeader + 'px';
                }
            }
        }

        // Función para cerrar el modal
        function cerrarModalFiltros() {
            if (filtrosModal) {
                filtrosModal.classList.remove('active');
                document.body.classList.remove('filtros-modal-open');
                document.body.style.overflow = '';

                // Restaurar padding del contenido (este sí necesita ser dinámico)
                if (filtrosModalContent) {
                    filtrosModalContent.style.paddingTop = '';
                }
            }
        }

        if (filtrosDropdownMobile && filtrosModal) {
            filtrosDropdownMobile.addEventListener('click', function (e) {
                e.preventDefault();
                abrirModalFiltros();
            });
        }

        // Cerrar modal con botón X
        if (filtrosModalClose) {
            filtrosModalClose.addEventListener('click', function (e) {
                e.preventDefault();
                cerrarModalFiltros();
            });
        }

        // Cerrar modal con overlay
        if (filtrosModalOverlay) {
            filtrosModalOverlay.addEventListener('click', function (e) {
                e.preventDefault();
                cerrarModalFiltros();
            });
        }

        // Botón "Limpiar filtros" del modal
        const limpiarFiltrosModal = document.getElementById('limpiar-filtros-modal');
        if (limpiarFiltrosModal) {
            limpiarFiltrosModal.addEventListener('click', function (e) {
                e.preventDefault();
                // Simular clic en el botón "Borrar todo" existente
                const borrarTodoBtn = document.getElementById('borrar-todo-filtros');
                if (borrarTodoBtn) {
                    borrarTodoBtn.click();
                }
            });
        }

        // Botón "Ver x resultados" del modal
        const verResultadosModal = document.getElementById('ver-resultados-modal');
        if (verResultadosModal) {
            verResultadosModal.addEventListener('click', function (e) {
                e.preventDefault();
                cerrarModalFiltros();
            });
        }

        ordenarBtnInicializado = true;
    }

    // Función para inicializar el filtrado después de cargar las cursadas
    function initializeFiltering() {
        const sedeRows = document.querySelectorAll('.contacto-sede-row[data-sede]');

        sedeRows.forEach(row => {
            row.addEventListener('click', function () {
                const sedeId = this.getAttribute('data-sede');
                const content = document.getElementById(sedeId + '-content');

                // Cerrar todos los otros contenidos
                document.querySelectorAll('.contacto-sede-content').forEach(otherContent => {
                    if (otherContent !== content) {
                        otherContent.classList.remove('active');
                    }
                });

                // Toggle del contenido actual
                if (content) {
                    content.classList.toggle('active');
                }
            });
        });

        // Funcionalidad de filtrado de cursadas
        const cursadasItems = document.querySelectorAll('.cursada-item');
        const contadorResultados = document.getElementById('contador-resultados');
        const filtrosAplicadosContainer = document.getElementById('filtros-aplicados');
        const borrarTodoBtn = document.getElementById('borrar-todo-filtros');
        const opcionesFiltro = document.querySelectorAll('.filtro-opcion');

        // Estado de los filtros seleccionados
        let filtrosSeleccionados = {
            carrera: '',
            sede: '',
            modalidad: '',
            turno: '',
            dia: '',
            promocion: ''
        };

        // Inicializar filtro de carrera si hay uno pre-seleccionado
        const carreraPreSeleccionada = document.querySelector('.filtro-opcion[data-tipo="carrera"][data-seleccionado="true"]');
        if (carreraPreSeleccionada) {
            const valorCarrera = carreraPreSeleccionada.getAttribute('data-valor');
            if (valorCarrera) {
                filtrosSeleccionados.carrera = valorCarrera;
            }
        }

        // Función para actualizar los colores de las opciones de filtro
        function actualizarColoresFiltros() {
            // Buscar todos los filtros dinámicamente para incluir los del modal
            const todasLasOpciones = document.querySelectorAll('.filtro-opcion');
            todasLasOpciones.forEach(opcion => {
                const tipo = opcion.getAttribute('data-tipo');
                const valor = opcion.getAttribute('data-valor');
                const estaSeleccionado = filtrosSeleccionados[tipo] === valor;

                opcion.style.color = estaSeleccionado ? '#65E09C' : 'var(--text-white)';
                opcion.setAttribute('data-seleccionado', estaSeleccionado ? 'true' : 'false');
            });
        }

        // Función para obtener el texto de visualización de un filtro
        function obtenerTextoFiltro(tipo, valor) {
            if (!valor) return null;

            // Buscar la opción correspondiente para obtener su texto
            const opcion = document.querySelector(`.filtro-opcion[data-tipo="${tipo}"][data-valor="${valor}"]`);
            if (opcion) {
                let texto = opcion.textContent.trim();
                // Si es modalidad, quitar la parte de los meses ( : X Meses) y los guiones
                if (tipo === 'modalidad') {
                    texto = texto.replace(/\s*:\s*\d+\s*Meses?/gi, ''); // Quitar ": 10 Meses"
                    texto = texto.replace(/\s*-\s*/g, ' '); // Reemplazar " - " con un espacio
                    texto = texto.trim();
                }
                return texto;
            }

            // Fallback: corregir nombres de carrera
            if (tipo === 'carrera') {
                const mapeos = {
                    'MECÁNICA Y TECNOLOGÍAS DEL AUTÓMOVIL 1': 'Mecánica y Tecnologías del Automóvil',
                    'ELECTRICIDAD Y ELECTRÓNICA DEL AUTOMÓVIL': 'Electricidad y Electrónica del Automóvil',
                    'MECÁNICA Y ELECTRÓNICA DE MOTOS 1': 'Mecánica y Electrónica de la Motocicleta'
                };
                const valorUpper = valor.toUpperCase().trim();
                if (mapeos[valorUpper]) {
                    return mapeos[valorUpper];
                }
                // Búsqueda parcial
                if (valorUpper.includes('MECÁNICA Y TECNOLOGÍAS DEL AUTÓMOVIL')) {
                    return 'Mecánica y Tecnologías del Automóvil';
                }
                if (valorUpper.includes('ELECTRICIDAD Y ELECTRÓNICA DEL AUTOMÓVIL')) {
                    return 'Electricidad y Electrónica del Automóvil';
                }
                if (valorUpper.includes('MECÁNICA Y ELECTRÓNICA DE MOTOS')) {
                    return 'Mecánica y Electrónica de la Motocicleta';
                }
            }

            // Fallback: corregir modalidad si es necesario
            if (tipo === 'modalidad') {
                // Si el valor contiene "|", es una combinación modalidad|regimen
                if (valor.includes('|')) {
                    const [modalidad, regimen] = valor.split('|');
                    let modalidadDisplay = modalidad.replace(/Sempresencial/gi, 'Semipresencial');
                    // NO agregar duración en los badges, solo mostrar modalidad - régimen
                    const combinacion = modalidadDisplay + ' - ' + regimen;
                    return combinacion;
                }
                return valor.replace(/Sempresencial/gi, 'Semipresencial');
            }

            // Fallback: para promociones
            if (tipo === 'promocion') {
                if (valor === 'con_descuento') {
                    return 'Cuotas con descuento';
                }
                return valor;
            }

            // Fallback: convertir días a nombres completos
            if (tipo === 'dia') {
                const mapeoDias = {
                    'lun': 'Lunes', 'lunes': 'Lunes',
                    'mar': 'Martes', 'martes': 'Martes',
                    'mie': 'Miércoles', 'mié': 'Miércoles', 'miercoles': 'Miércoles', 'miércoles': 'Miércoles',
                    'jue': 'Jueves', 'jueves': 'Jueves',
                    'vie': 'Viernes', 'viernes': 'Viernes',
                    'sab': 'Sábado', 'sáb': 'Sábado', 'sabado': 'Sábado', 'sábado': 'Sábado',
                    'dom': 'Domingo', 'domingo': 'Domingo'
                };

                const valorLower = valor.toLowerCase();
                const partes = valorLower.split(/[\s\-]+/);
                const diasCompletos = [];

                partes.forEach(function (parte) {
                    parte = parte.trim();
                    if (!parte) return;

                    let diaCompleto = null;
                    for (const abrev in mapeoDias) {
                        if (parte === abrev || parte.indexOf(abrev) === 0) {
                            diaCompleto = mapeoDias[abrev];
                            break;
                        }
                    }

                    if (diaCompleto) {
                        diasCompletos.push(diaCompleto);
                    } else {
                        diasCompletos.push(parte.charAt(0).toUpperCase() + parte.slice(1));
                    }
                });

                return diasCompletos.join(' y ');
            }

            // Fallback: corregir y simplificar sede si es necesario
            if (tipo === 'sede') {
                // Primero corregir el nombre (convertir a formato completo)
                const conversiones = {
                    'constituyentes': 'Villa Urquiza - Av. Constituyentes 4631',
                    'congreso': 'Villa Urquiza - Av. Congreso 5672',
                    'moron': 'Morón - E. Grant 301',
                    'morón': 'Morón - E. Grant 301',
                    'banfield': 'Banfield - Av. Hipólito Yrigoyen 7536',
                    'san isidro': 'San Isidro - Camino de la Ribera Nte. 150',
                    'beiró': 'Villa Devoto - Bermudez 3192',
                    'beiro': 'Villa Devoto - Bermudez 3192'
                };
                const valorLower = valor.toLowerCase().trim();
                let nombreCompleto = valor;

                if (conversiones[valorLower]) {
                    nombreCompleto = conversiones[valorLower];
                } else {
                    for (const [key, value] of Object.entries(conversiones)) {
                        if (valorLower.includes(key)) {
                            nombreCompleto = value;
                            break;
                        }
                    }
                }

                // Luego simplificar el nombre (igual que en el panel filtrado)
                const nombreLower = nombreCompleto.toLowerCase().trim();
                if (nombreLower.includes('congreso') && nombreLower.includes('urquiza')) {
                    return 'Urquiza Congreso';
                }
                if (nombreLower.includes('constituyentes') && nombreLower.includes('urquiza')) {
                    return 'Urquiza Constituyentes';
                }
                if (nombreLower.includes('banfield')) {
                    return 'Banfield';
                }
                if (nombreLower.includes('devoto') || nombreLower.includes('beiró') || nombreLower.includes('beiro') || nombreLower.includes('bermudez')) {
                    return 'Devoto';
                }
                if (nombreLower.includes('moron') || nombreLower.includes('morón')) {
                    return 'Morón';
                }
                if (nombreLower.includes('san isidro')) {
                    return 'San Isidro';
                }

                return nombreCompleto;
            }

            return valor;
        }

        // Función para actualizar los chips de filtros aplicados
        function actualizarChipsFiltros() {
            // Limpiar chips existentes en desktop
            filtrosAplicadosContainer.innerHTML = '';

            // Limpiar chips existentes en mobile
            const filtrosAplicadosMobile = document.getElementById('filtros-aplicados-mobile');
            if (filtrosAplicadosMobile) {
                filtrosAplicadosMobile.innerHTML = '';
            }

            // Contar filtros seleccionados
            let cantidadFiltros = 0;

            Object.keys(filtrosSeleccionados).forEach(tipo => {
                const valor = filtrosSeleccionados[tipo];
                if (valor) {
                    cantidadFiltros++;
                    const texto = obtenerTextoFiltro(tipo, valor);
                    if (texto) {
                        // Crear chip para desktop
                        const chip = document.createElement('span');
                        chip.className = 'filtro-chip';

                        const textoChip = document.createElement('span');
                        textoChip.textContent = texto;

                        const btnEliminar = document.createElement('span');
                        btnEliminar.className = 'filtro-chip-eliminar';
                        btnEliminar.textContent = 'X';
                        btnEliminar.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            eliminarFiltro(tipo);
                        });

                        chip.appendChild(textoChip);
                        chip.appendChild(btnEliminar);
                        filtrosAplicadosContainer.appendChild(chip);

                        // Crear chip para mobile (mismo estilo)
                        if (filtrosAplicadosMobile) {
                            const chipMobile = chip.cloneNode(true);
                            // Re-agregar el event listener al botón de eliminar del chip clonado
                            const btnEliminarMobile = chipMobile.querySelector('.filtro-chip-eliminar');
                            if (btnEliminarMobile) {
                                btnEliminarMobile.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    eliminarFiltro(tipo);
                                });
                            }
                            filtrosAplicadosMobile.appendChild(chipMobile);
                        }
                    }
                }
            });

            // Actualizar contador de filtros en mobile
            const contadorFiltrosMobile = document.getElementById('contador-filtros-mobile');
            if (contadorFiltrosMobile) {
                contadorFiltrosMobile.textContent = cantidadFiltros;
            }
        }

        // Función para eliminar un filtro específico
        function eliminarFiltro(tipo) {
            filtrosSeleccionados[tipo] = '';
            actualizarColoresFiltros();
            filtrarCursadas();
        }

        // Función para borrar todos los filtros
        function borrarTodosFiltros() {
            filtrosSeleccionados = {
                carrera: '',
                sede: '',
                modalidad: '',
                turno: '',
                dia: '',
                promocion: ''
            };
            actualizarColoresFiltros();
            filtrarCursadas();
        }

        function filtrarCursadas() {
            const carreraSeleccionada = filtrosSeleccionados.carrera;
            const sedeSeleccionada = filtrosSeleccionados.sede;
            const modalidadSeleccionada = filtrosSeleccionados.modalidad;
            const turnoSeleccionado = filtrosSeleccionados.turno;
            const diaSeleccionado = filtrosSeleccionados.dia;
            const promocionSeleccionada = filtrosSeleccionados.promocion;


            // Re-obtener todas las cursadas del DOM (por si hay cambios)
            const todasLasCursadas = document.querySelectorAll('.cursada-item');

            let visibleCount = 0;

            todasLasCursadas.forEach((item, index) => {
                const carrera = (item.getAttribute('data-carrera') || '').trim();
                const sede = (item.getAttribute('data-sede') || '').trim();
                const modalidad = (item.getAttribute('data-modalidad') || '').trim();
                const regimen = (item.getAttribute('data-regimen') || '').trim();
                const turno = (item.getAttribute('data-turno') || '').trim();
                const dia = (item.getAttribute('data-dia') || '').trim();
                const promocion = (item.getAttribute('data-promocion') || '').trim();

                // Función helper para normalizar y comparar strings (case-insensitive)
                function normalizarComparar(valor1, valor2) {
                    if (!valor1 || !valor2) return valor1 === valor2;
                    const v1 = valor1.toLowerCase().trim();
                    const v2 = valor2.toLowerCase().trim();
                    // Comparación exacta
                    if (v1 === v2) return true;
                    // Comparación parcial: si uno contiene al otro
                    if (v1.includes(v2) || v2.includes(v1)) return true;
                    return false;
                }

                // Verificar si coincide con los filtros
                // Si el filtro está vacío (''), mostrar todas las opciones
                const coincideCarrera = carreraSeleccionada === '' || normalizarComparar(carrera, carreraSeleccionada);

                // Para sede: comparación flexible (puede ser "URQUIZA CONSTITUYENTES" vs "URQUIZA" o "Urquiza")
                let coincideSede = true;
                if (sedeSeleccionada !== '') {
                    const sedeNormalizada = sede.toLowerCase().trim();
                    const sedeFiltroNormalizada = sedeSeleccionada.toLowerCase().trim();
                    // Comparación exacta
                    coincideSede = (sedeNormalizada === sedeFiltroNormalizada);
                    // Si no coincide exactamente, verificar si uno contiene al otro (para manejar "URQUIZA CONSTITUYENTES" vs "URQUIZA")
                    if (!coincideSede) {
                        coincideSede = (sedeNormalizada.includes(sedeFiltroNormalizada) || sedeFiltroNormalizada.includes(sedeNormalizada));
                    }
                }

                // Para modalidad: el valor es "modalidad|regimen"
                let coincideModalidad = true;
                if (modalidadSeleccionada !== '') {
                    if (modalidadSeleccionada.includes('|')) {
                        // Es una combinación modalidad|regimen
                        const [modalidadFiltro, regimenFiltro] = modalidadSeleccionada.split('|');
                        // Normalizar "Sempresencial" a "Semipresencial" en ambos lados
                        let modalidadNormalizada = (modalidad || '').toLowerCase().trim().replace(/sempresencial/g, 'semipresencial');
                        let modalidadFiltroNormalizada = (modalidadFiltro || '').toLowerCase().trim().replace(/sempresencial/g, 'semipresencial');
                        const regimenNormalizado = (regimen || '').toLowerCase().trim();
                        const regimenFiltroNormalizado = (regimenFiltro || '').toLowerCase().trim();

                        // Comparación exacta
                        coincideModalidad = (modalidadNormalizada === modalidadFiltroNormalizada) && (regimenNormalizado === regimenFiltroNormalizado);
                    } else {
                        // Solo modalidad (compatibilidad con formato anterior)
                        coincideModalidad = normalizarComparar(modalidad, modalidadSeleccionada);
                    }
                }

                // Para turno: comparación case-insensitive
                const coincideTurno = turnoSeleccionado === '' || normalizarComparar(turno, turnoSeleccionado);

                // Para día: comparación más flexible porque puede venir como "VIERNES" o "viernes" o "Viernes"
                // El filtro tiene el valor original de la BD, pero puede haber variaciones
                let coincideDia = true;
                if (diaSeleccionado !== '') {
                    const diaNormalizado = dia.toLowerCase().trim();
                    const diaFiltroNormalizado = diaSeleccionado.toLowerCase().trim();
                    // Comparación exacta
                    coincideDia = (diaNormalizado === diaFiltroNormalizado);
                    // Si no coincide, verificar si contienen palabras clave comunes de días
                    if (!coincideDia) {
                        const diasComunes = {
                            'lunes': ['lun', 'lunes'],
                            'martes': ['mar', 'martes'],
                            'miercoles': ['mie', 'miercoles', 'miércoles'],
                            'jueves': ['jue', 'jueves'],
                            'viernes': ['vie', 'viernes'],
                            'sabado': ['sab', 'sabado', 'sábado'],
                            'domingo': ['dom', 'domingo']
                        };
                        // Buscar si ambos valores se refieren al mismo día
                        for (const [diaCompleto, variantes] of Object.entries(diasComunes)) {
                            const diaEnVariantes = variantes.some(v => diaNormalizado.includes(v) || v.includes(diaNormalizado));
                            const filtroEnVariantes = variantes.some(v => diaFiltroNormalizado.includes(v) || v.includes(diaFiltroNormalizado));
                            if (diaEnVariantes && filtroEnVariantes) {
                                coincideDia = true;
                                break;
                            }
                        }
                    }
                }

                const coincidePromocion = promocionSeleccionada === '' || promocion === promocionSeleccionada;

                const pasaTodosLosFiltros = coincideCarrera && coincideSede && coincideModalidad && coincideTurno && coincideDia && coincidePromocion;

                if (pasaTodosLosFiltros) {
                    // Restaurar display original (flex o grid según el CSS)
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                    // Debug temporal: mostrar por qué no coincide
                    if (carreraSeleccionada && sedeSeleccionada && turnoSeleccionado && diaSeleccionado) {
                        const razones = [];
                        if (!coincideCarrera) razones.push('carrera: ' + carrera + ' vs ' + carreraSeleccionada);
                        if (!coincideSede) razones.push('sede: ' + sede + ' vs ' + sedeSeleccionada);
                        if (!coincideModalidad) razones.push('modalidad');
                        if (!coincideTurno) razones.push('turno: ' + turno + ' vs ' + turnoSeleccionado);
                        if (!coincideDia) razones.push('dia: ' + dia + ' vs ' + diaSeleccionado);
                    }
                }
            });

            // Actualizar contador de resultados
            if (contadorResultados) {
                contadorResultados.textContent = visibleCount;
            }

            // Actualizar contador del modal
            const contadorResultadosModal = document.getElementById('contador-resultados-modal');
            if (contadorResultadosModal) {
                contadorResultadosModal.textContent = visibleCount;
            }

            // Actualizar chips de filtros aplicados
            actualizarChipsFiltros();

            // Mostrar mensaje si no hay resultados
            const container = document.getElementById('cursadas-container');
            let mensajeNoResultados = document.getElementById('mensaje-no-resultados');

            if (visibleCount === 0) {
                if (!mensajeNoResultados && container) {
                    mensajeNoResultados = document.createElement('p');
                    mensajeNoResultados.id = 'mensaje-no-resultados';
                    mensajeNoResultados.textContent = 'No hay cursadas que coincidan con los filtros seleccionados.';
                    container.parentNode.insertBefore(mensajeNoResultados, container);
                }
                if (container) container.style.display = 'none';
            } else {
                if (mensajeNoResultados) {
                    mensajeNoResultados.remove();
                }
                if (container) container.style.display = 'flex';
            }
        }

        // Agregar event listeners a las opciones de filtro (incluyendo las del modal)
        // Usar delegación de eventos para que funcione con filtros del modal también
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('filtro-opcion')) {
                const opcion = e.target;
                const tipo = opcion.getAttribute('data-tipo');
                const valor = opcion.getAttribute('data-valor');

                // Si ya está seleccionado, deseleccionarlo
                if (filtrosSeleccionados[tipo] === valor) {
                    filtrosSeleccionados[tipo] = '';
                } else {
                    // Si es carrera, deseleccionar todas las demás opciones de carrera primero
                    if (tipo === 'carrera') {
                        filtrosSeleccionados.carrera = valor;
                    } else {
                        // Para otros filtros, si el valor está vacío, deseleccionar
                        if (valor === '') {
                            filtrosSeleccionados[tipo] = '';
                        } else {
                            filtrosSeleccionados[tipo] = valor;
                        }
                    }
                }

                actualizarColoresFiltros();
                filtrarCursadas();
            }
        });

        // Event listener para borrar todos los filtros
        if (borrarTodoBtn) {
            borrarTodoBtn.addEventListener('click', function (e) {
                e.preventDefault();
                borrarTodosFiltros();
            });
        }

        // Inicializar colores y filtros al cargar la página
        // IMPORTANTE: Primero actualizar colores, luego filtrar, luego mostrar chips
        actualizarColoresFiltros();
        filtrarCursadas(); // Esto ya llama a actualizarChipsFiltros() internamente

        // Inicializar formularios después de cargar las cursadas
        initializeForms();
    }

    // Función para limpiar inputs de un panel específico
    function limpiarInputsPanel(cursadaId) {
        const formulario = document.getElementById('formulario-' + cursadaId);
        if (formulario) {
            const inputs = formulario.querySelectorAll('.cursada-formulario-input');
            inputs.forEach(input => {
                input.value = ''; // Borrar el valor del input
            });
            const telefonoPrefijo = formulario.querySelector('#telefono-prefijo-' + cursadaId);
            if (telefonoPrefijo) {
                telefonoPrefijo.value = '+54'; // Resetear al valor por defecto
            }
        }
    }

    // Función para inicializar formularios y botones
    function initializeForms() {
        // Función para cerrar todos los paneles excepto uno
        function cerrarTodosLosPaneles(excluirCursadaId = null) {
            document.querySelectorAll('.cursada-valores-panel').forEach(panel => {
                const panelId = panel.getAttribute('id');
                const cursadaId = panelId.replace('panel-', '');

                if (cursadaId !== excluirCursadaId) {
                    panel.classList.remove('panel-visible');
                    panel.classList.add('panel-hidden');

                    // Remover estado desplegado del botón
                    document.querySelectorAll('.cursada-btn-ver-valores[data-cursada-id="' + cursadaId + '"]').forEach(botonVerValores => {
                        botonVerValores.classList.remove('panel-desplegado');
                    });

                    const infoTexto = document.getElementById('info-' + cursadaId);
                    if (infoTexto) {
                        infoTexto.classList.remove('panel-visible');
                        infoTexto.classList.add('panel-hidden');
                    }

                    const formulario = document.getElementById('formulario-' + cursadaId);
                    if (formulario) {
                        // Solo actualizar tabindex, NO borrar los datos
                        // Los datos se mantienen y se cargarán desde localStorage cuando se vuelva a abrir
                        const inputs = formulario.querySelectorAll('input, select');
                        inputs.forEach(input => {
                            input.setAttribute('tabindex', '-1');
                            // NO borrar el valor - se mantiene para reutilización
                        });
                    }

                    // Limpiar errores de validación
                    const errorElements = document.querySelectorAll('#formulario-' + cursadaId + ' .cursada-formulario-error');
                    const inputElements = document.querySelectorAll('#formulario-' + cursadaId + ' .cursada-formulario-input');
                    errorElements.forEach(error => {
                        error.classList.remove('show');
                        error.textContent = '';
                    });
                    inputElements.forEach(input => {
                        input.classList.remove('error');
                    });
                }
            });
        }

        // Funcionalidad para código de descuento
        document.addEventListener('click', function (e) {
            // Manejar click en el link "¡Tengo Código de descuento!"
            if (e.target.classList.contains('cursada-link-codigo-descuento')) {
                e.preventDefault();
                // Verificar si el link está deshabilitado
                if (e.target.classList.contains('cursada-link-disabled')) {
                    return;
                }
                const cursadaId = e.target.getAttribute('data-cursada-id');

                // Verificar si estamos dentro del modal
                const modalOverlay = document.getElementById('cursada-modal-overlay');
                const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalOverlay.contains(e.target);

                // Buscar el input container, primero en el modal si estamos ahí, luego en el DOM normal
                let inputContainer = null;
                if (isInModal) {
                    const modalBody = document.getElementById('cursada-modal-body');
                    if (modalBody) {
                        inputContainer = modalBody.querySelector('#codigo-input-modal-' + cursadaId) || modalBody.querySelector('#codigo-input-' + cursadaId);
                    }
                }
                if (!inputContainer) {
                    inputContainer = document.getElementById('codigo-input-modal-' + cursadaId) || document.getElementById('codigo-input-' + cursadaId);
                }

                if (inputContainer) {
                    const isCurrentlyVisible = inputContainer.style.display === 'flex';
                    if (isCurrentlyVisible) {
                        inputContainer.style.display = 'none';
                        inputContainer.classList.remove('input-visible');
                    } else {
                        inputContainer.style.display = 'flex';
                        inputContainer.classList.add('input-visible');
                        const input = inputContainer.querySelector('.cursada-codigo-descuento-input');
                        if (input) input.focus();

                        // Scroll automático SOLO si NO estamos en el modal (el modal ya tiene su propio scroll)
                        if (!isInModal) {
                            setTimeout(() => {
                                const btnReservar = document.querySelector(`button.cursada-btn-reservar[data-cursada-id="${cursadaId}"]`);
                                if (btnReservar) {
                                    btnReservar.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'end',
                                        inline: 'nearest'
                                    });
                                }
                            }, 100);
                        }
                    }
                }
            }

            // Manejar click en el botón "Aplicar"
            if (e.target.classList.contains('cursada-codigo-descuento-btn')) {
                e.preventDefault();
                const cursadaId = e.target.getAttribute('data-cursada-id');

                // Verificar si estamos dentro del modal
                const modalOverlay = document.getElementById('cursada-modal-overlay');
                const modalBody = document.getElementById('cursada-modal-body');
                const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

                // Buscar el link de código, primero en el modal si estamos ahí
                let linkCodigo = null;
                if (isInModal) {
                    linkCodigo = modalBody.querySelector('#link-codigo-modal-' + cursadaId) || modalBody.querySelector('#link-codigo-' + cursadaId);
                }
                if (!linkCodigo) {
                    linkCodigo = document.getElementById('link-codigo-modal-' + cursadaId) || document.getElementById('link-codigo-' + cursadaId);
                }

                // Verificar si el link de código está deshabilitado
                if (linkCodigo && linkCodigo.classList.contains('cursada-link-disabled')) {
                    return;
                }
                aplicarCodigoDescuento(cursadaId);
            }
        });

        // Manejar Enter en el input de código
        document.addEventListener('keypress', function (e) {
            if (e.target.classList.contains('cursada-codigo-descuento-input')) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const cursadaId = e.target.getAttribute('data-cursada-id');
                    aplicarCodigoDescuento(cursadaId);
                }
            }
        });

        // Manejar cambio en checkbox de términos y condiciones
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('cursada-checkbox-terminos-input')) {
                const cursadaId = e.target.getAttribute('data-cursada-id');
                const modalBody = document.getElementById('cursada-modal-body');
                const modalOpen = document.getElementById('cursada-modal-overlay')?.classList.contains('cursada-modal-open');

                // Actualizar clase del label para el estado visual
                const label = e.target.nextElementSibling;
                if (label && label.classList.contains('cursada-checkbox-terminos-label')) {
                    if (e.target.checked) {
                        label.classList.add('checkbox-checked');
                    } else {
                        label.classList.remove('checkbox-checked');
                    }
                }

                // Buscar botón por data-cursada-id (más confiable que por ID)
                let btnReservar = null;
                if (modalOpen && modalBody) {
                    btnReservar = modalBody.querySelector('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');
                }
                if (!btnReservar) {
                    btnReservar = document.querySelector('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');
                }
                if (btnReservar) {
                    btnReservar.disabled = !e.target.checked;
                }
            }
        });

        // NO interferir con el comportamiento nativo del label
        // El label nativo debería funcionar automáticamente y disparar el evento change del checkbox

        // Función para inicializar valores de descuento (cuando no hay descuento aplicado)
        function inicializarValoresDescuento(cursadaId) {
            // Verificar si estamos dentro del modal
            const modalOverlay = document.getElementById('cursada-modal-overlay');
            const modalBody = document.getElementById('cursada-modal-body');
            const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

            // Buscar elementos, primero en el modal si estamos ahí, luego en el DOM normal
            let descuentoAplicado = null;
            let totalAplicado = null;
            let valorMatricula = null;
            let cuotaInfo = null;

            if (isInModal) {
                descuentoAplicado = modalBody.querySelector('#descuento-aplicado-' + cursadaId);
                totalAplicado = modalBody.querySelector('#total-aplicado-' + cursadaId);
                valorMatricula = modalBody.querySelector('#valor-matricula-' + cursadaId);
                cuotaInfo = modalBody.querySelector('#cuota-info-' + cursadaId);
            }

            // Si no se encontraron en el modal, buscar en el DOM normal
            if (!descuentoAplicado) descuentoAplicado = document.getElementById('descuento-aplicado-' + cursadaId);
            if (!totalAplicado) totalAplicado = document.getElementById('total-aplicado-' + cursadaId);
            if (!valorMatricula) valorMatricula = document.getElementById('valor-matricula-' + cursadaId);
            if (!cuotaInfo) cuotaInfo = document.getElementById('cuota-info-' + cursadaId);

            const valorDescuento = descuentoAplicado ? descuentoAplicado.querySelector('.cursada-descuento-valor') : null;
            const valorTotal = totalAplicado ? totalAplicado.querySelector('.cursada-total-valor') : null;

            // Obtener el texto del valor de matrícula
            const valorMatriculaText = valorMatricula ? valorMatricula.textContent.trim() : '';

            // Verificar si hay un descuento aplicado (si el descuento no es "n/d" ni "$0,00")
            const descuentoText = valorDescuento ? valorDescuento.textContent.trim() : '';
            const tieneDescuentoAplicado = descuentoText && descuentoText !== 'n/d' && descuentoText !== '$0,00' && descuentoText.startsWith('-');

            let matricBase = 0;
            let descuentoValor = 0;

            // Si hay descuento aplicado, obtener el valor del descuento y calcular el total
            if (tieneDescuentoAplicado && descuentoAplicado) {
                // Obtener el valor del descuento del data attribute
                descuentoValor = parseFloat(descuentoAplicado.getAttribute('data-valor-descuento') || 0);
                const valorFinal = parseFloat(descuentoAplicado.getAttribute('data-valor-final') || 0);

                if (valorFinal > 0) {
                    // Usar el valor final que ya está calculado
                    const totalFormateado = valorFinal.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    if (valorTotal) {
                        valorTotal.textContent = '$' + totalFormateado;
                    }
                    return; // Salir temprano si hay descuento aplicado
                }
            }

            // Obtener el valor de matrícula base (con impuestos) desde cuotaInfo (este es el valor que se debe usar para el total)
            if (cuotaInfo) {
                const matricBaseAttr = cuotaInfo.getAttribute('data-matric-base');
                if (matricBaseAttr && matricBaseAttr !== '' && matricBaseAttr !== '0') {
                    matricBase = parseFloat(matricBaseAttr);
                    if (isNaN(matricBase)) {
                        matricBase = 0;
                    }
                }
            }

            // Si no se encontró en cuotaInfo, intentar obtener del valor de matrícula
            if (matricBase === 0 && valorMatricula) {
                const valorNumerico = valorMatricula.getAttribute('data-valor-numerico');
                if (valorNumerico && valorNumerico !== '' && valorNumerico !== '0') {
                    matricBase = parseFloat(valorNumerico);
                    if (isNaN(matricBase)) {
                        matricBase = 0;
                    }
                }
            }

            // Si aún no se encontró, intentar extraer del texto como último recurso
            if (matricBase === 0 && valorMatriculaText && valorMatriculaText !== 'n/d' && valorMatriculaText !== '' && valorMatriculaText.includes('$')) {
                // Remover el símbolo $ y espacios, luego reemplazar punto por nada (separador de miles) y coma por punto (decimal)
                const matricBaseText = valorMatriculaText
                    .replace(/\$/g, '')  // Eliminar todos los $
                    .trim()
                    .replace(/\./g, '')  // Eliminar puntos (separadores de miles)
                    .replace(',', '.');  // Reemplazar coma por punto (decimal)
                matricBase = parseFloat(matricBaseText);
                if (isNaN(matricBase)) {
                    matricBase = 0;
                }
            }

            // Si tenemos un valor de matrícula base, calcular y mostrar el total
            if (matricBase > 0) {
                // Establecer descuento en $0,00 (si no hay descuento aplicado)
                if (valorDescuento && !tieneDescuentoAplicado) {
                    valorDescuento.textContent = '$0,00';
                }

                // Establecer total igual a matrícula base (menos descuento si hay)
                if (valorTotal) {
                    const total = matricBase - descuentoValor;
                    const valorFormateado = total.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    valorTotal.textContent = '$' + valorFormateado;
                }
            } else {
                // Si no hay valor de matrícula, mantener "n/d"
                if (valorDescuento && !tieneDescuentoAplicado) {
                    valorDescuento.textContent = 'n/d';
                }
                if (valorTotal) {
                    valorTotal.textContent = 'n/d';
                }
            }

            // Ocultar icono de promo mat logo (buscar primero en modal)
            let promoBadgeDescuento = null;
            if (isInModal) {
                promoBadgeDescuento = modalBody.querySelector('#promo-badge-descuento-' + cursadaId);
            }
            if (!promoBadgeDescuento) {
                promoBadgeDescuento = document.getElementById('promo-badge-descuento-' + cursadaId);
            }
            if (promoBadgeDescuento) {
                promoBadgeDescuento.style.display = 'none';
            }
        }

        // Función para aplicar código de descuento
        function aplicarCodigoDescuento(cursadaId) {
            // Verificar si estamos dentro del modal
            const modalOverlay = document.getElementById('cursada-modal-overlay');
            const modalBody = document.getElementById('cursada-modal-body');
            const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

            // Buscar elementos, primero en el modal si estamos ahí, luego en el DOM normal
            let input = null;
            let descuentoAplicado = null;
            let totalAplicado = null;
            let cuotaInfo = null;
            let valorMatricula = null;

            if (isInModal) {
                input = modalBody.querySelector('#codigo-input-field-modal-' + cursadaId) || modalBody.querySelector('#codigo-input-field-' + cursadaId) || modalBody.querySelector('.cursada-codigo-descuento-input[data-cursada-id="' + cursadaId + '"]');
                descuentoAplicado = modalBody.querySelector('#descuento-aplicado-' + cursadaId);
                totalAplicado = modalBody.querySelector('#total-aplicado-' + cursadaId);
                cuotaInfo = modalBody.querySelector('#cuota-info-' + cursadaId);
                valorMatricula = modalBody.querySelector('#valor-matricula-' + cursadaId);
            }

            // Si no se encontraron en el modal, buscar en el DOM normal
            if (!input) input = document.getElementById('codigo-input-field-modal-' + cursadaId) || document.getElementById('codigo-input-field-' + cursadaId);
            if (!descuentoAplicado) descuentoAplicado = document.getElementById('descuento-aplicado-' + cursadaId);
            if (!totalAplicado) totalAplicado = document.getElementById('total-aplicado-' + cursadaId);
            if (!cuotaInfo) cuotaInfo = document.getElementById('cuota-info-' + cursadaId);
            if (!valorMatricula) valorMatricula = document.getElementById('valor-matricula-' + cursadaId);

            const valorDescuento = descuentoAplicado ? descuentoAplicado.querySelector('.cursada-descuento-valor') : null;
            const valorTotal = totalAplicado ? totalAplicado.querySelector('.cursada-total-valor') : null;

            if (!input) return;

            const codigo = input.value.trim();
            if (!codigo) {
                // Si no hay código, reinicializar valores
                inicializarValoresDescuento(cursadaId);
                return;
            }

            // Buscar el valor de matrícula base (con impuestos) - este es el valor base para calcular el descuento
            // (cuotaInfo y valorMatricula ya se obtuvieron arriba)
            let matricBase = 0;

            // Primero intentar obtener desde cuotaInfo
            if (cuotaInfo) {
                const matricBaseAttr = cuotaInfo.getAttribute('data-matric-base');
                if (matricBaseAttr && matricBaseAttr !== '' && matricBaseAttr !== '0') {
                    matricBase = parseFloat(matricBaseAttr);
                    if (isNaN(matricBase)) {
                        matricBase = 0;
                    }
                }
            }

            // Si no se encontró, intentar obtener del data attribute del valor de matrícula
            if (matricBase === 0 && valorMatricula) {
                const valorNumerico = valorMatricula.getAttribute('data-valor-numerico');
                if (valorNumerico && valorNumerico !== '' && valorNumerico !== '0') {
                    matricBase = parseFloat(valorNumerico);
                    if (isNaN(matricBase)) {
                        matricBase = 0;
                    }
                }
            }

            // Si aún no se encontró, intentar extraer del texto
            if (matricBase === 0 && valorMatricula) {
                const valorMatriculaText = valorMatricula.textContent.trim();
                if (valorMatriculaText && valorMatriculaText !== 'n/d' && valorMatriculaText !== '' && valorMatriculaText.includes('$')) {
                    const matricBaseText = valorMatriculaText
                        .replace(/\$/g, '')
                        .trim()
                        .replace(/\./g, '')
                        .replace(',', '.');
                    matricBase = parseFloat(matricBaseText);
                    if (isNaN(matricBase)) {
                        matricBase = 0;
                    }
                }
            }

            if (matricBase === 0) {
                mostrarNotificacion('No se pudo obtener el valor de matrícula. Por favor, completá el formulario primero.', 'error');
                return;
            }

            // Obtener el token CSRF actualizado (puede haber cambiado)
            const currentCsrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                window.inscripcionConfig?.csrfToken ||
                csrfToken;

            if (!currentCsrfToken) {
                mostrarNotificacion('Error: No se pudo obtener el token de seguridad. Por favor, recargá la página.', 'error');
                return;
            }

            // Hacer la petición al servidor usando FormData para mejor compatibilidad con CSRF
            const formData = new FormData();
            formData.append('codigo', codigo);
            formData.append('_token', currentCsrfToken);

            fetch(buscarDescuentoUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': currentCsrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => {
                    // Verificar el Content-Type antes de intentar parsear JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        // Si no es JSON, probablemente es HTML (error del servidor)
                        if (response.status === 404) {
                            throw new Error('Código de descuento no encontrado');
                        } else if (response.status === 419) {
                            throw new Error('La sesión expiró. Por favor, recargá la página.');
                        } else {
                            throw new Error('Error en el servidor. Por favor, intentá nuevamente.');
                        }
                    }

                    // Intentar parsear JSON
                    return response.json().then(data => {
                        // Si la respuesta no es exitosa pero tiene un mensaje, usarlo
                        if (!response.ok) {
                            if (data && data.message) {
                                throw new Error(data.message);
                            }
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return data;
                    }).catch(error => {
                        // Si el error ya tiene un mensaje útil, re-lanzarlo
                        if (error.message && !error.message.includes('Unexpected token')) {
                            throw error;
                        }
                        throw new Error('Error al procesar la respuesta del servidor');
                    });
                })
                .then(data => {
                    if (data.success && data.descuento) {
                        const porcentaje = parseFloat(data.descuento.porcentaje) || 0;
                        const descuentoCalculado = (matricBase * porcentaje) / 100;
                        const valorFinal = matricBase - descuentoCalculado;

                        // Actualizar el descuento aplicado
                        if (descuentoAplicado && valorDescuento) {
                            const valorFormateado = descuentoCalculado.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            valorDescuento.textContent = '-$' + valorFormateado;

                            // Guardar el descuento en un data attribute para uso posterior
                            descuentoAplicado.setAttribute('data-porcentaje', porcentaje);
                            descuentoAplicado.setAttribute('data-valor-descuento', descuentoCalculado);
                            descuentoAplicado.setAttribute('data-valor-final', valorFinal);
                        }

                        // Actualizar el total aplicado (matrícula - descuento)
                        if (totalAplicado && valorTotal) {
                            const totalFormateado = valorFinal.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            valorTotal.textContent = '$' + totalFormateado;
                        }

                        // Mostrar icono de promo mat logo solo si la cursada tiene Promo_Mat_logo === 'mostrar'
                        let panel = null;
                        let promoBadgeDescuento = null;

                        if (isInModal && modalBody) {
                            // Buscar en el modal
                            const panelInModal = modalBody.querySelector('[data-promo-mat-logo]');
                            if (panelInModal) {
                                panel = panelInModal;
                            }
                            promoBadgeDescuento = modalBody.querySelector('#promo-badge-descuento-' + cursadaId);
                        }

                        // Si no se encontraron en el modal, buscar en el DOM normal
                        if (!panel) panel = document.getElementById('panel-' + cursadaId);
                        if (!promoBadgeDescuento) promoBadgeDescuento = document.getElementById('promo-badge-descuento-' + cursadaId);

                        if (panel && promoBadgeDescuento) {
                            const promoMatLogo = (panel.getAttribute('data-promo-mat-logo') || '').toLowerCase().trim();
                            if (promoMatLogo === 'mostrar' && globalPromoBadgeInfo && globalPromoBadgeInfo.archivo && globalPromoBadgeInfo.image_path) {
                                promoBadgeDescuento.src = globalPromoBadgeInfo.image_path;
                                promoBadgeDescuento.style.display = 'inline-block';
                            } else {
                                // Ocultar el badge si no debe mostrarse
                                promoBadgeDescuento.style.display = 'none';
                            }
                        }

                        // Cerrar el input del código de descuento y deshabilitar el link
                        let inputContainerCodigo = null;
                        let linkCodigo = null;

                        if (isInModal && modalBody) {
                            inputContainerCodigo = modalBody.querySelector('#codigo-input-modal-' + cursadaId) || modalBody.querySelector('#codigo-input-' + cursadaId);
                            linkCodigo = modalBody.querySelector('#link-codigo-modal-' + cursadaId) || modalBody.querySelector('#link-codigo-' + cursadaId) || modalBody.querySelector('.cursada-link-codigo-descuento[data-cursada-id="' + cursadaId + '"]');
                        }

                        if (!inputContainerCodigo) {
                            inputContainerCodigo = document.getElementById('codigo-input-modal-' + cursadaId) || document.getElementById('codigo-input-' + cursadaId);
                        }
                        if (!linkCodigo) {
                            linkCodigo = document.getElementById('link-codigo-modal-' + cursadaId) || document.getElementById('link-codigo-' + cursadaId) || document.querySelector('.cursada-link-codigo-descuento[data-cursada-id="' + cursadaId + '"]');
                        }

                        // Cerrar el input container
                        if (inputContainerCodigo) {
                            inputContainerCodigo.style.display = 'none';
                            inputContainerCodigo.classList.remove('input-visible');
                            // Limpiar el input
                            const inputCodigo = inputContainerCodigo.querySelector('.cursada-codigo-descuento-input');
                            if (inputCodigo) {
                                inputCodigo.value = '';
                            }
                        }

                        // Deshabilitar el link "Tengo código de descuento"
                        if (linkCodigo) {
                            linkCodigo.classList.add('cursada-link-disabled');
                            linkCodigo.style.pointerEvents = 'none';
                            linkCodigo.style.opacity = '0.6';
                            linkCodigo.style.cursor = 'not-allowed';
                        }

                        // Mostrar notificación de éxito
                        mostrarNotificacion('¡Código de descuento aplicado correctamente!', 'success');
                    } else {
                        // Si no se encontró el código, reinicializar valores
                        inicializarValoresDescuento(cursadaId);
                        mostrarNotificacion(data.message || 'Código de descuento no encontrado', 'error');
                    }
                })
                .catch(error => {
                    // Si hay error, reinicializar valores
                    inicializarValoresDescuento(cursadaId);
                    // Mostrar el mensaje de error específico
                    mostrarNotificacion(error.message || 'Error al buscar el código de descuento. Por favor, intentá nuevamente.', 'error');
                });
        }

        // Funciones de validación simplificadas
        function mostrarError(input, errorElement, mensaje) {
            if (input) input.classList.add('error');
            if (errorElement) {
                errorElement.textContent = mensaje;
                errorElement.classList.add('show');
            }
        }

        function ocultarError(input, errorElement) {
            if (input) input.classList.remove('error');
            if (errorElement) {
                errorElement.classList.remove('show');
                errorElement.textContent = '';
            }
        }

        function validarCampoTexto(valor) {
            return valor && valor.trim().length > 0;
        }

        function validarDni(dni) {
            if (!dni?.value?.trim()) return false;
            return /^[0-9]{7,8}$/.test(dni.value.trim());
        }

        function validarCorreo(correo) {
            if (!correo?.value?.trim()) return false;
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value.trim());
        }

        function normalizarTelefono(telefono) {
            if (!telefono) return '';
            return telefono.value.trim().replace(/\D/g, '').slice(0, 14);
        }

        function validarTelefono(telefono) {
            const valor = normalizarTelefono(telefono);
            return /^[0-9]{8,14}$/.test(valor);
        }

        function validarFormularioCompleto(cursadaId) {
            // Verificar si estamos dentro del modal
            const modalOverlay = document.getElementById('cursada-modal-overlay');
            const modalBody = document.getElementById('cursada-modal-body');
            const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

            // Buscar formulario, primero en el modal si estamos ahí
            let formulario = null;
            if (isInModal) {
                // Buscar por ID primero
                formulario = modalBody.querySelector('#formulario-' + cursadaId);
                // Si no se encuentra, buscar cualquier formulario en el modal
                if (!formulario) {
                    formulario = modalBody.querySelector('form.cursada-formulario');
                }
            }
            // Si no se encontró en el modal, buscar en el DOM normal
            if (!formulario) {
                formulario = document.getElementById('formulario-' + cursadaId);
            }

            if (!formulario) {
                return false;
            }

            // Buscar inputs dentro del formulario encontrado (usando querySelector dentro del formulario para evitar IDs duplicados)
            // En el modal, los inputs pueden tener IDs diferentes, así que buscar por name primero
            const nombre = formulario.querySelector('input[name="nombre"]');
            const apellido = formulario.querySelector('input[name="apellido"]');
            const dni = formulario.querySelector('input[name="dni"]');
            const correo = formulario.querySelector('input[name="correo"]');
            const telefono = formulario.querySelector('input[name="telefono"]');

            const nombreVal = nombre?.value?.trim() || '';
            const apellidoVal = apellido?.value?.trim() || '';
            const dniVal = dni?.value?.trim() || '';
            const correoVal = correo?.value?.trim() || '';
            const telefonoVal = telefono?.value?.trim() || '';

            const validNombre = validarCampoTexto(nombreVal);
            const validApellido = validarCampoTexto(apellidoVal);
            const validDni = validarDni ? validarDni(dni) : (dniVal && /^[0-9]{7,8}$/.test(dniVal));
            const validCorreo = validarCorreo ? validarCorreo(correo) : (correoVal && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoVal));
            const validTelefono = validarTelefono ? validarTelefono(telefono) : (telefonoVal && /^[0-9]{8,14}$/.test(telefonoVal.replace(/\D/g, '')));

            const esValido = validNombre && validApellido && validDni && validCorreo && validTelefono;

            return esValido;
        }

        function actualizarEstadoBotonContinuar(cursadaId) {
            // Verificar si estamos dentro del modal
            const modalOverlay = document.getElementById('cursada-modal-overlay');
            const modalBody = document.getElementById('cursada-modal-body');
            const isInModal = modalOverlay && modalOverlay.classList.contains('cursada-modal-open') && modalBody;

            // Buscar botón, primero en el modal si estamos ahí
            let botonContinuar = null;
            if (isInModal) {
                // Buscar por data-cursada-id primero
                botonContinuar = modalBody.querySelector('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]');
                // Si no se encuentra, buscar cualquier botón continuar en el modal
                if (!botonContinuar) {
                    botonContinuar = modalBody.querySelector('.cursada-btn-continuar');
                    // Si se encuentra, actualizar su data-cursada-id
                    if (botonContinuar) {
                        botonContinuar.setAttribute('data-cursada-id', cursadaId);
                    }
                }
            }
            // Si no se encontró en el modal, buscar en el DOM normal
            if (!botonContinuar) {
                botonContinuar = document.querySelector('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]');
            }

            if (!botonContinuar) {
                return;
            }

            const esValido = validarFormularioCompleto(cursadaId);

            // Si esValido es undefined o null, no actualizar
            if (esValido === undefined || esValido === null) {
                return;
            }

            // Actualizar clase activo
            if (esValido) {
                botonContinuar.classList.add('activo');
            } else {
                botonContinuar.classList.remove('activo');
            }

            // Actualizar disabled
            botonContinuar.disabled = !esValido;

            // Actualizar estilos visuales
            if (esValido) {
                botonContinuar.style.opacity = '1';
                botonContinuar.style.cursor = 'pointer';
            } else {
                botonContinuar.style.opacity = '0.6';
                botonContinuar.style.cursor = 'not-allowed';
            }
        }

        // NOTA: guardarDatosFormulario ya está definida arriba (junto con cargarDatosFormulario) 
        // para que esté disponible cuando se use en el modal

        // Función para cargar datos en todos los formularios visibles
        function cargarDatosEnTodosLosFormularios() {
            document.querySelectorAll('.cursada-formulario').forEach(formulario => {
                const formularioId = formulario.getAttribute('id');
                if (formularioId) {
                    const cursadaId = formularioId.replace('formulario-', '');
                    cargarDatosFormulario(cursadaId);
                }
            });
        }


        // Función para inicializar validación y carga de datos de formularios
        function initializeFormularios() {
            document.querySelectorAll('.cursada-formulario').forEach(formulario => {
                const formularioId = formulario.getAttribute('id');
                const cursadaId = formularioId.replace('formulario-', '');

                const nombre = formulario.querySelector('#nombre-' + cursadaId);
                const apellido = formulario.querySelector('#apellido-' + cursadaId);
                const dni = formulario.querySelector('#dni-' + cursadaId);
                const correo = formulario.querySelector('#correo-' + cursadaId);
                const telefono = formulario.querySelector('#telefono-' + cursadaId);

                const errorNombre = document.getElementById('error-nombre-' + cursadaId);
                const errorApellido = document.getElementById('error-apellido-' + cursadaId);
                const errorDni = document.getElementById('error-dni-' + cursadaId);
                const errorCorreo = document.getElementById('error-correo-' + cursadaId);
                const errorTelefono = document.getElementById('error-telefono-' + cursadaId);

                // Cargar datos guardados al inicializar el formulario
                // Usar un pequeño delay para asegurar que el DOM esté completamente listo
                // Pero solo si el formulario no está ya completado
                setTimeout(() => {
                    // Verificar si hay datos completos guardados
                    const datosGuardados = sessionStorage.getItem(STORAGE_KEY);
                    if (datosGuardados) {
                        const datos = JSON.parse(datosGuardados);
                        const formularioCompleto = datos.nombre && datos.apellido && datos.dni &&
                            datos.correo && datos.telefono;
                        // Si el formulario está completo, no cargar datos aquí (se hará en verificarYRestaurarEstadoCompletado)
                        if (!formularioCompleto) {
                            cargarDatosFormulario(cursadaId);
                        }
                    } else {
                        cargarDatosFormulario(cursadaId);
                    }
                }, 100);

                // Función genérica para validar campo de texto
                function setupCampoTexto(input, errorElement, mensaje) {
                    if (!input) return;
                    input.addEventListener('blur', function () {
                        if (!validarCampoTexto(this.value)) {
                            mostrarError(this, errorElement, mensaje);
                        } else {
                            ocultarError(this, errorElement);
                        }
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                    input.addEventListener('input', function () {
                        if (this.value.trim()) ocultarError(this, errorElement);
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                }

                // Función genérica para validar campo con función personalizada
                function setupCampoValidado(input, errorElement, validarFn, mensajeError) {
                    if (!input) return;
                    input.addEventListener('blur', function () {
                        if (!validarFn(this)) {
                            mostrarError(this, errorElement, mensajeError);
                        } else {
                            ocultarError(this, errorElement);
                        }
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                    input.addEventListener('input', function () {
                        if (validarFn(this)) ocultarError(this, errorElement);
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                }

                // Configurar validaciones
                setupCampoTexto(nombre, errorNombre, 'Este campo es obligatorio');
                setupCampoTexto(apellido, errorApellido, 'Este campo es obligatorio');
                setupCampoValidado(dni, errorDni, validarDni, 'El DNI debe tener entre 7 y 8 dígitos');

                // Configurar botón editar form
                const btnEditar = document.getElementById('editar-form-' + cursadaId);
                if (btnEditar) {
                    btnEditar.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Habilitar inputs del formulario actual
                        const formInputs = formulario.querySelectorAll('input, select');
                        formInputs.forEach(input => {
                            input.removeAttribute('readonly');
                            input.removeAttribute('disabled');
                            input.style.pointerEvents = 'auto';
                            input.style.opacity = '1';
                            input.style.cursor = input.tagName === 'SELECT' ? 'pointer' : 'text';
                        });

                        // Habilitar botón continuar correspondiente
                        const botonContinuar = document.querySelector('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]');
                        if (botonContinuar) {
                            botonContinuar.disabled = false;
                            botonContinuar.style.opacity = '1';
                            botonContinuar.style.cursor = 'pointer';
                            // Re-validar para asegurar estado correcto del botón
                            actualizarEstadoBotonContinuar(cursadaId);
                        }

                        // Ocultar este botón de editar
                        this.style.display = 'none';

                        // OCULTAR Y DESHABILITAR PANELES DERECHOS (Modo Edición)

                        // 1. Ocultar información de cuota y panel derecho
                        const cuotaInfo = document.getElementById('cuota-info-' + cursadaId);
                        if (cuotaInfo) {
                            cuotaInfo.style.display = 'none';
                            cuotaInfo.setAttribute('hidden', '');
                            cuotaInfo.classList.remove('visible');
                            cuotaInfo.classList.add('hidden');
                        }

                        // 2. Deshabilitar checkbox de términos y desmarcarlo
                        // Buscar tanto en el DOM normal como en modal por si acaso
                        const checkboxTerminos = document.getElementById('acepto-terminos-' + cursadaId) ||
                            document.getElementById('acepto-terminos-modal-' + cursadaId);
                        if (checkboxTerminos) {
                            checkboxTerminos.checked = false;
                            checkboxTerminos.disabled = true;
                        }

                        // 3. Deshabilitar botón Reservar
                        const btnReservar = document.querySelector('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');
                        if (btnReservar) {
                            btnReservar.disabled = true;
                        }

                        // 4. Deshabilitar link de código de descuento
                        const linkCodigo = document.getElementById('link-codigo-' + cursadaId) ||
                            document.getElementById('link-codigo-modal-' + cursadaId);
                        if (linkCodigo) {
                            linkCodigo.classList.add('cursada-link-disabled');
                        }

                        // 5. Ocultar input de código si estaba abierto
                        const codigoInputContainer = document.getElementById('codigo-input-' + cursadaId);
                        if (codigoInputContainer) {
                            codigoInputContainer.classList.remove('input-visible');
                            codigoInputContainer.style.display = 'none';
                        }
                    });
                }

                if (correo) {
                    correo.addEventListener('blur', function () {
                        if (!validarCorreo(this)) {
                            mostrarError(this, errorCorreo, this.value.trim() ? 'Por favor ingrese un correo electrónico válido' : 'Este campo es obligatorio');
                        } else {
                            ocultarError(this, errorCorreo);
                        }
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                    correo.addEventListener('input', function () {
                        if (validarCorreo(this)) ocultarError(this, errorCorreo);
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                }

                // Validación de teléfono (especial por normalización)
                if (telefono) {
                    function normalizarYValidarTelefono() {
                        const valor = normalizarTelefono(telefono);
                        telefono.value = valor;
                        // Solo actualizar el estado del botón, no mostrar errores
                        actualizarEstadoBotonContinuar(cursadaId);
                    }

                    telefono.addEventListener('input', normalizarYValidarTelefono);
                    telefono.addEventListener('paste', () => setTimeout(normalizarYValidarTelefono, 0));
                    telefono.addEventListener('blur', function () {
                        normalizarYValidarTelefono();
                        // Solo actualizar el estado del botón, no mostrar errores
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                }
            });

            // Funcionalidad del botón "Continuar" - guardar lead cuando está activo
            document.querySelectorAll('.cursada-btn-continuar').forEach(boton => {
                // Inicializar como inactivo
                boton.classList.remove('activo');

                boton.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Solo procesar si el botón está activo
                    if (!this.classList.contains('activo')) {
                        return;
                    }

                    const cursadaId = this.getAttribute('data-cursada-id');
                    const formulario = document.getElementById('formulario-' + cursadaId);

                    if (!formulario) return;

                    // Si el botón está activo, el formulario ya está validado
                    if (!validarFormularioCompleto(cursadaId)) {
                        return; // No debería pasar, pero por seguridad
                    }

                    const nombre = formulario.querySelector('#nombre-' + cursadaId);
                    const apellido = formulario.querySelector('#apellido-' + cursadaId);
                    const dni = formulario.querySelector('#dni-' + cursadaId);
                    const correo = formulario.querySelector('#correo-' + cursadaId);
                    const telefono = formulario.querySelector('#telefono-' + cursadaId);
                    const telefonoPrefijo = formulario.querySelector('#telefono-prefijo-' + cursadaId);
                    const checkboxTerminos = formulario.querySelector('#acepto-terminos-' + cursadaId);

                    // Limpiar todos los errores antes de enviar
                    const errorNombre = document.getElementById('error-nombre-' + cursadaId);
                    const errorApellido = document.getElementById('error-apellido-' + cursadaId);
                    const errorDni = document.getElementById('error-dni-' + cursadaId);
                    const errorCorreo = document.getElementById('error-correo-' + cursadaId);
                    const errorTelefono = document.getElementById('error-telefono-' + cursadaId);

                    ocultarError(nombre, errorNombre);
                    ocultarError(apellido, errorApellido);
                    ocultarError(dni, errorDni);
                    ocultarError(correo, errorCorreo);
                    ocultarError(telefono, errorTelefono);

                    // Construir número de teléfono completo (prefijo + número)
                    const telefonoCompleto = (telefonoPrefijo?.value || '+54') + normalizarTelefono(telefono);

                    // Obtener el ID_Curso desde el item de la cursada
                    const cursadaItem = formulario.closest('.cursada-item');
                    let idCurso = null;
                    if (cursadaItem) {
                        idCurso = cursadaItem.getAttribute('data-id-curso');
                    }
                    // Si no se encuentra en el item, intentar obtenerlo del panel
                    if (!idCurso) {
                        const formCursadaId = cursadaId.replace('cursada-', '');
                        const panel = document.getElementById('panel-' + formCursadaId);
                        if (panel) {
                            const panelItem = panel.closest('.cursada-item');
                            if (panelItem) {
                                idCurso = panelItem.getAttribute('data-id-curso');
                            }
                        }
                    }

                    if (!idCurso) {
                        mostrarNotificacion('Error: No se pudo obtener el ID del curso. Por favor, recargá la página.', 'error');
                        return;
                    }

                    // Verificar si ya existe un leadId para esta cursada (edición)
                    // Intentar encontrar el panel con varias combinaciones de ID para ser robusto
                    let panel = document.getElementById('panel-' + cursadaId);
                    if (!panel) {
                        // Si no lo encuentra, intentar quitando 'cursada-' si lo tiene, o agregándolo
                        if (cursadaId.includes('cursada-')) {
                            panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                        } else {
                            panel = document.getElementById('panel-cursada-' + cursadaId);
                        }
                    }

                    const existingLeadId = panel ? panel.getAttribute('data-lead-id') : null;

                    // Guardar lead
                    executeRecaptcha().then(recaptchaToken => {
                        return fetch(window.inscripcionConfig.leadsStoreUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                id: existingLeadId, // Enviar ID si existe para actualizar
                                nombre: nombre.value.trim(),
                                apellido: apellido.value.trim(),
                                dni: dni.value.trim(),
                                correo: correo.value.trim(),
                                telefono: telefonoCompleto,
                                cursada_id: idCurso,
                                tipo: 'Lead',
                                acepto_terminos: checkboxTerminos ? checkboxTerminos.checked : false,
                                'g-recaptcha-response': recaptchaToken
                            })
                        });
                    })
                        .then(async response => {
                            // Verificar el Content-Type antes de intentar parsear
                            const contentType = response.headers.get('content-type');
                            const isJson = contentType && contentType.includes('application/json');

                            if (!response.ok) {
                                // Si la respuesta no es JSON, leer como texto
                                if (!isJson) {
                                    await response.text();
                                    throw new Error('Error del servidor. Por favor, intente nuevamente.');
                                }
                                // Si es JSON, parsear y extraer el mensaje
                                try {
                                    const data = await response.json();
                                    throw new Error(data.message || 'Error al guardar los datos');
                                } catch (e) {
                                    if (e instanceof Error && e.message) {
                                        throw e;
                                    }
                                    throw new Error('Error al guardar los datos');
                                }
                            }

                            // Verificar que la respuesta sea JSON antes de parsear
                            if (!isJson) {
                                await response.text();
                                throw new Error('Error: El servidor no devolvió una respuesta válida');
                            }

                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Guardar el ID del lead en el panel correspondiente para usarlo después
                                let panel = document.getElementById('panel-' + cursadaId);
                                if (!panel) {
                                    if (cursadaId.includes('cursada-')) {
                                        panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                                    } else {
                                        panel = document.getElementById('panel-cursada-' + cursadaId);
                                    }
                                }

                                if (panel && data.lead_id) {
                                    panel.setAttribute('data-lead-id', data.lead_id);
                                }

                                // Habilitar el checkbox de términos si existe
                                if (checkboxTerminos) {
                                    checkboxTerminos.disabled = false;
                                }

                                // Guardar los datos del formulario en sessionStorage después de guardar exitosamente
                                guardarDatosFormulario(cursadaId);

                                // Desactivar TODOS los formularios después de guardar exitosamente
                                // Buscar en el DOM normal y también en el modal
                                const modalBody = document.getElementById('cursada-modal-body');
                                const todosLosFormularios = [];

                                // Agregar formularios del DOM normal
                                document.querySelectorAll('.cursada-formulario').forEach(form => {
                                    todosLosFormularios.push(form);
                                });

                                // Agregar formularios del modal si está abierto
                                if (modalBody) {
                                    modalBody.querySelectorAll('.cursada-formulario').forEach(form => {
                                        if (!todosLosFormularios.includes(form)) {
                                            todosLosFormularios.push(form);
                                        }
                                    });
                                }

                                todosLosFormularios.forEach(form => {
                                    const formId = form.getAttribute('id');
                                    if (formId) {
                                        const formCursadaId = formId.replace('formulario-', '');
                                        const formInputs = form.querySelectorAll('input, select');
                                        formInputs.forEach(input => {
                                            input.setAttribute('readonly', 'readonly');
                                            input.setAttribute('disabled', 'disabled');
                                            input.style.pointerEvents = 'none';
                                            input.style.opacity = '0.6';
                                            input.style.cursor = 'not-allowed';
                                        });

                                        // Desactivar todos los botones continuar (buscar en modal y DOM normal)
                                        let botonContinuarForm = null;
                                        if (modalBody) {
                                            botonContinuarForm = modalBody.querySelector('.cursada-btn-continuar[data-cursada-id="' + formCursadaId + '"]') ||
                                                modalBody.querySelector('.cursada-btn-continuar');
                                        }
                                        if (!botonContinuarForm) {
                                            botonContinuarForm = document.querySelector('.cursada-btn-continuar[data-cursada-id="' + formCursadaId + '"]');
                                        }
                                        if (botonContinuarForm) {
                                            botonContinuarForm.classList.remove('activo');
                                            botonContinuarForm.disabled = true;
                                            botonContinuarForm.style.opacity = '0.6';
                                            botonContinuarForm.style.cursor = 'not-allowed';
                                        }

                                        // Mostrar botón editar
                                        const btnEditar = document.getElementById('editar-form-' + formCursadaId);
                                        if (btnEditar) {
                                            btnEditar.style.display = 'inline';
                                        }
                                    }
                                });

                                // Mostrar valores en TODOS los formularios (reutilizar la lista ya creada)
                                todosLosFormularios.forEach(form => {
                                    const formId = form.getAttribute('id');
                                    if (formId) {
                                        const formCursadaId = formId.replace('formulario-', '');
                                        mostrarValoresEnFormulario(formCursadaId);
                                    }
                                });

                                // Cargar los datos en todos los formularios después de guardar
                                setTimeout(() => {
                                    if (typeof cargarDatosEnTodosLosFormularios === 'function') {
                                        cargarDatosEnTodosLosFormularios();
                                    }
                                }, 300);

                                // Verificar y restaurar estado de readonly en todos los formularios
                                verificarYRestaurarEstadoCompletado();

                            } else {
                                alert('Error al guardar los datos. Por favor, intente nuevamente.');
                            }
                        })
                        .catch(error => {
                            alert(error.message || 'Error al guardar los datos. Por favor, intente nuevamente.');
                        });
                });
            });
        }


        // Lógica para el checkbox de términos y el botón de reservar (Mercado Pago)
        function inicializarLogicaPago(cursadaId) {
            const checkboxTerminos = document.getElementById('acepto-terminos-' + cursadaId) ||
                document.getElementById('acepto-terminos-modal-' + cursadaId);

            const btnReservar = document.querySelector('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');

            if (checkboxTerminos && btnReservar) {
                // Remover listeners anteriores para evitar duplicados (hack simple: clonar y reemplazar)
                // Mejor enfoque: usar un flag o verificar si ya tiene listener.
                // Para simplificar y dado que esto se puede llamar varias veces, hacemos un check simple
                if (checkboxTerminos.dataset.listenerAdded === 'true') return;

                checkboxTerminos.addEventListener('change', function () {
                    if (this.checked) {
                        btnReservar.disabled = false;
                        btnReservar.classList.add('activo');

                        // Obtener leadId
                        let panel = document.getElementById('panel-' + cursadaId);
                        if (!panel && cursadaId.includes('cursada-')) {
                            panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                        } else if (!panel) {
                            panel = document.getElementById('panel-cursada-' + cursadaId);
                        }

                        const leadId = panel ? panel.getAttribute('data-lead-id') : null;

                        // Actualizar términos en backend (opcional pero recomendado)
                        if (leadId) {
                            fetch(`/leads/${leadId}/terms`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({ acepto_terminos: true })
                            }).catch(e => console.error('Error actualizando términos:', e));
                        }
                    } else {
                        btnReservar.disabled = true;
                        btnReservar.classList.remove('activo');
                    }
                });

                checkboxTerminos.dataset.listenerAdded = 'true';

                // Listener para el botón de reservar (PAGO)
                // MODIFICADO: Buscar TODOS los botones (desktop y mobile/modal)
                const allReservarBtns = document.querySelectorAll('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');

                allReservarBtns.forEach(btn => {
                    if (btn.dataset.paymentListenerAdded === 'true') return;

                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (this.disabled) return;

                        const originalText = this.textContent;
                        this.textContent = 'Procesando...';
                        this.disabled = true;

                        // Obtener leadId
                        let panel = document.getElementById('panel-' + cursadaId);
                        if (!panel && cursadaId.includes('cursada-')) {
                            panel = document.getElementById('panel-' + cursadaId.replace('cursada-', ''));
                        } else if (!panel) {
                            panel = document.getElementById('panel-cursada-' + cursadaId);
                        }

                        // ID de Cursada
                        let idCursoToSend = cursadaId; // Fallback
                        const cursadaItem = this.closest('.cursada-item') || (panel ? panel.closest('.cursada-item') : null);
                        if (cursadaItem && cursadaItem.dataset.idCurso) {
                            idCursoToSend = cursadaItem.dataset.idCurso;
                        }

                        const leadId = panel ? panel.getAttribute('data-lead-id') : null;

                        if (!leadId) {
                            alert('Error: No se ha identificado al usuario. Por favor complete el formulario nuevamente.');
                            this.textContent = originalText;
                            this.disabled = false;
                            return;
                        }

                        // Llamada a Mercado Pago
                        fetch('/mp/create_preference', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                lead_id: leadId,
                                cursada_id: idCursoToSend
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.init_point) {
                                    // Redireccionar a MP
                                    window.location.href = data.init_point;
                                } else {
                                    throw new Error(data.error || 'Error al obtener link de pago');
                                }
                            })
                            .catch(error => {
                                console.error('Error pago:', error);
                                alert('Hubo un error al iniciar el pago: ' + error.message);
                                this.textContent = originalText;
                                this.disabled = false;
                            });
                    });

                    btn.dataset.paymentListenerAdded = 'true';
                });
            }
        }

        // Hook para llamar a inicializarLogicaPago cuando se renderiza o muestra el formulario
        // Modificamos mostrarValoresEnFormulario para que llame a esta lógica
        const originalMostrarValores = mostrarValoresEnFormulario;
        mostrarValoresEnFormulario = function (formCursadaId) {
            originalMostrarValores(formCursadaId);
            // Dar un pequeño tiempo para que el DOM se actualice si es necesario
            setTimeout(() => {
                inicializarLogicaPago(formCursadaId);
            }, 100);
        };

        // Inicializar formularios cuando se cargan las cursadas
        initializeFormularios();


        // Verificar y restaurar estado completado después de inicializar formularios
        setTimeout(() => {
            if (typeof verificarYRestaurarEstadoCompletado === 'function') {
                verificarYRestaurarEstadoCompletado();
            }
        }, 500);

        // Inicializar: deshabilitar tabindex de todos los inputs en paneles ocultos
        document.querySelectorAll('.cursada-valores-panel.panel-hidden').forEach(panel => {
            const panelId = panel.getAttribute('id');
            const cursadaId = panelId.replace('panel-', '');
            const formulario = document.getElementById('formulario-' + cursadaId);
            if (formulario) {
                const inputs = formulario.querySelectorAll('.cursada-formulario-input');
                inputs.forEach(input => {
                    input.setAttribute('tabindex', '-1');
                });
            }
        });

        // Validaciones de formularios
        document.querySelectorAll('.cursada-formulario-input').forEach(input => {
            // Función para obtener el elemento de error
            function getErrorElement(input) {
                // El ID del input es como "nombre-123" o "dni-123"
                const parts = input.id.split('-');
                const fieldName = parts[0];
                const cursadaId = parts.slice(1).join('-');
                return document.getElementById('error-' + fieldName + '-' + cursadaId);
            }

            // Función para mostrar error
            function mostrarError(input, mensaje) {
                const errorElement = getErrorElement(input);
                if (errorElement) {
                    input.classList.add('error');
                    errorElement.textContent = mensaje;
                    errorElement.classList.add('show');
                }
            }

            // Función para ocultar error
            function ocultarError(input) {
                const errorElement = getErrorElement(input);
                if (errorElement) {
                    input.classList.remove('error');
                    errorElement.classList.remove('show');
                    errorElement.textContent = '';
                }
            }

            // Validación de DNI: solo números, máximo 8 dígitos, sin puntos
            if (input.name === 'dni') {
                input.addEventListener('input', function (e) {
                    // Remover cualquier carácter que no sea número
                    let valor = e.target.value.replace(/[^0-9]/g, '');
                    // Limitar a 8 dígitos
                    if (valor.length > 8) {
                        valor = valor.substring(0, 8);
                    }
                    e.target.value = valor;
                    // Limpiar error mientras escribe
                    ocultarError(e.target);
                });

                input.addEventListener('blur', function (e) {
                    const valor = e.target.value.trim();
                    if (valor && (valor.length < 7 || valor.length > 8)) {
                        mostrarError(e.target, 'El DNI debe tener entre 7 y 8 dígitos');
                    } else {
                        ocultarError(e.target);
                    }
                });
            }

            // Validación de email
            if (input.type === 'email') {
                input.addEventListener('blur', function (e) {
                    const valor = e.target.value.trim();
                    if (valor) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(valor)) {
                            mostrarError(e.target, 'Por favor ingrese un correo electrónico válido');
                        } else {
                            ocultarError(e.target);
                        }
                    } else {
                        ocultarError(e.target);
                    }
                });
            }

            // Limpiar errores cuando el usuario empieza a escribir
            input.addEventListener('input', function (e) {
                if (e.target.name !== 'dni' && e.target.name !== 'telefono') {
                    ocultarError(e.target);
                }
            });
        });
    } // Cierre de initializeForms()

    // Calcular dinámicamente el top del sticky wrapper en mobile
    function calcularTopStickyWrapper() {
        // Solo ejecutar en mobile
        if (window.innerWidth > 599) return;

        const stickyWrapper = document.querySelector('.inscripcion-mobile-sticky-wrapper');
        if (!stickyWrapper) return;

        // Calcular altura del header
        const header = document.querySelector('.header');
        let headerHeight = 0;
        if (header) {
            const headerRect = header.getBoundingClientRect();
            headerHeight = headerRect.height;
        }

        // Calcular altura del sticky-bar si existe y está visible
        let stickyBarHeight = 0;
        const stickyBar = document.querySelector('.sticky-bar');
        if (stickyBar && stickyBar.offsetParent !== null) {
            // El sticky-bar está visible
            const stickyBarRect = stickyBar.getBoundingClientRect();
            stickyBarHeight = stickyBarRect.height;
        }

        // Calcular el top total
        const topTotal = headerHeight + stickyBarHeight;

        // Aplicar el top al sticky wrapper
        stickyWrapper.style.top = topTotal + 'px';
    }

    // Ejecutar al cargar y al redimensionar
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', calcularTopStickyWrapper);
    } else {
        calcularTopStickyWrapper();
    }

    // Recalcular al redimensionar la ventana
    let resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(calcularTopStickyWrapper, 100);
    });

    // Recalcular cuando cambia la visibilidad del sticky-bar (si se agrega/elimina dinámicamente)
    const observer = new MutationObserver(function (mutations) {
        calcularTopStickyWrapper();
    });

    const stickyBar = document.querySelector('.sticky-bar');
    if (stickyBar) {
        observer.observe(stickyBar, { attributes: true, attributeFilter: ['style', 'class'] });
    }
})(); // IIFE - se ejecuta inmediatamente
