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
                            <h1 class="text-2xl font-bold">Gestión de Partners</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Partners: {{ $partners->count() }}
                            </p>
                        </div>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Partner
                        </button>
                    </div>

                    @if($partners->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-20">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-24">Logo</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-32">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($partners as $partner)
                                        <tr>
                                            <!-- Orden -->
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverPartner({{ $partner->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverPartner({{ $partner->id }}, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            <!-- Logo -->
                                            <td class="px-4 py-4 text-center">
                                                @if($partner->logo)
                                                    <img src="{{ asset('storage/' . $partner->logo) }}" 
                                                         alt="Logo" 
                                                         class="w-12 h-8 object-contain mx-auto">
                                                @else
                                                    <div class="w-12 h-8 bg-gray-600 rounded flex items-center justify-center text-xs text-gray-400 mx-auto">
                                                        No
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <!-- URL -->
                                            <td class="px-4 py-4 text-sm text-gray-300 text-center" title="{{ $partner->url }}">
                                                <a href="{{ $partner->url }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                                    {{ Str::limit($partner->url, 30) }}
                                                </a>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex space-x-2 justify-center">
                                                    <!-- Editar -->
                                                    <button onclick="editPartner({{ $partner->id }})" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Eliminar -->
                                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay partners</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo partner.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar partner -->
    <div id="partnerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white mb-4">Agregar Partner</h3>
                    
                    <form id="partnerForm" action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                        @csrf
                        <input type="hidden" id="partnerId" name="partner_id" value="">
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
                    <!-- URL -->
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">URL <span class="text-red-400">*</span></label>
                        <input type="text" id="modalUrl" name="url" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="https://example.com">
                    </div>
                    
                    <!-- Logo -->
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Logo <span class="text-red-400" id="logoRequired">*</span></label>
                        <input type="file" id="modalLogo" name="logo" accept="image/*"
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        <p class="text-xs text-gray-400 mt-1" id="logoHelp">Formatos: JPEG, PNG, JPG, GIF, WebP. Máximo 2MB</p>
                    </div>
                </div>
                        
                        <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                            <button type="button" onclick="resetModal(); closeModal();" 
                                    class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                                Cancelar
                            </button>
                            <button type="submit" id="modalSubmitBtn"
                                    class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                                Agregar Partner
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
            document.getElementById('partnerModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Agregar Partner';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Partner';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('partnerForm').action = '{{ route("admin.partners.store") }}';
            isEditMode = false;
        }

        function closeModal() {
            document.getElementById('partnerModal').classList.add('hidden');
        }

        function resetModal() {
            document.getElementById('partnerForm').reset();
            document.getElementById('logoRequired').style.display = 'inline';
            document.getElementById('logoHelp').textContent = 'Formatos: JPEG, PNG, JPG, GIF, WebP. Máximo 2MB';
        }

        function editPartner(id) {
            isEditMode = true;
            document.getElementById('modalTitle').textContent = 'Editar Partner';
            document.getElementById('modalSubmitBtn').textContent = 'Actualizar Partner';
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('partnerForm').action = `/admin/partners/${id}`;
            document.getElementById('logoRequired').style.display = 'none';
            document.getElementById('logoHelp').textContent = 'Dejar vacío para mantener el logo actual';
            
            // Obtener datos del partner
            fetch(`/admin/partners/${id}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalUrl').value = data.url;
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Error al cargar los datos del partner', 'error');
                });
            
            document.getElementById('partnerModal').classList.remove('hidden');
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Detectar si estamos en modo edición
            const isEditMode = document.getElementById('methodField').value === 'PUT';
            
            // Validar campos básicos
            const url = document.querySelector('input[name="url"]').value.trim();
            const logo = document.querySelector('input[name="logo"]').files.length;
            
            // Validar campos obligatorios
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
            
            // Solo validar logo si NO estamos en modo edición
            if (!isEditMode) {
                if (logo === 0) {
                    showValidationModal("Error", "El logo es obligatorio");
                    return false;
                }
            }
            
            // Si todo está bien, enviar el formulario
            document.getElementById('partnerForm').submit();
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

        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar este partner?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        function moverPartner(id, direccion) {
            console.log('Moviendo partner:', { id, direccion });
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: id,
                direccion: direccion
            };
            
            console.log('Enviando datos:', requestData);
            
            fetch(`/admin/partners/mover`, {
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
                    throw new Error(data.message || 'Error al mover el partner');
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
            const container = document.getElementById('toast-container');
            const messageDiv = document.createElement('div');
            messageDiv.className = `px-6 py-4 rounded-lg shadow-lg border-l-4 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out ${
                type === 'success' ? 'bg-green-500 text-white border-green-400' : 'bg-red-500 text-white border-red-400'
            }`;
            
            messageDiv.innerHTML = `
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                ${message}
            `;
            
            container.appendChild(messageDiv);
            
            // Animar entrada
            setTimeout(() => {
                messageDiv.classList.remove('translate-x-full');
            }, 100);
            
            // Auto-remove después de 5 segundos
            setTimeout(() => {
                messageDiv.classList.add('translate-x-full');
                setTimeout(() => {
                    container.removeChild(messageDiv);
                }, 300);
            }, 5000);
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

    </script>
</x-app-layout>