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
                    <div class="overflow-hidden rounded-xl border border-gray-700 shadow-lg">
                        <div class="overflow-x-auto" style="max-height: calc(100vh - 250px);">
                            <table id="tablaLeads" class="min-w-full border-collapse bg-gray-900 text-left text-sm text-gray-300">
                                <thead class="bg-gray-800 text-xs font-medium uppercase tracking-wider text-gray-400 sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 w-40 text-center">
                                            Intereses
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hover:text-white transition-colors group" data-sort="nombre">
                                            <div class="flex items-center gap-1">
                                                Usuario
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hover:text-white transition-colors group" data-sort="dni">
                                            <div class="flex items-center gap-1">
                                                DNI
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hover:text-white transition-colors group" data-sort="correo">
                                            <div class="flex items-center gap-1">
                                                Contacto
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-right cursor-pointer hover:text-white transition-colors group" data-sort="created_at">
                                            <div class="flex items-center justify-end gap-1">
                                                Registrado
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaLeadsBody" class="divide-y divide-gray-800 border-t border-gray-800">
                                    @forelse($leads as $lead)
                                        <!-- Fila Principal (Usuario) -->
                                        <tr class="hover:bg-gray-800 transition-colors duration-150 cursor-pointer group" onclick="toggleAccordion('{{ $lead->id }}')">
                                            <td class="px-4 py-3 text-center align-middle">
                                                <div class="relative inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-400 transition-colors ring-1 ring-gray-700">
                                                    <svg id="icon-{{ $lead->id }}" class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    @if($lead->cursadas->count() > 0)
                                                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-blue-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-gray-900">
                                                        {{ $lead->cursadas->count() }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <!-- Avatar Generado con Iniciales -->
                                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-900 to-indigo-900 flex items-center justify-center text-xs font-bold text-blue-200 ring-2 ring-gray-800">
                                                        {{ substr($lead->nombre, 0, 1) }}{{ substr($lead->apellido, 0, 1) }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-white font-medium text-sm">{{ $lead->nombre }} {{ $lead->apellido }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-mono text-xs text-gray-400 bg-gray-800/50 px-2 py-1 rounded border border-gray-700/50">
                                                    {{ $lead->dni }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col gap-1.5 items-start">
                                                    <a href="mailto:{{ $lead->correo }}" class="text-xs text-gray-300 hover:text-white hover:underline transition-colors flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        {{ $lead->correo }}
                                                    </a>
                                                    <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 12.284 3 6V5z"></path></svg>
                                                        {{ $lead->telefono }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex flex-col items-end">
                                                    <span class="text-sm text-gray-300 font-medium">
                                                        {{ $lead->created_at ? $lead->created_at->format('d/m/Y') : 'N/A' }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $lead->created_at ? $lead->created_at->diffForHumans() : '' }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Fila Secundaria (Historial - Oculta) -->
                                        <tr id="accordion-{{ $lead->id }}" class="hidden bg-gray-900 shadow-inner">
                                            <td colspan="5" class="px-0 py-0 border-t border-gray-800">
                                                <div class="px-6 py-4 bg-gray-900/50 shadow-inner border-y border-gray-800">
                                                    <div class="pl-12">
                                                        <h4 class="text-xs uppercase text-gray-500 font-bold mb-3 tracking-widest flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            Historial de Intereses
                                                        </h4>
                                                        <div class="overflow-hidden rounded-lg border border-gray-700">
                                                            <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                                                                <thead class="bg-gray-900 text-gray-400 text-xs uppercase">
                                                                    <tr>
                                                                        <th class="px-4 py-3 font-medium text-left">Curso / Carrera</th>
                                                                        <th class="px-4 py-3 font-medium text-left">Tipo</th>
                                                                        <th class="px-4 py-3 font-medium text-center">Términos</th>
                                                                        <th class="px-4 py-3 font-medium text-right">Fecha</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-700 text-sm text-gray-300">
                                                                    @foreach($lead->cursadas as $cursada)
                                                                    <tr class="hover:bg-gray-700/50 transition-colors">
                                                                        <td class="px-4 py-3 font-medium text-blue-300">
                                                                            {{ $cursada->cursada_id ?? 'N/A' }}
                                                                        </td>
                                                                        <td class="px-4 py-3">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                                                                {{ $cursada->tipo ?? 'Lead' }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="px-4 py-3 text-center">
                                                                            @if($cursada->acepto_terminos)
                                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-green-900/30 text-green-400 border border-green-800">
                                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                                    Aceptado
                                                                                </span>
                                                                            @else
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-500 border border-gray-600">
                                                                                    Pendiente
                                                                                </span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="px-4 py-3 text-right text-gray-400 font-mono text-xs">
                                                                            {{ $cursada->created_at ? $cursada->created_at->format('d/m/Y H:i') : 'N/A' }}
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center text-gray-500">
                                                    <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                    <span class="text-lg font-medium">No hay leads registrados aún</span>
                                                    <span class="text-sm mt-1">Los nuevos registros aparecerán aquí automáticamente</span>
                                                </div>
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
        // Funcionalidad de Acordeón
        function toggleAccordion(leadId) {
            const row = document.getElementById('accordion-' + leadId);
            const icon = document.getElementById('icon-' + leadId);
            
            if (row.classList.contains('hidden')) {
                // Abrir
                row.classList.remove('hidden');
                // Rotar ícono 90 grados
                icon.classList.add('rotate-90');
                // Highlight fila padre
                row.previousElementSibling.classList.add('bg-gray-600');
            } else {
                // Cerrar
                row.classList.add('hidden');
                // Resetear rotación
                icon.classList.remove('rotate-90');
                // Remover highlight
                row.previousElementSibling.classList.remove('bg-gray-600');
            }
        }

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
