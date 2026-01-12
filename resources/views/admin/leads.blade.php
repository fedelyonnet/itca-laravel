<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js'])

    <div class="py-12">
        <div class="w-full px-6">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold">Leads</h1>
                        <a href="{{ route('admin.leads.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar Grilla
                        </a>
                    </div>

                    <!-- Grilla de leads -->
                    <div class="overflow-x-auto" style="max-height: calc(100vh - 250px);">
                        <table id="tablaLeads" class="min-w-full border-collapse bg-gray-700">
                            <thead class="bg-gray-600 sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 120px; width: 150px;" data-sort="cursada_id">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                ID Curso
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 200px;" data-sort="nombre">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Nombre
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 200px;" data-sort="apellido">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Apellido
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="dni">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                DNI
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 200px; width: 250px;" data-sort="correo">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Correo
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 120px; width: 150px;" data-sort="telefono">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Teléfono
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="tipo">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Tipo
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 120px; width: 150px;" data-sort="acepto_terminos">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Aceptó Términos
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 180px;" data-sort="created_at">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Fecha de Creación
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaLeadsBody" class="bg-gray-700 text-gray-200">
                                @forelse($leads as $lead)
                                    <tr class="hover:bg-gray-600">
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->cursada_id ?? 'N/A' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->nombre }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->apellido }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->dni }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->correo }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->telefono }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->tipo ?? 'N/A' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->acepto_terminos ? 'Sí' : 'No' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->created_at ? $lead->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="border border-gray-500 px-3 py-8 text-center text-gray-400">
                                            No hay leads registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para columnas redimensionables */
        .resizable-header {
            position: relative;
            border-right: 2px solid #4b5563 !important;
        }
        
        .resizable-header:hover {
            border-right-color: #6b7280 !important;
        }
        
        .resizer {
            position: absolute;
            top: 0;
            right: -1px;
            width: 6px;
            height: 100%;
            cursor: col-resize;
            user-select: none;
            background: transparent;
            z-index: 2;
            border-right: 2px solid transparent;
        }
        
        .resizer:hover {
            background: rgba(59, 130, 246, 0.3);
            border-right: 2px solid rgba(59, 130, 246, 0.8);
        }
        
        .resizer.resizing {
            background: rgba(59, 130, 246, 0.5);
            border-right: 2px solid rgba(59, 130, 246, 1);
        }
        
        #tablaLeads {
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        #tablaLeads th,
        #tablaLeads td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            border-right: 2px solid #4b5563;
            box-sizing: border-box;
        }
        
        #tablaLeads th.resizable-header {
            position: relative;
        }
        
        #tablaLeads th:last-child,
        #tablaLeads td:last-child {
            border-right: none;
        }
        
        #tablaLeads tbody tr:hover {
            background-color: #4b5563;
        }
        
        #tablaLeads tbody tr:hover td {
            border-right-color: #6b7280;
        }
        
        /* Estilos para headers ordenables */
        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
        }
        
        .sortable-header:hover {
            background-color: #4b5563;
        }
        
        .sort-indicator {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 4px;
            opacity: 0.3;
            font-size: 10px;
        }
        
        .sortable-header.sort-asc .sort-indicator::after {
            content: '▲';
            color: #60a5fa;
            opacity: 1;
            font-size: 10px;
        }
        
        .sortable-header.sort-desc .sort-indicator::after {
            content: '▼';
            color: #60a5fa;
            opacity: 1;
            font-size: 10px;
        }
    </style>

    <script>
        // Funcionalidad de ordenamiento
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('tablaLeads');
            const tbody = table.querySelector('tbody');
            const headers = table.querySelectorAll('.sortable-header');
            let currentSort = { column: null, direction: 'asc' };
            
            // Función helper para parsear fechas en formato dd/mm/yyyy HH:mm
            function parseDate(dateStr) {
                if (!dateStr || dateStr === 'N/A') return null;
                // Formato esperado: dd/mm/yyyy HH:mm
                const parts = dateStr.split(' ');
                if (parts.length !== 2) return null;
                const datePart = parts[0].split('/');
                const timePart = parts[1].split(':');
                if (datePart.length !== 3 || timePart.length !== 2) return null;
                const day = parseInt(datePart[0], 10);
                const month = parseInt(datePart[1], 10) - 1; // Mes es 0-indexed
                const year = parseInt(datePart[2], 10);
                const hour = parseInt(timePart[0], 10);
                const minute = parseInt(timePart[1], 10);
                return new Date(year, month, day, hour, minute);
            }

            headers.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.dataset.sort;
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    
                    // Determinar dirección de ordenamiento
                    if (currentSort.column === column) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort.direction = 'asc';
                    }
                    currentSort.column = column;

                    // Remover clases de ordenamiento de todos los headers
                    headers.forEach(h => {
                        h.classList.remove('sort-asc', 'sort-desc');
                    });

                    // Agregar clase al header actual
                    this.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');

                    // Ordenar filas
                    rows.sort((a, b) => {
                        const aText = a.querySelector(`td:nth-child(${getColumnIndex(column)})`)?.textContent.trim() || '';
                        const bText = b.querySelector(`td:nth-child(${getColumnIndex(column)})`)?.textContent.trim() || '';
                        
                        // Si es la columna de fecha, parsear como fecha para ordenar correctamente
                        if (column === 'created_at') {
                            const aDate = parseDate(aText);
                            const bDate = parseDate(bText);
                            if (aDate && bDate) {
                                return currentSort.direction === 'asc' ? aDate - bDate : bDate - aDate;
                            }
                        }
                        
                        // Si es la columna de acepto_terminos, ordenar por "Sí"/"No"
                        if (column === 'acepto_terminos') {
                            const aValue = aText === 'Sí' ? 1 : 0;
                            const bValue = bText === 'Sí' ? 1 : 0;
                            return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
                        }
                        
                        if (currentSort.direction === 'asc') {
                            return aText.localeCompare(bText);
                        } else {
                            return bText.localeCompare(aText);
                        }
                    });

                    // Reordenar filas en el DOM
                    rows.forEach(row => tbody.appendChild(row));
                });
            });

            function getColumnIndex(column) {
                const columnMap = {
                    'cursada_id': 1,
                    'nombre': 2,
                    'apellido': 3,
                    'dni': 4,
                    'correo': 5,
                    'telefono': 6,
                    'tipo': 7,
                    'acepto_terminos': 8,
                    'created_at': 9
                };
                return columnMap[column] || 1;
            }

            // Funcionalidad de redimensionamiento de columnas
            const resizers = table.querySelectorAll('.resizer');
            let currentResizer = null;
            let startX = 0;
            let startWidth = 0;

            resizers.forEach(resizer => {
                resizer.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    currentResizer = this;
                    const header = this.closest('th');
                    startX = e.pageX;
                    startWidth = header.offsetWidth;
                    
                    this.classList.add('resizing');
                    document.addEventListener('mousemove', handleResize);
                    document.addEventListener('mouseup', stopResize);
                });
            });

            function handleResize(e) {
                if (!currentResizer) return;
                
                const header = currentResizer.closest('th');
                const diff = e.pageX - startX;
                const newWidth = startWidth + diff;
                
                if (newWidth > 50) {
                    header.style.width = newWidth + 'px';
                    header.style.minWidth = newWidth + 'px';
                }
            }

            function stopResize() {
                if (currentResizer) {
                    currentResizer.classList.remove('resizing');
                    currentResizer = null;
                }
                document.removeEventListener('mousemove', handleResize);
                document.removeEventListener('mouseup', stopResize);
            }
        });
    </script>
</x-app-layout>
