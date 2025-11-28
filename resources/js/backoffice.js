// Backoffice JavaScript
// Maneja toda la funcionalidad JavaScript del área de administración

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success messages
    initSuccessMessages();
    
    // Initialize other backoffice functionality
    initBackofficeFeatures();
    
// Make confirmDelete globally available
window.confirmDelete = confirmDelete;

/**
 * SweetAlert2 validation for complete course form - EXACT SAME PATTERN as confirmDelete
 */
function validateModalidades(event) {
    event.preventDefault();
    
    // Get the form element
    const form = event.target;
    
    // Check if we're in edit mode by looking for _method input
    const isEditMode = document.querySelector('input[name="_method"]') !== null;
    
    // Check all required fields
    const nombre = document.querySelector('input[name="nombre"]').value.trim();
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value.trim();
    const online = document.querySelector('input[name="modalidad_online"]').checked;
    const presencial = document.querySelector('input[name="modalidad_presencial"]').checked;
    const ilustracionDesktop = document.querySelector('input[name="ilustracion_desktop"]').files.length;
    const ilustracionMobile = document.querySelector('input[name="ilustracion_mobile"]').files.length;
    
    // Check for missing fields
    let errorMessage = '';
    
    if (!nombre) {
        errorMessage = 'El nombre del curso es obligatorio';
    } else if (!fechaInicio) {
        errorMessage = 'La fecha de inicio es obligatoria';
    } else if (!online && !presencial) {
        errorMessage = 'Debes seleccionar al menos una modalidad (Online o Presencial)';
    } else if (!isEditMode && ilustracionDesktop === 0) {
        // Only validate images if we're NOT in edit mode
        errorMessage = 'La ilustración desktop es obligatoria';
    } else if (!isEditMode && ilustracionMobile === 0) {
        // Only validate images if we're NOT in edit mode
        errorMessage = 'La ilustración mobile es obligatoria';
    }
    
    if (errorMessage) {
        // Show popup modal
        showValidationModal("Campos requeridos", errorMessage);
        return false;
    }
    
    // If all fields are OK, submit the form
    form.submit();
    return false;
}

// Make validateModalidades globally available
window.validateModalidades = validateModalidades;
    
    // Initialize file upload functionality
    initFileUploads();
});

/**
 * Initialize toast message animations
 */
function initSuccessMessages() {
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    // Animate success message
    if (successMessage) {
        // Slide in from right
        setTimeout(() => {
            successMessage.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            successMessage.classList.add('translate-x-full');
            setTimeout(function() {
                successMessage.remove();
            }, 300);
        }, 5000);
    }
    
    // Animate error message
    if (errorMessage) {
        // Slide in from right
        setTimeout(() => {
            errorMessage.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            errorMessage.classList.add('translate-x-full');
            setTimeout(function() {
                errorMessage.remove();
            }, 300);
        }, 5000);
    }
}

/**
 * Initialize other backoffice features
 */
function initBackofficeFeatures() {
    // Add any other backoffice-specific JavaScript here
    console.log('Backoffice JavaScript initialized');
    
    // Funcionalidad de sedes - Acordeón (para página pública)
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
}

/**
 * Initialize file upload functionality
 */
function initFileUploads() {
    // Find all file inputs and ensure they submit on change
    // BUT exclude inputs inside modals
    const fileInputs = document.querySelectorAll('input[type="file"]:not([data-no-auto-submit])');
    fileInputs.forEach(input => {
        // Skip if input is inside a modal
        if (input.closest('[id*="modal"]')) {
            return;
        }
        
        input.addEventListener('change', function() {
            if (this.files.length > 0) {
                this.form.submit();
            }
        });
    });
}

/**
 * Utility function to show success messages
 */
function showSuccessMessage(message) {
    // Remove existing success messages
    const existingMessage = document.getElementById('success-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new success message
    const successDiv = document.createElement('div');
    successDiv.id = 'success-message';
    successDiv.className = 'bg-green-500 text-white p-4 rounded mb-6';
    successDiv.textContent = message;
    
    // Insert at the top of the content
    const content = document.querySelector('.p-6');
    if (content) {
        content.insertBefore(successDiv, content.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            successDiv.style.transition = 'opacity 0.5s ease-out';
            successDiv.style.opacity = '0';
            setTimeout(function() {
                successDiv.remove();
            }, 500);
        }, 5000);
    }
}

