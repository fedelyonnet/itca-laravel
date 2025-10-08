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
                            <h1 class="text-2xl font-bold">Gestión de Testimonios</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Testimonios visibles: {{ $testimonios->where('visible', true)->count() }}/8
                            </p>
                        </div>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Testimonio
                        </button>
                    </div>

                    @if($testimonios->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Avatar</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Icono</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Sede</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Carrera</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Tiempo</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($testimonios as $testimonio)
                                        <tr>
                                            <!-- Orden -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverTestimonio({{ $testimonio->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverTestimonio({{ $testimonio->id }}, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            <!-- Avatar -->
                                            <td class="px-4 py-4">
                                                @if($testimonio->avatar)
                                                    <img src="{{ asset('storage/' . $testimonio->avatar) }}" 
                                                         alt="Avatar" 
                                                         class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-xs text-gray-400">
                                                        No
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <!-- Icono -->
                                            <td class="px-4 py-4">
                                                @if($testimonio->icono)
                                                    <img src="{{ asset('storage/' . $testimonio->icono) }}" 
                                                         alt="Icono" 
                                                         class="w-6 h-6 object-cover">
                                                @else
                                                    <div class="w-6 h-6 bg-gray-600 rounded flex items-center justify-center text-xs text-gray-400">
                                                        No
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <!-- Nombre -->
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $testimonio->nombre }}</td>
                                            
                                            <!-- Sede -->
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $testimonio->sede }}</td>
                                            
                                            <!-- Carrera -->
                                            <td class="px-4 py-4 text-sm text-gray-300">
                                                {{ Str::limit($testimonio->carrera, 30) }}
                                            </td>
                                            
                                            <!-- Tiempo -->
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $testimonio->tiempo_testimonio }} meses</td>
                                            
                                            <!-- Estado -->
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonio->visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $testimonio->visible ? 'Visible' : 'Oculto' }}
                                                </span>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <!-- Cambiar Visibilidad -->
                                                    <button onclick="toggleVisibility({{ $testimonio->id }}, {{ $testimonio->visible ? 'false' : 'true' }})" 
                                                            class="{{ $testimonio->visible ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-3 py-2 rounded text-sm transition-colors"
                                                            title="{{ $testimonio->visible ? 'Ocultar testimonio' : 'Mostrar testimonio' }}">
                                                        @if($testimonio->visible)
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                        @endif
                                                    </button>
                                                    
                                                    <!-- Editar -->
                                                    <button onclick="editTestimonio({{ $testimonio->id }})" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Eliminar -->
                                                    <form action="{{ route('admin.testimonios.destroy', $testimonio->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay testimonios</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo testimonio.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Testimonio -->
    <div id="testimonioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-[240px] sm:max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="modalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Testimonio</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="testimonioForm" action="{{ route('admin.testimonios.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="testimonioId" name="testimonio_id" value="">
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
                <!-- Primera fila: Nombre y Sede -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre <span class="text-red-400">*</span></label>
                        <input type="text" id="modalNombre" name="nombre" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="Ej: Marina Gimenez">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Sede <span class="text-red-400">*</span></label>
                        <input type="text" id="modalSede" name="sede" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="Ej: Sede Banfield">
                    </div>
                </div>

                <!-- Segunda fila: Carrera y Tiempo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Carrera <span class="text-red-400">*</span></label>
                        <input type="text" id="modalCarrera" name="carrera" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="Ej: Mecánica y Tecnologías del Automóvil">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Tiempo (meses) <span class="text-red-400">*</span></label>
                        <input type="text" id="modalTiempo" name="tiempo_testimonio" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="Ej: 5">
                    </div>
                </div>

                <!-- Tercera fila: Texto del Testimonio -->
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Texto del Testimonio <span class="text-red-400">*</span></label>
                    <textarea id="modalTexto" name="texto" rows="3" 
                              class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                              placeholder="Escribe el testimonio completo..."></textarea>
                </div>

                <!-- Cuarta fila: Imágenes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Avatar <span class="text-red-400" id="avatarRequired">*</span></label>
                        <input type="file" id="modalAvatar" name="avatar" accept="image/*" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        <p class="text-xs text-gray-400 mt-1" id="avatarHelp">80 × 80 px</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Icono <span class="text-red-400" id="iconoRequired">*</span></label>
                        <input type="file" id="modalIcono" name="icono" accept="image/*" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        <p class="text-xs text-gray-400 mt-1" id="iconoHelp">33 × 33 px</p>
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
                    Agregar Testimonio
                </button>
            </div>
        </form>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;

        function openModal() {
            resetModal();
            document.getElementById('testimonioModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Agregar Testimonio';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Testimonio';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('testimonioForm').action = '{{ route("admin.testimonios.store") }}';
            isEditMode = false;
        }

        function closeModal() {
            document.getElementById('testimonioModal').classList.add('hidden');
            resetModal();
        }

        function resetModal() {
            document.getElementById('testimonioForm').reset();
            document.getElementById('testimonioId').value = '';
            isEditMode = false;
        }

        function editTestimonio(testimonioId) {
            resetModal();
            isEditMode = true;
            
            fetch(`/admin/testimonios/${testimonioId}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Editar Testimonio';
                    document.getElementById('modalSubmitBtn').textContent = 'Actualizar Testimonio';
                    document.getElementById('methodField').value = 'PUT';
                    document.getElementById('testimonioId').value = data.id;
                    document.getElementById('testimonioForm').action = `/admin/testimonios/${data.id}`;
                    
                    // Llenar campos
                    document.getElementById('modalNombre').value = data.nombre;
                    document.getElementById('modalSede').value = data.sede;
                    document.getElementById('modalCarrera').value = data.carrera;
                    document.getElementById('modalTexto').value = data.texto;
                    document.getElementById('modalTiempo').value = data.tiempo_testimonio;
                    
                    // Actualizar ayuda de imágenes para modo edición
                    document.getElementById('avatarHelp').textContent = 'Opcional - mantiene la imagen actual si no se selecciona';
                    document.getElementById('iconoHelp').textContent = 'Opcional - mantiene la imagen actual si no se selecciona';
                    document.getElementById('avatarRequired').textContent = '';
                    document.getElementById('iconoRequired').textContent = '';
                    
                    document.getElementById('testimonioModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error al cargar los datos del testimonio:', error);
                    showMessage('Error al cargar los datos del testimonio', 'error');
                });
        }

        function deleteTestimonio(testimonioId) {
            if (confirm('¿Estás seguro de que quieres eliminar este testimonio?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/testimonios/${testimonioId}`;
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Sistema de botones arriba/abajo
        function moverTestimonio(testimonioId, direccion) {
            console.log('Moviendo testimonio:', { testimonioId, direccion });
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: testimonioId,
                direccion: direccion
            };
            
            console.log('Enviando datos:', requestData);
            
            fetch(`/admin/testimonios/mover`, {
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
                    throw new Error(data.message || 'Error al mover el testimonio');
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

        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar este testimonio?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        function toggleVisibility(testimonioId, newVisibility) {
            fetch(`/admin/testimonios/${testimonioId}/toggle-visibility`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    
                    // Recargar la página para mostrar el nuevo estado
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    // Mostrar mensaje de error sin recargar
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(error.message, 'error');
            });
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Detectar si estamos en modo edición
            const isEditMode = document.getElementById('methodField').value === 'PUT';
            
            // Validar campos básicos
            const nombre = document.querySelector('input[name="nombre"]').value.trim();
            const sede = document.querySelector('input[name="sede"]').value.trim();
            const carrera = document.querySelector('input[name="carrera"]').value.trim();
            const texto = document.querySelector('textarea[name="texto"]').value.trim();
            const tiempo = document.querySelector('input[name="tiempo_testimonio"]').value;
            const avatar = document.querySelector('input[name="avatar"]').files.length;
            const icono = document.querySelector('input[name="icono"]').files.length;
            
            // Validar campos obligatorios
            if (!nombre) {
                showValidationModal("Error", "El nombre es obligatorio");
                return false;
            }
            if (!sede) {
                showValidationModal("Error", "La sede es obligatoria");
                return false;
            }
            if (!carrera) {
                showValidationModal("Error", "La carrera es obligatoria");
                return false;
            }
            if (!texto) {
                showValidationModal("Error", "El texto del testimonio es obligatorio");
                return false;
            }
            if (!tiempo) {
                showValidationModal("Error", "El tiempo es obligatorio");
                return false;
            }
            
            const tiempoNum = parseInt(tiempo);
            if (isNaN(tiempoNum) || tiempoNum < 1 || tiempoNum > 24) {
                showValidationModal("Error", "El tiempo debe ser un número entre 1 y 24 meses");
                return false;
            }
            if (texto.length < 50) {
                showValidationModal("Error", "El texto del testimonio debe tener al menos 50 caracteres");
                return false;
            }
            if (texto.length > 500) {
                showValidationModal("Error", "El texto del testimonio no puede exceder 500 caracteres");
                return false;
            }
            
            // Solo validar imágenes si NO estamos en modo edición
            if (!isEditMode) {
                if (avatar === 0) {
                    showValidationModal("Error", "El avatar es obligatorio");
                    return false;
                }
                if (icono === 0) {
                    showValidationModal("Error", "El icono es obligatorio");
                    return false;
                }
            }
            
            // Si todo está bien, enviar el formulario
            document.getElementById('testimonioForm').submit();
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
                const confirmButton = modal.querySelector('button[class*="bg-red-600"]');
                
                if (cancelButton) {
                    cancelButton.style.display = 'none';
                }
                
                if (confirmButton) {
                    confirmButton.textContent = 'Aceptar';
                    confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700';
                }
            } else {
                // Fallback to native alert
                alert(title + ": " + message);
            }
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
                }, 3000);
            }
        });
    </script>
</x-app-layout>