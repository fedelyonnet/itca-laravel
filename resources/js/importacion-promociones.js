// Importación de Promociones - Funcionalidad JavaScript

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
    const table = document.getElementById('tablaPromociones');
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
            originalMinWidth = parseInt(window.getComputedStyle(header).minWidth) || startWidth;
            
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
            
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
        };
        
        const onMouseMove = (e) => {
            if (!isResizing) return;
            
            const diff = e.pageX - startX;
            const newWidth = Math.max(originalMinWidth, startWidth + diff);
            
            header.style.width = newWidth + 'px';
            
            const cells = getColumnCells(index);
            cells.forEach(cell => {
                cell.style.width = newWidth + 'px';
            });
        };
        
        const onMouseUp = () => {
            if (!isResizing) return;
            
            isResizing = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
            
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        };
        
        resizer.addEventListener('mousedown', onMouseDown);
    });
}

/**
 * Inicializa la funcionalidad de ordenamiento de tabla
 */
function initTableSorting() {
    const table = document.getElementById('tablaPromociones');
    if (!table) return;
    
    const headers = table.querySelectorAll('thead th.sortable-header');
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    
    headers.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const sortColumn = this.getAttribute('data-sort');
            if (!sortColumn) return;
            
            // Determinar dirección
            if (currentSort.column === sortColumn) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.column = sortColumn;
                currentSort.direction = 'asc';
            }
            
            // Actualizar indicadores visuales
            headers.forEach(h => {
                const indicator = h.querySelector('.sort-indicator');
                if (indicator) {
                    indicator.textContent = '';
                }
            });
            
            const indicator = this.querySelector('.sort-indicator');
            if (indicator) {
                indicator.textContent = currentSort.direction === 'asc' ? ' ▲' : ' ▼';
            }
            
            // Ordenar tabla
            sortTable(table, sortColumn, currentSort.direction);
        });
    });
}

/**
 * Ordena la tabla por columna
 */
function sortTable(table, column, direction) {
    const tbody = table.querySelector('tbody');
    if (!tbody) return;
    
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const columnIndex = Array.from(table.querySelectorAll('thead th')).findIndex(
        th => th.getAttribute('data-sort') === column
    );
    
    if (columnIndex === -1) return;
    
    // Campos numéricos
    const numericFields = ['Porcentaje'];
    
    rows.sort((a, b) => {
        const aCell = a.cells[columnIndex];
        const bCell = b.cells[columnIndex];
        
        if (!aCell || !bCell) return 0;
        
        let aValue = (aCell.textContent || '').trim();
        let bValue = (bCell.textContent || '').trim();
        
        // Manejar campos vacíos
        if (!aValue && !bValue) return 0;
        if (!aValue) return 1;
        if (!bValue) return -1;
        
        // Ordenar por número (Porcentaje)
        if (numericFields.includes(column)) {
            // Remover % y espacios, convertir coma a punto
            aValue = aValue.replace(/[%\s]/g, '').replace(',', '.');
            bValue = bValue.replace(/[%\s]/g, '').replace(',', '.');
            
            const aNum = parseFloat(aValue) || 0;
            const bNum = parseFloat(bValue) || 0;
            
            if (aNum === bNum) return 0;
            return direction === 'asc' ? (aNum - bNum) : (bNum - aNum);
        }
        
        // Comparación de texto (case insensitive)
        aValue = aValue.toLowerCase();
        bValue = bValue.toLowerCase();
        
        const comparison = aValue.localeCompare(bValue, undefined, { numeric: true, sensitivity: 'base' });
        return direction === 'asc' ? comparison : -comparison;
    });
    
    // Reordenar filas en el DOM
    rows.forEach(row => tbody.appendChild(row));
}

/**
 * Procesa la importación del archivo Excel
 */
function procesarImportacion(e) {
    const btnImportar = document.getElementById('btnImportarExcel');
    const input = document.getElementById('archivoExcel');
    
    if (!input || !input.files || input.files.length === 0) {
        alert('Por favor, selecciona un archivo Excel.');
        return;
    }
    
    const file = input.files[0];
    const route = btnImportar ? btnImportar.getAttribute('data-route') : null;
    
    if (!route) {
        alert('Error: No se encontró la ruta de importación.');
        return;
    }
    
    const formData = new FormData();
    formData.append('archivo_excel', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
    
    // Deshabilitar botón y mostrar estado de carga
    const textoOriginal = btnImportar ? btnImportar.textContent : 'Importar';
    if (btnImportar) {
        btnImportar.disabled = true;
        btnImportar.textContent = 'Importando...';
    }
    
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Error al importar el archivo');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta de importación:', data);
        
        // Restaurar botón
        if (btnImportar) {
            btnImportar.disabled = false;
            btnImportar.textContent = textoOriginal;
        }
        
        // Limpiar input
        input.value = '';
        
        // Buscar el elemento con Alpine.js
        const alpineContainer = document.querySelector('.py-12[x-data]');
        
        if (!alpineContainer) {
            console.error('No se encontró el contenedor Alpine.js');
            alert(data?.message || (data?.success ? 'Importación realizada correctamente' : 'Error al importar el archivo'));
            if (data?.success) {
                setTimeout(() => window.location.reload(), 1000);
            }
            return;
        }
        
        // Acceder a Alpine.js
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
        
        // Guardar resultado
        alpineData.resultadoImportacion = data;
        
        // Cerrar modal de importación
        alpineData.modalImportarOpen = false;
        
        // Abrir modal de resultado
        requestAnimationFrame(() => {
            alpineData.modalResultadoOpen = true;
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