/**
 * Utility function to show error messages
 */
function showErrorMessage(message) {
    // Remove existing error messages
    const existingMessage = document.getElementById('error-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new error message
    const errorDiv = document.createElement('div');
    errorDiv.id = 'error-message';
    errorDiv.className = 'bg-red-500 text-white p-4 rounded mb-6';
    errorDiv.textContent = message;
    
    // Insert at the top of the content
    const content = document.querySelector('.p-6');
    if (content) {
        content.insertBefore(errorDiv, content.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            errorDiv.style.transition = 'opacity 0.5s ease-out';
            errorDiv.style.opacity = '0';
            setTimeout(function() {
                errorDiv.remove();
            }, 500);
        }, 5000);
    }
}

/**
 * Bootbox confirmation for delete actions
 */
function confirmDelete(event) {
    event.preventDefault();
    
    // Get the form element
    const form = event.target;
    
    // Use Alpine.js modal
    const modal = document.getElementById('confirmation-modal');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        modal._x_dataStack[0].title = '¿Estás seguro?';
        modal._x_dataStack[0].message = 'Esta acción no se puede deshacer';
        modal._x_dataStack[0].onConfirm = () => form.submit();
        modal._x_dataStack[0].open = true;
    } else {
        // Fallback to native confirm
        if (confirm('¿Estás seguro de eliminar este archivo?')) {
            form.submit();
        }
    }
    
    return false;
}

/**
 * Show validation modal using Alpine.js
 */
function showValidationModal(title, message) {
    const modal = document.getElementById('confirmation-modal');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        // First, restore buttons to default state
        const cancelButton = modal.querySelector('button[class*="bg-white"]');
        const confirmButton = modal.querySelector('button[class*="bg-red-600"], button[class*="bg-blue-600"]');
        
        if (cancelButton) {
            cancelButton.style.display = '';
        }
        
        if (confirmButton) {
            confirmButton.textContent = 'Confirmar';
            confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700';
        }
        
        modal._x_dataStack[0].title = title;
        modal._x_dataStack[0].message = message;
        modal._x_dataStack[0].onConfirm = () => {
            modal._x_dataStack[0].open = false;
        };
        modal._x_dataStack[0].open = true;
        
        // Hide cancel button and modify confirm button for validation
        setTimeout(() => {
            const cancelBtn = modal.querySelector('button[class*="bg-white"]');
            const confirmBtn = modal.querySelector('button[class*="bg-red-600"]');
            
            if (cancelBtn) {
                cancelBtn.style.display = 'none';
            }
            
            if (confirmBtn) {
                confirmBtn.textContent = 'Aceptar';
                confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700';
            }
        }, 10);
    } else {
        // Fallback to native alert
        alert(title + ": " + message);
    }
}

// Make showValidationModal globally available
window.showValidationModal = showValidationModal;

/**
 * Show confirmation modal using Alpine.js
 */
function showConfirmModal(title, message, onConfirm) {
    const modal = document.getElementById('confirmation-modal');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        modal._x_dataStack[0].title = title;
        modal._x_dataStack[0].message = message;
        modal._x_dataStack[0].onConfirm = () => {
            if (onConfirm) {
                onConfirm();
            }
            modal._x_dataStack[0].open = false;
        };
        modal._x_dataStack[0].open = true;
    } else {
        // Fallback to native confirm
        if (confirm(message)) {
            if (onConfirm) {
                onConfirm();
            }
        }
    }
}

/**
 * Show alert modal using Alpine.js (similar to validation modal but with OK button)
 */
function showAlertModal(title, message) {
    const modal = document.getElementById('confirmation-modal');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        // First, restore buttons to default state
        const cancelButton = modal.querySelector('button[class*="bg-white"]');
        const confirmButton = modal.querySelector('button[class*="bg-red-600"], button[class*="bg-blue-600"]');
        
        if (cancelButton) {
            cancelButton.style.display = '';
        }
        
        if (confirmButton) {
            confirmButton.textContent = 'Confirmar';
            confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700';
        }
        
        modal._x_dataStack[0].title = title;
        modal._x_dataStack[0].message = message;
        modal._x_dataStack[0].onConfirm = () => {
            modal._x_dataStack[0].open = false;
        };
        modal._x_dataStack[0].open = true;
        
        // Hide cancel button and modify confirm button for alert
        setTimeout(() => {
            const cancelBtn = modal.querySelector('button[class*="bg-white"]');
            const confirmBtn = modal.querySelector('button[class*="bg-red-600"]');
            
            if (cancelBtn) {
                cancelBtn.style.display = 'none';
            }
            
            if (confirmBtn) {
                confirmBtn.textContent = 'Aceptar';
                confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700';
            }
        }, 10);
    } else {
        // Fallback to native alert
        alert(title + ": " + message);
    }
}

