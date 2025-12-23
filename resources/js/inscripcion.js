        // Funcionalidad de sedes - Acordeón
        // Optimización: ejecutar inmediatamente si el DOM ya está listo
        (function() {
            // Variables globales para el scope de la función
            let cursadasContainer = null;
            let loadingIndicator = null;
            // Construir URL de forma más robusta
            const buscarDescuentoUrl = (function() {
                try {
                    const baseUrl = window.location.origin;
                    return baseUrl + '/buscar-descuento';
                } catch(e) {
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
                            console.error('Error al cargar cursadas:', error);
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
            
            // Función para renderizar cursadas desde JSON (optimizada)
            function renderCursadas(cursadas, promoBadgeInfo) {
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
                    
                    // Actualizar contenido dinámico
                    const diaTurnoContainer = clone.querySelector('.cursada-item-dia-turno');
                    if (diaTurnoContainer) {
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
                    }
                    
                    const horario = clone.querySelector('.cursada-dia-turno-horario');
                    if (horario) {
                        if (pre.horarioFormateado && pre.horarioFormateado !== 'TEMPLATE_HORARIO') {
                            horario.textContent = ' (' + pre.horarioFormateado + ')';
                        } else {
                            horario.remove();
                        }
                    }
                    
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
                    
                    const vacantes = clone.querySelector('.cursada-lugares-texto strong');
                    if (vacantes) vacantes.textContent = vacantesCount;
                    
                    // Actualizar descuento
                    const descuentoWrapper = clone.querySelector('.cursada-descuento-wrapper');
                    if (descuentoWrapper) {
                        const descuento = Math.abs(pre.dtoCuotaValue || 0);
                        if (descuento > 0.01 && vacantesCount > 0) {
                            const badgeDescuento = descuentoWrapper.querySelector('.cursada-badge-descuento');
                            if (badgeDescuento) badgeDescuento.textContent = Math.round(descuento) + '% OFF';
                        } else {
                            descuentoWrapper.remove();
                        }
                    }
                    
                    // Actualizar badge promo
                    const promoBadgeImg = clone.querySelector('.cursada-promo-badge');
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
                    
                    // Actualizar botón ver valores - IMPORTANTE: remover clases y disabled por defecto
                    const btnVerValores = clone.querySelector('.cursada-btn-ver-valores');
                    if (btnVerValores) {
                        btnVerValores.classList.remove('sin-vacantes');
                        btnVerValores.disabled = false;
                        // Solo aplicar sin-vacantes si realmente no hay vacantes
                        if (sinVacantes) {
                            btnVerValores.classList.add('sin-vacantes');
                            btnVerValores.disabled = true;
                        }
                    }
                    
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
                            const valor = parseFloat(cursada.Cta_Web || 0).toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
                        
                        const precioTotal = cuotaInfo.querySelector('.cursada-cuota-precio-total');
                        if (precioTotal) {
                            const precio = parseFloat(cursada.Sin_IVA_cta || 0).toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
                        setTimeout(initializeFiltering, 0);
                    }
                }
                
                // Iniciar procesamiento por lotes
                processBatch();
            }
            
            // Función para inicializar el filtrado después de cargar las cursadas
            function initializeFiltering() {
                const sedeRows = document.querySelectorAll('.contacto-sede-row[data-sede]');
                
                sedeRows.forEach(row => {
                    row.addEventListener('click', function() {
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
                opcionesFiltro.forEach(opcion => {
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
                    
                    partes.forEach(function(parte) {
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
                // Limpiar chips existentes
                filtrosAplicadosContainer.innerHTML = '';
                
                Object.keys(filtrosSeleccionados).forEach(tipo => {
                    const valor = filtrosSeleccionados[tipo];
                    if (valor) {
                        const texto = obtenerTextoFiltro(tipo, valor);
                        if (texto) {
                            const chip = document.createElement('span');
                            chip.className = 'filtro-chip';
                            
                            const textoChip = document.createElement('span');
                            textoChip.textContent = texto;
                            
                            const btnEliminar = document.createElement('span');
                            btnEliminar.className = 'filtro-chip-eliminar';
                            btnEliminar.textContent = 'X';
                            btnEliminar.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                eliminarFiltro(tipo);
                            });
                            
                            chip.appendChild(textoChip);
                            chip.appendChild(btnEliminar);
                            filtrosAplicadosContainer.appendChild(chip);
                        }
                    }
                });
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
                
                todasLasCursadas.forEach(item => {
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
                    
                    // Para modalidad: el valor puede ser "modalidad|regimen" o solo "modalidad"
                    let coincideModalidad = true;
                    if (modalidadSeleccionada !== '') {
                        if (modalidadSeleccionada.includes('|')) {
                            // Es una combinación modalidad|regimen
                            const [modalidadFiltro, regimenFiltro] = modalidadSeleccionada.split('|');
                            coincideModalidad = normalizarComparar(modalidad, modalidadFiltro) && normalizarComparar(regimen, regimenFiltro);
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
                    
                    if (coincideCarrera && coincideSede && coincideModalidad && coincideTurno && coincideDia && coincidePromocion) {
                        item.style.display = 'block';
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
                            // console.log('No coincide:', razones.join(', '));
                        }
                    }
                });
                
                // Actualizar contador de resultados
                if (contadorResultados) {
                    contadorResultados.textContent = visibleCount;
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
            
            // Agregar event listeners a las opciones de filtro
            opcionesFiltro.forEach(opcion => {
                opcion.addEventListener('click', function() {
                    const tipo = this.getAttribute('data-tipo');
                    const valor = this.getAttribute('data-valor');
                    
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
                });
            });
            
            // Event listener para borrar todos los filtros
            if (borrarTodoBtn) {
                borrarTodoBtn.addEventListener('click', function(e) {
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
                        const botonVerValores = document.querySelector('.cursada-btn-ver-valores[data-cursada-id="' + cursadaId + '"]');
                        if (botonVerValores) {
                            botonVerValores.classList.remove('panel-desplegado');
                        }
                        
                        const infoTexto = document.getElementById('info-' + cursadaId);
                        if (infoTexto) {
                            infoTexto.classList.remove('panel-visible');
                            infoTexto.classList.add('panel-hidden');
                        }
                        
                        const formulario = document.getElementById('formulario-' + cursadaId);
                        if (formulario) {
                            const inputs = formulario.querySelectorAll('.cursada-formulario-input');
                            inputs.forEach(input => {
                                input.setAttribute('tabindex', '-1');
                                input.value = ''; // Borrar el valor del input
                            });
                            const telefonoPrefijo = formulario.querySelector('#telefono-prefijo-' + cursadaId);
                            if (telefonoPrefijo) {
                                telefonoPrefijo.setAttribute('tabindex', '-1');
                                telefonoPrefijo.value = '+54'; // Resetear al valor por defecto
                            }
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
            
            // Funcionalidad para mostrar/ocultar panel de valores
            const botonesVerValores = document.querySelectorAll('.cursada-btn-ver-valores');
            botonesVerValores.forEach(boton => {
                boton.addEventListener('click', function() {
                    // Prevenir click si el botón está deshabilitado (sin vacantes)
                    if (this.classList.contains('sin-vacantes') || this.disabled) {
                        return;
                    }
                    
                    const cursadaId = this.getAttribute('data-cursada-id');
                    const panel = document.getElementById('panel-' + cursadaId);
                    const infoTexto = document.getElementById('info-' + cursadaId);
                    const formulario = document.getElementById('formulario-' + cursadaId);
                    
                    if (panel) {
                        // Toggle del panel con animación
                        if (panel.classList.contains('panel-visible')) {
                            // Cerrar este panel
                            panel.classList.remove('panel-visible');
                            panel.classList.add('panel-hidden');
                            this.classList.remove('panel-desplegado');
                            if (infoTexto) {
                                infoTexto.classList.remove('panel-visible');
                                infoTexto.classList.add('panel-hidden');
                            }
                            // Deshabilitar tabindex y limpiar inputs cuando el panel se oculta
                            if (formulario) {
                                const inputs = formulario.querySelectorAll('.cursada-formulario-input');
                                inputs.forEach(input => {
                                    input.setAttribute('tabindex', '-1');
                                    input.value = ''; // Borrar el valor del input
                                });
                                const telefonoPrefijo = formulario.querySelector('#telefono-prefijo-' + cursadaId);
                                if (telefonoPrefijo) {
                                    telefonoPrefijo.setAttribute('tabindex', '-1');
                                    telefonoPrefijo.value = '+54'; // Resetear al valor por defecto
                                }
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
                        } else {
                            // Cerrar todos los demás paneles antes de abrir este
                            cerrarTodosLosPaneles(cursadaId);
                            
                            // Abrir este panel
                            panel.classList.remove('panel-hidden');
                            panel.classList.add('panel-visible');
                            this.classList.add('panel-desplegado');
                            if (infoTexto) {
                                infoTexto.classList.remove('panel-hidden');
                                infoTexto.classList.add('panel-visible');
                            }
                            // Inicializar valores de descuento cuando se abre el panel
                            // Asegurar que muestren "n/d" si el formulario no está validado
                            setTimeout(() => {
                                inicializarValoresDescuento(cursadaId);
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
                            }
                        }
                    }
                });
            });
            
            // Funcionalidad para código de descuento
            document.addEventListener('click', function(e) {
                // Manejar click en el link "¡Tengo Código de descuento!"
                if (e.target.classList.contains('cursada-link-codigo-descuento')) {
                    e.preventDefault();
                    // Verificar si el link está deshabilitado
                    if (e.target.classList.contains('cursada-link-disabled')) {
                        return;
                    }
                    const cursadaId = e.target.getAttribute('data-cursada-id');
                    const inputContainer = document.getElementById('codigo-input-' + cursadaId);
                    if (inputContainer) {
                        const isCurrentlyVisible = inputContainer.style.display === 'flex';
                        if (isCurrentlyVisible) {
                            inputContainer.style.display = 'none';
                            inputContainer.classList.remove('input-visible');
                        } else {
                            inputContainer.style.display = 'flex';
                            inputContainer.classList.add('input-visible');
                            const input = document.getElementById('codigo-input-field-' + cursadaId);
                            if (input) input.focus();
                        }
                    }
                }
                
                // Manejar click en el botón "Aplicar"
                if (e.target.classList.contains('cursada-codigo-descuento-btn')) {
                    e.preventDefault();
                    const cursadaId = e.target.getAttribute('data-cursada-id');
                    // Verificar si el link de código está deshabilitado
                    const linkCodigo = document.getElementById('link-codigo-' + cursadaId);
                    if (linkCodigo && linkCodigo.classList.contains('cursada-link-disabled')) {
                        return;
                    }
                    aplicarCodigoDescuento(cursadaId);
                }
            });
            
            // Manejar Enter en el input de código
            document.addEventListener('keypress', function(e) {
                if (e.target.classList.contains('cursada-codigo-descuento-input')) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const cursadaId = e.target.getAttribute('data-cursada-id');
                        aplicarCodigoDescuento(cursadaId);
                    }
                }
            });
            
            // Manejar cambio en checkbox de términos y condiciones
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('cursada-checkbox-terminos-input')) {
                    const cursadaId = e.target.getAttribute('data-cursada-id');
                    const btnReservar = document.querySelector('.cursada-btn-reservar[data-cursada-id="' + cursadaId + '"]');
                    if (btnReservar) {
                        btnReservar.disabled = !e.target.checked;
                    }
                }
            });
            
            // Función para inicializar valores de descuento (cuando no hay descuento aplicado)
            function inicializarValoresDescuento(cursadaId) {
                const descuentoAplicado = document.getElementById('descuento-aplicado-' + cursadaId);
                const totalAplicado = document.getElementById('total-aplicado-' + cursadaId);
                const valorDescuento = descuentoAplicado ? descuentoAplicado.querySelector('.cursada-descuento-valor') : null;
                const valorTotal = totalAplicado ? totalAplicado.querySelector('.cursada-total-valor') : null;
                
                // Verificar si el formulario está validado
                // El formulario está validado solo si cuotaInfo está visible Y el valor de matrícula no es "n/d"
                const cuotaInfo = document.getElementById('cuota-info-' + cursadaId);
                const valorMatricula = document.getElementById('valor-matricula-' + cursadaId);
                const formularioValidado = cuotaInfo && cuotaInfo.style.display !== 'none' && 
                                          valorMatricula && valorMatricula.textContent.trim() !== 'n/d';
                
                if (formularioValidado) {
                    // Si el formulario está validado, mostrar valores reales
                    const matricBaseEl = document.querySelector('#panel-' + cursadaId + ' .cursada-valores-renglon-1 strong');
                    if (matricBaseEl) {
                        const matricBaseText = matricBaseEl.textContent.replace(/[^0-9,]/g, '').replace(',', '.');
                        const matricBase = parseFloat(matricBaseText) || 0;
                        
                        // Establecer descuento en $0,00
                        if (valorDescuento) {
                            valorDescuento.textContent = '$0,00';
                        }
                        
                        // Establecer total igual a matrícula
                        if (valorTotal) {
                            const valorFormateado = matricBase.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            valorTotal.textContent = '$' + valorFormateado;
                        }
                    }
                } else {
                    // Si el formulario no está validado, mantener "n/d"
                    if (valorDescuento) {
                        valorDescuento.textContent = 'n/d';
                    }
                    if (valorTotal) {
                        valorTotal.textContent = 'n/d';
                    }
                }
                
                // Ocultar icono de promo mat logo
                const promoBadgeDescuento = document.getElementById('promo-badge-descuento-' + cursadaId);
                if (promoBadgeDescuento) {
                    promoBadgeDescuento.style.display = 'none';
                }
            }
            
            // Función para aplicar código de descuento
            function aplicarCodigoDescuento(cursadaId) {
                const input = document.getElementById('codigo-input-field-' + cursadaId);
                const descuentoAplicado = document.getElementById('descuento-aplicado-' + cursadaId);
                const totalAplicado = document.getElementById('total-aplicado-' + cursadaId);
                const valorDescuento = descuentoAplicado ? descuentoAplicado.querySelector('.cursada-descuento-valor') : null;
                const valorTotal = totalAplicado ? totalAplicado.querySelector('.cursada-total-valor') : null;
                
                if (!input) return;
                
                const codigo = input.value.trim();
                if (!codigo) {
                    // Si no hay código, reinicializar valores
                    inicializarValoresDescuento(cursadaId);
                    return;
                }
                
                // Buscar el valor de matrícula base
                const matricBaseEl = document.querySelector('#panel-' + cursadaId + ' .cursada-valores-renglon-1 strong');
                if (!matricBaseEl) return;
                
                const matricBaseText = matricBaseEl.textContent.replace(/[^0-9,]/g, '').replace(',', '.');
                const matricBase = parseFloat(matricBaseText) || 0;
                
                // Hacer la petición al servidor usando FormData para mejor compatibilidad con CSRF
                const formData = new FormData();
                formData.append('codigo', codigo);
                formData.append('_token', csrfToken);
                
                fetch(buscarDescuentoUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
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
                            const valorFormateado = descuentoCalculado.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            valorDescuento.textContent = '-$' + valorFormateado;
                            
                            // Guardar el descuento en un data attribute para uso posterior
                            descuentoAplicado.setAttribute('data-porcentaje', porcentaje);
                            descuentoAplicado.setAttribute('data-valor-descuento', descuentoCalculado);
                            descuentoAplicado.setAttribute('data-valor-final', valorFinal);
                        }
                        
                        // Actualizar el total aplicado (matrícula - descuento)
                        if (totalAplicado && valorTotal) {
                            const totalFormateado = valorFinal.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            valorTotal.textContent = '$' + totalFormateado;
                        }
                        
                        // Mostrar icono de promo mat logo solo si la cursada tiene Promo_Mat_logo === 'mostrar'
                        const panel = document.getElementById('panel-' + cursadaId);
                        const promoBadgeDescuento = document.getElementById('promo-badge-descuento-' + cursadaId);
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
                    const formulario = document.getElementById('formulario-' + cursadaId);
                if (!formulario) return false;
                
                const nombre = formulario.querySelector('#nombre-' + cursadaId);
                const apellido = formulario.querySelector('#apellido-' + cursadaId);
                const dni = formulario.querySelector('#dni-' + cursadaId);
                const correo = formulario.querySelector('#correo-' + cursadaId);
                const telefono = formulario.querySelector('#telefono-' + cursadaId);
                
                return validarCampoTexto(nombre?.value) &&
                       validarCampoTexto(apellido?.value) &&
                       validarDni(dni) &&
                       validarCorreo(correo) &&
                       validarTelefono(telefono);
            }
            
            function actualizarEstadoBotonContinuar(cursadaId) {
                const botonContinuar = document.querySelector('.cursada-btn-continuar[data-cursada-id="' + cursadaId + '"]');
                if (botonContinuar) {
                    botonContinuar.classList.toggle('activo', validarFormularioCompleto(cursadaId));
                }
            }
            
            // Validación en tiempo real para todos los formularios
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
                            
                // Función genérica para validar campo de texto
                function setupCampoTexto(input, errorElement, mensaje) {
                    if (!input) return;
                    input.addEventListener('blur', function() {
                        if (!validarCampoTexto(this.value)) {
                            mostrarError(this, errorElement, mensaje);
                        } else {
                            ocultarError(this, errorElement);
                        }
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                    input.addEventListener('input', function() {
                        if (this.value.trim()) ocultarError(this, errorElement);
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                }
                
                // Función genérica para validar campo con función personalizada
                function setupCampoValidado(input, errorElement, validarFn, mensajeError) {
                    if (!input) return;
                    input.addEventListener('blur', function() {
                        if (!validarFn(this)) {
                            mostrarError(this, errorElement, mensajeError);
                        } else {
                            ocultarError(this, errorElement);
                        }
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                    input.addEventListener('input', function() {
                        if (validarFn(this)) ocultarError(this, errorElement);
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                }
                
                // Configurar validaciones
                setupCampoTexto(nombre, errorNombre, 'Este campo es obligatorio');
                setupCampoTexto(apellido, errorApellido, 'Este campo es obligatorio');
                setupCampoValidado(dni, errorDni, validarDni, 'El DNI debe tener entre 7 y 8 dígitos');
                if (correo) {
                    correo.addEventListener('blur', function() {
                        if (!validarCorreo(this)) {
                            mostrarError(this, errorCorreo, this.value.trim() ? 'Por favor ingrese un correo electrónico válido' : 'Este campo es obligatorio');
                        } else {
                            ocultarError(this, errorCorreo);
                        }
                        actualizarEstadoBotonContinuar(cursadaId);
                    });
                    correo.addEventListener('input', function() {
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
                    telefono.addEventListener('blur', function() {
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
                
                boton.addEventListener('click', function(e) {
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
                    
                                // Guardar lead
                                fetch(window.inscripcionConfig.leadsStoreUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                    },
                                    body: JSON.stringify({
                                        nombre: nombre.value.trim(),
                                        apellido: apellido.value.trim(),
                                        dni: dni.value.trim(),
                                        correo: correo.value.trim(),
                            telefono: telefonoCompleto
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json().then(data => {
                                            throw new Error(data.message || 'Error al guardar los datos');
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                            // Desactivar formulario y botón después de guardar exitosamente
                            const inputs = formulario.querySelectorAll('input, select');
                            inputs.forEach(input => {
                                input.setAttribute('readonly', 'readonly');
                                input.style.pointerEvents = 'none';
                                input.style.opacity = '0.6';
                            });
                            
                            // Desactivar botón
                            this.classList.remove('activo');
                            this.disabled = true;
                            this.style.opacity = '0.6';
                            this.style.cursor = 'not-allowed';
                            
                            // Mostrar sección de información de cuota
                            const cuotaInfo = document.getElementById('cuota-info-' + cursadaId);
                            if (cuotaInfo) {
                                cuotaInfo.style.display = 'block';
                            }
                            
                            // Habilitar elementos que estaban deshabilitados
                            const linkCodigo = document.getElementById('link-codigo-' + cursadaId);
                            if (linkCodigo) {
                                linkCodigo.classList.remove('cursada-link-disabled');
                            }
                            const checkboxTerminos = document.getElementById('acepto-terminos-' + cursadaId);
                            if (checkboxTerminos) {
                                checkboxTerminos.disabled = false;
                            }
                            const labelCheckbox = document.querySelector('label[for="acepto-terminos-' + cursadaId + '"]');
                            if (labelCheckbox) {
                                labelCheckbox.classList.remove('cursada-checkbox-disabled');
                            }
                            const linkVer = document.getElementById('link-ver-' + cursadaId);
                            if (linkVer) {
                                linkVer.classList.remove('cursada-link-disabled');
                            }
                            
                            // Actualizar valores de "n/d" a valores reales
                            const panel = document.getElementById('panel-' + cursadaId);
                            if (panel) {
                                // Obtener valores de los data attributes o del cuotaInfo
                                const matricBase = parseFloat(cuotaInfo?.getAttribute('data-matric-base') || 0);
                                const sinIvaMat = parseFloat(cuotaInfo?.getAttribute('data-sin-iva-mat') || 0);
                                
                                // Actualizar valor de matrícula
                                const valorMatricula = document.getElementById('valor-matricula-' + cursadaId);
                                if (valorMatricula && matricBase > 0) {
                                    const valorFormateado = matricBase.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    valorMatricula.textContent = '$' + valorFormateado;
                                }
                                
                                // Actualizar precio total de matrícula sin impuestos
                                const precioTotalMatricula = document.getElementById('precio-total-matricula-' + cursadaId);
                                if (precioTotalMatricula && sinIvaMat > 0) {
                                    const precioFormateado = sinIvaMat.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    precioTotalMatricula.textContent = '$' + precioFormateado;
                                }
                                
                                // Actualizar descuento y total usando la función de inicialización
                                // Esto asegura que se muestren correctamente según el estado del formulario
                                inicializarValoresDescuento(cursadaId);
                            }
                            
                                    } else {
                                        alert('Error al guardar los datos. Por favor, intente nuevamente.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert(error.message || 'Error al guardar los datos. Por favor, intente nuevamente.');
                    });
                });
            });
            
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
                    input.addEventListener('input', function(e) {
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
                    
                    input.addEventListener('blur', function(e) {
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
                    input.addEventListener('blur', function(e) {
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
                input.addEventListener('input', function(e) {
                    if (e.target.name !== 'dni' && e.target.name !== 'telefono') {
                        ocultarError(e.target);
                    }
                });
            });
            } // Cierre de initializeForms()
        })(); // IIFE - se ejecuta inmediatamente
