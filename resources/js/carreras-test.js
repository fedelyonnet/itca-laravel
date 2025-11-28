// JavaScript para la vista de gestión de carreras (test.blade.php)
// Funciones para manejar toasts - igual que el resto del sitio
function showNotify(type, message) {
    const container = document.getElementById('toast-container');
    if (!container) {
        console.error('Toast container no encontrado');
        return;
    }
    
    // Remover mensajes existentes del mismo tipo
    const existingMessage = document.getElementById(type === 'success' ? 'success-message' : 'error-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Crear nuevo toast
    const toastDiv = document.createElement('div');
    toastDiv.id = type === 'success' ? 'success-message' : 'error-message';
    toastDiv.className = type === 'success' 
        ? 'bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out'
        : 'bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out';
    
    // Icono
    const iconSvg = type === 'success'
        ? '<svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
        : '<svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
    
    toastDiv.innerHTML = iconSvg + '<span>' + message + '</span>';
    container.appendChild(toastDiv);
    
    // Animar entrada
    setTimeout(() => {
        toastDiv.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-cerrar solo para success después de 5 segundos
    if (type === 'success') {
        setTimeout(() => {
            toastDiv.classList.add('translate-x-full');
            setTimeout(() => toastDiv.remove(), 300);
        }, 5000);
    }
}

// Modal de confirmación
let confirmCallback = null;
let isConfirmModalOpen = false;
function showConfirm(title, message, callback) {
    // Prevenir abrir el modal si ya está abierto
    if (isConfirmModalOpen) {
        return;
    }
    isConfirmModalOpen = true;
    confirmCallback = callback;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    confirmCallback = null;
    isConfirmModalOpen = false;
}

function executeConfirm() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirmModal();
}

// ========== FUNCIONES PARA AÑOS ==========

function openModalAnio(cursoId) {
    resetAnioModal();
    document.getElementById('anioCursoId').value = cursoId;
    document.getElementById('anioModalTitle').textContent = 'Agregar Año';
    document.getElementById('anioSubmitBtn').textContent = 'Agregar Año';
    document.getElementById('anioForm').action = window.routes.admin.programas.anios.store;
    document.getElementById('anioMethodField').value = '';
    document.getElementById('anioErrors').classList.add('hidden');
    document.getElementById('anioModal').classList.remove('hidden');
}

function editAnio(anioId) {
    resetAnioModal();
    
    fetch(window.routes.admin.programas.anios.data.replace(':id', anioId))
        .then(response => response.json())
        .then(data => {
            document.getElementById('anioModalTitle').textContent = 'Editar Año';
            document.getElementById('anioSubmitBtn').textContent = 'Actualizar Año';
            document.getElementById('anioMethodField').value = 'PUT';
            document.getElementById('anioId').value = data.id;
            document.getElementById('anioCursoId').value = data.curso_id;
            document.getElementById('anioForm').action = window.routes.admin.programas.anios.update.replace(':id', data.id);
            document.getElementById('anioNumero').value = data.año || '';
            
            document.getElementById('anioErrors').classList.add('hidden');
            document.getElementById('anioModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error al cargar los datos del año:', error);
            showNotify('error', 'Error al cargar los datos del año');
        });
}

function closeAnioModal() {
    document.getElementById('anioModal').classList.add('hidden');
    resetAnioModal();
}

function resetAnioModal() {
    document.getElementById('anioForm').reset();
    document.getElementById('anioId').value = '';
    document.getElementById('anioCursoId').value = '';
    document.getElementById('anioMethodField').value = '';
    document.getElementById('anioErrors').classList.add('hidden');
}

function handleAnioFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    if (document.getElementById('anioMethodField').value === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showNotify('success', 'Año guardado correctamente');
            closeAnioModal();
            setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.set('tab', 'programa');
                window.location.href = url.toString();
            }, 1000);
        } else {
            return response.text().then(text => {
                throw new Error(text);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al guardar el año. Verifica los datos.');
    });
    
    return false;
}

function deleteAnio(id) {
    fetch(window.routes.admin.programas.anios.destroy.replace(':id', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'DELETE',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showNotify('success', 'Año eliminado correctamente');
            setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.set('tab', 'programa');
                window.location.href = url.toString();
            }, 1000);
        } else {
            showNotify('error', 'Error al eliminar el año');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al eliminar el año');
    });
}

// ========== FUNCIONES PARA UNIDADES ==========

function openModalUnidad(cursoAnioId) {
    resetUnidadModal();
    document.getElementById('unidadCursoAnioId').value = cursoAnioId;
    document.getElementById('unidadModalTitle').textContent = 'Agregar Unidad';
    document.getElementById('unidadSubmitBtn').textContent = 'Agregar Unidad';
    document.getElementById('unidadForm').action = window.routes.admin.programas.unidades.store;
    document.getElementById('unidadMethodField').value = '';
    document.getElementById('unidadErrors').classList.add('hidden');
    document.getElementById('unidadModal').classList.remove('hidden');
}

function editUnidad(unidadId) {
    resetUnidadModal();
    
    fetch(window.routes.admin.programas.unidades.data.replace(':id', unidadId))
        .then(response => response.json())
        .then(data => {
            document.getElementById('unidadModalTitle').textContent = 'Editar Unidad';
            document.getElementById('unidadSubmitBtn').textContent = 'Actualizar Unidad';
            document.getElementById('unidadMethodField').value = 'PUT';
            document.getElementById('unidadId').value = data.id;
            document.getElementById('unidadCursoAnioId').value = data.curso_anio_id;
            document.getElementById('unidadForm').action = window.routes.admin.programas.unidades.update.replace(':id', data.id);
            document.getElementById('unidadNumero').value = data.numero || '';
            document.getElementById('unidadTitulo').value = data.titulo || '';
            document.getElementById('unidadSubtitulo').value = data.subtitulo || '';
            
            document.getElementById('unidadErrors').classList.add('hidden');
            document.getElementById('unidadModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error al cargar los datos de la unidad:', error);
            showNotify('error', 'Error al cargar los datos de la unidad');
        });
}

function closeUnidadModal() {
    document.getElementById('unidadModal').classList.add('hidden');
    resetUnidadModal();
}

function resetUnidadModal() {
    document.getElementById('unidadForm').reset();
    document.getElementById('unidadId').value = '';
    document.getElementById('unidadCursoAnioId').value = '';
    document.getElementById('unidadMethodField').value = '';
    document.getElementById('unidadErrors').classList.add('hidden');
}

function handleUnidadFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    if (document.getElementById('unidadMethodField').value === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showNotify('success', 'Unidad guardada correctamente');
            closeUnidadModal();
            setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.set('tab', 'programa');
                window.location.href = url.toString();
            }, 1000);
        } else {
            return response.text().then(text => {
                throw new Error(text);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al guardar la unidad. Verifica los datos.');
    });
    
    return false;
}

function deleteUnidad(id) {
    fetch(window.routes.admin.programas.unidades.destroy.replace(':id', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'DELETE',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showNotify('success', 'Unidad eliminada correctamente');
            setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.set('tab', 'programa');
                window.location.href = url.toString();
            }, 1000);
        } else {
            showNotify('error', 'Error al eliminar la unidad');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al eliminar la unidad');
    });
}

// ========== FUNCIONES PARA MODALIDADES ==========