// Make functions globally available
window.showConfirmModal = showConfirmModal;
window.showAlertModal = showAlertModal;

// ========================================
// MODALIDADES MANAGEMENT
// ========================================

// ========== FUNCIONES PARA MODALIDADES ==========

function openModalidadCreate(cursoId) {
    document.getElementById('modalidadModalTitle').textContent = 'Nueva Modalidad';
    document.getElementById('modalidadId').value = '';
    document.getElementById('modalidadCursoId').value = cursoId;
    document.getElementById('modalidadForm').action = '/admin/modalidades';
    document.getElementById('modalidadMethodField').value = 'POST';
    document.getElementById('modalidadSubmitBtn').textContent = 'Crear Modalidad';
    
    // Limpiar campos
    document.getElementById('modalidadNombreLinea1').value = '';
    document.getElementById('modalidadNombreLinea2').value = '';
    document.getElementById('modalidadTextoInfo').value = '';
    
    document.getElementById('modalidadModal').classList.remove('hidden');
}

function editModalidad(id) {
    fetch(`/admin/modalidades/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalidadModalTitle').textContent = 'Editar Modalidad';
            document.getElementById('modalidadId').value = data.id;
            document.getElementById('modalidadForm').action = `/admin/modalidades/${data.id}`;
            document.getElementById('modalidadMethodField').value = 'PUT';
            document.getElementById('modalidadSubmitBtn').textContent = 'Actualizar Modalidad';
            
            document.getElementById('modalidadNombreLinea1').value = data.nombre_linea1 || data.nombre || '';
            document.getElementById('modalidadNombreLinea2').value = data.nombre_linea2 || '';
            document.getElementById('modalidadTextoInfo').value = data.texto_info || '';
            
            document.getElementById('modalidadModal').classList.remove('hidden');
        })
                .catch(error => {
                    console.error('Error al cargar los datos de la modalidad:', error);
                    showAlertModal('Error', 'Error al cargar los datos de la modalidad');
                });
}

function deleteModalidad(id) {
    showConfirmModal(
        '¿Estás seguro?',
        '¿Estás seguro de que deseas eliminar esta modalidad? Esta acción también eliminará todas sus columnas, tipos y horarios.',
        () => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/modalidades/${id}`;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfField);
            
            document.body.appendChild(form);
            form.submit();
        }
    );
}

function closeModalidadModal() {
    document.getElementById('modalidadModal').classList.add('hidden');
    document.getElementById('modalidadForm').reset();
}

function handleModalidadFormSubmit(event) {
    return true;
}

// ========== FUNCIONES PARA COLUMNAS ==========

function openModalColumna(modalidadId) {
    resetColumnaModal();
    document.getElementById('columnaModalidadId').value = modalidadId;
    document.getElementById('columnaModalTitle').textContent = 'Agregar Columna';
    document.getElementById('columnaSubmitBtn').textContent = 'Agregar Columna';
    document.getElementById('columnaForm').action = window.modalidadesRoutes?.store || '/admin/modalidades/columnas';
    document.getElementById('columnaMethodField').value = '';
    document.getElementById('columnaModal').classList.remove('hidden');
    
    // Agregar listener para el input file después de un pequeño delay para asegurar que el DOM esté listo
    setTimeout(function() {
        const columnaIconoFile = document.getElementById('columnaIconoFile');
        if (columnaIconoFile) {
            // Remover cualquier listener anterior
            const newInput = columnaIconoFile.cloneNode(true);
            columnaIconoFile.parentNode.replaceChild(newInput, columnaIconoFile);
            
            // Agregar nuevo listener
            document.getElementById('columnaIconoFile').addEventListener('change', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleIconoFileChange(this);
                return false;
            });
        }
    }, 100);
}

