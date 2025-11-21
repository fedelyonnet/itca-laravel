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
                            <h1 class="text-2xl font-bold">Edición multimedia</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Fotos: {{ $fotos->count() }}
                            </p>
                        </div>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Foto
                        </button>
                    </div>

                    @if($fotos->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider text-center">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider text-center">Imagen</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($fotos->sortBy('orden') as $foto)
                                        <tr>
                                            <!-- Orden -->
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex flex-col items-center space-y-1 justify-center">
                                                    <button onclick="moverFoto({{ $foto->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverFoto({{ $foto->id }}, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            <!-- Imagen Thumbnail -->
                                            <td class="px-4 py-4 text-center">
                                                <div class="relative group inline-block" 
                                                     x-data="{ showTooltip: false }" 
                                                     @mouseenter="showTooltip = true" 
                                                     @mouseleave="showTooltip = false">
                                                    <div class="w-16 h-16 bg-gray-500 rounded overflow-hidden cursor-pointer">
                                                        @if($foto->imagen)
                                                            <img src="{{ asset('storage/' . $foto->imagen) }}" 
                                                                 alt="Foto carrera" 
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
                                                        @if($foto->imagen)
                                                            <img src="{{ asset('storage/' . $foto->imagen) }}" 
                                                                 alt="Foto Preview" 
                                                                 class="max-w-xs max-h-64 object-contain">
                                                        @else
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex space-x-2 justify-center items-center">
                                                    <!-- Editar -->
                                                    <div class="relative group">
                                                        <button onclick="editFoto({{ $foto->id }})" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Editar Foto
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Eliminar -->
                                                    <div class="relative group">
                                                        <form action="{{ route('admin.carreras.multimedia.destroy', $foto->id) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirmDeleteFoto(event)" 
                                                              class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <div class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Eliminar Foto
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-200">No hay fotos</h3>
                            <p class="mt-1 text-sm text-gray-300">Comienza agregando tu primera foto.</p>
                        </div>
                    @endif
                    
                    <!-- Card de Video de Testimonios -->
                    <div class="mt-8 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-100 mb-4">Video de Testimonios</h3>
                        <form id="videoForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.carreras.multimedia.updateVideo') }}">
                            @csrf
                            <!-- Video y URL lado a lado -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Video -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Video <span class="text-red-400" id="videoRequired"></span>
                                    </label>
                                    <input type="file" 
                                           name="video" 
                                           id="videoInput" 
                                           accept="video/mp4,video/webm"
                                           class="block w-full text-sm text-gray-300 border border-gray-500 rounded-md cursor-pointer bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 file:cursor-pointer"
                                           onchange="previewVideo(this); updateFileName(this)">
                                    <p id="fileName" class="text-xs text-gray-400 mt-1"></p>
                                    <p class="text-xs text-gray-400 mt-1">Tamaño recomendado: 280×600px. MP4, WEBM. Máximo 100MB.</p>
                                </div>
                                
                                <!-- URL -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        URL del enlace
                                    </label>
                                    <input type="text" 
                                           name="url" 
                                           id="urlInput" 
                                           value="{{ $videoTestimonios->url ?? '#' }}"
                                           placeholder="https://ejemplo.com o #"
                                           class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-xs text-gray-400 mt-1">URL del botón "Ver más testimonios". Usa # para deshabilitar.</p>
                                </div>
                            </div>
                            
                            @if($videoTestimonios && $videoTestimonios->video)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-400 mb-2">Video actual:</p>
                                    <video id="currentVideo" src="{{ asset('storage/' . $videoTestimonios->video) }}" controls class="max-w-full h-48 rounded" style="max-height: 200px;"></video>
                                </div>
                            @endif
                            
                            <div id="videoPreview" class="mb-4 hidden">
                                <p class="text-sm text-gray-400 mb-2">Vista previa del nuevo video:</p>
                                <video id="previewVideo" src="" controls class="max-w-full h-48 rounded" style="max-height: 200px;"></video>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors">
                                    Guardar Video
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar foto -->
    <div id="fotoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-100" id="modalTitle">Agregar Foto</h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="fotoForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="foto_id" id="foto_id">

                    <!-- Imagen -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Imagen <span class="text-red-400">*</span>
                        </label>
                        <input type="file" 
                               name="imagen" 
                               id="imagen" 
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               onchange="previewImage(this)">
                        <p class="text-xs text-gray-400 mt-1">Tamaño recomendado: 800×600px. JPEG, PNG, JPG, GIF, WEBP. Máximo 2MB.</p>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img id="previewImg" src="" alt="Preview" class="max-w-full h-48 object-contain rounded">
                        </div>
                        <div id="currentImage" class="mt-4 hidden">
                            <p class="text-sm text-gray-400 mb-2">Imagen actual:</p>
                            <img id="currentImg" src="" alt="Imagen actual" class="max-w-full h-48 object-contain rounded">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal()" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-md transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const routes = {
            store: '{{ route("admin.carreras.multimedia.store") }}',
            update: '{{ route("admin.carreras.multimedia.update", ":id") }}',
            getData: '{{ route("admin.carreras.multimedia.data", ":id") }}'
        };

        function openModal() {
            document.getElementById('fotoModal').classList.remove('hidden');
            document.getElementById('fotoModal').classList.add('flex');
            document.getElementById('modalTitle').textContent = 'Agregar Foto';
            document.getElementById('fotoForm').action = routes.store;
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('foto_id').value = '';
            document.getElementById('fotoForm').reset();
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('currentImage').classList.add('hidden');
        }

        function closeModal() {
            document.getElementById('fotoModal').classList.add('hidden');
            document.getElementById('fotoModal').classList.remove('flex');
            document.getElementById('fotoForm').reset();
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('currentImage').classList.add('hidden');
        }

        function editFoto(id) {
            fetch(routes.getData.replace(':id', id))
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Editar Foto';
                    document.getElementById('fotoForm').action = routes.update.replace(':id', id);
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('foto_id').value = data.id;
                    
                    // Mostrar imagen actual
                    if (data.imagen) {
                        document.getElementById('currentImg').src = '/storage/' + data.imagen;
                        document.getElementById('currentImage').classList.remove('hidden');
                    }
                    
                    document.getElementById('imagen').required = false;
                    document.getElementById('fotoModal').classList.remove('hidden');
                    document.getElementById('fotoModal').classList.add('flex');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlertModal('Error', 'Error al cargar los datos de la foto');
                });
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('currentImage').classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function confirmDeleteFoto(event) {
            event.preventDefault();
            const form = event.target;
            showConfirmModal(
                '¿Estás seguro?',
                '¿Estás seguro de eliminar esta foto?',
                () => form.submit()
            );
            return false;
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('fotoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Función para mostrar el nombre del archivo seleccionado
        function updateFileName(input) {
            const fileName = input.files && input.files[0] ? input.files[0].name : '';
            const fileNameElement = document.getElementById('fileName');
            if (fileNameElement) {
                fileNameElement.textContent = fileName ? `Archivo seleccionado: ${fileName}` : '';
            }
        }

        // Función para previsualizar video
        function previewVideo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewVideo').src = e.target.result;
                    document.getElementById('videoPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Sistema de botones arriba/abajo para mover fotos
        function moverFoto(fotoId, direccion) {
            console.log('Moviendo foto:', { fotoId, direccion });
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: fotoId,
                direccion: direccion
            };
            
            console.log('Enviando datos:', requestData);
            
            fetch('{{ route("admin.carreras.multimedia.mover") }}', {
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
                    throw new Error(data.message || 'Error al mover la foto');
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