function openModalidadCreate(cursoId) {
    resetModalidadModal();
    document.getElementById('modalidadCursoId').value = cursoId;
    document.getElementById('modalidadModalTitle').textContent = 'Agregar Modalidad';
    document.getElementById('modalidadSubmitBtn').textContent = 'Agregar Modalidad';
    document.getElementById('modalidadForm').action = window.routes.admin.modalidades.store;
    document.getElementById('modalidadMethodField').value = '';
    document.getElementById('modalidadErrors').classList.add('hidden');
    document.getElementById('modalidadModal').classList.remove('hidden');
}

function editModalidad(modalidadId) {
    resetModalidadModal();
    
    fetch(window.routes.admin.modalidades.data.replace(':id', modalidadId))
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalidadModalTitle').textContent = 'Editar Modalidad';
            document.getElementById('modalidadSubmitBtn').textContent = 'Actualizar Modalidad';
            document.getElementById('modalidadMethodField').value = 'PUT';
            document.getElementById('modalidadId').value = data.id;
            // Obtener el curso_id desde el contexto de Alpine
            const carreraId = document.querySelector('[x-data]')?._x_dataStack?.[0]?.carreraId || window.carreraSeleccionadaData?.id;
            document.getElementById('modalidadCursoId').value = data.curso_id || carreraId;
            document.getElementById('modalidadForm').action = window.routes.admin.modalidades.update.replace(':id', data.id);
            
            document.getElementById('modalidadNombreLinea1').value = data.nombre_linea1 || '';
            document.getElementById('modalidadNombreLinea2').value = data.nombre_linea2 || '';
            document.getElementById('modalidadTextoInfo').value = data.texto_info || '';
            
            document.getElementById('modalidadErrors').classList.add('hidden');
            document.getElementById('modalidadModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error al cargar los datos de la modalidad:', error);
            showNotify('error', 'Error al cargar los datos de la modalidad');
        });
}

function closeModalidadModal() {
    document.getElementById('modalidadModal').classList.add('hidden');
    resetModalidadModal();
}

function resetModalidadModal() {
    document.getElementById('modalidadForm').reset();
    document.getElementById('modalidadId').value = '';
    document.getElementById('modalidadCursoId').value = '';
    document.getElementById('modalidadMethodField').value = '';
    document.getElementById('modalidadErrors').classList.add('hidden');
}

function handleModalidadFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Agregar from_test para indicar que viene de la vista test
    formData.append('from_test', '1');
    
    // Obtener el curso_id del formulario
    const cursoId = document.getElementById('modalidadCursoId').value;
    
    // Si es PUT, agregar _method
    if (document.getElementById('modalidadMethodField').value === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => {
        if (response.ok || response.redirected) {
            showNotify('success', 'Modalidad guardada correctamente');
            closeModalidadModal();
            // Recargar la página manteniendo el curso_id y la pestaña activa
            setTimeout(() => {
                const url = new URL(window.routes.admin.carreras.test);
                if (cursoId) {
                    url.searchParams.set('curso_id', cursoId);
                }
                url.searchParams.set('tab', 'modalidades');
                window.location.href = url.toString();
            }, 1000);
        } else {
            return response.text().then(text => {
                throw new Error(text);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al guardar la modalidad. Verifica los datos.');
    });
    
    return false;
}

function deleteModalidadFromTest(id) {
    if (!id) {
        showNotify('error', 'ID de modalidad no válido');
        return;
    }
    
    if (!window.routes || !window.routes.admin || !window.routes.admin.modalidades || !window.routes.admin.modalidades.destroy) {
        showNotify('error', 'Error: Ruta de eliminación no configurada');
        return;
    }
    
    const url = window.routes.admin.modalidades.destroy.replace(':id', id);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'DELETE',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ _method: 'DELETE', from_test: '1' })
    })
    .then(response => {
        if (response.ok) {
            return response.json().then(data => {
                showNotify('success', data.message || 'Modalidad eliminada correctamente');
                // Recargar la página para actualizar la lista
                setTimeout(() => {
                    const url = new URL(window.location);
                    url.searchParams.set('tab', 'modalidades');
                    window.location.href = url.toString();
                }, 1000);
            });
        } else {
            return response.json().then(data => {
                showNotify('error', data.message || 'Error al eliminar la modalidad');
            }).catch(() => {
                showNotify('error', 'Error al eliminar la modalidad');
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al eliminar la modalidad: ' + error.message);
    });
}

// ========== ALPINE.JS COMPONENTS ==========

// Función helper para formatear fecha de aaaa-mm-dd a dd/mm/aaaa
function formatearFechaParaMostrar(fechaISO) {
    if (!fechaISO) return '';
    const fecha = new Date(fechaISO);
    if (isNaN(fecha.getTime())) return '';
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const año = fecha.getFullYear();
    return `${dia}/${mes}/${año}`;
}

// Función helper para convertir fecha de dd/mm/aaaa a aaaa-mm-dd
function convertirFechaParaBackend(fechaFormateada) {
    if (!fechaFormateada || !fechaFormateada.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
        return fechaFormateada; // Si no tiene el formato esperado, devolverlo tal cual
    }
    const [dia, mes, año] = fechaFormateada.split('/');
    return `${año}-${mes}-${dia}`;
}

// Componente Alpine.js para gestionar carreras
function carreraManager() {
    return {
        carreraId: '',
        modoCrear: false,
        tabActiva: 'basico',
        featuredCount: window.featuredCount || 0,
        formData: {
            nombre: '',
            descripcion: '',
            fecha_inicio: '',
            orden: 1,
            modalidad_online: false,
            modalidad_presencial: false,
            featured: false,
            sedes: [],
            imagenes: {
                ilustracion_desktop: null,
                ilustracion_mobile: null,
                imagen_show_desktop: null,
                imagen_show_mobile: null
            },
            anios: [],
            modalidades: []
        },
        previews: {},

        get puedeMarcarDestacada() {
            // Si ya está marcada como destacada, puede desmarcarla
            if (this.formData.featured) {
                return true;
            }
            // Si no está marcada, solo puede marcarla si hay menos de 2 destacadas
            // featuredCount representa cuántas otras carreras están destacadas (excluyendo esta)
            return this.featuredCount < 2;
        },

        init() {
            const carrera = window.carreraSeleccionadaData;
            if (carrera) {
                this.carreraId = carrera.id || '';
                this.formData = {
                    nombre: carrera.nombre || '',
                    descripcion: carrera.descripcion || '',
                    fecha_inicio: carrera.fecha_inicio ? formatearFechaParaMostrar(carrera.fecha_inicio) : '',
                    orden: carrera.orden || 1,
                    modalidad_online: carrera.modalidad_online || false,
                    modalidad_presencial: carrera.modalidad_presencial || false,
                    featured: carrera.featured || false,
                    sedes: carrera.sedes ? carrera.sedes.map(s => s.id) : [],
                    imagenes: {
                        ilustracion_desktop: carrera.ilustracion_desktop || null,
                        ilustracion_mobile: carrera.ilustracion_mobile || null,
                        imagen_show_desktop: carrera.imagen_show_desktop || null,
                        imagen_show_mobile: carrera.imagen_show_mobile || null
                    },
                    anios: carrera.anios || [],
                    modalidades: carrera.modalidades || []
                };
            }
            // featuredCount ya viene calculado del servidor (excluyendo la carrera actual si está destacada)
            
            // Restaurar pestaña activa desde URL si existe
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && ['basico', 'imagenes', 'sedes', 'programa', 'modalidades'].includes(tab)) {
                this.tabActiva = tab;
            }
        },
        
        initToasts() {
            // Inicializar toasts existentes (de session)
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.remove('translate-x-full');
                    setTimeout(() => {
                        successMessage.classList.add('translate-x-full');
                    }, 5000);
                }, 100);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.remove('translate-x-full');
                    setTimeout(() => {
                        errorMessage.classList.add('translate-x-full');
                    }, 5000);
                }, 100);
            }
        },

        nuevaCarrera() {
            this.modoCrear = true;
            this.carreraId = '';
            this.limpiarFormulario();
            this.tabActiva = 'basico';
        },

        limpiarFormulario() {
            this.formData = {
                nombre: '',
                descripcion: '',
                fecha_inicio: '',
                orden: 1,
                modalidad_online: false,
                modalidad_presencial: false,
                featured: false,
                sedes: [],
                imagenes: {},
                anios: [],
                modalidades: []
            };
            this.previews = {};
        },

        previewImagen(event, campo) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews[campo] = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        validarFeatured(event) {
            // Si está intentando marcar como destacada
            if (event.target.checked) {
                // featuredCount = cuántas otras carreras están destacadas
                // Si esta se marca, el total sería featuredCount + 1
                // Solo permitir si featuredCount < 2
                if (this.featuredCount >= 2) {
                    event.target.checked = false;
                    this.formData.featured = false;
                    showNotify('error', 'Solo pueden haber máximo 2 carreras destacadas. Ya hay ' + this.featuredCount + ' otras carreras destacadas.');
                    return;
                }
                // No incrementar el conteo aquí, porque featuredCount representa otras carreras
                // Esta carrera aún no está guardada como destacada
            } else {
                // Si está desmarcando, no cambiar el conteo porque featuredCount ya excluye esta carrera
            }
        },

        async guardarBasico() {
            // Validar campos obligatorios antes de enviar
            if (!this.formData.nombre || this.formData.nombre.trim() === '') {
                showNotify('error', 'El nombre de la carrera es obligatorio');
                return;
            }
            
            if (!this.formData.fecha_inicio || this.formData.fecha_inicio.trim() === '') {
                showNotify('error', 'La fecha de inicio es obligatoria');
                return;
            }
            
            if (!this.formData.modalidad_online && !this.formData.modalidad_presencial) {
                showNotify('error', 'Debes seleccionar al menos una modalidad');
                return;
            }
            
            // Validar featured antes de enviar
            // featuredCount = cuántas otras carreras están destacadas (excluyendo esta)
            // Si esta carrera se marca como destacada, el total sería featuredCount + 1
            // Solo permitir si featuredCount + 1 <= 2, es decir, featuredCount < 2
            if (this.formData.featured && this.featuredCount >= 2) {
                showNotify('error', 'Solo pueden haber máximo 2 carreras destacadas. Ya hay ' + this.featuredCount + ' otras carreras destacadas.');
                this.formData.featured = false;
                return;
            }
            
            // Mantener la pestaña activa
            const tabActivaActual = this.tabActiva;

            const formData = new FormData();
            formData.append('nombre', this.formData.nombre);
            formData.append('descripcion', this.formData.descripcion);
            // Convertir fecha de dd/mm/aaaa a aaaa-mm-dd para el backend
            const fechaParaBackend = convertirFechaParaBackend(this.formData.fecha_inicio);
            
            // Validar que la fecha convertida sea válida
            if (!fechaParaBackend || fechaParaBackend.trim() === '' || !fechaParaBackend.match(/^\d{4}-\d{2}-\d{2}$/)) {
                showNotify('error', 'La fecha de inicio debe tener el formato dd/mm/aaaa');
                return;
            }
            
            formData.append('fecha_inicio', fechaParaBackend);
            formData.append('orden', this.formData.orden);
            if (this.formData.modalidad_online) formData.append('modalidad_online', '1');
            if (this.formData.modalidad_presencial) formData.append('modalidad_presencial', '1');
            // Siempre enviar featured (incluso si es false) para que el backend sepa el estado actual
            formData.append('featured', this.formData.featured ? '1' : '0');
            formData.append('from_test', '1'); // Indicar que viene de la vista test

            const url = this.carreraId 
                ? window.routes.admin.carreras.update.replace(':id', this.carreraId)
                : window.routes.admin.carreras.store;

            if (this.carreraId) {
                formData.append('_method', 'PUT');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                if (response.ok || response.redirected) {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        const result = await response.json();
                        if (result.success) {
                            showNotify('success', 'Guardado exitosamente');
                            // Mantener la pestaña activa
                            this.tabActiva = tabActivaActual;
                            setTimeout(() => {
                                if (!this.carreraId) {
                                    window.location.href = `${window.routes.admin.carreras.test}?curso_id=${result.curso_id}&tab=${tabActivaActual}`;
                                } else {
                                    // Recargar pero mantener la pestaña
                                    window.location.href = `${window.routes.admin.carreras.test}?curso_id=${this.carreraId}&tab=${tabActivaActual}`;
                                }
                            }, 1500);
                        }
                    }
                } else {
                    // Intentar leer como JSON primero (errores de validación)
                    let errorMessage = 'Error al guardar. Verifica los datos.';
                    try {
                        const errorData = await response.json();
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        } else if (errorData.errors) {
                            // Si hay errores de validación, mostrar todos los errores
                            const errorMessages = [];
                            for (const [field, messages] of Object.entries(errorData.errors)) {
                                if (Array.isArray(messages) && messages.length > 0) {
                                    errorMessages.push(...messages);
                                }
                            }
                            if (errorMessages.length > 0) {
                                errorMessage = errorMessages.join('. ');
                            }
                        }
                    } catch (e) {
                        // Si no es JSON, leer como texto
                        try {
                            const errorText = await response.text();
                            console.error('Error:', errorText);
                            // Intentar parsear como HTML y extraer mensajes de error
                            if (errorText.includes('fecha_inicio')) {
                                errorMessage = 'La fecha de inicio es obligatoria y debe ser válida';
                            }
                        } catch (e2) {
                            console.error('Error al leer respuesta:', e2);
                        }
                    }
                    showNotify('error', errorMessage);
                    // Restaurar la pestaña activa
                    this.tabActiva = tabActivaActual;
                }
            } catch (error) {
                console.error('Error guardando:', error);
                showNotify('error', 'Error al guardar');
                // Restaurar la pestaña activa
                this.tabActiva = tabActivaActual;
            }
        },

        async guardarImagenes() {
            if (!this.carreraId) {
                showNotify('error', 'Primero debes guardar la información básica de la carrera');
                return;
            }

            const form = document.getElementById('formImagenes');
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            formData.append('from_test', '1'); // Indicar que viene de la vista test

            // Agregar las imágenes seleccionadas
            const inputs = form.querySelectorAll('input[type="file"]');
            inputs.forEach(input => {
                if (input.files[0]) {
                    formData.append(input.name || input.id, input.files[0]);
                }
            });

            // Mantener la pestaña activa
            const tabActivaActual = this.tabActiva;

            try {
                const response = await fetch(window.routes.admin.carreras.update.replace(':id', this.carreraId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                if (response.ok) {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const result = await response.json();
                        if (result.success) {
                            showNotify('success', 'Imágenes guardadas exitosamente');
                            // Recargar manteniendo el curso_id y la pestaña activa usando GET
                            setTimeout(() => {
                                window.location.href = window.routes.admin.carreras.test + '?curso_id=' + this.carreraId + '&tab=' + tabActivaActual;
                            }, 1500);
                        } else {
                            showNotify('error', result.message || 'Error al guardar las imágenes');
                        }
                    } else {
                        // Si no es JSON, probablemente es un redirect, recargar manteniendo parámetros usando GET
                        showNotify('success', 'Imágenes guardadas exitosamente');
                        setTimeout(() => {
                            window.location.href = window.routes.admin.carreras.test + '?curso_id=' + this.carreraId + '&tab=' + tabActivaActual;
                        }, 1500);
                    }
                } else {
                    const error = await response.text();
                    console.error('Error:', error);
                    showNotify('error', 'Error al guardar las imágenes');
                }
            } catch (error) {
                console.error('Error guardando imágenes:', error);
                showNotify('error', 'Error al guardar las imágenes');
            }
        },

        async guardarSedes() {
            if (!this.carreraId) {
                showNotify('error', 'Primero debes guardar la información básica de la carrera');
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('nombre', this.formData.nombre); // Mantener otros campos
            formData.append('descripcion', this.formData.descripcion);
            // Convertir fecha de dd/mm/aaaa a aaaa-mm-dd para el backend
            const fechaParaBackend = convertirFechaParaBackend(this.formData.fecha_inicio);
            formData.append('fecha_inicio', fechaParaBackend);
            formData.append('modalidad_online', this.formData.modalidad_online ? '1' : '');
            formData.append('modalidad_presencial', this.formData.modalidad_presencial ? '1' : '');
            // Siempre enviar featured para mantener su valor actual
            formData.append('featured', this.formData.featured ? '1' : '0');
            formData.append('from_test', '1'); // Indicar que viene de la vista test
            
            // Agregar sedes seleccionadas - siempre enviar el campo sedes para que el backend sepa que se está actualizando
            // Si hay sedes seleccionadas, agregarlas
            if (this.formData.sedes && this.formData.sedes.length > 0) {
                this.formData.sedes.forEach(sedeId => {
                    if (sedeId && sedeId !== '') {
                        formData.append('sedes[]', sedeId);
                    }
                });
            }
            // Si no hay sedes seleccionadas, enviar un indicador explícito de que se está actualizando desde la pestaña de sedes
            // El backend detectará que sedes está presente (aunque vacío) y sincronizará con array vacío
            formData.append('actualizando_sedes', '1');

            try {
                const response = await fetch(window.routes.admin.carreras.update.replace(':id', this.carreraId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        // Mostrar mensaje de éxito
                        showNotify('success', 'Sedes guardadas exitosamente');
                        // Mantener la pestaña de sedes activa
                        this.tabActiva = 'sedes';
                        // No recargar la página, los datos ya están actualizados en el frontend
                    } else {
                        showNotify('error', result.message || 'Error al guardar las sedes');
                    }
                } else {
                    // Intentar leer como JSON primero (errores de validación)
                    let errorMessage = 'Error al guardar las sedes. Verifica los datos.';
                    try {
                        const errorData = await response.json();
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        } else if (errorData.errors) {
                            // Si hay errores de validación, mostrar todos los errores
                            const errorMessages = [];
                            for (const [field, messages] of Object.entries(errorData.errors)) {
                                if (Array.isArray(messages) && messages.length > 0) {
                                    errorMessages.push(...messages);
                                }
                            }
                            if (errorMessages.length > 0) {
                                errorMessage = errorMessages.join('. ');
                            }
                        }
                    } catch (e) {
                        // Si no es JSON, leer como texto
                        const errorText = await response.text();
                        console.error('Error response:', errorText);
                    }
                    showNotify('error', errorMessage);
                }
            } catch (error) {
                console.error('Error guardando sedes:', error);
                showNotify('error', 'Error al guardar sedes: ' + error.message);
            }
        },


        initDatePicker(inputElement) {
            // Esperar a que Alpine.js termine de inicializar y que flatpickr esté disponible
            this.$nextTick(() => {
                // Esperar un poco más para asegurar que flatpickr esté cargado
                setTimeout(() => {
                    if (inputElement && typeof flatpickr !== 'undefined') {
                        // Verificar si ya está inicializado
                        if (inputElement._flatpickr) {
                            inputElement._flatpickr.destroy();
                        }
                        
                        // Obtener el valor actual del input (ya en formato dd/mm/aaaa)
                        const valorActual = this.formData.fecha_inicio;
                        
                        // Inicializar flatpickr con locale español
                        const fp = flatpickr(inputElement, {
                            dateFormat: 'd/m/Y',
                            defaultDate: valorActual || null,
                            locale: 'es',
                            onChange: (selectedDates, dateStr) => {
                                // Mantener el formato dd/mm/aaaa en formData para mostrar
                                this.formData.fecha_inicio = dateStr || '';
                            }
                        });
                        
                        // Si hay un valor inicial, establecerlo
                        if (valorActual) {
                            fp.setDate(valorActual, false);
                        }
                    }
                }, 100);
            });
        },

        abrirModalOrden() {
            abrirModalOrdenCarreras();
        },

        abrirModalAnio() {
            if (!this.carreraId) {
                showNotify('error', 'Primero debes guardar la información básica de la carrera');
                return;
            }
            openModalAnio(this.carreraId);
        },

        editarAnio(anioId) {
            editAnio(anioId);
        },

        eliminarAnio(id) {
            showConfirm('Eliminar Año', '¿Estás seguro de eliminar este año? Esta acción también eliminará todas sus unidades y no se puede deshacer.', () => {
                deleteAnio(id);
            });
        },

        abrirModalUnidad(anioId) {
            openModalUnidad(anioId);
        },

        editarUnidad(unidadId) {
            editUnidad(unidadId);
        },
        
        eliminarUnidad(unidadId) {
            showConfirm('Eliminar Unidad', '¿Estás seguro de eliminar esta unidad? Esta acción no se puede deshacer.', () => {
                deleteUnidad(unidadId);
            });
        },
        

        abrirModalModalidad() {
            if (!this.carreraId) {
                showNotify('error', 'Primero debes guardar la información básica de la carrera');
                return;
            }
            openModalidadCreate(this.carreraId);
        },

        editarModalidad(modalidadId) {
            editModalidad(modalidadId);
        },

        toggleModalidadActivo(modalidadId, currentActivo) {
            fetch(window.routes.admin.modalidades.toggleActivo.replace(':id', modalidadId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el estado en el array de modalidades
                    const modalidad = this.formData.modalidades.find(m => m.id == modalidadId);
                    if (modalidad) {
                        modalidad.activo = data.activo;
                    }
                    showNotify('success', data.message);
                } else {
                    showNotify('error', 'Error al cambiar el estado de la modalidad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotify('error', 'Error al cambiar el estado de la modalidad');
            });
        },

        eliminarModalidad(id) {
            // Usar el modal del layout (claro) en lugar del modal oscuro
            if (typeof window.showConfirmModal === 'function') {
                window.showConfirmModal('Eliminar Modalidad', '¿Estás seguro de eliminar esta modalidad? Esta acción también eliminará todas sus columnas, tipos y horarios, y no se puede deshacer.', () => {
                    deleteModalidadFromTest(id);
                });
            } else {
                // Fallback al modal oscuro si no existe el del layout
                showConfirm('Eliminar Modalidad', '¿Estás seguro de eliminar esta modalidad? Esta acción también eliminará todas sus columnas, tipos y horarios, y no se puede deshacer.', () => {
                    deleteModalidadFromTest(id);
                });
            }
        },

        eliminarCarrera() {
            if (!this.carreraId) {
                showNotify('error', 'No hay carrera seleccionada para eliminar');
                return;
            }
            
            const nombreCarrera = this.formData.nombre || 'esta carrera';
            showConfirm(
                'Eliminar Carrera', 
                `¿Estás seguro de eliminar "${nombreCarrera}"? Esta acción eliminará permanentemente la carrera, sus años, unidades, modalidades y todas sus relaciones. Esta acción no se puede deshacer.`, 
                () => {
                    this.confirmarEliminacionCarrera();
                }
            );
        },

        async confirmarEliminacionCarrera() {
            try {
                const response = await fetch(window.routes.admin.carreras.destroy.replace(':id', this.carreraId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                if (response.ok || response.redirected) {
                    showNotify('success', 'Carrera eliminada correctamente');
                    // Redirigir a la vista test sin carrera seleccionada
                    setTimeout(() => {
                        window.location.href = window.routes.admin.carreras.test;
                    }, 1000);
                } else {
                    const errorData = await response.json().catch(() => ({ message: 'Error desconocido' }));
                    showNotify('error', 'Error al eliminar la carrera: ' + (errorData.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error al eliminar carrera:', error);
                showNotify('error', 'Error al eliminar la carrera: ' + error.message);
            }
        }
    }
}

// ========== GESTIÓN DE MODALIDADES - GRILLA DE TIPOS ==========

function modalidadManager(modalidad) {
    return {
        modalidadId: modalidad.id,
        // Columnas fijas para todas las modalidades (6 columnas)
        columnasFijas: [
            { nombre: 'Duración', campo_dato: 'duracion' },
            { nombre: 'Dedicación', campo_dato: 'dedicacion' },
            { nombre: 'Clases', campo_dato: 'clases_semana' },
            { nombre: 'Teoría y Práctica', campo_dato: 'teoria_practica' },
            { nombre: 'Teoría', campo_dato: 'horas_teoria' },
            { nombre: 'Práctica', campo_dato: 'horas_practica' },
            { nombre: 'Mes de Inicio', campo_dato: 'mes_inicio' }
        ],
        // Por defecto, no seleccionar ninguna columna (vacío)
        columnasSeleccionadas: [],
        tipos: modalidad.tipos ? [...modalidad.tipos] : [],
        draggedIndex: null,
        draggedCampoDato: null,
        horarios: [],
        guardarHorariosDebounce: null,
        
        init() {
            // Inicializar tipos vacíos si no hay
            if (this.tipos.length === 0) {
                this.tipos = [];
            }
            
            // Asegurar que todos los tipos tengan un _tempId para mantener el key estable
            this.tipos.forEach((tipo, index) => {
                if (!tipo._tempId && !tipo.id) {
                    tipo._tempId = `temp-${Date.now()}-${index}-${Math.random().toString(36).substr(2, 9)}`;
                }
            });
            
            // Si hay columnas existentes en la modalidad, usar esas como seleccionadas
            // Filtrar horas_presenciales y horas_virtuales ya que no son columnas visibles
            if (modalidad.columnas_visibles && modalidad.columnas_visibles.length > 0) {
                this.columnasSeleccionadas = modalidad.columnas_visibles
                    .map(c => c.campo_dato || c.campo || c.field)
                    .filter(campo => campo !== 'horas_presenciales' && campo !== 'horas_virtuales');
            } else {
                // Si no hay columnas_visibles, dejar vacío (sin columnas seleccionadas)
                this.columnasSeleccionadas = [];
            }
            
            // Inicializar horarios
            this.inicializarHorarios(modalidad.horarios_visibles);
        },
        
        inicializarHorarios(horariosVisibles) {
            // Horarios fijos con sus iconos y labels
            const horariosFijos = [
                { nombre: 'Mañana', icono: '/images/desktop/morning.png', orden: 1 },
                { nombre: 'Tarde', icono: '/images/desktop/sun.png', orden: 2 },
                { nombre: 'Noche', icono: '/images/desktop/night.png', orden: 3 }
            ];
            
            // Si hay horarios guardados, usarlos; sino, crear los fijos
            if (horariosVisibles && Array.isArray(horariosVisibles) && horariosVisibles.length > 0) {
                // Mapear los horarios guardados a los fijos
                this.horarios = horariosFijos.map(horarioFijo => {
                    const horarioGuardado = horariosVisibles.find(h => 
                        h.nombre && h.nombre.toLowerCase() === horarioFijo.nombre.toLowerCase()
                    );
                    
                    if (horarioGuardado) {
                        // Usar SOLO el campo horas directamente, sin construir nada
                        return {
                            ...horarioFijo,
                            horas: horarioGuardado.horas || ''
                        };
                    } else {
                        return {
                            ...horarioFijo,
                            horas: ''
                        };
                    }
                });
            } else {
                // Si no hay horarios guardados, inicializar con los fijos vacíos
                this.horarios = horariosFijos.map(h => ({
                    ...h,
                    horas: '',
                    hora_inicio: '',
                    hora_fin: ''
                }));
            }
        },
        
        guardarHorarios() {
            // Limpiar timeout anterior si existe
            if (this.guardarHorariosDebounce) {
                clearTimeout(this.guardarHorariosDebounce);
            }
            
            // Usar debounce para no guardar en cada blur
            this.guardarHorariosDebounce = setTimeout(async () => {
                await this.guardarHorariosReal();
                this.guardarHorariosDebounce = null;
            }, 500);
        },
        
        async guardarHorariosReal() {
            // Guardar el string completo de horas tal como está, sin parsear
            // SOLO guardar horarios que tengan horas no vacías
            const horariosParaGuardar = this.horarios
                .filter(horario => horario.horas && horario.horas.trim() !== '')
                .map(horario => {
                    return {
                        nombre: horario.nombre,
                        horas: horario.horas, // Guardar el string completo
                        icono: horario.icono,
                        orden: horario.orden
                    };
                });
            
            // Guardar en la base de datos
            const formData = new FormData();
            formData.append('horarios_visibles', JSON.stringify(horariosParaGuardar));
            formData.append('_method', 'PUT');
            formData.append('from_test', '1');
            
            try {
                const response = await fetch(window.routes.admin.modalidades.update.replace(':id', this.modalidadId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const result = await response.json();
                    // Los horarios ya están actualizados localmente, no necesitamos actualizar nada más
                    
                    // Actualizar también en el componente padre
                    const carreraManager = this.$root;
                    if (carreraManager && carreraManager.formData && carreraManager.formData.modalidades) {
                        const modalidadIndex = carreraManager.formData.modalidades.findIndex(m => m.id === this.modalidadId);
                        if (modalidadIndex !== -1) {
                            carreraManager.formData.modalidades[modalidadIndex].horarios_visibles = horariosParaGuardar;
                        }
                    }
                } else {
                    // Intentar obtener el mensaje de error
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    try {
                        const errorData = JSON.parse(errorText);
                        showNotify('error', errorData.message || 'Error al guardar los horarios');
                    } catch (e) {
                        showNotify('error', 'Error al guardar los horarios');
                    }
                }
            } catch (error) {
                console.error('Error guardando horarios:', error);
                showNotify('error', 'Error al guardar los horarios: ' + error.message);
            }
        },
        
        // Computed: mostrar primero las columnas seleccionadas en su orden, luego las no seleccionadas
        get columnasOrdenadas() {
            // Primero, obtener las columnas seleccionadas en el orden de columnasSeleccionadas
            const seleccionadas = this.columnasSeleccionadas
                .map(campoDato => this.columnasFijas.find(c => c.campo_dato === campoDato))
                .filter(c => c !== undefined);
            
            // Luego, obtener las columnas no seleccionadas en el orden original
            const noSeleccionadas = this.columnasFijas.filter(
                c => !this.columnasSeleccionadas.includes(c.campo_dato)
            );
            
            // Combinar: primero las seleccionadas (en su orden), luego las no seleccionadas
            return [...seleccionadas, ...noSeleccionadas];
        },
        
        getIconoPorDefecto(campoDato) {
            const iconos = {
                'duracion': '/images/desktop/clock.png',
                'dedicacion': '/images/desktop/gear.png',
                'clases_semana': '/images/desktop/student.png',
                'teoria_practica': '/images/desktop/wrench.png',
                'horas_virtuales': '/images/desktop/video.png',
                'horas_teoria': '/images/desktop/video.png',
                'horas_practica': '/images/desktop/wrench.png',
                'mes_inicio': '/images/desktop/calendar.png'
            };
            return iconos[campoDato] || '/images/desktop/gear.png';
        },
        
        // Validar selección de columnas: Teoría y Práctica es mutuamente excluyente con Teoría o Práctica
        validarSeleccionColumnas(campoDato, estaSeleccionado) {
            // Si se está seleccionando "Teoría y Práctica"
            if (campoDato === 'teoria_practica' && estaSeleccionado) {
                // Deseleccionar "Teoría" y "Práctica" si están seleccionadas
                this.columnasSeleccionadas = this.columnasSeleccionadas.filter(
                    c => c !== 'horas_teoria' && c !== 'horas_practica'
                );
            }
            // Si se está seleccionando "Teoría" o "Práctica"
            if ((campoDato === 'horas_teoria' || campoDato === 'horas_practica') && estaSeleccionado) {
                // Deseleccionar "Teoría y Práctica" si está seleccionada
                this.columnasSeleccionadas = this.columnasSeleccionadas.filter(
                    c => c !== 'teoria_practica'
                );
            }
            
            // Validar máximo de 6 columnas
            if (this.columnasSeleccionadas.length > 6) {
                showNotify('warning', 'Máximo 6 columnas permitidas');
                // Remover la última columna agregada (la que se acaba de seleccionar)
                const ultimaAgregada = this.columnasSeleccionadas[this.columnasSeleccionadas.length - 1];
                this.columnasSeleccionadas = this.columnasSeleccionadas.filter(c => c !== ultimaAgregada);
                // Forzar actualización del checkbox
                this.$nextTick(() => {
                    const checkbox = document.querySelector(`input[value="${ultimaAgregada}"]`);
                    if (checkbox) checkbox.checked = false;
                });
            }
        },
        
        async sincronizarColumnas() {
            // Construir el array de columnas_visibles desde los checkboxes seleccionados
            // Filtrar horas_presenciales y horas_virtuales ya que no son columnas visibles
            const columnasVisibles = this.columnasSeleccionadas
                .filter(campoDato => campoDato !== 'horas_presenciales' && campoDato !== 'horas_virtuales')
                .map((campoDato, index) => {
                    const columnaInfo = this.columnasFijas.find(c => c.campo_dato === campoDato);
                    return {
                        campo_dato: campoDato,
                        nombre: columnaInfo ? columnaInfo.nombre : campoDato,
                        icono: this.getIconoPorDefecto(campoDato),
                        orden: index + 1
                    };
                });
            
            // Actualizar directamente el campo columnas_visibles de la modalidad
            try {
                const response = await fetch(window.routes.admin.modalidades.update.replace(':id', this.modalidadId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        columnas_visibles: columnasVisibles,
                        from_test: '1'
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    showNotify('error', 'Error al guardar columnas: ' + (data.message || 'Error desconocido'));
                    // Revertir el cambio si falla
                    await this.recargarModalidad();
                    return;
                }
                
                // Actualizar la modalidad en el contexto padre para reflejar los cambios
                this.actualizarModalidadEnPadre(columnasVisibles);
            } catch (error) {
                console.error('Error al guardar columnas:', error);
                showNotify('error', 'Error al guardar columnas: ' + error.message);
                // Revertir el cambio si falla
                await this.recargarModalidad();
            }
        },
        
        actualizarModalidadEnPadre(columnasVisibles) {
            // Actualizar la modalidad en el array formData.modalidades del componente padre
            const carreraManager = this.$root;
            if (carreraManager && carreraManager.formData && carreraManager.formData.modalidades) {
                const modalidadIndex = carreraManager.formData.modalidades.findIndex(m => m.id === this.modalidadId);
                if (modalidadIndex !== -1) {
                    carreraManager.formData.modalidades[modalidadIndex].columnas_visibles = columnasVisibles;
                }
            }
        },
        
        // Funciones para drag and drop de columnas
        getSelectedIndex(campoDato) {
            return this.columnasSeleccionadas.indexOf(campoDato);
        },
        
        dragStart(event, index) {
            if (index === -1) {
                event.preventDefault();
                return; // No arrastrar si no está seleccionado
            }
            this.draggedIndex = index;
            this.draggedCampoDato = this.columnasSeleccionadas[index];
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', index.toString());
        },
        
        dragOver(event, index) {
            // Solo permitir drop sobre columnas seleccionadas
            if (index === -1 || this.draggedIndex === null || this.draggedIndex === index) {
                return;
            }
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            
            // Agregar indicador visual
            const target = event.currentTarget;
            if (!target.classList.contains('border-blue-500')) {
                target.classList.add('border-blue-500', 'bg-blue-900');
            }
        },
        
        dragLeave(event) {
            // Limpiar estilos cuando el mouse sale del elemento
            const target = event.currentTarget;
            target.classList.remove('border-blue-500', 'bg-blue-900');
        },
        
        drop(event, dropIndex) {
            // Solo permitir drop sobre columnas seleccionadas
            if (dropIndex === -1 || this.draggedIndex === null || this.draggedIndex === dropIndex) {
                this.draggedIndex = null;
                this.draggedCampoDato = null;
                return;
            }
            
            event.preventDefault();
            event.stopPropagation();
            
            // Reordenar el array columnasSeleccionadas
            const items = [...this.columnasSeleccionadas];
            const draggedItem = items[this.draggedIndex];
            items.splice(this.draggedIndex, 1);
            items.splice(dropIndex, 0, draggedItem);
            
            // Actualizar el array - esto actualizará automáticamente columnasOrdenadas
            this.columnasSeleccionadas = items;
            
            // Guardar automáticamente el nuevo orden
            this.sincronizarColumnas();
            
            // Limpiar estilos
            const target = event.currentTarget;
            target.classList.remove('border-blue-500', 'bg-blue-900');
            
            this.draggedIndex = null;
            this.draggedCampoDato = null;
        },
        
        dragEnd(event) {
            // Limpiar estilos de todos los elementos
            const grid = event.currentTarget.closest('.grid');
            if (grid) {
                const allItems = grid.querySelectorAll('[draggable="true"]');
                allItems.forEach(el => {
                    el.classList.remove('border-blue-500', 'bg-blue-900');
                });
            }
            
            this.draggedIndex = null;
            this.draggedCampoDato = null;
        },
        
        isDraggingOver(index) {
            return this.draggedIndex !== null && this.draggedIndex !== index && index !== -1;
        },
        
        async recargarModalidad() {
            // Obtener carreraId del contexto padre
            const carreraId = this.$root.carreraId || window.carreraSeleccionadaData?.id;
            
            // Recargar los datos de la carrera completa para actualizar las columnas
            if (carreraId) {
                try {
                    const response = await fetch(`${window.routes.admin.carreras.test}?curso_id=${carreraId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        // Recargar la página para reflejar los cambios
                        window.location.reload();
                    } else {
                        // Si falla, recargar de todas formas
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Error al recargar:', error);
                    // Recargar de todas formas
                    window.location.reload();
                }
            } else {
                window.location.reload();
            }
        },
        
        getNombreColumna(campoDato) {
            const columna = this.columnasFijas.find(c => c.campo_dato === campoDato);
            return columna ? columna.nombre : campoDato;
        },
        
        agregarTipo() {
            // Generar un ID temporal único para mantener el key estable
            const tempId = `temp-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            this.tipos.push({
                _tempId: tempId,
                id: null,
                nombre: '',
                duracion: '',
                dedicacion: '',
                clases_semana: '',
                teoria_practica: '',
                horas_virtuales: '',
                horas_teoria: '',
                horas_practica: '',
                mes_inicio: ''
            });
        },
        
        guardarTipoDebounce: null,
        
        handleTabKey(event, tipo) {
            // No hacer nada aquí, solo permitir que Tab se ejecute normalmente
            // El guardado se hará en focusout con un delay apropiado
        },
        
        async guardarTipo(tipo, event) {
            if (!tipo.nombre || tipo.nombre.trim() === '') {
                return; // No guardar si no tiene nombre
            }
            
            // Si el evento es focusout y el relatedTarget es otro input de la tabla,
            // significa que se está navegando con Tab dentro de la misma fila
            // En este caso, esperar más tiempo para que Tab pueda mover el foco primero
            const isNavigatingToAnotherInput = event && 
                                                event.relatedTarget && 
                                                event.relatedTarget.tagName === 'INPUT' &&
                                                event.relatedTarget.closest('tr') === event.target.closest('tr');
            
            const delay = isNavigatingToAnotherInput ? 1000 : 100;
            
            // Limpiar timeout anterior si existe
            if (this.guardarTipoDebounce) {
                clearTimeout(this.guardarTipoDebounce);
            }
            
            // Usar debounce para no bloquear la navegación por Tab
            this.guardarTipoDebounce = setTimeout(async () => {
                await this.guardarTipoReal(tipo);
                this.guardarTipoDebounce = null;
            }, delay);
        },
        
        async guardarTipoReal(tipo) {
            const formData = new FormData();
            formData.append('modalidad_id', this.modalidadId);
            formData.append('nombre', tipo.nombre);
            formData.append('from_test', '1'); // Indicar que viene de la vista test
            
            // Agregar todos los campos (pueden estar vacíos)
            formData.append('duracion', tipo.duracion || '');
            formData.append('dedicacion', tipo.dedicacion || '');
            formData.append('clases_semana', tipo.clases_semana || '');
            formData.append('teoria_practica', tipo.teoria_practica || '');
            formData.append('horas_virtuales', tipo.horas_virtuales || '');
            formData.append('horas_teoria', tipo.horas_teoria || '');
            formData.append('horas_practica', tipo.horas_practica || '');
            formData.append('mes_inicio', tipo.mes_inicio || '');
            
            let url, method;
            if (tipo.id) {
                // Actualizar
                url = window.routes.admin.modalidades.tipos.update.replace(':id', tipo.id);
                formData.append('_method', 'PUT');
                method = 'POST';
            } else {
                // Crear
                url = window.routes.admin.modalidades.tipos.store;
                method = 'POST';
            }
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const result = await response.json();
                    if (result.success && result.id) {
                        // Actualizar el ID, pero mantener el _tempId para que el key no cambie
                        // Esto evita que Alpine.js re-renderice la fila y se pierda el foco
                        tipo.id = result.id;
                        // Mantener _tempId para que el key siga siendo estable
                        // El key usa: tipo._tempId || tipo.id || `temp-${tIndex}`
                        // Así que si tiene _tempId, ese será el key y no cambiará
                        
                        // Asegurar que la pestaña de modalidades se mantenga activa
                        if (this.$root) {
                            this.$root.tabActiva = 'modalidades';
                        }
                        // NO mostrar toast de confirmación, guardar silenciosamente
                        // NO recargar la página, solo actualizar el ID del tipo
                    } else {
                        showNotify('error', 'Error al guardar el tipo');
                    }
                } else {
                    // Intentar parsear como JSON primero
                    try {
                        const errorData = await response.json();
                        showNotify('error', errorData.message || 'Error al guardar el tipo');
                    } catch (e) {
                        const errorText = await response.text();
                        console.error('Error response:', errorText);
                        showNotify('error', 'Error al guardar el tipo');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showNotify('error', 'Error al guardar el tipo');
            }
        },
        
        // Método real que hace el guardado
        
        async eliminarTipo(tipoId, index) {
            if (!tipoId) {
                // Si no tiene ID, solo eliminar de la lista
                this.tipos.splice(index, 1);
                return;
            }
            
            showConfirm('Eliminar Tipo', '¿Estás seguro de eliminar este tipo?', () => {
                fetch(window.routes.admin.modalidades.tipos.destroy.replace(':id', tipoId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => {
                    if (response.ok || response.redirected) {
                        this.tipos.splice(index, 1);
                        showNotify('success', 'Tipo eliminado');
                    } else {
                        showNotify('error', 'Error al eliminar el tipo');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotify('error', 'Error al eliminar el tipo');
                });
            });
        }
    }
}

// Hacer funciones disponibles globalmente
window.showNotify = showNotify;
window.showConfirm = showConfirm;
window.closeConfirmModal = closeConfirmModal;
window.executeConfirm = executeConfirm;
window.openModalAnio = openModalAnio;
window.editAnio = editAnio;
window.closeAnioModal = closeAnioModal;
window.handleAnioFormSubmit = handleAnioFormSubmit;
window.deleteAnio = deleteAnio;
window.openModalUnidad = openModalUnidad;
window.editUnidad = editUnidad;
window.closeUnidadModal = closeUnidadModal;
window.handleUnidadFormSubmit = handleUnidadFormSubmit;
window.deleteUnidad = deleteUnidad;
window.openModalidadCreate = openModalidadCreate;
window.editModalidad = editModalidad;
window.closeModalidadModal = closeModalidadModal;
window.handleModalidadFormSubmit = handleModalidadFormSubmit;
window.deleteModalidadFromTest = deleteModalidadFromTest;

// Función para toggle activo/inactivo de modalidad
function toggleModalidadActivo(modalidadId, currentActivo) {
    fetch(window.routes.admin.modalidades.toggleActivo.replace(':id', modalidadId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar el estado en el componente Alpine
            const carreraManager = document.querySelector('[x-data]')?._x_dataStack?.[0];
            if (carreraManager && carreraManager.formData && carreraManager.formData.modalidades) {
                const modalidad = carreraManager.formData.modalidades.find(m => m.id == modalidadId);
                if (modalidad) {
                    modalidad.activo = data.activo;
                }
            }
            showNotify('success', data.message);
        } else {
            showNotify('error', 'Error al cambiar el estado de la modalidad');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al cambiar el estado de la modalidad');
    });
}

// ========== GESTIÓN DE ORDEN DE CARRERAS ==========

let carrerasOrdenadas = [];

function abrirModalOrdenCarreras() {
    const modal = document.getElementById('modalOrdenCarreras');
    const lista = document.getElementById('listaCarrerasOrden');
    
    // Obtener todas las carreras ordenadas desde el selector del formulario
    const select = document.querySelector('select[name="curso_id"]');
    
    if (!select) {
        showNotify('error', 'No se pudieron cargar las carreras');
        return;
    }
    
    // Obtener las carreras del selector (ya vienen ordenadas por orden)
    const carrerasDelSelector = Array.from(select.options)
        .filter(option => option.value !== '')
        .map((option, index) => ({
            id: option.value,
            nombre: option.textContent.trim(),
            orden: index + 1 // El selector ya muestra las carreras en orden
        }));
    
    // Hacer fetch para obtener el orden real de cada carrera desde el backend
    // Necesitamos obtener los datos completos de las carreras para tener el orden correcto
    fetch(window.routes.admin.carreras.test, {
        headers: {
            'Accept': 'text/html',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parsear el HTML para extraer las carreras del selector (que ya vienen ordenadas)
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const selectFromHtml = doc.querySelector('select[name="curso_id"]');
        
        if (selectFromHtml) {
            carrerasOrdenadas = Array.from(selectFromHtml.options)
                .filter(option => option.value !== '')
                .map((option, index) => ({
                    id: option.value,
                    nombre: option.textContent.trim(),
                    orden: index + 1
                }));
        } else {
            // Fallback: usar las carreras del selector actual
            carrerasOrdenadas = carrerasDelSelector;
        }
        
        renderizarListaCarreras();
        modal.classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error al cargar carreras:', error);
        // Fallback: usar las carreras del selector actual
        carrerasOrdenadas = carrerasDelSelector;
        renderizarListaCarreras();
        modal.classList.remove('hidden');
    });
}

function actualizarOrdenEnFormulario() {
    // Obtener el orden actual desde el selector de la página
    const select = document.querySelector('select[name="curso_id"]');
    if (!select) return;
    
    // Obtener el ID de la carrera seleccionada
    const carreraIdSeleccionada = select.value;
    if (!carreraIdSeleccionada) return;
    
    // Calcular el orden basado en la posición en el selector
    const carrerasActualizadas = Array.from(select.options)
        .filter(option => option.value !== '')
        .map((option, index) => ({
            id: option.value,
            nombre: option.textContent.trim(),
            orden: index + 1
        }));
    
    const carreraActual = carrerasActualizadas.find(c => c.id == carreraIdSeleccionada);
    if (!carreraActual) return;
    
    // Buscar el componente Alpine.js carreraManager
    const carreraManagerElement = document.querySelector('[x-data*="carreraManager"]');
    if (!carreraManagerElement) return;
    
    // Intentar diferentes formas de acceder al componente Alpine.js
    let carreraManager = null;
    if (carreraManagerElement.__x) {
        carreraManager = carreraManagerElement.__x.$data;
    } else if (carreraManagerElement._x_dataStack && carreraManagerElement._x_dataStack[0]) {
        carreraManager = carreraManagerElement._x_dataStack[0];
    }
    
    if (!carreraManager || carreraManager.carreraId != carreraIdSeleccionada) return;
    
    // Actualizar el orden en el componente Alpine.js
    carreraManager.formData.orden = carreraActual.orden;
    
    // También actualizar directamente el input si existe
    const inputOrden = document.querySelector('input[x-model="formData.orden"]');
    if (inputOrden) {
        inputOrden.value = carreraActual.orden;
        // Disparar evento para que Alpine.js detecte el cambio
        inputOrden.dispatchEvent(new Event('input', { bubbles: true }));
    }
}

function cerrarModalOrden() {
    const modal = document.getElementById('modalOrdenCarreras');
    modal.classList.add('hidden');
    
    // Actualizar el campo orden en el formulario
    actualizarOrdenEnFormulario();
}

function renderizarListaCarreras() {
    const lista = document.getElementById('listaCarrerasOrden');
    lista.innerHTML = '';
    
    carrerasOrdenadas.forEach((carrera, index) => {
        const item = document.createElement('div');
        item.className = 'flex items-center justify-between p-3 bg-gray-700 rounded-md hover:bg-gray-600 transition-colors';
        item.innerHTML = `
            <div class="flex items-center space-x-3 flex-1">
                <span class="text-sm font-semibold text-gray-400 w-8 text-center">${carrera.orden}</span>
                <span class="text-white flex-1">${carrera.nombre}</span>
            </div>
            <div class="flex flex-col space-y-1 ml-4">
                <button 
                    onclick="moverCarreraOrden(${carrera.id}, 'up')"
                    class="mover-orden-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                    ${index === 0 ? 'disabled' : ''}
                    title="Mover arriba">
                    ↑
                </button>
                <button 
                    onclick="moverCarreraOrden(${carrera.id}, 'down')"
                    class="mover-orden-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                    ${index === carrerasOrdenadas.length - 1 ? 'disabled' : ''}
                    title="Mover abajo">
                    ↓
                </button>
            </div>
        `;
        lista.appendChild(item);
    });
}

function moverCarreraOrden(carreraId, direccion) {
    // Deshabilitar todos los botones temporalmente
    const botones = document.querySelectorAll('.mover-orden-btn');
    botones.forEach(btn => btn.disabled = true);
    
    fetch('/admin/carreras/mover', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            id: carreraId,
            direccion: direccion
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar la lista desde el servidor para obtener el orden actualizado
            // Esto asegura que tenemos el orden correcto después del cambio
            fetch(window.routes.admin.carreras.test, {
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const selectFromHtml = doc.querySelector('select[name="curso_id"]');
                
                if (selectFromHtml) {
                    carrerasOrdenadas = Array.from(selectFromHtml.options)
                        .filter(option => option.value !== '')
                        .map((option, index) => ({
                            id: option.value,
                            nombre: option.textContent.trim(),
                            orden: index + 1
                        }));
                }
                
                // Actualizar el selector principal si existe
                const selectPrincipal = document.querySelector('select[name="curso_id"]');
                if (selectPrincipal) {
                    // Reordenar las opciones del selector según el nuevo orden
                    carrerasOrdenadas.forEach((carrera, index) => {
                        const option = selectPrincipal.querySelector(`option[value="${carrera.id}"]`);
                        if (option && option.parentNode) {
                            // Mover la opción a su nueva posición
                            selectPrincipal.insertBefore(option, selectPrincipal.children[index + 1] || null);
                        }
                    });
                }
                
                // Re-renderizar la lista
                renderizarListaCarreras();
                
                // Actualizar el campo orden en el formulario inmediatamente
                setTimeout(() => {
                    actualizarOrdenEnFormulario();
                }, 100);
            })
            .catch(error => {
                console.error('Error al recargar carreras:', error);
                // Si falla, al menos re-renderizar con los datos actuales
                renderizarListaCarreras();
            });
            
            showNotify('success', 'Orden actualizado correctamente');
        } else {
            showNotify('error', data.message || 'Error al mover la carrera');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotify('error', 'Error al mover la carrera');
    })
    .finally(() => {
        // Rehabilitar botones
        botones.forEach(btn => btn.disabled = false);
    });
}

window.toggleModalidadActivo = toggleModalidadActivo;
window.carreraManager = carreraManager;
window.modalidadManager = modalidadManager;
window.abrirModalOrdenCarreras = abrirModalOrdenCarreras;
window.cerrarModalOrden = cerrarModalOrden;
window.moverCarreraOrden = moverCarreraOrden;