function editColumna(id) {
    resetColumnaModal();
    
    fetch(`/admin/modalidades/columnas/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('columnaModalTitle').textContent = 'Editar Columna';
            document.getElementById('columnaSubmitBtn').textContent = 'Actualizar Columna';
            document.getElementById('columnaMethodField').value = 'PUT';
            document.getElementById('columnaId').value = data.id;
            document.getElementById('columnaModalidadId').value = data.modalidad_id;
            document.getElementById('columnaForm').action = `/admin/modalidades/columnas/${data.id}`;
            
            document.getElementById('columnaNombre').value = data.nombre;
            document.getElementById('columnaCampoDato').value = data.campo_dato;
            
            // Mostrar preview del icono actual si existe
            if (data.icono) {
                const previewDiv = document.getElementById('columnaIconoPreview');
                const previewImg = document.getElementById('columnaIconoPreviewImg');
                previewImg.src = data.icono;
                previewDiv.classList.remove('hidden');
            }
            
            document.getElementById('columnaModal').classList.remove('hidden');
            
            // Agregar listener para el input file después de un pequeño delay
            setTimeout(function() {
                const columnaIconoFile = document.getElementById('columnaIconoFile');
                if (columnaIconoFile) {
                    // Remover cualquier listener anterior
                    const newInput = columnaIconoFile.cloneNode(true);
                    columnaIconoFile.parentNode.replaceChild(newInput, columnaIconoFile);
                    
                    // Agregar nuevo listener
                    document.getElementById('columnaIconoFile').addEventListener('change', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleIconoFileChange(this);
                        return false;
                    });
                }
            }, 100);
        })
                .catch(error => {
                    console.error('Error al cargar los datos de la columna:', error);
                    showAlertModal('Error', 'Error al cargar los datos de la columna');
                });
}

function closeColumnaModal() {
    document.getElementById('columnaModal').classList.add('hidden');
    resetColumnaModal();
}

function resetColumnaModal() {
    document.getElementById('columnaForm').reset();
    document.getElementById('columnaId').value = '';
    document.getElementById('columnaModalidadId').value = '';
    document.getElementById('columnaMethodField').value = '';
    const previewDiv = document.getElementById('columnaIconoPreview');
    if (previewDiv) {
        previewDiv.classList.add('hidden');
    }
    const previewImg = document.getElementById('columnaIconoPreviewImg');
    if (previewImg) {
        previewImg.src = '';
    }
}

function handleIconoFileChange(input) {
    const file = input.files[0];
    const previewDiv = document.getElementById('columnaIconoPreview');
    const previewImg = document.getElementById('columnaIconoPreviewImg');
    const iconoInput = document.getElementById('columnaIcono');
    
    if (file) {
        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
        
        // El campo de texto se puede dejar vacío o mostrar una nota
        // La ruta real se generará en el servidor
        if (iconoInput) {
            iconoInput.value = '';
        }
    } else {
        if (previewDiv) {
            previewDiv.classList.add('hidden');
        }
    }
}

function handleColumnaFormSubmit(event) {
    const nombre = document.getElementById('columnaNombre').value;
    const iconoFile = document.getElementById('columnaIconoFile').files[0];
    const campoDato = document.getElementById('columnaCampoDato').value;
    
    if (!nombre || !iconoFile || !campoDato) {
        showAlertModal('Campos requeridos', 'Por favor completa todos los campos obligatorios, incluyendo el icono');
        return false;
    }
    
    return true;
}

// ========== FUNCIONES PARA TIPOS ==========

function openModalTipo(modalidadId) {
    resetTipoModal();
    document.getElementById('tipoModalidadId').value = modalidadId;
    document.getElementById('tipoModalTitle').textContent = 'Agregar Tipo';
    document.getElementById('tipoSubmitBtn').textContent = 'Agregar Tipo';
    document.getElementById('tipoForm').action = window.modalidadesRoutes?.tiposStore || '/admin/modalidades/tipos';
    document.getElementById('tipoMethodField').value = '';
    document.getElementById('tipoModal').classList.remove('hidden');
}

function editTipo(id) {
    resetTipoModal();
    
    fetch(`/admin/modalidades/tipos/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('tipoModalTitle').textContent = 'Editar Tipo';
            document.getElementById('tipoSubmitBtn').textContent = 'Actualizar Tipo';
            document.getElementById('tipoMethodField').value = 'PUT';
            document.getElementById('tipoId').value = data.id;
            document.getElementById('tipoModalidadId').value = data.modalidad_id;
            document.getElementById('tipoForm').action = `/admin/modalidades/tipos/${data.id}`;
            
            document.getElementById('tipoNombre').value = data.nombre;
            document.getElementById('tipoDuracion').value = data.duracion;
            document.getElementById('tipoDedicacion').value = data.dedicacion;
            document.getElementById('tipoClasesSemana').value = data.clases_semana;
            document.getElementById('tipoHorasPresenciales').value = data.horas_presenciales || '';
            document.getElementById('tipoHorasVirtuales').value = data.horas_virtuales || '';
            document.getElementById('tipoHorasTeoria').value = data.horas_teoria || '';
            document.getElementById('tipoHorasPractica').value = data.horas_practica || '';
            document.getElementById('tipoMesInicio').value = data.mes_inicio;
            
            document.getElementById('tipoModal').classList.remove('hidden');
        })
                .catch(error => {
                    console.error('Error al cargar los datos del tipo:', error);
                    showAlertModal('Error', 'Error al cargar los datos del tipo');
                });
}

