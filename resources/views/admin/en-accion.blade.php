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
                            <h1 class="text-2xl font-bold">Gestión de En Acción</h1>
                        </div>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Video
                        </button>
                    </div>

                    @if($videos->count() > 0)
                        <!-- Tabla de videos -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-300">
                                <thead class="text-xs text-gray-300 uppercase bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Plataforma</th>
                                        <th scope="col" class="px-4 py-3">Versión</th>
                                        <th scope="col" class="px-4 py-3">URL</th>
                                        <th scope="col" class="px-4 py-3">Video</th>
                                        <th scope="col" class="px-4 py-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videos as $video)
                                        <tr class="bg-gray-800 border-b border-gray-700">
                                            <td class="px-4 py-3">
                                                @php
                                                    $url = strtolower($video->url);
                                                    $plataforma = 'Desconocida';
                                                    $color = 'bg-gray-600';
                                                    
                                                    if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                                                        $plataforma = 'YouTube';
                                                        $color = 'bg-red-600';
                                                    } elseif (str_contains($url, 'instagram.com')) {
                                                        $plataforma = 'Instagram';
                                                        $color = 'bg-pink-600';
                                                    } elseif (str_contains($url, 'tiktok.com')) {
                                                        $plataforma = 'TikTok';
                                                        $color = 'bg-black';
                                                    }
                                                @endphp
                                                <span class="px-2 py-1 text-xs rounded-full {{ $color }} text-white">
                                                    {{ $plataforma }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $video->version === 'mob' ? 'bg-green-600' : 'bg-blue-600' }}">
                                                    {{ $video->version === 'mob' ? 'Mobile' : 'Desktop' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ $video->url }}" target="_blank" class="text-blue-400 hover:text-blue-300 truncate block max-w-xs">
                                                    {{ $video->url }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($video->video)
                                                    <span class="text-green-400">✓ Subido</span>
                                                @else
                                                    <span class="text-red-400">✗ Sin video</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex space-x-2 justify-center">
                                                    <!-- Editar -->
                                                    <button onclick="editVideo({{ $video->id }})" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Eliminar -->
                                                    <form action="{{ route('admin.en-accion.destroy', $video->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay videos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo video.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar video -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white mb-4">Agregar Video</h3>
                    
                    <form id="videoForm" action="{{ route('admin.en-accion.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                        @csrf
                        <input type="hidden" id="videoId" name="video_id" value="">
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
                    <!-- Versión -->
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Versión <span class="text-red-400">*</span></label>
                        <select id="modalVersion" name="version" 
                                class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                            <option value="">Seleccionar versión</option>
                            <option value="mob">Mobile</option>
                            <option value="desktop">Desktop</option>
                        </select>
                    </div>
                    
                    <!-- URL -->
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">URL <span class="text-red-400">*</span></label>
                        <input type="text" id="modalUrl" name="url" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="https://youtube.com/watch?v=...">
                    </div>
                    
                    <!-- Video -->
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Video <span class="text-red-400" id="videoRequired">*</span></label>
                        <input type="file" id="modalVideo" name="video" accept="video/*"
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        <p class="text-xs text-gray-400 mt-1" id="videoHelp">Formatos: MP4, AVI, MOV, WMV. Máximo 100MB</p>
                    </div>
                </div>
                        
                        <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                            <button type="button" onclick="resetModal(); closeModal();" 
                                    class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                                Cancelar
                            </button>
                            <button type="submit" id="modalSubmitBtn"
                                    class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                                Agregar Video
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;

        function openModal() {
            resetModal();
            document.getElementById('videoModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Agregar Video';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Video';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('videoForm').action = '{{ route("admin.en-accion.store") }}';
            
            // Configurar para modo creación
            document.getElementById('modalVideo').setAttribute('required', 'required');
            document.getElementById('videoRequired').style.display = 'inline';
            document.getElementById('videoHelp').textContent = 'Formatos: MP4, AVI, MOV, WMV. Máximo 100MB';
            
            isEditMode = false;
        }

        function closeModal() {
            document.getElementById('videoModal').classList.add('hidden');
        }

        function resetModal() {
            document.getElementById('videoForm').reset();
            document.getElementById('videoRequired').style.display = 'inline';
            document.getElementById('videoHelp').textContent = 'Formatos: MP4, AVI, MOV, WMV. Máximo 100MB';
            document.getElementById('modalVideo').setAttribute('required', 'required');
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Detectar si estamos en modo edición
            const isEditMode = document.getElementById('methodField').value === 'PUT';
            
            // Validar campos básicos
            const version = document.querySelector('select[name="version"]').value.trim();
            const url = document.querySelector('input[name="url"]').value.trim();
            const video = document.querySelector('input[name="video"]').files.length;
            
            // Validar campos obligatorios
            if (!version) {
                showValidationModal("Error", "La versión es obligatoria");
                return false;
            }
            
            if (!url) {
                showValidationModal("Error", "La URL es obligatoria");
                return false;
            }
            
            // Validar formato de URL
            const urlPattern = /^https?:\/\/.+/;
            if (!urlPattern.test(url)) {
                showValidationModal("Error", "La URL debe comenzar con http:// o https://");
                return false;
            }
            
            // Solo validar video si NO estamos en modo edición
            if (!isEditMode) {
                if (video === 0) {
                    showValidationModal("Error", "El video es obligatorio");
                    return false;
                }
            }
            
            // Si todo está bien, enviar el formulario
            document.getElementById('videoForm').submit();
            return false;
        }

        function showValidationModal(title, message) {
            const modal = document.getElementById('confirmation-modal');
            if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
                modal._x_dataStack[0].title = title;
                modal._x_dataStack[0].message = message;
                modal._x_dataStack[0].onConfirm = () => modal._x_dataStack[0].open = false;
                modal._x_dataStack[0].open = true;
                
                // Hide cancel button and modify confirm button for validation
                const cancelButton = modal.querySelector('button[class*="bg-white"]');
                const confirmButton = modal.querySelector('button[class*="bg-blue-600"]');
                
                if (cancelButton) {
                    cancelButton.style.display = 'none';
                }
                
                if (confirmButton) {
                    confirmButton.textContent = 'Aceptar';
                }
            } else {
                // Fallback to native alert
                alert(title + ": " + message);
            }
        }

        // Auto-hide success messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add('translate-x-full');
                }, 3000);
            }
            
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.add('translate-x-full');
                }, 5000);
            }
        });

        // Función para editar video
        async function editVideo(videoId) {
            try {
                const response = await fetch(`/admin/en-accion/${videoId}/data`);
                const video = await response.json();
                
                // Resetear el modal primero
                resetModal();
                
                // Abrir modal
                document.getElementById('videoModal').classList.remove('hidden');
                
                // Configurar para edición
                document.getElementById('modalTitle').textContent = 'Editar Video';
                document.getElementById('modalSubmitBtn').textContent = 'Actualizar Video';
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('videoForm').action = `/admin/en-accion/${videoId}`;
                
                // Hacer el video opcional en modo edición
                document.getElementById('modalVideo').removeAttribute('required');
                document.getElementById('videoRequired').style.display = 'none';
                document.getElementById('videoHelp').textContent = 'Dejar vacío para mantener el video actual';
                
                // Llenar el formulario con los datos del video
                document.getElementById('modalVersion').value = video.version;
                document.getElementById('modalUrl').value = video.url;
                
                isEditMode = true;
            } catch (error) {
                console.error('Error al cargar datos del video:', error);
                alert('Error al cargar los datos del video');
            }
        }

        // Función para confirmar eliminación
        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar este video?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        // Función para eliminar video (mantenida por compatibilidad)
        function deleteVideo(videoId) {
            if (confirm('¿Estás seguro de que quieres eliminar este video?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/en-accion/${videoId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
