<x-app-layout>
    

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
                        <h1 class="text-2xl font-bold">Gestión de FAQs</h1>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar FAQ
                        </button>
                    </div>

                    @if($dudas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Pregunta</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Respuesta</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($dudas->sortBy('orden') as $duda)
                                        <tr>
                                            <!-- Orden -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverDuda({{ $duda->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverDuda({{ $duda->id }}, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            <!-- Pregunta -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-white max-w-xs">{{ $duda->pregunta }}</div>
                                            </td>
                                            
                                            <!-- Respuesta -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-300 max-w-md overflow-hidden text-ellipsis whitespace-nowrap">
                                                    {{ Str::limit($duda->respuesta, 100) }}
                                                </div>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <!-- Editar -->
                                                    <div class="relative group">
                                                        <button onclick="editDuda({{ $duda->id }})" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Editar FAQ
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Eliminar -->
                                                    <div class="relative group">
                                                        <form action="{{ route('admin.dudas.destroy', $duda->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <div class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Eliminar FAQ
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
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay FAQs</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando tu primera FAQ.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar FAQ -->
    <div id="modalAgregarDuda" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-[240px] sm:max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="modalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Nueva FAQ</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="modalForm" action="{{ route('admin.dudas.store') }}" method="POST" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="dudaId" name="duda_id" value="">
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
                <!-- Pregunta -->
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Pregunta <span class="text-red-400">*</span></label>
                    <input type="text" id="modalPregunta" name="pregunta" 
                           class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                           placeholder="Ej: ¿Quiénes pueden cursar?">
                </div>

                <!-- Respuesta -->
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Respuesta <span class="text-red-400">*</span></label>
                    <textarea id="modalRespuesta" name="respuesta" rows="6" 
                              class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                              placeholder="Escribí la respuesta..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                <button type="button" onclick="resetModal(); closeModal();" 
                        class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                    Cancelar
                </button>
                <button type="submit" id="modalSubmitBtn"
                        class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                    Agregar FAQ
                </button>
            </div>
        </form>
            </div>
        </div>
    </div>

        <script>

        function handleFormSubmit(event) {
            event.preventDefault();
            
            const pregunta = document.querySelector('input[name="pregunta"]').value.trim();
            const respuesta = document.querySelector('textarea[name="respuesta"]').value.trim();
            
            if (!pregunta) {
                showValidationModal("Error", "La pregunta es obligatoria");
                return false;
            }
            if (!respuesta) {
                showValidationModal("Error", "La respuesta es obligatoria");
                return false;
            }
            
            document.getElementById('modalForm').submit();
            return false;
        }

        function openModal() {
            resetModal();
            document.getElementById('modalTitle').textContent = 'Agregar Nueva FAQ';
            document.getElementById('modalForm').action = '{{ route("admin.dudas.store") }}';
            document.getElementById('modalForm').method = 'POST';
            document.getElementById('methodField').value = '';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar FAQ';
            
            document.getElementById('modalAgregarDuda').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalAgregarDuda').classList.add('hidden');
        }

        function editDuda(id) {
            resetModal();
            
            fetch(`/admin/dudas/${id}/data`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const modalTitle = document.getElementById('modalTitle');
                    const modalForm = document.getElementById('modalForm');
                    const methodField = document.getElementById('methodField');
                    const modalSubmitBtn = document.getElementById('modalSubmitBtn');
                    
                    if (modalTitle) modalTitle.textContent = 'Editar FAQ';
                    if (modalForm) {
                        modalForm.action = '{{ route("admin.dudas.update", ":id") }}'.replace(':id', id);
                        modalForm.method = 'POST';
                    }
                    if (methodField) methodField.value = 'PUT';
                    if (modalSubmitBtn) modalSubmitBtn.textContent = 'Actualizar FAQ';
                    
                    const pregunta = document.getElementById('modalPregunta');
                    const respuesta = document.getElementById('modalRespuesta');
                    
                    if (pregunta) pregunta.value = data.pregunta || '';
                    if (respuesta) respuesta.value = data.respuesta || '';
                    
                    const modal = document.getElementById('modalAgregarDuda');
                    if (modal) modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error al cargar datos de la FAQ:', error);
                    alert('Error al cargar los datos de la FAQ: ' + error.message);
                });
        }

        function resetModal() {
            document.getElementById('modalForm').reset();
            document.getElementById('dudaId').value = '';
            document.getElementById('methodField').value = '';
        }

        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar esta FAQ?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        document.getElementById('modalAgregarDuda').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function showValidationModal(title, message) {
            const modal = document.getElementById('confirmation-modal');
            if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
                modal._x_dataStack[0].title = title;
                modal._x_dataStack[0].message = message;
                modal._x_dataStack[0].onConfirm = () => modal._x_dataStack[0].open = false;
                modal._x_dataStack[0].open = true;
                
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
                alert(title + ": " + message);
            }
        }

        function moverDuda(dudaId, direccion) {
            console.log('Moviendo FAQ:', { dudaId, direccion });
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: dudaId,
                direccion: direccion
            };
            
            fetch(`/admin/dudas/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Error al mover la FAQ');
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
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
    </script>
</x-app-layout>