function closeTipoModal() {
    document.getElementById('tipoModal').classList.add('hidden');
    resetTipoModal();
}

function resetTipoModal() {
    document.getElementById('tipoForm').reset();
    document.getElementById('tipoId').value = '';
    document.getElementById('tipoModalidadId').value = '';
    document.getElementById('tipoMethodField').value = '';
}

function handleTipoFormSubmit(event) {
    const nombre = document.getElementById('tipoNombre').value;
    const duracion = document.getElementById('tipoDuracion').value;
    const dedicacion = document.getElementById('tipoDedicacion').value;
    const clasesSemana = document.getElementById('tipoClasesSemana').value;
    const mesInicio = document.getElementById('tipoMesInicio').value;
    
    if (!nombre || !duracion || !dedicacion || !clasesSemana || !mesInicio) {
        showAlertModal('Campos requeridos', 'Por favor completa todos los campos obligatorios');
        return false;
    }
    
    return true;
}

// ========== FUNCIONES PARA HORARIOS ==========

function openModalHorario(modalidadId) {
    resetHorarioModal();
    document.getElementById('horarioModalidadId').value = modalidadId;
    document.getElementById('horarioModalTitle').textContent = 'Agregar Horario';
    document.getElementById('horarioSubmitBtn').textContent = 'Agregar Horario';
    document.getElementById('horarioForm').action = window.modalidadesRoutes?.horariosStore || '/admin/modalidades/horarios';
    document.getElementById('horarioMethodField').value = '';
    document.getElementById('horarioModal').classList.remove('hidden');
    
    // Agregar listener para el input file después de un pequeño delay
    setTimeout(function() {
        const horarioIconoFile = document.getElementById('horarioIconoFile');
        if (horarioIconoFile) {
            // Remover cualquier listener anterior
            const newInput = horarioIconoFile.cloneNode(true);
            horarioIconoFile.parentNode.replaceChild(newInput, horarioIconoFile);
            
            // Agregar nuevo listener
            document.getElementById('horarioIconoFile').addEventListener('change', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleHorarioIconoFileChange(this);
                return false;
            });
        }
    }, 100);
}

