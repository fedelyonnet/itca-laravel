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
        // Show Alpine.js modal
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
        modal._x_dataStack[0].title = title;
        modal._x_dataStack[0].message = message;
        modal._x_dataStack[0].onConfirm = () => modal._x_dataStack[0].open = false; // Just close the modal
        modal._x_dataStack[0].open = true;
        
        // Hide cancel button and modify confirm button for validation
        const cancelButton = modal.querySelector('button[class*="bg-white"]');
        const confirmButton = modal.querySelector('button[class*="bg-red-600"]');
        
        if (cancelButton) {
            cancelButton.style.display = 'none';
        }
        
        if (confirmButton) {
            confirmButton.textContent = 'Aceptar';
            confirmButton.className = confirmButton.className.replace('bg-red-600', 'bg-blue-600').replace('hover:bg-red-700', 'hover:bg-blue-700');
        }
    } else {
        // Fallback to native alert
        alert(title + ": " + message);
    }
}

// Make showValidationModal globally available
window.showValidationModal = showValidationModal;
