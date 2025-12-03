// Importación de Cursos - Funcionalidad JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initResizableTable();
    initImportacionButton();
    initTableSorting();
});

/**
 * Inicializa el botón de importación
 */
function initImportacionButton() {
    const btnImportar = document.getElementById('btnImportarExcel');
    if (btnImportar) {
        btnImportar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            procesarImportacion(e);
        });
    }
}

/**
 * Inicializa la funcionalidad de redimensionamiento de columnas
 */
function initResizableTable() {
    const table = document.getElementById('tablaCursadas');
    if (!table) return;
    
    const headers = Array.from(table.querySelectorAll('thead th.resizable-header'));
    
    headers.forEach((header, index) => {
        const resizer = header.querySelector('.resizer');
        if (!resizer) return;
        
        let startX = 0;
        let startWidth = 0;
        let originalMinWidth = 0;
        let isResizing = false;
        
        const getColumnCells = (colIndex) => {
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            return rows.map(row => row.cells[colIndex]).filter(cell => cell !== undefined);
        };
        
        const onMouseDown = (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            isResizing = true;
            startX = e.pageX;
            startWidth = header.offsetWidth;
            
            // Guardar el minWidth original del header
            const computedStyle = window.getComputedStyle(header);
            originalMinWidth = parseInt(computedStyle.minWidth) || parseInt(header.getAttribute('data-min-width')) || 50;
            
            resizer.classList.add('resizing');
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
            
            // Prevenir selección de texto
            document.addEventListener('selectstart', preventSelection);
            
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        };
        
        const onMouseMove = (e) => {
            if (!isResizing) return;
            
            const diff = e.pageX - startX;
            const newWidth = Math.max(startWidth + diff, originalMinWidth);
            
            // Aplicar solo el ancho al header, NO cambiar min-width ni max-width
            header.style.setProperty('width', newWidth + 'px', 'important');
            
            // Aplicar solo el ancho a todas las celdas de esta columna
            const cells = getColumnCells(index);
            cells.forEach(cell => {
                if (cell) {
                    cell.style.setProperty('width', newWidth + 'px', 'important');
                }
            });
        };
        
        const onMouseUp = () => {
            if (!isResizing) return;
            
            isResizing = false;
            resizer.classList.remove('resizing');
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
            
            document.removeEventListener('selectstart', preventSelection);
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        };
        
        const preventSelection = (e) => {
            e.preventDefault();
        };
        
        resizer.addEventListener('mousedown', onMouseDown);
    });
}

/**
 * Procesa la importación del archivo Excel
 */
