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
                        <h1 class="text-2xl font-bold">Gestión de Beneficios</h1>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Beneficio
                        </button>
                    </div>

                    @if($beneficios->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Desktop</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Mobile</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Título Línea 1</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Título Línea 2</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Descripción</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Link</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($beneficios->sortBy('orden') as $beneficio)
                                        <tr>
                                            <!-- Orden -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverBeneficio({{ $beneficio->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverBeneficio({{ $beneficio->id }}, 'down')" 
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
                                                        @if($beneficio->imagen_desktop)
                                                            <img src="{{ asset('storage/' . $beneficio->imagen_desktop) }}" 
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
                                                        @if($beneficio->imagen_desktop)
                                                            <img src="{{ asset('storage/' . $beneficio->imagen_desktop) }}" 
                                                                 alt="Desktop Preview" 
                                                                 class="max-w-xs max-h-64 object-contain">
                                                        @else
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen desktop</div>
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
                                                        @if($beneficio->imagen_mobile)
                                                            <img src="{{ asset('storage/' . $beneficio->imagen_mobile) }}" 
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
                                                        @if($beneficio->imagen_mobile)
                                                            <img src="{{ asset('storage/' . $beneficio->imagen_mobile) }}" 
                                                                 alt="Mobile Preview" 
                                                                 class="max-w-xs max-h-64 object-contain">
                                                        @else
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen mobile</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Título Línea 1 -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-white">{{ $beneficio->titulo_linea1 }}</div>
                                            </td>
                                            
                                            <!-- Título Línea 2 -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-white">{{ $beneficio->titulo_linea2 }}</div>
                                            </td>
                                            
                                            <!-- Descripción -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-300 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap">
                                                    {!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($beneficio->descripcion)) !!}
                                                </div>
                                            </td>
                                            
                                            <!-- Link -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-300">
                                                    @if($beneficio->tipo_accion === 'link' && $beneficio->url && $beneficio->texto_boton)
                                                        <!-- Es un link con texto personalizado -->
                                                        <div>
                                                            <div class="text-gray-300">
                                                                <span class="text-gray-400">Texto visible: </span>{{ $beneficio->texto_boton }}
                                                            </div>
                                                            <div class="text-xs text-gray-400 mt-1">{{ Str::limit($beneficio->url, 40) }}</div>
                                                        </div>
                                                    @elseif($beneficio->tipo_accion === 'button' && $beneficio->url)
                                                        <!-- Es un botón -->
                                                        <div>
                                                            <div class="text-gray-300">Botón</div>
                                                            <div class="text-xs text-gray-400 mt-1">{{ Str::limit($beneficio->url, 40) }}</div>
                                                        </div>
                                                    @elseif($beneficio->tipo_accion === 'none')
                                                        <!-- Sin link -->
                                                        <span class="text-gray-500">Sin link</span>
                                                    @else
                                                        <!-- Fallback para datos legacy -->
                                                        @if($beneficio->url && $beneficio->link)
                                                            <div>
                                                                <div class="text-gray-300">
                                                                    <span class="text-gray-400">Texto visible: </span>{{ $beneficio->link }}
                                                                </div>
                                                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($beneficio->url, 40) }}</div>
                                                            </div>
                                                        @elseif($beneficio->url && !$beneficio->link)
                                                            <div>
                                                                <div class="text-gray-300">Botón</div>
                                                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($beneficio->url, 40) }}</div>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-500">Sin link</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <!-- Editar -->
                                                    <button onclick="editBeneficio({{ $beneficio->id }})" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Eliminar -->
                                                    <form action="{{ route('admin.beneficios.destroy', $beneficio->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay beneficios</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando tu primer beneficio.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Beneficio -->
    <div id="modalAgregarBeneficio" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-[240px] sm:max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="modalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Nuevo Beneficio</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                        <form id="modalForm" action="{{ route('admin.beneficios.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                            @csrf
                            <input type="hidden" id="beneficioId" name="beneficio_id" value="">
                            <input type="hidden" id="methodField" name="_method" value="">
                    
                    @if($errors->any())
                        <div class="bg-red-600 text-white px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Diseño Ultra Compacto -->
                    <div class="space-y-4">
                        <!-- Primera fila: Títulos -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Título Línea 1 <span class="text-red-400">*</span></label>
                                <input type="text" id="modalTituloLinea1" name="titulo_linea1" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: Club">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Título Línea 2 <span class="text-red-400">*</span></label>
                                <input type="text" id="modalTituloLinea2" name="titulo_linea2" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                       placeholder="Ej: ITCA">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center text-xs text-gray-300">
                                    <input type="checkbox" id="modalTipoTitulo" name="tipo_titulo" value="small" 
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    Línea 2 tamaño reducido
                                </label>
                            </div>
                        </div>

                        <!-- Segunda fila: Descripción -->
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Descripción <span class="text-red-400">*</span></label>
                            <textarea id="modalDescripcion" name="descripcion" rows="2" 
                                      class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                                      placeholder="Escribe la descripción..."></textarea>
                        </div>

                        <!-- Tercera fila: Tipo de Acción -->
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-2">Tipo de Acción <span class="text-red-400">*</span></label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="tipo_accion" value="none" checked 
                                           class="mr-2 text-blue-600 focus:ring-blue-500" 
                                           onchange="toggleActionFields()">
                                    <span class="text-sm text-gray-300">Sin acción</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tipo_accion" value="button" 
                                           class="mr-2 text-blue-600 focus:ring-blue-500" 
                                           onchange="toggleActionFields()">
                                    <span class="text-sm text-gray-300">Botón</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tipo_accion" value="link" 
                                           class="mr-2 text-blue-600 focus:ring-blue-500" 
                                           onchange="toggleActionFields()">
                                    <span class="text-sm text-gray-300">Link</span>
                                </label>
                            </div>
                        </div>

                        <!-- Campos condicionales -->
                        <div id="urlAndTextFields" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-300 mb-1">URL <span class="text-red-400">*</span></label>
                                    <input type="url" id="modalUrlNew" name="url" 
                                           class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                           placeholder="https://ejemplo.com">
                                </div>
                                
                                <div id="textoBotonField">
                                    <label class="block text-xs font-medium text-gray-300 mb-1">Texto del Enlace <span class="text-red-400">*</span></label>
                                    <input type="text" id="modalTextoBoton" name="texto_boton" 
                                           class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                           placeholder="Ej: Ir a tienda nube">
                                </div>
                            </div>
                        </div>

                        <!-- Cuarta fila: Imágenes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Imagen Desktop <span class="text-red-400" id="desktopRequired">*</span></label>
                                <input type="file" id="modalDesktop" name="imagen_desktop" accept="image/*" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                                <p class="text-xs text-gray-400 mt-1" id="desktopHelp">378 × 420 px</p>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Imagen Mobile <span class="text-red-400" id="mobileRequired">*</span></label>
                                <input type="file" id="modalMobile" name="imagen_mobile" accept="image/*" 
                                       class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                                <p class="text-xs text-gray-400 mt-1" id="mobileHelp">378 × 273 px</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="resetModal(); closeModal();" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="modalSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Beneficio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

            <script>

                // Función para manejar los campos de acción
                function toggleActionFields() {
                    const tipoAccion = document.querySelector('input[name="tipo_accion"]:checked').value;
                    const urlAndTextFields = document.getElementById('urlAndTextFields');
                    const textoBotonField = document.getElementById('textoBotonField');
                    
                    if (tipoAccion === 'link') {
                        // Mostrar URL y texto del enlace
                        urlAndTextFields.classList.remove('hidden');
                        textoBotonField.classList.remove('hidden');
                    } else if (tipoAccion === 'button') {
                        // Mostrar solo URL
                        urlAndTextFields.classList.remove('hidden');
                        textoBotonField.classList.add('hidden');
                        document.getElementById('modalTextoBoton').value = '';
                    } else {
                        // Ocultar todos los campos
                        urlAndTextFields.classList.add('hidden');
                        document.getElementById('modalTextoBoton').value = '';
                        document.getElementById('modalUrlNew').value = '';
                    }
                }

                // Función para manejar los radio buttons del link (legacy)
                function toggleLinkFields() {
                    const linkTypeRadio = document.querySelector('input[name="link_type"]:checked');
                    const linkType = linkTypeRadio ? linkTypeRadio.value : 'none';
                    const linkFields = document.getElementById('linkFields');
                    const urlField = document.getElementById('urlField');
                    const linkTextField = document.getElementById('linkTextField');
                    
                    if (linkType === 'none') {
                        // Sin URL - ocultar todos los campos y limpiar valores
                        if (linkFields) linkFields.classList.add('hidden');
                        if (urlField) urlField.classList.add('hidden');
                        if (linkTextField) linkTextField.classList.add('hidden');
                        const modalUrl = document.getElementById('modalUrl');
                        const modalLink = document.getElementById('modalLink');
                        if (modalUrl) modalUrl.value = '';
                        if (modalLink) modalLink.value = '';
                    } else if (linkType === 'button') {
                        // Botón - mostrar solo URL
                        if (linkFields) linkFields.classList.remove('hidden');
                        if (urlField) urlField.classList.remove('hidden');
                        if (linkTextField) linkTextField.classList.add('hidden');
                        const modalLink = document.getElementById('modalLink');
                        if (modalLink) modalLink.value = '';
                    } else if (linkType === 'link') {
                        // Link - mostrar URL y texto del link
                        if (linkFields) linkFields.classList.remove('hidden');
                        if (urlField) urlField.classList.remove('hidden');
                        if (linkTextField) linkTextField.classList.remove('hidden');
                    }
                }

                function handleFormSubmit(event) {
                    event.preventDefault();
                    
                    // Detectar si estamos en modo edición
                    const isEditMode = document.getElementById('methodField').value === 'PUT';
                    
                    // Validar campos básicos
                    const tituloLinea1 = document.querySelector('input[name="titulo_linea1"]').value.trim();
                    const tituloLinea2 = document.querySelector('input[name="titulo_linea2"]').value.trim();
                    const descripcion = document.querySelector('textarea[name="descripcion"]').value.trim();
                    const imagenDesktop = document.querySelector('input[name="imagen_desktop"]').files.length;
                    const imagenMobile = document.querySelector('input[name="imagen_mobile"]').files.length;
                    
                    // Validar tipo de acción
                    const tipoAccion = document.querySelector('input[name="tipo_accion"]:checked');
                    if (!tipoAccion) {
                        showValidationModal("Error", "Debe seleccionar un tipo de acción");
                        return false;
                    }
                    
                    const url = document.querySelector('input[name="url"]').value.trim();
                    const textoBoton = document.querySelector('input[name="texto_boton"]').value.trim();
                    
                    // Validar campos obligatorios
                    if (!tituloLinea1) {
                        showValidationModal("Error", "El título línea 1 es obligatorio");
                        return false;
                    }
                    if (!tituloLinea2) {
                        showValidationModal("Error", "El título línea 2 es obligatorio");
                        return false;
                    }
                    if (!descripcion) {
                        showValidationModal("Error", "La descripción es obligatoria");
                        return false;
                    }
                    
                    // Solo validar imágenes si NO estamos en modo edición
                    if (!isEditMode) {
                        if (imagenDesktop === 0) {
                            showValidationModal("Error", "La imagen desktop es obligatoria");
                            return false;
                        }
                        if (imagenMobile === 0) {
                            showValidationModal("Error", "La imagen mobile es obligatoria");
                            return false;
                        }
                    }
                    
                    // Validar campos condicionales
                    if (tipoAccion.value === 'button' && !url) {
                        showValidationModal("Error", "La URL es obligatoria cuando se selecciona 'Botón'");
                        return false;
                    }
                    if (tipoAccion.value === 'link' && (!url || !textoBoton)) {
                        showValidationModal("Error", "La URL y el texto del enlace son obligatorios cuando se selecciona 'Link'");
                        return false;
                    }
                    
                    // Si todo está bien, enviar el formulario
                    document.getElementById('modalForm').submit();
                    return false;
                }

        function openModal() {
            // Reset form for new benefit
            resetModal();
            document.getElementById('modalTitle').textContent = 'Agregar Nuevo Beneficio';
            document.getElementById('modalForm').action = '{{ route("admin.beneficios.store") }}';
            document.getElementById('modalForm').method = 'POST';
            document.getElementById('methodField').value = '';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Beneficio';
            
            document.getElementById('modalAgregarBeneficio').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalAgregarBeneficio').classList.add('hidden');
        }

        function editBeneficio(id) {
            // Resetear modal primero
            resetModal();
            
            // Cargar datos del beneficio
            fetch(`/admin/beneficios/${id}/data`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);
                    
                    // Configurar modal para edición
                    const modalTitle = document.getElementById('modalTitle');
                    const modalForm = document.getElementById('modalForm');
                    const methodField = document.getElementById('methodField');
                    const modalSubmitBtn = document.getElementById('modalSubmitBtn');
                    
                    if (modalTitle) modalTitle.textContent = 'Editar Beneficio';
                    if (modalForm) {
                        modalForm.action = '{{ route("admin.beneficios.update", ":id") }}'.replace(':id', id);
                        modalForm.method = 'POST';
                    }
                    if (methodField) methodField.value = 'PUT';
                    if (modalSubmitBtn) modalSubmitBtn.textContent = 'Actualizar Beneficio';
                    
                    // Llenar campos del formulario
                    const tituloLinea1 = document.getElementById('modalTituloLinea1');
                    const tituloLinea2 = document.getElementById('modalTituloLinea2');
                    const descripcion = document.getElementById('modalDescripcion');
                    const url = document.getElementById('modalUrlNew');
                    const link = document.getElementById('modalLink');
                    
                    if (tituloLinea1) tituloLinea1.value = data.titulo_linea1 || '';
                    if (tituloLinea2) tituloLinea2.value = data.titulo_linea2 || '';
                    if (descripcion) descripcion.value = data.descripcion || '';
                    if (url) url.value = data.url || '';
                    if (link) link.value = data.link || '';
                    
                    // Configurar nuevos campos parametrizables
                    const tipoAccionRadio = document.querySelector(`input[name="tipo_accion"][value="${data.tipo_accion || 'none'}"]`);
                    if (tipoAccionRadio) tipoAccionRadio.checked = true;
                    
                    const textoBoton = document.getElementById('modalTextoBoton');
                    if (textoBoton) textoBoton.value = data.texto_boton || '';
                    
                    const tipoTitulo = document.getElementById('modalTipoTitulo');
                    if (tipoTitulo) tipoTitulo.checked = data.tipo_titulo === 'small';
                    
                    if (typeof toggleActionFields === 'function') {
                        toggleActionFields();
                    }
                    
                    // Los campos de link_type ya no existen, solo usar tipo_accion
                    
                    // Configurar para modo edición - ocultar asteriscos y cambiar texto de ayuda
                    const desktopRequired = document.getElementById('desktopRequired');
                    const mobileRequired = document.getElementById('mobileRequired');
                    const desktopHelp = document.getElementById('desktopHelp');
                    const mobileHelp = document.getElementById('mobileHelp');
                    
                    if (desktopRequired) desktopRequired.style.display = 'none';
                    if (mobileRequired) mobileRequired.style.display = 'none';
                    if (desktopHelp) desktopHelp.textContent = 'Dejar vacío para mantener la imagen actual';
                    if (mobileHelp) mobileHelp.textContent = 'Dejar vacío para mantener la imagen actual';
                    
                    // Abrir modal
                    const modal = document.getElementById('modalAgregarBeneficio');
                    if (modal) modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error al cargar datos del beneficio:', error);
                    alert('Error al cargar los datos del beneficio: ' + error.message);
                });
        }

                function resetModal() {
                    document.getElementById('modalForm').reset();
                    document.getElementById('beneficioId').value = '';
                    document.getElementById('methodField').value = '';
                    
                    // Reset radio buttons to "Sin acción"
                    document.querySelector('input[name="tipo_accion"][value="none"]').checked = true;
                    document.getElementById('urlAndTextFields').classList.add('hidden');
                    toggleActionFields();
                    
                    // Reset para modo creación - mostrar asteriscos y texto original
                    document.getElementById('desktopRequired').style.display = 'inline';
                    document.getElementById('mobileRequired').style.display = 'inline';
                    document.getElementById('desktopHelp').textContent = '378 × 420 px';
                    document.getElementById('mobileHelp').textContent = '378 × 273 px';
                }

        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar este beneficio?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalAgregarBeneficio').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

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

        // Sistema de botones arriba/abajo
        function moverBeneficio(beneficioId, direccion) {
            console.log('Moviendo beneficio:', { beneficioId, direccion });
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: beneficioId,
                direccion: direccion
            };
            
            console.log('Enviando datos:', requestData);
            
            fetch(`/admin/beneficios/mover`, {
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
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Error al mover el beneficio');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(error.message, 'error');
            })
            .finally(() => {
                botones.forEach(btn => btn.disabled = false);
            });
        }

        function showMessage(message, type) {
            // Crear elemento de mensaje
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            messageDiv.textContent = message;
            
            // Agregar al DOM
            document.body.appendChild(messageDiv);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
    </script>
</x-app-layout>
