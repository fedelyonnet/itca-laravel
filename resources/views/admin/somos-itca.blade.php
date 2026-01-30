

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edición Somos ITCA') }}
        </h2>
    </x-slot>

    <!-- Toast Messages -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400">
                {{ session('error') }}
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
                        <div class="space-y-2">
                            @foreach($instalaciones as $inst)
                                <div class="flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 hover:border-gray-600 transition-colors">
                                    <!-- Nombre + Preview Hover -->
                                    <div class="relative group cursor-help">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-sm font-medium text-gray-300 group-hover:text-blue-400 transition-colors">
                                                {{ basename($inst->image_path) }}
                                            </span>
                                        </div>
                                        
                                        <!-- Tooltip Image -->
                                        <div class="fixed hidden group-hover:block z-50 bg-gray-900 border border-gray-600 p-1 rounded shadow-2xl ml-4 pointer-events-none" style="margin-top: -20px;">
                                            <img src="{{ asset('storage/' . $inst->image_path) }}" class="h-48 w-auto max-w-xs object-cover rounded">
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <form action="{{ route('admin.somos-itca.instalaciones.destroy', $inst->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Imagen', '¿Estás seguro de que deseas eliminar esta imagen de instalación?');">
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

    <script>
        function openModalInstalacion() {
            document.getElementById('modalInstalacion').classList.remove('hidden');
        }
        function openModalFormador() {
            document.getElementById('modalFormador').classList.remove('hidden');
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
</x-app-layout>