function procesarImportacion(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
    }
    
    console.log('procesarImportacion llamado');
    const input = document.getElementById('archivo_excel');
    if (!input) {
        console.error('No se encontró el input archivo_excel');
        mostrarToast('Error: No se encontró el campo de archivo', 'error');
        return;
    }
    
    const file = input.files[0];
    console.log('Archivo seleccionado:', file ? file.name : 'ninguno');
    
    if (!file) {
        mostrarToast('Por favor selecciona un archivo Excel', 'error');
        return;
    }
    
    // Validar extensión
    const extension = file.name.split('.').pop().toLowerCase();
    if (extension !== 'xlsx' && extension !== 'xls') {
        mostrarToast('El archivo debe ser un Excel (.xlsx o .xls)', 'error');
        return;
    }
    
    // Obtener el token CSRF
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                 || document.querySelector('input[name="_token"]')?.value;
    
    if (!token) {
        mostrarToast('Error: No se pudo obtener el token de seguridad', 'error');
        return;
    }
    
    // Crear FormData
    const formData = new FormData();
    formData.append('archivo_excel', file);
    formData.append('_token', token);
    
    // Obtener el botón de importar
    const btnImportar = document.getElementById('btnImportarExcel') || event?.target;
    const textoOriginal = btnImportar?.textContent || 'Importar';
    
    // Mostrar loading
    if (btnImportar) {
        btnImportar.disabled = true;
        btnImportar.textContent = 'Importando...';
    }
    
    // Obtener la ruta desde el atributo data-route o construirla
    const route = btnImportar?.getAttribute('data-route') || '/admin/carreras/importacion';
    
    console.log('Enviando a:', route);
    console.log('Token CSRF:', token ? 'presente' : 'ausente');
    
    // Enviar al servidor
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        }
    })
    .then(async response => {
        // Intentar parsear como JSON siempre
        let data;
        try {
            const text = await response.text();
            data = JSON.parse(text);
        } catch (e) {
            console.error('Error parseando JSON:', e);
            throw new Error('El servidor no retornó JSON válido');
        }
        
        if (!response.ok) {
            throw new Error(data.message || 'Error en la respuesta del servidor');
        }
        
        return data;
    })
    .then(data => {
        console.log('Respuesta recibida:', data);
        
        // Restaurar botón
        if (btnImportar) {
            btnImportar.disabled = false;
            btnImportar.textContent = textoOriginal;
        }
        
        // Limpiar input
        input.value = '';
        
        // Buscar el elemento con Alpine.js - método más directo
        const alpineContainer = document.querySelector('.py-12[x-data]');
        
        if (!alpineContainer) {
            console.error('No se encontró el contenedor Alpine.js');
            alert(data?.message || (data?.success ? 'Importación realizada correctamente' : 'Error al importar el archivo'));
            if (data?.success) {
                setTimeout(() => window.location.reload(), 1000);
            }
            return;
        }
        
        // Acceder a Alpine.js de forma más directa
        const alpine = window.Alpine || (alpineContainer.__x && alpineContainer.__x);
        
        if (!alpine) {
            console.error('Alpine.js no está disponible');
            alert(data?.message || (data?.success ? 'Importación realizada correctamente' : 'Error al importar el archivo'));
            if (data?.success) {
                setTimeout(() => window.location.reload(), 1000);
            }
            return;
        }
        
        // Obtener los datos de Alpine
        let alpineData;
        if (alpineContainer.__x && alpineContainer.__x.$data) {
            alpineData = alpineContainer.__x.$data;
        } else {
            // Intentar obtener desde Alpine global
            alpineData = alpine.$data && alpine.$data(alpineContainer);
        }
        
        if (!alpineData) {
            console.error('No se pudieron obtener los datos de Alpine.js');
            alert(data?.message || (data?.success ? 'Importación realizada correctamente' : 'Error al importar el archivo'));
            if (data?.success) {
                setTimeout(() => window.location.reload(), 1000);
            }
            return;
        }
        
        console.log('Datos de Alpine encontrados:', alpineData);
        
        // Guardar resultado primero
        alpineData.resultadoImportacion = data;
        console.log('Resultado guardado:', data);
        
        // Cerrar modal de importación
        alpineData.modalImportarOpen = false;
        console.log('Modal de importación cerrado:', alpineData.modalImportarOpen);
        
        // Usar requestAnimationFrame para asegurar que el DOM se actualice
        requestAnimationFrame(() => {
            // Abrir modal de resultado
            alpineData.modalResultadoOpen = true;
            console.log('Modal de resultado abierto:', alpineData.modalResultadoOpen);
            console.log('Estado completo:', {
                modalImportarOpen: alpineData.modalImportarOpen,
                modalResultadoOpen: alpineData.modalResultadoOpen,
                resultadoImportacion: alpineData.resultadoImportacion
            });
            
            // Verificar que el modal esté visible en el DOM
            setTimeout(() => {
                const modalResultado = document.querySelector('[x-show*="modalResultadoOpen"]');
                if (modalResultado) {
                    const isVisible = window.getComputedStyle(modalResultado).display !== 'none';
                    console.log('Modal de resultado visible en DOM:', isVisible);
                    console.log('Estilos del modal:', window.getComputedStyle(modalResultado).display);
                } else {
                    console.error('No se encontró el modal de resultado en el DOM');
                }
            }, 100);
        });
    })
    .catch(error => {
        console.error('Error completo:', error);
        
        // Restaurar botón
        if (btnImportar) {
            btnImportar.disabled = false;
            btnImportar.textContent = textoOriginal;
        }
        
        const alpineContainer = document.querySelector('.py-12[x-data]');
        
        if (alpineContainer && alpineContainer.__x && alpineContainer.__x.$data) {
            const alpineData = alpineContainer.__x.$data;
            
            // Cerrar modal de importación
            alpineData.modalImportarOpen = false;
            
            // Configurar error
            alpineData.resultadoImportacion = {
                success: false,
                message: error.message || 'Error al procesar la importación'
            };
            
            // Abrir modal de resultado
            setTimeout(() => {
                alpineData.modalResultadoOpen = true;
            }, 300);
        } else {
            // Fallback solo si no hay Alpine.js
            alert('Error: ' + (error.message || 'Error al procesar la importación'));
        }
    })
    .finally(() => {
        if (btnImportar) {
            btnImportar.disabled = false;
            btnImportar.textContent = textoOriginal;
        }
    });
}

