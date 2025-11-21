    <x-app-layout>
        <!-- SortableJS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        
        <!-- Rutas para JavaScript -->
        <script>
            window.modalidadesRoutes = {
                store: '{{ route("admin.modalidades.columnas.store") }}',
                tiposStore: '{{ route("admin.modalidades.tipos.store") }}',
                horariosStore: '{{ route("admin.modalidades.horarios.store") }}'
            };
        </script>
        
        <style>
            .columna-item {
                transition: all 0.2s ease;
            }
            .columna-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            }
            .columna-item.sortable-ghost {
                opacity: 0.4;
                background-color: #4b5563;
            }
            .columna-item.sortable-drag {
                opacity: 0.8;
            }
            .drag-handle {
                cursor: grab;
            }
            .drag-handle:active {
                cursor: grabbing;
            }
            /* Evitar reajustes visuales al colapsar */
            [x-cloak] {
                display: none !important;
            }
            /* Asegurar que el grid mantenga su estructura */
            [id^="columnas-container-"] {
                min-height: 0;
            }
        </style>
    

    <!-- Toast Messages Container - Completely outside layout -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div id="success-message" class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div id="error-message" class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Gestión de Modalidades</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Selecciona un curso para gestionar sus modalidades, columnas, tipos y horarios
                            </p>
                        </div>
                    </div>

                    <!-- Selector de Curso -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Seleccionar Curso</label>
                        <form method="GET" action="{{ route('admin.modalidades') }}" class="flex gap-4">
                            <select name="curso_id" 
                                    onchange="this.form.submit()" 
                                    class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[300px]">
                                <option value="">-- Selecciona un curso --</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    @if($cursoSeleccionado)
                        <div class="mb-4 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-white">Modalidades de: <span class="text-blue-400">{{ $cursoSeleccionado->nombre }}</span></h2>
                            <button onclick="openModalidadCreate({{ $cursoSeleccionado->id }})" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Nueva Modalidad</span>
                            </button>
                        </div>

                        @if($modalidades->count() > 0)
                            <div class="space-y-4">
                                @foreach($modalidades as $modalidad)
                                    <div class="bg-gray-700 rounded-lg p-4" 
                                         x-data="{ expanded: false }">
                                        <!-- Header de la Modalidad -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 flex-1">
                                                <!-- Información de la Modalidad -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-lg font-bold text-white">
                                                            @if($modalidad->nombre_linea2)
                                                                {{ strtoupper($modalidad->nombre_linea1) }} {{ strtoupper($modalidad->nombre_linea2) }}
                                                            @else
                                                                {{ strtoupper($modalidad->nombre_linea1 ?? $modalidad->nombre) }}
                                                            @endif
                                                        </span>
                                                        <span onclick="toggleModalidadActivo({{ $modalidad->id }}, {{ $modalidad->activo ? 'true' : 'false' }})" 
                                                              class="px-2 py-1 text-white text-xs rounded cursor-pointer transition-colors hover:opacity-80 {{ $modalidad->activo ? 'bg-green-600' : 'bg-red-600' }}"
                                                              id="modalidad-badge-{{ $modalidad->id }}">
                                                            {{ $modalidad->activo ? 'Activa' : 'Inactiva' }}
                                                        </span>
                                                    </div>
                                                    @if($modalidad->texto_info)
                                                        <div class="text-sm text-gray-300 mt-1">{{ Str::limit($modalidad->texto_info, 80) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Botones de acción -->
                                            <div class="flex items-center space-x-2">
                                                <button @click="expanded = !expanded" 
                                                        class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-2 rounded text-sm transition-colors">
                                                    <span x-show="!expanded">Ver Detalles</span>
                                                    <span x-show="expanded">Ocultar</span>
                                                </button>
                                                <button onclick="editModalidad({{ $modalidad->id }})" 
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="deleteModalidad({{ $modalidad->id }})" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Detalles expandibles -->
                                        <div x-show="expanded" 
                                             x-cloak
                                             class="mt-4 pt-4 border-t border-gray-600 space-y-4">
                                            <!-- Columnas -->
                                            <div>
                                                <div class="flex justify-between items-center mb-3">
                                                    <h3 class="text-sm font-medium text-gray-300">Columnas del Header ({{ $modalidad->columnas->count() }}/6)</h3>
                                                    @if($modalidad->columnas->count() < 6)
                                                        <button onclick="openModalColumna({{ $modalidad->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                                            + Agregar Columna
                                                        </button>
                                                    @else
                                                        <span class="text-xs text-gray-400">Máximo alcanzado</span>
                                                    @endif
                                                </div>
                                                
                                                @if($modalidad->columnas->count() > 0)
                                                    <div id="columnas-container-{{ $modalidad->id }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                        @foreach($modalidad->columnas as $columna)
                                                            <div class="columna-item bg-gray-600 rounded p-3 flex items-center justify-between cursor-move hover:bg-gray-500 transition-colors" 
                                                                 data-columna-id="{{ $columna->id }}"
                                                                 data-modalidad-id="{{ $modalidad->id }}">
                                                                <div class="flex items-center space-x-3 flex-1">
                                                                    <svg class="w-5 h-5 text-gray-400 drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                                    </svg>
                                                                    @if($columna->icono)
                                                                        <img src="{{ $columna->icono }}" alt="{{ $columna->nombre }}" class="w-8 h-8 object-contain">
                                                                    @endif
                                                                    <div>
                                                                        <div class="font-medium text-white text-sm">{{ $columna->nombre }}</div>
                                                                        <div class="text-xs text-gray-400">{{ $columna->campo_dato }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center space-x-2">
                                                                    <button onclick="editColumna({{ $columna->id }})" 
                                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                        </svg>
                                                                    </button>
                                                                    <form action="{{ route('admin.modalidades.columnas.destroy', $columna->id) }}" method="POST" onsubmit="return confirmDeleteColumna(event)" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-400">No hay columnas. Agrega la primera columna.</p>
                                                @endif
                                            </div>

                                            <!-- Tipos -->
                                            <div>
                                                <div class="flex justify-between items-center mb-3">
                                                    <h3 class="text-sm font-medium text-gray-300">Tipos ({{ $modalidad->tipos->count() }})</h3>
                                                    <button onclick="openModalTipo({{ $modalidad->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                                        + Agregar Tipo
                                                    </button>
                                                </div>
                                                
                                                @if($modalidad->tipos->count() > 0)
                                                    <div class="space-y-2">
                                                        @foreach($modalidad->tipos as $tipo)
                                                            <div class="bg-gray-600 rounded p-3 flex items-center justify-between">
                                                                <div class="flex-1">
                                                                    <div class="flex items-center space-x-3">
                                                                        <div class="font-medium text-white">{{ $tipo->nombre }}</div>
                                                                        <span onclick="toggleTipoActivo({{ $tipo->id }}, {{ $tipo->activo ?? true ? 'true' : 'false' }})" 
                                                                              class="px-2 py-1 text-white text-xs rounded cursor-pointer transition-colors hover:opacity-80 {{ ($tipo->activo ?? true) ? 'bg-green-600' : 'bg-red-600' }}"
                                                                              id="tipo-badge-{{ $tipo->id }}">
                                                                            {{ ($tipo->activo ?? true) ? 'Activo' : 'Inactivo' }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="text-sm text-gray-300 mt-1">
                                                                        {{ $tipo->duracion }} | {{ $tipo->dedicacion }} | {{ $tipo->clases_semana }} | {{ $tipo->mes_inicio }}
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center space-x-2">
                                                                    <button onclick="editTipo({{ $tipo->id }})" 
                                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                        </svg>
                                                                    </button>
                                                                    <form action="{{ route('admin.modalidades.tipos.destroy', $tipo->id) }}" method="POST" onsubmit="return confirmDeleteTipo(event)" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-400">No hay tipos. Agrega el primer tipo.</p>
                                                @endif
                                            </div>

                                            <!-- Horarios -->
                                            <div>
                                                <div class="flex justify-between items-center mb-3">
                                                    <h3 class="text-sm font-medium text-gray-300">Horarios ({{ $modalidad->horarios->count() }})</h3>
                                                    <button onclick="openModalHorario({{ $modalidad->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                                        + Agregar Horario
                                                    </button>
                                                </div>
                                                
                                                @if($modalidad->horarios->count() > 0)
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                        @foreach($modalidad->horarios as $horario)
                                                            <div class="bg-gray-600 rounded p-3 flex items-center justify-between">
                                                                <div class="flex items-center space-x-3 flex-1">
                                                                    @if($horario->icono)
                                                                        <img src="{{ $horario->icono }}" alt="{{ $horario->nombre }}" class="w-8 h-8 object-contain">
                                                                    @endif
                                                                    <div>
                                                                        <div class="font-medium text-white text-sm">{{ $horario->nombre }}</div>
                                                                        <div class="text-xs text-gray-400">{{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center space-x-2">
                                                                    <button onclick="editHorario({{ $horario->id }})" 
                                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                        </svg>
                                                                    </button>
                                                                    <form action="{{ route('admin.modalidades.horarios.destroy', $horario->id) }}" method="POST" onsubmit="return confirmDeleteHorario(event)" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-400">No hay horarios. Agrega el primer horario.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-700 rounded-lg">
                                <p class="text-gray-400 mb-4">Este curso aún no tiene modalidades configuradas.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12 bg-gray-700 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">Selecciona un curso</h3>
                            <p class="mt-1 text-sm text-gray-500">Elige un curso del selector para gestionar sus modalidades.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Modalidad -->
    <div id="modalidadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="modalidadModalTitle" class="text-sm sm:text-lg font-semibold text-white">Editar Modalidad</h3>
                    <button onclick="closeModalidadModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="modalidadForm" action="" method="POST" onsubmit="return handleModalidadFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="modalidadId" name="modalidad_id" value="">
                    <input type="hidden" id="modalidadCursoId" name="curso_id" value="">
                    <input type="hidden" id="modalidadMethodField" name="_method" value="PUT">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre - Línea 1 <span class="text-red-400">*</span></label>
                            <input type="text" id="modalidadNombreLinea1" name="nombre_linea1" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: PRESENCIAL" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre - Línea 2 (opcional)</label>
                            <input type="text" id="modalidadNombreLinea2" name="nombre_linea2" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: PRESENCIAL (si es muy largo, se mostrará en 2 líneas)">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Texto Informativo</label>
                            <textarea id="modalidadTextoInfo" name="texto_info" rows="3" 
                                      class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                                      placeholder="Ej: Podes elegir un día entre martes y sábados..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeModalidadModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="modalidadSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Actualizar Modalidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Columna -->
    <div id="columnaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="columnaModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Columna</h3>
                    <button onclick="closeColumnaModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="columnaForm" action="{{ route('admin.modalidades.columnas.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleColumnaFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="columnaId" name="columna_id" value="">
                    <input type="hidden" id="columnaMethodField" name="_method" value="">
                    <input type="hidden" id="columnaModalidadId" name="modalidad_id" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre <span class="text-red-400">*</span></label>
                            <input type="text" id="columnaNombre" name="nombre" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Duración, Dedicación, Clases" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Icono <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <div>
                                    <input type="file" 
                                           id="columnaIconoFile" 
                                           name="icono_file" 
                                           accept="image/*"
                                           data-no-auto-submit="true"
                                           class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600"
                                           required>
                                </div>
                                <div id="columnaIconoPreview" class="hidden mt-2">
                                    <p class="text-xs text-gray-400 mb-1">Vista previa:</p>
                                    <img id="columnaIconoPreviewImg" src="" alt="Preview" class="w-16 h-16 object-contain border border-gray-600 rounded">
                                </div>
                            </div>
                            <input type="hidden" id="columnaIcono" name="icono" value="">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Campo de Dato <span class="text-red-400">*</span></label>
                            <select id="columnaCampoDato" name="campo_dato" 
                                    class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm" required>
                                <option value="">Selecciona un campo</option>
                                <option value="duracion">duracion</option>
                                <option value="dedicacion">dedicacion</option>
                                <option value="clases_semana">clases_semana</option>
                                <option value="horas_presenciales">horas_presenciales</option>
                                <option value="horas_virtuales">horas_virtuales</option>
                                <option value="horas_teoria">horas_teoria</option>
                                <option value="horas_practica">horas_practica</option>
                                <option value="mes_inicio">mes_inicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeColumnaModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="columnaSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Columna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Tipo -->
    <div id="tipoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="tipoModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Tipo</h3>
                    <button onclick="closeTipoModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="tipoForm" action="{{ route('admin.modalidades.tipos.store') }}" method="POST" onsubmit="return handleTipoFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="tipoId" name="tipo_id" value="">
                    <input type="hidden" id="tipoMethodField" name="_method" value="">
                    <input type="hidden" id="tipoModalidadId" name="modalidad_id" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre <span class="text-red-400">*</span></label>
                            <input type="text" id="tipoNombre" name="nombre" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: REGULAR, INTENSIVO, DESFASADO" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Duración <span class="text-red-400">*</span></label>
                                <input type="text" id="tipoDuracion" name="duracion" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 10 meses" required>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Mes de Inicio <span class="text-red-400">*</span></label>
                                <input type="text" id="tipoMesInicio" name="mes_inicio" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: Marzo, Agosto" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Dedicación <span class="text-red-400">*</span></label>
                            <input type="text" id="tipoDedicacion" name="dedicacion" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: 3hs y media cada clase" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Clases por Semana <span class="text-red-400">*</span></label>
                            <input type="text" id="tipoClasesSemana" name="clases_semana" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: 1 x semana" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Horas Presenciales</label>
                                <input type="text" id="tipoHorasPresenciales" name="horas_presenciales" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 110hs presenciales">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Horas Virtuales</label>
                                <input type="text" id="tipoHorasVirtuales" name="horas_virtuales" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 70hs virtuales">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Horas Teoría</label>
                                <input type="text" id="tipoHorasTeoria" name="horas_teoria" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 70hs virtuales">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Horas Práctica</label>
                                <input type="text" id="tipoHorasPractica" name="horas_practica" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 110hs presenciales">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeTipoModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="tipoSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Tipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Horario -->
    <div id="horarioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="horarioModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Horario</h3>
                    <button onclick="closeHorarioModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="horarioForm" action="{{ route('admin.modalidades.horarios.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleHorarioFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="horarioId" name="horario_id" value="">
                    <input type="hidden" id="horarioMethodField" name="_method" value="">
                    <input type="hidden" id="horarioModalidadId" name="modalidad_id" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre <span class="text-red-400">*</span></label>
                            <input type="text" id="horarioNombre" name="nombre" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Mañana, Tarde, Noche" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Hora Inicio <span class="text-red-400">*</span></label>
                                <input type="text" id="horarioHoraInicio" name="hora_inicio" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 9:00" required>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Hora Fin <span class="text-red-400">*</span></label>
                                <input type="text" id="horarioHoraFin" name="hora_fin" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: 12:30" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Icono</label>
                            <div class="space-y-2">
                                <div>
                                    <input type="file" 
                                           id="horarioIconoFile" 
                                           name="icono_file" 
                                           accept="image/*"
                                           data-no-auto-submit="true"
                                           class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                                </div>
                                <div id="horarioIconoPreview" class="hidden mt-2">
                                    <p class="text-xs text-gray-400 mb-1">Vista previa:</p>
                                    <img id="horarioIconoPreviewImg" src="" alt="Preview" class="w-16 h-16 object-contain border border-gray-600 rounded">
                                </div>
                            </div>
                            <input type="hidden" id="horarioIcono" name="icono" value="">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeHorarioModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="horarioSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Horario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
