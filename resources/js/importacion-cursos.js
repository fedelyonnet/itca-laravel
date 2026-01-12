// Importación de Cursos - Funcionalidad JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initResizableTable();
    initImportacionButton();
    initTableSorting();
    initTableFilters();
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
    
    const input = document.getElementById('archivo_excel');
    if (!input) {
        console.error('No se encontró el input archivo_excel');
        mostrarToast('Error: No se encontró el campo de archivo', 'error');
        return;
    }
    
    const file = input.files[0];
    
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
    
    
    // Enviar al servidor
    // NOTA: El navegador mostrará códigos 400+ como "errores" en la consola de red,
    // pero esto es normal y esperado cuando el servidor valida y rechaza datos inválidos.
    // El código JavaScript maneja estos casos correctamente mostrando el error en el modal.
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
        
        // Si la respuesta no es OK (400, 500, etc.), manejar el error
        // Esto es normal cuando el servidor valida y rechaza datos inválidos
        if (!response.ok) {
            // Si hay un mensaje de error en los datos, lanzar error con ese mensaje
            const errorMessage = data.message || data.error || 'Error en la respuesta del servidor';
            throw new Error(errorMessage);
        }
        
        return data;
    })
    .then(data => {
        // Restaurar botón
        if (btnImportar) {
            btnImportar.disabled = false;
            btnImportar.textContent = textoOriginal;
        }
        
        // Limpiar input
        input.value = '';
        
        // Si la respuesta indica error, manejarlo como error ANTES de procesar
        if (data && data.success === false) {
            throw new Error(data.message || 'Error al procesar la importación');
        }
        
        // Buscar el elemento con Alpine.js - método más directo
        const alpineContainer = document.querySelector('.py-12[x-data]');
        
        if (!alpineContainer) {
            console.error('No se encontró el contenedor Alpine.js');
            // Si no hay Alpine, lanzar error para que vaya al catch
            throw new Error(data?.message || 'Error al importar el archivo');
        }
        
        // Acceder a Alpine.js de forma más directa
        const alpine = window.Alpine || (alpineContainer.__x && alpineContainer.__x);
        
        if (!alpine) {
            console.error('Alpine.js no está disponible');
            // Si no hay Alpine, lanzar error para que vaya al catch
            throw new Error(data?.message || 'Error al importar el archivo');
        }
        
        // Obtener los datos de Alpine - múltiples métodos
        let alpineData;
        if (alpineContainer.__x && alpineContainer.__x.$data) {
            alpineData = alpineContainer.__x.$data;
        } else if (window.Alpine && window.Alpine.$data) {
            alpineData = window.Alpine.$data(alpineContainer);
        } else if (alpineContainer._x_dataStack && alpineContainer._x_dataStack[0]) {
            alpineData = alpineContainer._x_dataStack[0];
        }
        
        if (!alpineData) {
            console.error('No se pudieron obtener los datos de Alpine.js');
            // Si no hay Alpine, lanzar error para que vaya al catch
            throw new Error(data?.message || 'Error al importar el archivo');
        }
        
        // Guardar resultado primero
        alpineData.resultadoImportacion = data;
        
        // Cerrar modal de importación
        alpineData.modalImportarOpen = false;
        
        // Usar requestAnimationFrame para asegurar que el DOM se actualice
        requestAnimationFrame(() => {
            // Abrir modal de resultado
            alpineData.modalResultadoOpen = true;
        });
    })
    .catch(error => {
        // Restaurar botón
        if (btnImportar) {
            btnImportar.disabled = false;
            btnImportar.textContent = textoOriginal;
        }
        
        // Usar el mismo método que en el caso de éxito para obtener Alpine data
        const alpineContainer = document.querySelector('.py-12[x-data]');
        
        // Obtener los datos de Alpine - múltiples métodos (igual que en el caso de éxito)
        let alpineData;
        if (alpineContainer && alpineContainer.__x && alpineContainer.__x.$data) {
            alpineData = alpineContainer.__x.$data;
        } else if (alpineContainer && window.Alpine && typeof window.Alpine.$data === 'function') {
            try {
                alpineData = window.Alpine.$data(alpineContainer);
            } catch (e) {
                console.error('Error accediendo a Alpine.$data:', e);
            }
        } else if (alpineContainer && alpineContainer._x_dataStack && alpineContainer._x_dataStack.length > 0) {
            alpineData = alpineContainer._x_dataStack[0];
        }
        
        if (alpineData) {
            // Cerrar modal de importación
            alpineData.modalImportarOpen = false;
            
            // Configurar error con mensaje descriptivo
            const errorMessage = error.message || 'Error al procesar la importación';
            
            alpineData.resultadoImportacion = {
                success: false,
                message: errorMessage
            };
            
            // Usar requestAnimationFrame como en el caso de éxito para asegurar que Alpine se actualice
            requestAnimationFrame(() => {
                alpineData.modalResultadoOpen = true;
            });
        } else {
            console.error('Alpine.js no disponible en catch, usando fallback DOM');
            // Fallback: manipulación directa del DOM usando el mismo selector que Alpine
            const modalResultado = document.querySelector('[x-show*="modalResultadoOpen"]');
            if (modalResultado) {
                // Forzar mostrar el modal removiendo el style="display: none"
                modalResultado.removeAttribute('style');
                modalResultado.style.display = 'flex';
                modalResultado.style.zIndex = '50';
                
                const modalContent = modalResultado.querySelector('.bg-gray-800');
                if (modalContent) {
                    // Actualizar título
                    const title = modalContent.querySelector('h3');
                    if (title) {
                        title.textContent = 'Error en la Importación';
                    }
                    
                    // Buscar el div .p-6 que contiene el contenido
                    const p6Div = modalContent.querySelector('.p-6');
                    if (p6Div) {
                        // Ocultar sección de éxito si existe
                        const successSection = p6Div.querySelector('div[x-show*="resultadoImportacion.success"]');
                        if (successSection) {
                            successSection.style.display = 'none';
                        }
                        
                        // Buscar o crear sección de error
                        let errorSection = p6Div.querySelector('div[x-show*="!resultadoImportacion.success"]');
                        if (!errorSection) {
                            // Crear la sección de error
                            errorSection = document.createElement('div');
                            errorSection.className = 'space-y-4';
                            p6Div.appendChild(errorSection);
                        }
                        
                        // Mostrar la sección de error
                        errorSection.style.display = 'block';
                        
                        // Actualizar o crear el contenido del error
                        let errorContent = errorSection.querySelector('.flex.items-start');
                        if (!errorContent) {
                            errorContent = document.createElement('div');
                            errorContent.className = 'flex items-start';
                            errorSection.appendChild(errorContent);
                        }
                        
                        // Actualizar el mensaje
                        let messageP = errorContent.querySelector('.text-gray-200');
                        if (!messageP) {
                            // Crear estructura completa si no existe
                            errorContent.innerHTML = `
                                <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-gray-200">${error.message || 'Error al procesar la importación'}</p>
                                </div>
                            `;
                        } else {
                            messageP.textContent = error.message || 'Error al procesar la importación';
                        }
                    }
                }
            } else {
                // Último recurso: alert
                alert('Error: ' + (error.message || 'Error al procesar la importación'));
            }
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
    
    // Mapeo de campos a índices de columna (orden exacto de la tabla)
    const fieldIndexMap = {
        'ID_Curso': 0,
        'carrera': 1,
        'Cod1': 2,
        'Fecha_Inicio': 3,
        'xDias': 4,
        'xModalidad': 5,
        'Régimen': 6,
        'xTurno': 7,
        'Horario': 8,
        'Vacantes': 9,
        'Matric_Base': 10,
        'Sin_iva_Mat': 11,
        'Cta_Web': 12,
        'Sin_IVA_cta': 13,
        'Dto_Cuota': 14,
        'cuotas': 15,
        'sede': 16,
        'Promo_Mat_logo': 17,
        'ver_curso': 18
    };
    
    const columnIndex = fieldIndexMap[field];
    if (columnIndex === undefined) {
        console.warn('Campo de ordenamiento no encontrado:', field);
        return;
    }
    
    // Campos numéricos
    const numericFields = ['ID_Curso', 'Vacantes', 'Matric_Base', 'Sin_iva_Mat', 'Cta_Web', 'Sin_IVA_cta', 'Dto_Cuota', 'cuotas'];
    // Campos de fecha
    const dateFields = ['Fecha_Inicio'];
    // Campos booleanos
    const booleanFields = ['casilla_Promo'];
    
    rows.sort((a, b) => {
        const aCell = a.cells[columnIndex];
        const bCell = b.cells[columnIndex];
        
        if (!aCell || !bCell) return 0;
        
        let aValue = aCell.textContent.trim();
        let bValue = bCell.textContent.trim();
        
        // Manejar campos vacíos
        if (!aValue && !bValue) return 0;
        if (!aValue) return 1; // Los vacíos van al final
        if (!bValue) return -1;
        
        // Ordenar por fecha
        if (dateFields.includes(field)) {
            // Formato esperado: dd/mm/yyyy
            const parseDate = (dateStr) => {
                const parts = dateStr.split('/');
                if (parts.length === 3) {
                    return new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
                }
                return new Date(0); // Fecha inválida va al final
            };
            
            const aDate = parseDate(aValue);
            const bDate = parseDate(bValue);
            
            if (aDate.getTime() === bDate.getTime()) return 0;
            return direction === 'asc' 
                ? (aDate.getTime() - bDate.getTime())
                : (bDate.getTime() - aDate.getTime());
        }
        
        // Ordenar por booleano
        if (booleanFields.includes(field)) {
            // Convertir ✓ a true, - o vacío a false
            const aBool = aValue === '✓' || aValue.toLowerCase() === 'true' || aValue === '1';
            const bBool = bValue === '✓' || bValue.toLowerCase() === 'true' || bValue === '1';
            
            if (aBool === bBool) return 0;
            return direction === 'asc' 
                ? (aBool ? 1 : -1) - (bBool ? 1 : -1)
                : (bBool ? 1 : -1) - (aBool ? 1 : -1);
        }
        
        // Ordenar por número
        if (numericFields.includes(field)) {
            // Para campos monetarios, convertir formato argentino (punto=miles, coma=decimal) a número
            if (field === 'Matric_Base' || field === 'Cta_Web' || field === 'Sin_iva_Mat' || field === 'Sin_IVA_cta') {
                // Remover símbolo $ y espacios
                aValue = aValue.replace(/[$,\s]/g, '');
                bValue = bValue.replace(/[$,\s]/g, '');
                // Formato: 294.200,00 -> 294200.00
                // Primero remover puntos (separador de miles)
                aValue = aValue.replace(/\./g, '');
                bValue = bValue.replace(/\./g, '');
                // Luego convertir coma (decimal) a punto
                aValue = aValue.replace(',', '.');
                bValue = bValue.replace(',', '.');
            } else if (field === 'Dto_Cuota') {
                // Para porcentaje, remover % y espacios, convertir coma a punto
                aValue = aValue.replace(/[%\s]/g, '').replace(',', '.');
                bValue = bValue.replace(/[%\s]/g, '').replace(',', '.');
            }
            
            const aNum = parseFloat(aValue) || 0;
            const bNum = parseFloat(bValue) || 0;
            
            if (aNum === bNum) return 0;
            return direction === 'asc' ? (aNum - bNum) : (bNum - aNum);
        }
        
        // Ordenar por texto (case insensitive)
        aValue = aValue.toLowerCase();
        bValue = bValue.toLowerCase();
        
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

/**
 * Inicializa los filtros de la tabla
 */
function initTableFilters() {
    const filtroCarrera = document.getElementById('filtroCarrera');
    const filtroSede = document.getElementById('filtroSede');
    const filtroModalidad = document.getElementById('filtroModalidad');
    const filtroTurno = document.getElementById('filtroTurno');
    const filtroDias = document.getElementById('filtroDias');
    const filtroRegimen = document.getElementById('filtroRegimen');
    const filtroVerCurso = document.getElementById('filtroVerCurso');
    const filtroVacantes = document.getElementById('filtroVacantes');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    
    if (!filtroCarrera || !filtroSede || !filtroModalidad || !filtroTurno || !filtroDias || !filtroRegimen || !filtroVerCurso || !filtroVacantes) {
        return;
    }
    
    // Función para aplicar filtros
    function aplicarFiltros() {
        const tbody = document.getElementById('tablaCursadasBody');
        if (!tbody) return;
        
        // Obtener contadores dentro de la función para asegurar que siempre se encuentren
        const contadorTotalEl = document.getElementById('contadorTotal');
        const contadorWebEl = document.getElementById('contadorWeb');
        
        const rows = Array.from(tbody.querySelectorAll('tr'));
        let totalVisible = 0;
        let totalWeb = 0;
        
        const carreraValue = filtroCarrera.value.trim().toLowerCase();
        const sedeValue = filtroSede.value.trim().toLowerCase();
        const modalidadValue = filtroModalidad.value.trim().toLowerCase();
        const turnoValue = filtroTurno.value.trim().toLowerCase();
        const diasValue = filtroDias.value.trim().toLowerCase();
        const regimenValue = filtroRegimen.value.trim().toLowerCase();
        const verCursoValue = filtroVerCurso.value.trim().toLowerCase();
        const vacantesValue = filtroVacantes.value.trim();
        
        rows.forEach(row => {
            if (row.cells.length < 19) {
                row.style.display = 'none';
                return;
            }
            
            let mostrar = true;
            
            // Filtro por carrera (columna 1, índice 1)
            if (carreraValue && row.cells[1]) {
                const carrera = (row.cells[1].textContent || '').trim().toLowerCase();
                if (carrera !== carreraValue) {
                    mostrar = false;
                }
            }
            
            // Filtro por sede (columna 16, índice 16 - después de cuotas)
            if (mostrar && sedeValue && row.cells[16]) {
                const sede = (row.cells[16].textContent || '').trim().toLowerCase();
                if (sede !== sedeValue) {
                    mostrar = false;
                }
            }
            
            // Filtro por modalidad (columna 5, índice 5)
            if (mostrar && modalidadValue && row.cells[5]) {
                const modalidad = (row.cells[5].textContent || '').trim().toLowerCase();
                // Normalizar "sempresencial" a "semipresencial" para comparación
                const modalidadNormalizada = modalidad.replace('sempresencial', 'semipresencial');
                const filtroNormalizado = modalidadValue.replace('sempresencial', 'semipresencial');
                if (modalidadNormalizada !== filtroNormalizado) {
                    mostrar = false;
                }
            }
            
            // Filtro por turno (columna 7, índice 7)
            if (mostrar && turnoValue && row.cells[7]) {
                const turno = (row.cells[7].textContent || '').trim().toLowerCase();
                if (turno !== turnoValue) {
                    mostrar = false;
                }
            }
            
            // Filtro por días (columna 4, índice 4)
            if (mostrar && diasValue && row.cells[4]) {
                const diaOriginal = (row.cells[4].getAttribute('data-dia-original') || '').trim().toLowerCase();
                if (diaOriginal !== diasValue) {
                    mostrar = false;
                }
            }
            
            // Filtro por régimen (columna 6, índice 6)
            if (mostrar && regimenValue && row.cells[6]) {
                const regimen = (row.cells[6].textContent || '').trim().toLowerCase();
                if (regimen !== regimenValue) {
                    mostrar = false;
                }
            }
            
            // Filtro por ver_curso (columna 17, índice 17)
            // Filtro por ver curso (columna 18, índice 18 - después de Promo_Mat_logo)
            if (mostrar && verCursoValue && row.cells[18]) {
                const verCurso = (row.cells[18].textContent || '').trim().toLowerCase();
                if (verCurso !== verCursoValue) {
                    mostrar = false;
                }
            }
            
            // Filtro por vacantes (columna 9, índice 9)
            if (mostrar && vacantesValue && row.cells[9]) {
                const vacantesText = (row.cells[9].textContent || '').trim();
                const vacantes = parseInt(vacantesText) || 0;
                
                if (vacantesValue === 'con' && vacantes === 0) {
                    mostrar = false;
                } else if (vacantesValue === 'sin' && vacantes > 0) {
                    mostrar = false;
                }
            }
            
            if (mostrar) {
                row.style.display = '';
                totalVisible++;
                
                // Verificar si tiene "ver" en ver_curso (columna 18)
                if (row.cells[18]) {
                    const verCurso = (row.cells[18].textContent || row.cells[18].innerText || '').trim().toLowerCase();
                    if (verCurso === 'ver') {
                        totalWeb++;
                    }
                }
            } else {
                row.style.display = 'none';
            }
        });
        
        // Actualizar contadores (las variables ya están declaradas al inicio de la función)
        if (contadorTotalEl) {
            contadorTotalEl.textContent = totalVisible;
        }
        if (contadorWebEl) {
            contadorWebEl.textContent = totalWeb;
        }
    }
    
    // Agregar event listeners a los filtros
    filtroCarrera.addEventListener('change', aplicarFiltros);
    filtroSede.addEventListener('change', aplicarFiltros);
    filtroModalidad.addEventListener('change', aplicarFiltros);
    filtroTurno.addEventListener('change', aplicarFiltros);
    filtroDias.addEventListener('change', aplicarFiltros);
    filtroRegimen.addEventListener('change', aplicarFiltros);
    filtroVerCurso.addEventListener('change', aplicarFiltros);
    filtroVacantes.addEventListener('change', aplicarFiltros);
    
    // Botón limpiar filtros
    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', function() {
            filtroCarrera.value = '';
            filtroSede.value = '';
            filtroModalidad.value = '';
            filtroTurno.value = '';
            filtroDias.value = '';
            filtroRegimen.value = '';
            filtroVerCurso.value = '';
            filtroVacantes.value = '';
            aplicarFiltros();
        });
    }
    
    // Aplicar filtros iniciales (para contar registros)
    aplicarFiltros();
}

// Hacer funciones disponibles globalmente
window.procesarImportacion = procesarImportacion;
window.mostrarToast = mostrarToast;
window.initResizableTable = initResizableTable;