function editHorario(id) {
    resetHorarioModal();
    
    fetch(`/admin/modalidades/horarios/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('horarioModalTitle').textContent = 'Editar Horario';
            document.getElementById('horarioSubmitBtn').textContent = 'Actualizar Horario';
            document.getElementById('horarioMethodField').value = 'PUT';
            document.getElementById('horarioId').value = data.id;
            document.getElementById('horarioModalidadId').value = data.modalidad_id;
            document.getElementById('horarioForm').action = `/admin/modalidades/horarios/${data.id}`;
            
            document.getElementById('horarioNombre').value = data.nombre;
            document.getElementById('horarioHoraInicio').value = data.hora_inicio;
            document.getElementById('horarioHoraFin').value = data.hora_fin;
            
            // Mostrar preview del icono actual si existe
            if (data.icono) {
                const previewDiv = document.getElementById('horarioIconoPreview');
                const previewImg = document.getElementById('horarioIconoPreviewImg');
                previewImg.src = data.icono;
                previewDiv.classList.remove('hidden');
            }
            
            document.getElementById('horarioModal').classList.remove('hidden');
            
            // Agregar listener para el input file después de un pequeño delay
            setTimeout(function() {
                const horarioIconoFile = document.getElementById('horarioIconoFile');
                if (horarioIconoFile) {
                    // Remover cualquier listener anterior
                    const newInput = horarioIconoFile.cloneNode(true);
                    horarioIconoFile.parentNode.replaceChild(newInput, horarioIconoFile);
                    
                    // Agregar nuevo listener
                    document.getElementById('horarioIconoFile').addEventListener('change', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleHorarioIconoFileChange(this);
                        return false;
                    });
                }
            }, 100);
        })
                .catch(error => {
                    console.error('Error al cargar los datos del horario:', error);
                    showAlertModal('Error', 'Error al cargar los datos del horario');
                });
}

function closeHorarioModal() {
    document.getElementById('horarioModal').classList.add('hidden');
    resetHorarioModal();
}

function resetHorarioModal() {
    document.getElementById('horarioForm').reset();
    document.getElementById('horarioId').value = '';
    document.getElementById('horarioModalidadId').value = '';
    document.getElementById('horarioMethodField').value = '';
    const previewDiv = document.getElementById('horarioIconoPreview');
    if (previewDiv) {
        previewDiv.classList.add('hidden');
    }
    const previewImg = document.getElementById('horarioIconoPreviewImg');
    if (previewImg) {
        previewImg.src = '';
    }
}

function handleHorarioIconoFileChange(input) {
    const file = input.files[0];
    const previewDiv = document.getElementById('horarioIconoPreview');
    const previewImg = document.getElementById('horarioIconoPreviewImg');
    
    if (file) {
        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        if (previewDiv) {
            previewDiv.classList.add('hidden');
        }
    }
}

function handleHorarioFormSubmit(event) {
    const nombre = document.getElementById('horarioNombre').value;
    const horaInicio = document.getElementById('horarioHoraInicio').value;
    const horaFin = document.getElementById('horarioHoraFin').value;
    
    if (!nombre || !horaInicio || !horaFin) {
        showAlertModal('Campos requeridos', 'Por favor completa todos los campos obligatorios');
        return false;
    }
    
    return true;
}

// ========== TOGGLE ACTIVO/INACTIVO ==========

function toggleModalidadActivo(modalidadId, currentActivo) {
    fetch(`/admin/modalidades/${modalidadId}/toggle-activo`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`modalidad-badge-${modalidadId}`);
            if (badge) {
                if (data.activo) {
                    badge.textContent = 'Activa';
                    badge.classList.remove('bg-red-600');
                    badge.classList.add('bg-green-600');
                } else {
                    badge.textContent = 'Inactiva';
                    badge.classList.remove('bg-green-600');
                    badge.classList.add('bg-red-600');
                }
            }
        } else {
            showAlertModal('Error', 'Error al cambiar el estado de la modalidad');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlertModal('Error', 'Error al cambiar el estado de la modalidad');
    });
}

function toggleTipoActivo(tipoId, currentActivo) {
    fetch(`/admin/modalidades/tipos/${tipoId}/toggle-activo`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`tipo-badge-${tipoId}`);
            if (badge) {
                if (data.activo) {
                    badge.textContent = 'Activo';
                    badge.classList.remove('bg-red-600');
                    badge.classList.add('bg-green-600');
                } else {
                    badge.textContent = 'Inactivo';
                    badge.classList.remove('bg-green-600');
                    badge.classList.add('bg-red-600');
                }
            }
        } else {
            showAlertModal('Error', 'Error al cambiar el estado del tipo');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlertModal('Error', 'Error al cambiar el estado del tipo');
    });
}

// ========== SORTABLE COLUMNAS ==========

// Inicializar drag and drop para columnas
function initSortableColumnas() {
    if (typeof Sortable === 'undefined') {
        return;
    }
    
    const containers = document.querySelectorAll('[id^="columnas-container-"]');
    
    containers.forEach(container => {
        // Evitar inicializar múltiples veces
        if (container.sortableInstance) {
            return;
        }
        
        const modalidadId = container.id.replace('columnas-container-', '');
        
        container.sortableInstance = new Sortable(container, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-drag',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Obtener todos los IDs de las columnas en el nuevo orden
                const columnas = Array.from(container.querySelectorAll('.columna-item'));
                const columnasIds = columnas.map(item => item.getAttribute('data-columna-id'));
                
                // Enviar el nuevo orden al servidor
                updateColumnasOrden(modalidadId, columnasIds);
            }
        });
    });
}

// Actualizar el orden de las columnas
function updateColumnasOrden(modalidadId, columnasIds) {
    fetch('/admin/modalidades/columnas/reordenar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            modalidad_id: modalidadId,
            columnas_ids: columnasIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito (opcional)
            console.log('Orden actualizado correctamente');
        } else {
            console.error('Error al actualizar el orden:', data.message);
            // Recargar para restaurar el orden anterior
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Recargar para restaurar el orden anterior
        location.reload();
    });
}

// Inicializar cuando el DOM esté listo (solo en página de modalidades)
document.addEventListener('DOMContentLoaded', function() {
    // Toast messages auto-hide (solo si existen)
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.classList.remove('translate-x-full');
            setTimeout(() => {
                successMessage.classList.add('translate-x-full');
            }, 3000);
        }, 100);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.classList.remove('translate-x-full');
            setTimeout(() => {
                errorMessage.classList.add('translate-x-full');
            }, 3000);
        }, 100);
    }

    // Inicializar SortableJS para las columnas (solo si estamos en la página de modalidades)
    if (document.querySelector('[id^="columnas-container-"]')) {
        initSortableColumnas();
        
        // Observar cambios en el DOM para reinicializar cuando se expandan modalidades
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'x-show') {
                    // Esperar un momento para que el DOM se actualice
                    setTimeout(() => {
                        initSortableColumnas();
                    }, 100);
                }
            });
        });
        
        // Observar todos los elementos con x-show
        document.querySelectorAll('[x-show]').forEach(el => {
            observer.observe(el, { attributes: true });
        });
    }
});

// Funciones de confirmación para formularios
function confirmDeleteColumna(event) {
    event.preventDefault();
    const form = event.target;
    showConfirmModal(
        '¿Estás seguro?',
        '¿Estás seguro de eliminar esta columna?',
        () => form.submit()
    );
    return false;
}

function confirmDeleteTipo(event) {
    event.preventDefault();
    const form = event.target;
    showConfirmModal(
        '¿Estás seguro?',
        '¿Estás seguro de eliminar este tipo?',
        () => form.submit()
    );
    return false;
}

function confirmDeleteHorario(event) {
    event.preventDefault();
    const form = event.target;
    showConfirmModal(
        '¿Estás seguro?',
        '¿Estás seguro de eliminar este horario?',
        () => form.submit()
    );
    return false;
}

// Hacer todas las funciones disponibles globalmente
window.openModalidadCreate = openModalidadCreate;
window.editModalidad = editModalidad;
window.deleteModalidad = deleteModalidad;
window.closeModalidadModal = closeModalidadModal;
window.handleModalidadFormSubmit = handleModalidadFormSubmit;
window.openModalColumna = openModalColumna;
window.editColumna = editColumna;
window.closeColumnaModal = closeColumnaModal;
window.resetColumnaModal = resetColumnaModal;
window.handleIconoFileChange = handleIconoFileChange;
window.handleColumnaFormSubmit = handleColumnaFormSubmit;
window.openModalTipo = openModalTipo;
window.editTipo = editTipo;
window.closeTipoModal = closeTipoModal;
window.resetTipoModal = resetTipoModal;
window.handleTipoFormSubmit = handleTipoFormSubmit;
window.openModalHorario = openModalHorario;
window.editHorario = editHorario;
window.closeHorarioModal = closeHorarioModal;
window.resetHorarioModal = resetHorarioModal;
window.handleHorarioIconoFileChange = handleHorarioIconoFileChange;
window.handleHorarioFormSubmit = handleHorarioFormSubmit;
window.toggleModalidadActivo = toggleModalidadActivo;
window.toggleTipoActivo = toggleTipoActivo;
window.initSortableColumnas = initSortableColumnas;
window.updateColumnasOrden = updateColumnasOrden;
window.confirmDeleteColumna = confirmDeleteColumna;
window.confirmDeleteTipo = confirmDeleteTipo;
window.confirmDeleteHorario = confirmDeleteHorario;
