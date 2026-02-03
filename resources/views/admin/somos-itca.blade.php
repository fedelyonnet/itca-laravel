

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edición Somos ITCA') }}
        </h2>
    </x-slot>

    <!-- Toast Messages -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400 flex items-center justify-between min-w-[300px]">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-green-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center justify-between min-w-[300px]">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-red-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    </div>

    <div class="py-12 space-y-12">
        <!-- SECCIÓN 1: CONTENIDO GENERAL (Video + Imagen 'Por Qué') -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <h3 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">Contenido Principal</h3>
                    
                    <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- COLUMNA VIDEO -->
                            <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                <label class="block text-sm font-bold text-blue-400 mb-2 uppercase">Video (Dropdown "¿Qué es ITCA?")</label>
                                
                                <div class="relative group">
                                    <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm" class="hidden" 
                                           onchange="previewVideo(this)">
                                           
                                    <label for="video_file" class="relative block w-full h-48 bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner flex flex-col items-center justify-center">
                                        
                                        <!-- Video Preview / Current -->
                                        @if(isset($content->video_url) && $content->video_url)
                                            <video id="video_preview" src="{{ asset('storage/' . $content->video_url) }}" controls class="w-full h-full object-cover"></video>
                                        @else
                                            <video id="video_preview" class="w-full h-full object-cover hidden"></video>
                                        @endif
                                        
                                        <!-- Placeholder -->
                                        <div id="video_placeholder" class="{{ (isset($content->video_url) && $content->video_url) ? 'hidden' : '' }} absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none">
                                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs uppercase font-bold">Subir Video (MP4)</span>
                                        </div>
                                    </label>
                                    <p class="text-[10px] text-gray-500 mt-1 text-center">Máx: 50MB</p>
                                </div>
                            </div>

                            <!-- COLUMNA IMAGEN -->
                            <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                <label class="block text-sm font-bold text-blue-400 mb-2 uppercase">Imagen "Por qué elegirnos"</label>
                                
                                <div class="relative group">
                                    <input type="file" name="img_por_que" id="img_por_que" accept="image/*" class="hidden" 
                                           onchange="previewImage(this)">
                                           
                                    <label for="img_por_que" class="relative block w-full h-48 bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                        
                                        <!-- Image Preview / Current -->
                                        @if(isset($content->img_por_que) && $content->img_por_que)
                                            <img id="img_preview" src="{{ asset('storage/' . $content->img_por_que) }}" class="w-full h-full object-cover">
                                        @else
                                            <img id="img_preview" class="w-full h-full object-cover hidden">
                                        @endif
                                        
                                        <!-- Placeholder -->
                                        <div id="img_placeholder" class="{{ (isset($content->img_por_que) && $content->img_por_que) ? 'hidden' : '' }} absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none">
                                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs uppercase font-bold">Subir Imagen</span>
                                        </div>
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-blue-600/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </div>
                                    </label>
                                    <p class="text-[10px] text-gray-500 mt-1 text-center">Recomendado: 800x600px</p>
                                </div>
                            </div>

                        </div>
                        
                        <!-- TEXTO 'QUÉ ES ITCA' -->
                        <div class="bg-gray-900 p-4 rounded border border-gray-700">
                            <label for="que_es_itca" class="block text-sm font-bold text-blue-400 mb-2 uppercase">Texto "¿Qué es ITCA?"</label>
                            <p class="text-xs text-gray-400 mb-2">
                                Para poner un texto en <strong>negrita</strong>, encerralo entre asteriscos y barras así: <code class="bg-gray-800 px-1 rounded">*/texto destacado/*</code>.
                            </p>
                            <textarea name="que_es_itca" id="que_es_itca" rows="5" 
                                class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded focus:border-blue-500 focus:ring-0 p-3"
                                placeholder="Escribí aquí el texto...">{{ old('que_es_itca', $content->que_es_itca) }}</textarea>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-700">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:-translate-y-0.5">
                                GUARDAR CAMBIOS
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2 y 3: INSTALACIONES Y FORMADORES (En fila) -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- INSTALACIONES -->
                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                        <label class="block text-sm font-bold text-blue-400 uppercase">Instalaciones</label>
                        <button onclick="openModalInstalacion()" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-xs font-bold transition-colors shadow">
                            + AGREGAR
                        </button>
                    </div>

                    @if(isset($instalaciones) && $instalaciones->count() > 0)
                        <div id="instalacionesList" class="space-y-2 max-h-96 overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($instalaciones as $inst)
                                <div data-id="{{ $inst->id }}" class="flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 hover:border-gray-600 transition-colors gap-2">
                                    <!-- Preview -->
                                    <div class="flex items-center gap-2">
                                        <div class="relative group cursor-help w-10 h-10 flex-shrink-0">
                                            <img src="{{ asset('storage/' . $inst->image_path) }}" class="w-full h-full object-cover rounded border border-gray-600">
                                            <!-- Tooltip Image -->
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-50 pointer-events-none">
                                                <img src="{{ asset('storage/' . $inst->image_path) }}" class="max-w-xs rounded border border-gray-500 shadow-xl">
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400 ml-2">Imagen {{ $loop->iteration }}</span>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.somos-itca.instalaciones.destroy', $inst->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar', '¿Borrar esta instalación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors p-1" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-32 flex items-center justify-center text-gray-500 text-xs italic border border-gray-700 border-dashed rounded">
                            Sin imágenes cargadas
                        </div>
                    @endif
                </div>

                <!-- INSTALACIONES - ITEMS (ESTRELLAS) -->
                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                        <div class="flex flex-col">
                            <label class="block text-sm font-bold text-blue-400 uppercase">Instalaciones - Items (Estrellas)</label>
                            <span class="text-[10px] text-gray-400">Items con icono de estrella. Usa */negrita/* para resaltar.</span>
                        </div>
                        <button onclick="document.getElementById('modalInstalacionItem').classList.remove('hidden')" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-xs font-bold transition-colors shadow">
                            + AGREGAR ITEM
                        </button>
                    </div>

                    @if(isset($instalacionItems) && $instalacionItems->count() > 0)
                        <div id="instalacionItemsList" class="space-y-2 max-h-96 overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($instalacionItems as $item)
                                <div data-id="{{ $item->id }}" class="instalacion-item-draggable flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 hover:border-gray-600 transition-colors cursor-move">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500 cursor-move drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                        <span class="text-sm text-gray-200">{{ $item->descripcion }}</span>
                                    </div>
                                    
                                    <form action="{{ route('admin.somos-itca.instalacion-items.destroy', $item->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Item', '¿Estás seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors p-1" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-16 flex items-center justify-center text-gray-500 text-xs italic border border-gray-700 border-dashed rounded">
                            Sin items cargados
                        </div>
                    @endif
                </div>

                <!-- POR QUÉ ELEGIRNOS - ITEMS -->
                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                        <label class="block text-sm font-bold text-blue-400 uppercase">Items "Por qué elegirnos"</label>
                        <button onclick="openModalPorQue()" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-xs font-bold transition-colors shadow">
                            + AGREGAR
                        </button>
                    </div>

                    @if(isset($porQueItems) && $porQueItems->count() > 0)
                        <div id="porQueList" class="space-y-2 max-h-96 overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($porQueItems as $item)
                                <div data-id="{{ $item->id }}" class="por-que-draggable flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 hover:border-gray-600 transition-colors cursor-move">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500 cursor-move drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                        <span class="text-sm text-gray-200">{{ $item->descripcion }}</span>
                                    </div>
                                    
                                    <form action="{{ route('admin.somos-itca.porque.destroy', $item->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Item', '¿Estás seguro de eliminar este item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors p-1" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-32 flex items-center justify-center text-gray-500 text-xs italic border border-gray-700 border-dashed rounded">
                            Sin items cargados
                        </div>
                    @endif
                </div>

                <!-- FORMADORES -->
                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                        <label class="block text-sm font-bold text-blue-400 uppercase">Formadores</label>
                        <button onclick="openModalFormador()" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-xs font-bold transition-colors shadow">
                            + AGREGAR
                        </button>
                    </div>

                    @if(isset($formadores) && $formadores->count() > 0)
                        <div class="space-y-2 max-h-96 overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($formadores as $formador)
                                <div class="flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 hover:border-gray-600 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $formador->image_path) }}" alt="{{ $formador->nombre }}" class="h-10 w-10 rounded-full object-cover border border-gray-600">
                                        <span class="text-sm font-medium text-gray-200">{{ $formador->nombre }}</span>
                                    </div>
                                    <form action="{{ route('admin.somos-itca.formadores.destroy', $formador->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Formador', '¿Estás seguro de que deseas eliminar a este formador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-32 flex items-center justify-center text-gray-500 text-xs italic border border-gray-700 border-dashed rounded">
                            Sin formadores cargados
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL INSTALACIÓN -->
    <div id="modalInstalacion" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-white mb-4">Agregar Imagen Instalación</h3>
            <form action="{{ route('admin.somos-itca.instalaciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Imagen</label>
                    <input type="file" name="image" accept="image/*" required class="w-full text-gray-300 text-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modalInstalacion').classList.add('hidden')" class="px-4 py-2 bg-gray-600 rounded text-white text-sm hover:bg-gray-500">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded text-white text-sm hover:bg-green-500">Subir</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL INSTALACION ITEM (ESTRELLAS) -->
    <div id="modalInstalacionItem" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-white mb-4">Agregar Item Instalación (Estrella)</h3>
            <form action="{{ route('admin.somos-itca.instalacion-items.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Ej: Equipamiento */completo/* para formación."></textarea>
                    <p class="text-[10px] text-gray-400 mt-1">Usa */texto/* para negrita.</p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modalInstalacionItem').classList.add('hidden')" class="px-4 py-2 bg-gray-600 rounded text-white text-sm hover:bg-gray-500">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded text-white text-sm hover:bg-green-500">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FORMADOR -->
    <div id="modalFormador" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-white mb-4">Agregar Formador</h3>
            <form action="{{ route('admin.somos-itca.formadores.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Nombre</label>
                    <input type="text" name="nombre" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Foto</label>
                    <input type="file" name="image" accept="image/*" required class="w-full text-gray-300 text-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modalFormador').classList.add('hidden')" class="px-4 py-2 bg-gray-600 rounded text-white text-sm hover:bg-gray-500">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded text-white text-sm hover:bg-green-500">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POR QUÉ ITEM -->
    <div id="modalPorQue" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-white mb-4">Agregar Item "Por qué elegirnos"</h3>
            <form action="{{ route('admin.somos-itca.porque.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modalPorQue').classList.add('hidden')" class="px-4 py-2 bg-gray-600 rounded text-white text-sm hover:bg-gray-500">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded text-white text-sm hover:bg-green-500">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalInstalacion() {
            document.getElementById('modalInstalacion').classList.remove('hidden');
        }
        function openModalFormador() {
            document.getElementById('modalFormador').classList.remove('hidden');
        }
        function openModalPorQue() {
            document.getElementById('modalPorQue').classList.remove('hidden');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('img_preview');
                    const placeholder = document.getElementById('img_placeholder');
                    
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewVideo(input) {
            if (input.files && input.files[0]) {
                if(input.files[0].size > 50 * 1024 * 1024) {
                    alert("El archivo es demasiado grande (Máx 50MB)");
                    input.value = "";
                    return;
                }

                const url = URL.createObjectURL(input.files[0]);
                const video = document.getElementById('video_preview');
                const placeholder = document.getElementById('video_placeholder');
                
                video.src = url;
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        /* Sistema de Confirmación usando el Modal del Layout */
        function confirmSubmission(event, title, message) {
            event.preventDefault();
            const form = event.target;
            
            // Acceder al componente Alpine 'confirmation-modal'
            const modal = document.getElementById('confirmation-modal');
            
            // Verificar si Alpine está disponible y tiene datos
            if (modal && modal._x_dataStack) {
                const modalData = modal._x_dataStack[0];
                modalData.title = title;
                modalData.message = message;
                modalData.onConfirm = () => {
                    form.submit();
                };
                modalData.open = true;
            } else {
                // Fallback si Alpine falla
                if (confirm(message)) {
                    form.submit();
                }
            }
            
            return false;
        }
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('porQueList');
            if(el){
                new Sortable(el, {
                    animation: 150,
                    ghostClass: 'bg-gray-700',
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        var orden = [];
                        document.querySelectorAll('#porQueList .por-que-draggable').forEach(function(item){
                            orden.push(item.getAttribute('data-id'));
                        });

                        fetch("{{ route('admin.somos-itca.porque.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ orden: orden })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(!data.success) alert('Error al guardar orden');
                        })
                        .catch(error => console.error('Error:', error));
                    },
                });
            }

            // Sortable for 'Instalaciones' (REMOVED)
            // Sortable for 'Instalaciones Items (Estrellas)' needs to stay, but I removed Images sortable block.
            // Sortable for 'Instalacion Items (Estrellas)'
            var elInstItems = document.getElementById('instalacionItemsList');
            if(elInstItems){
                new Sortable(elInstItems, {
                    animation: 150,
                    ghostClass: 'bg-gray-700',
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        var orden = [];
                        document.querySelectorAll('#instalacionItemsList .instalacion-item-draggable').forEach(function(item){
                            orden.push(item.getAttribute('data-id'));
                        });

                        fetch("{{ route('admin.somos-itca.instalacion-items.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ orden: orden })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(!data.success) alert('Error al guardar orden');
                        })
                        .catch(error => console.error('Error:', error));
                    },
                });
            }
            // Auto-hide Toast Messages
            const toasts = document.querySelectorAll('#toast-container > div');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(20px)';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            });
        });
    </script>
</x-app-layout>