/**
 * Muestra un mensaje toast
 */
function mostrarToast(mensaje, tipo) {
    const container = document.getElementById('toast-container');
    if (!container) {
        console.error('No se encontró el contenedor de toasts');
        return;
    }
    
    const bgColor = tipo === 'success' ? 'bg-green-500' : 'bg-red-500';
    const borderColor = tipo === 'success' ? 'border-green-400' : 'border-red-400';
    const icon = tipo === 'success' 
        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>';
    
    const toast = document.createElement('div');
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg border-l-4 ${borderColor} flex items-center transform translate-x-full transition-transform duration-300 ease-in-out`;
    toast.innerHTML = `
        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            ${icon}
        </svg>
        ${mensaje}
    `;
    
    container.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 10);
    
    // Remover después de 5 segundos
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

/**
 * Inicializa el ordenamiento de la tabla
 */
function initTableSorting() {
    const sortableHeaders = document.querySelectorAll('.sortable-header');
    let currentSort = {
        column: null,
        direction: 'asc' // 'asc' o 'desc'
    };
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // No ordenar si se hace clic en el resizer
            if (e.target.classList.contains('resizer')) {
                return;
            }
            
            const sortField = this.getAttribute('data-sort');
            if (!sortField) return;
            
            // Determinar dirección de ordenamiento
            if (currentSort.column === sortField) {
                // Cambiar dirección si es la misma columna
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                // Nueva columna, empezar con ascendente
                currentSort.column = sortField;
                currentSort.direction = 'asc';
            }
            
            // Remover indicadores de todas las columnas
            sortableHeaders.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });
            
            // Agregar indicador a la columna actual
            this.classList.add(`sort-${currentSort.direction}`);
            
            // Ordenar la tabla
            sortTable(sortField, currentSort.direction);
        });
    });
}

/**
 * Ordena la tabla según el campo y dirección especificados
 */
function sortTable(field, direction) {
    const tbody = document.getElementById('tablaCursadasBody');
    if (!tbody) return;
    
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Mapeo de campos a índices de columna
    const fieldIndexMap = {
        'id_curso': 0,
        'nombre_curso': 1,
        'vacantes': 2,
        'sede': 3,
        'x_modalidad': 4
    };
    
    const columnIndex = fieldIndexMap[field];
    if (columnIndex === undefined) return;
    
    rows.sort((a, b) => {
        const aCell = a.cells[columnIndex];
        const bCell = b.cells[columnIndex];
        
        if (!aCell || !bCell) return 0;
        
        let aValue = aCell.textContent.trim();
        let bValue = bCell.textContent.trim();
        
        // Convertir a número si es campo numérico
        if (field === 'vacantes') {
            aValue = parseInt(aValue) || 0;
            bValue = parseInt(bValue) || 0;
        } else {
            // Comparación de texto (case insensitive)
            aValue = aValue.toLowerCase();
            bValue = bValue.toLowerCase();
        }
        
        if (aValue < bValue) {
            return direction === 'asc' ? -1 : 1;
        }
        if (aValue > bValue) {
            return direction === 'asc' ? 1 : -1;
        }
        return 0;
    });
    
    // Reordenar las filas en el DOM
    rows.forEach(row => tbody.appendChild(row));
}

// Hacer funciones disponibles globalmente
window.procesarImportacion = procesarImportacion;
window.mostrarToast = mostrarToast;
window.initResizableTable = initResizableTable;

