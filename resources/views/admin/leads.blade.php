<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js'])

    <div class="py-12">
        <div class="w-full px-6">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold">Leads</h1>
                    </div>

                    <!-- Grilla de leads -->
                    <div class="overflow-x-auto" style="max-height: calc(100vh - 250px);">
                        <table id="tablaLeads" class="min-w-full border-collapse bg-gray-700">
                            <thead class="bg-gray-600 sticky top-0 z-10">
                                <tr>
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
                                </tr>
                            </thead>
                            <tbody id="tablaLeadsBody" class="bg-gray-700 text-gray-200">
                                @forelse($leads as $lead)
                                    <tr class="hover:bg-gray-600">
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->nombre }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->apellido }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->dni }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->correo }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $lead->telefono }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="border border-gray-500 px-3 py-8 text-center text-gray-400">
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
                    'nombre': 1,
                    'apellido': 2,
                    'dni': 3,
                    'correo': 4,
                    'telefono': 5
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
