<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js'])
    

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
                            <h1 class="text-2xl font-bold">Gestión de Carreras</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Carreras visibles en el home: {{ $cursos->where('featured', true)->count() }}/2
                            </p>
                        </div>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Carrera
                        </button>
                    </div>
                    
                    @if($cursos->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-20">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Desktop</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Mobile</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Modalidad</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Fecha Inicio</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($cursos as $curso)
                                        <tr>
                                            <!-- Columna de Orden -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverCurso({{ $curso->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverCurso({{ $curso->id }}, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            <!-- Desktop Thumbnail -->
                                            <td class="px-4 py-4">
                                                <div class="relative group" 
                                                     x-data="{ showTooltip: false }" 
                                                     @mouseenter="showTooltip = true" 
                                                     @mouseleave="showTooltip = false">
                                                    <div class="w-16 h-16 bg-gray-500 rounded overflow-hidden mx-auto cursor-pointer">
                                                        @if($curso->ilustracion_desktop)
                                                            <img src="{{ asset('storage/' . $curso->ilustracion_desktop) }}" 
                                                                 alt="Desktop" 
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Tooltip con imagen -->
                                                    <div x-show="showTooltip" 
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-95"
                                                         class="fixed bottom-auto left-1/2 transform -translate-x-1/2 mb-2 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden pointer-events-none"
                                                         style="z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                        @if($curso->ilustracion_desktop)
                                                            <div class="relative">
                                                                <img src="{{ asset('storage/' . $curso->ilustracion_desktop) }}" 
                                                                     alt="Desktop Preview" 
                                                                     class="max-w-xs max-h-64 object-contain">
                                                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-3">
                                                                    <div class="font-medium text-sm">Desktop: {{ $curso->nombre }}</div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen desktop</div>
                                                                    <div class="text-xs text-gray-400">{{ $curso->nombre }}</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Mobile Thumbnail -->
                                            <td class="px-4 py-4">
                                                <div class="relative group" 
                                                     x-data="{ showTooltip: false }" 
                                                     @mouseenter="showTooltip = true" 
                                                     @mouseleave="showTooltip = false">
                                                    <div class="w-16 h-16 bg-gray-500 rounded overflow-hidden mx-auto cursor-pointer">
                                                        @if($curso->ilustracion_mobile)
                                                            <img src="{{ asset('storage/' . $curso->ilustracion_mobile) }}" 
                                                                 alt="Mobile" 
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Tooltip con imagen -->
                                                    <div x-show="showTooltip" 
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-95"
                                                         class="fixed bottom-auto left-1/2 transform -translate-x-1/2 mb-2 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden pointer-events-none"
                                                         style="z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                        @if($curso->ilustracion_mobile)
                                                            <div class="relative">
                                                                <img src="{{ asset('storage/' . $curso->ilustracion_mobile) }}" 
                                                                     alt="Mobile Preview" 
                                                                     class="max-w-xs max-h-64 object-contain">
                                                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-3">
                                                                    <div class="font-medium text-sm">Mobile: {{ $curso->nombre }}</div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen mobile</div>
                                                                    <div class="text-xs text-gray-400">{{ $curso->nombre }}</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Nombre -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-white">{{ $curso->nombre }}</div>
                                            </td>
                                            
                                            <!-- Modalidades -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @if($curso->modalidad_online)
                                                        <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">Online</span>
                                                    @endif
                                                    @if($curso->modalidad_presencial)
                                                        <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">Presencial</span>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <!-- Fecha de inicio -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-300">{{ $curso->fecha_inicio->format('d/m/Y') }}</div>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <!-- Editar -->
                                                    <button onclick="openEditModal({{ $curso->id }}, '{{ $curso->nombre }}', '{{ addslashes($curso->descripcion) }}', {{ $curso->modalidad_online ? 'true' : 'false' }}, {{ $curso->modalidad_presencial ? 'true' : 'false' }}, '{{ $curso->fecha_inicio->format('Y-m-d') }}', '{{ $curso->ilustracion_desktop }}', '{{ $curso->ilustracion_mobile }}')" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Featured toggle -->
                                                    @php
                                                        $featuredCount = $cursos->where('featured', true)->count();
                                                        $canSelect = !$curso->featured && $featuredCount < 2;
                                                        $canDeselect = $curso->featured;
                                                        $canToggle = $canSelect || $canDeselect;
                                                    @endphp
                                                    <form action="{{ route('admin.carreras.toggle-featured', $curso->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="{{ $curso->featured ? 'bg-yellow-500 hover:bg-yellow-600' : ($canSelect ? 'bg-gray-400 hover:bg-gray-500' : 'bg-gray-500 cursor-not-allowed') }} text-white px-3 py-2 rounded text-sm transition-colors"
                                                                {{ $canToggle ? '' : 'disabled' }}>
                                                            @if($curso->featured)
                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                                </svg>
                                                            @endif
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Eliminar -->
                                                    <form action="{{ route('admin.carreras.destroy', $curso->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay carreras</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando una nueva carrera.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Carrera -->
    <div id="modalAgregarCarrera" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2">
            <div class="bg-gray-800 rounded-lg p-4 w-full max-w-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white">Agregar Nueva Carrera</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="modalForm" action="{{ route('admin.carreras.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateModalidades(event)">
                    @csrf
                    <input type="hidden" id="cursoId" name="curso_id" value="">
                    
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
                        <!-- Fila 1: Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Curso <span class="text-red-400">*</span></label>
                            <input type="text" id="modalNombre" name="nombre" 
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Fila 2: Descripción -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
                            <textarea id="modalDescripcion" name="descripcion" rows="2"
                                      class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Descripción del curso..."></textarea>
                        </div>
                        
                        <!-- Fila 3: Modalidades (izq) y Fecha (der) -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Modalidades -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Modalidades <span class="text-red-400">*</span></label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="modalOnline" name="modalidad_online" value="1" 
                                               class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-300">Online</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="modalPresencial" name="modalidad_presencial" value="1" 
                                               class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-300">Presencial</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Selecciona al menos una modalidad</p>
                            </div>
                            
                            <!-- Fecha de inicio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha de Inicio <span class="text-red-400">*</span></label>
                                <input type="date" id="modalFecha" name="fecha_inicio" 
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- Fila 4: Ilustración Desktop (izq) y Mobile (der) -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Ilustración Desktop -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Ilustración Desktop <span class="text-red-400" id="desktopRequired">*</span></label>
                                <input type="file" id="modalDesktop" name="ilustracion_desktop" accept="image/*" 
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1" id="desktopHelp">Recomendado: 768 × 418 px</p>
                            </div>
                            
                            <!-- Ilustración Mobile -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Ilustración Mobile <span class="text-red-400" id="mobileRequired">*</span></label>
                                <input type="file" id="modalMobile" name="ilustracion_mobile" accept="image/*" 
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1" id="mobileHelp">Recomendado: 388 × 429 px</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="resetModal(); closeModal();" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" id="modalSubmitBtn"
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors">
                            Agregar Carrera
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            if (confirm('¿Estás seguro de que quieres eliminar esta carrera?')) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        }

        function openModal() {
            // Reset form for new course
            resetModal();
            document.getElementById('modalTitle').textContent = 'Agregar Nueva Carrera';
            document.getElementById('modalForm').action = '{{ route("admin.carreras.store") }}';
            document.getElementById('modalForm').method = 'POST';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Carrera';
            document.getElementById('desktopRequired').style.display = 'inline';
            document.getElementById('mobileRequired').style.display = 'inline';
            document.getElementById('desktopHelp').textContent = 'Recomendado: 768 × 418 px';
            document.getElementById('mobileHelp').textContent = 'Recomendado: 388 × 429 px';
            document.getElementById('modalAgregarCarrera').classList.remove('hidden');
        }

        function openEditModal(id, nombre, descripcion, online, presencial, fecha, desktopImg, mobileImg) {
            // Fill form with course data
            document.getElementById('cursoId').value = id;
            document.getElementById('modalNombre').value = nombre;
            document.getElementById('modalDescripcion').value = descripcion || '';
            document.getElementById('modalOnline').checked = online;
            document.getElementById('modalPresencial').checked = presencial;
            document.getElementById('modalFecha').value = fecha;
            
            // Clear file inputs
            document.getElementById('modalDesktop').value = '';
            document.getElementById('modalMobile').value = '';
            
            // Update modal for editing
            document.getElementById('modalTitle').textContent = 'Editar Carrera';
            document.getElementById('modalForm').action = `/admin/carreras/${id}`;
            document.getElementById('modalForm').method = 'POST';
            
            // Remove existing _method input if it exists
            const existingMethod = document.querySelector('input[name="_method"]');
            if (existingMethod) {
                existingMethod.remove();
            }
            
            // Add _method input for PUT request
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            document.getElementById('modalForm').appendChild(methodInput);
            
            document.getElementById('modalSubmitBtn').textContent = 'Actualizar Carrera';
            document.getElementById('desktopRequired').style.display = 'none';
            document.getElementById('mobileRequired').style.display = 'none';
            document.getElementById('desktopHelp').textContent = 'Dejar vacío para mantener la imagen actual';
            document.getElementById('mobileHelp').textContent = 'Dejar vacío para mantener la imagen actual';
            
            document.getElementById('modalAgregarCarrera').classList.remove('hidden');
        }

        function resetModal() {
            document.getElementById('modalNombre').value = '';
            document.getElementById('modalDescripcion').value = '';
            document.getElementById('modalOnline').checked = false;
            document.getElementById('modalPresencial').checked = false;
            document.getElementById('modalFecha').value = '';
            document.getElementById('modalDesktop').value = '';
            document.getElementById('modalMobile').value = '';
            document.getElementById('cursoId').value = '';
            
            // Reset modal to create mode
            document.getElementById('modalTitle').textContent = 'Agregar Carrera';
            document.getElementById('modalForm').action = '/admin/carreras';
            document.getElementById('modalForm').method = 'POST';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Carrera';
            document.getElementById('desktopRequired').style.display = 'inline';
            document.getElementById('mobileRequired').style.display = 'inline';
            document.getElementById('desktopHelp').textContent = 'Recomendado: 768 × 418 px';
            document.getElementById('mobileHelp').textContent = 'Recomendado: 388 × 429 px';
            
            // Remove _method input if it exists
            const existingMethod = document.querySelector('input[name="_method"]');
            if (existingMethod) {
                existingMethod.remove();
            }
        }
        
        function closeModal() {
            document.getElementById('modalAgregarCarrera').classList.add('hidden');
        }
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('modalAgregarCarrera').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Sistema de botones arriba/abajo
        function moverCurso(cursoId, direccion) {
            console.log('Moviendo curso:', { cursoId, direccion });
            
            // Deshabilitar todos los botones temporalmente
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: cursoId,
                direccion: direccion
            };
            
            console.log('Enviando datos:', requestData);
            
            fetch(`/admin/carreras/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Recargar inmediatamente para mostrar el nuevo orden
                    location.reload();
                } else {
                    throw new Error(data.message || 'Error al mover la carrera');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Mostrar mensaje de error
                showMessage(error.message, 'error');
            })
            .finally(() => {
                // Rehabilitar botones
                botones.forEach(btn => btn.disabled = false);
            });
        }
        
        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg border-l-4 ${
                type === 'success' ? 'bg-green-500 text-white border-green-400' : 'bg-red-500 text-white border-red-400'
            } flex items-center transform translate-x-full transition-transform duration-300 ease-in-out`;
            
            messageDiv.innerHTML = `
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                ${message}
            `;
            
            document.body.appendChild(messageDiv);
            
            // Animar entrada
            setTimeout(() => {
                messageDiv.classList.remove('translate-x-full');
            }, 100);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                messageDiv.classList.add('translate-x-full');
                setTimeout(() => {
                    messageDiv.remove();
                }, 300);
            }, 3000);
        }
        
        
    </script>


</x-app-layout>
