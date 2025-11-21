<x-app-layout>
    <!-- Toast Messages Container -->
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
                            <h1 class="text-2xl font-bold">Gestión de Programa</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Selecciona un curso para gestionar su programa
                            </p>
                        </div>
                    </div>

                    <!-- Selector de Curso -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Seleccionar Curso</label>
                        <form method="GET" action="{{ route('admin.programas') }}" class="flex gap-4">
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
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-white">Programa de: <span class="text-blue-400">{{ $cursoSeleccionado->nombre }}</span></h2>
                        </div>

                        @if($anios->count() > 0)
                            <div class="space-y-4">
                                @foreach($anios as $anio)
                                    <div class="bg-gray-700 rounded-lg p-4" x-data="{ expanded: false }">
                                        <!-- Header del Año -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 flex-1">
                                                <!-- Botones de orden -->
                                                <div class="flex flex-col space-y-1">
                                                    <button onclick="moverAnio({{ $anio->id }}, 'up')" 
                                                            class="mover-anio-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverAnio({{ $anio->id }}, 'down')" 
                                                            class="mover-anio-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                                
                                                <!-- Información del Año -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-lg font-bold text-white">{{ $anio->año }}° AÑO</span>
                                                        <span class="text-sm text-gray-300">{{ $anio->titulo }}</span>
                                                        <span class="px-2 py-1 bg-purple-600 text-white text-xs rounded">{{ $anio->nivel }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Botones de acción -->
                                            <div class="flex items-center space-x-2">
                                                <button @click="expanded = !expanded" 
                                                        class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-2 rounded text-sm transition-colors">
                                                    <span x-show="!expanded">Ver Unidades</span>
                                                    <span x-show="expanded">Ocultar</span>
                                                </button>
                                                <button onclick="editAnio({{ $anio->id }})" 
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('admin.programas.anios.destroy', $anio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este año y todas sus unidades?')" class="inline">
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

                                        <!-- Unidades (expandible) -->
                                        <div x-show="expanded" x-cloak class="mt-4 pt-4 border-t border-gray-600">
                                            <div class="flex justify-between items-center mb-3">
                                                <h3 class="text-sm font-medium text-gray-300">Unidades</h3>
                                                <button onclick="openModalUnidad({{ $anio->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                                    + Agregar Unidad
                                                </button>
                                            </div>
                                            
                                            @if($anio->unidades->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach($anio->unidades as $unidad)
                                                        <div class="bg-gray-600 rounded p-3 flex items-center justify-between">
                                                            <div class="flex items-center space-x-4 flex-1">
                                                                <!-- Botones de orden -->
                                                                <div class="flex flex-col space-y-1">
                                                                    <button onclick="moverUnidad({{ $unidad->id }}, 'up')" 
                                                                            class="mover-unidad-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                                            title="Mover arriba">
                                                                        ↑
                                                                    </button>
                                                                    <button onclick="moverUnidad({{ $unidad->id }}, 'down')" 
                                                                            class="mover-unidad-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                                            title="Mover abajo">
                                                                        ↓
                                                                    </button>
                                                                </div>
                                                                
                                                                <!-- Información de la Unidad -->
                                                                <div class="flex-1">
                                                                    <div class="font-medium text-white">{{ $unidad->numero }}: {{ $unidad->titulo }}</div>
                                                                    <div class="text-sm text-gray-300 mt-1">{{ $unidad->subtitulo }}</div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Botones de acción -->
                                                            <div class="flex items-center space-x-2">
                                                                <button onclick="editUnidad({{ $unidad->id }})" 
                                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                    </svg>
                                                                </button>
                                                                <form action="{{ route('admin.programas.unidades.destroy', $unidad->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta unidad?')" class="inline">
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
                                                <p class="text-sm text-gray-400">No hay unidades. Agrega la primera unidad.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-700 rounded-lg">
                                <p class="text-gray-400 mb-4">Este curso aún no tiene años en su programa.</p>
                            </div>
                        @endif

                        <div class="mt-6">
                            <button onclick="openModalAnio({{ $cursoSeleccionado->id }})" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                                + Agregar Año
                            </button>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-700 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">Selecciona un curso</h3>
                            <p class="mt-1 text-sm text-gray-500">Elige un curso del selector para gestionar su programa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Año -->
    <div id="anioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="anioModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Año</h3>
                    <button onclick="closeAnioModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="anioForm" action="{{ route('admin.programas.anios.store') }}" method="POST" onsubmit="return handleAnioFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="anioId" name="anio_id" value="">
                    <input type="hidden" id="anioMethodField" name="_method" value="">
                    <input type="hidden" id="anioCursoId" name="curso_id" value="">
            
                    @if($errors->any())
                        <div class="bg-red-600 text-white px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Año <span class="text-red-400">*</span></label>
                            <input type="number" id="anioAnio" name="año" min="1" max="10" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: 1, 2, 3" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Título <span class="text-red-400">*</span></label>
                            <input type="text" id="anioTitulo" name="titulo" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Analista técnico de motores" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nivel <span class="text-red-400">*</span></label>
                            <select id="anioNivel" name="nivel" 
                                    class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm" required>
                                <option value="">Selecciona un nivel</option>
                                <option value="Inicial">Inicial</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Avanzado">Avanzado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeAnioModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="anioSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Año
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Unidad -->
    <div id="unidadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="unidadModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Unidad</h3>
                    <button onclick="closeUnidadModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="unidadForm" action="{{ route('admin.programas.unidades.store') }}" method="POST" onsubmit="return handleUnidadFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="unidadId" name="unidad_id" value="">
                    <input type="hidden" id="unidadMethodField" name="_method" value="">
                    <input type="hidden" id="unidadCursoAnioId" name="curso_anio_id" value="">
            
                    @if($errors->any())
                        <div class="bg-red-600 text-white px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Número <span class="text-red-400">*</span></label>
                            <input type="text" id="unidadNumero" name="numero" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Unidad 1, Unidad 2" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Título <span class="text-red-400">*</span></label>
                            <input type="text" id="unidadTitulo" name="titulo" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Introducción al Taller y sus elementos" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Subtítulo <span class="text-red-400">*</span></label>
                            <textarea id="unidadSubtitulo" name="subtitulo" rows="3" 
                                      class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                                      placeholder="Ej: Normas de seguridad e higiene. Herramientas e instrumental." required></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeUnidadModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="unidadSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Unidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ========== FUNCIONES PARA AÑOS ==========
        
        function openModalAnio(cursoId) {
            resetAnioModal();
            document.getElementById('anioCursoId').value = cursoId;
            document.getElementById('anioModalTitle').textContent = 'Agregar Año';
            document.getElementById('anioSubmitBtn').textContent = 'Agregar Año';
            document.getElementById('anioForm').action = '{{ route("admin.programas.anios.store") }}';
            document.getElementById('anioMethodField').value = '';
            document.getElementById('anioModal').classList.remove('hidden');
        }

        function editAnio(anioId) {
            resetAnioModal();
            
            fetch(`/admin/programas/anios/${anioId}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('anioModalTitle').textContent = 'Editar Año';
                    document.getElementById('anioSubmitBtn').textContent = 'Actualizar Año';
                    document.getElementById('anioMethodField').value = 'PUT';
                    document.getElementById('anioId').value = data.id;
                    document.getElementById('anioCursoId').value = data.curso_id;
                    document.getElementById('anioForm').action = `/admin/programas/anios/${data.id}`;
                    
                    document.getElementById('anioAnio').value = data.año;
                    document.getElementById('anioTitulo').value = data.titulo;
                    document.getElementById('anioNivel').value = data.nivel;
                    
                    document.getElementById('anioModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error al cargar los datos del año:', error);
                    alert('Error al cargar los datos del año');
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
        }

        function handleAnioFormSubmit(event) {
            // Validación básica
            const año = document.getElementById('anioAnio').value;
            const titulo = document.getElementById('anioTitulo').value;
            const nivel = document.getElementById('anioNivel').value;
            
            if (!año || !titulo || !nivel) {
                alert('Por favor completa todos los campos obligatorios');
                return false;
            }
            
            return true;
        }

        function moverAnio(anioId, direccion) {
            const botones = document.querySelectorAll('.mover-anio-btn');
            botones.forEach(btn => btn.disabled = true);
            
            fetch(`/admin/programas/anios/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: anioId,
                    direccion: direccion
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error al mover el año');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al mover el año');
            })
            .finally(() => {
                botones.forEach(btn => btn.disabled = false);
            });
        }

        // ========== FUNCIONES PARA UNIDADES ==========
        
        function openModalUnidad(cursoAnioId) {
            resetUnidadModal();
            document.getElementById('unidadCursoAnioId').value = cursoAnioId;
            document.getElementById('unidadModalTitle').textContent = 'Agregar Unidad';
            document.getElementById('unidadSubmitBtn').textContent = 'Agregar Unidad';
            document.getElementById('unidadForm').action = '{{ route("admin.programas.unidades.store") }}';
            document.getElementById('unidadMethodField').value = '';
            document.getElementById('unidadModal').classList.remove('hidden');
        }

        function editUnidad(unidadId) {
            resetUnidadModal();
            
            fetch(`/admin/programas/unidades/${unidadId}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('unidadModalTitle').textContent = 'Editar Unidad';
                    document.getElementById('unidadSubmitBtn').textContent = 'Actualizar Unidad';
                    document.getElementById('unidadMethodField').value = 'PUT';
                    document.getElementById('unidadId').value = data.id;
                    document.getElementById('unidadCursoAnioId').value = data.curso_anio_id;
                    document.getElementById('unidadForm').action = `/admin/programas/unidades/${data.id}`;
                    
                    document.getElementById('unidadNumero').value = data.numero;
                    document.getElementById('unidadTitulo').value = data.titulo;
                    document.getElementById('unidadSubtitulo').value = data.subtitulo;
                    
                    document.getElementById('unidadModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error al cargar los datos de la unidad:', error);
                    alert('Error al cargar los datos de la unidad');
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
        }

        function handleUnidadFormSubmit(event) {
            // Validación básica
            const numero = document.getElementById('unidadNumero').value;
            const titulo = document.getElementById('unidadTitulo').value;
            const subtitulo = document.getElementById('unidadSubtitulo').value;
            
            if (!numero || !titulo || !subtitulo) {
                alert('Por favor completa todos los campos obligatorios');
                return false;
            }
            
            return true;
        }

        function moverUnidad(unidadId, direccion) {
            const botones = document.querySelectorAll('.mover-unidad-btn');
            botones.forEach(btn => btn.disabled = true);
            
            fetch(`/admin/programas/unidades/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: unidadId,
                    direccion: direccion
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error al mover la unidad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al mover la unidad');
            })
            .finally(() => {
                botones.forEach(btn => btn.disabled = false);
            });
        }

        // Auto-hide success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add('translate-x-full');
                }, 3000);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.add('translate-x-full');
                }, 5000);
            }
        });
    </script>
</x-app-layout>
