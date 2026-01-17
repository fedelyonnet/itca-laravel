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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Sección Info de Contacto -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Gestión Datos de Contacto</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Información General
                            </p>
                        </div>
                        <button onclick="openModal('info')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Info
                        </button>
                    </div>

                    @if($contactosInfo->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Descripción</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Contenido</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($contactosInfo as $contacto)
                                        <tr>
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $contacto->descripcion }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ Str::limit($contacto->contenido, 50) }}</td>
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <div class="relative group">
                                                        <button onclick="editContacto({{ $contacto->id }})" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Editar
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="relative group">
                                                        <form action="{{ route('admin.contacto.destroy', $contacto->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <div class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Eliminar
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
                            <p class="mt-1 text-sm text-gray-300">No hay información de contacto cargada.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sección Redes Sociales -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold">Redes Sociales</h2>
                        </div>
                        <button onclick="openModal('social')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Red Social
                        </button>
                    </div>

                    @if($contactosSocial->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Icono</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Red Social</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">URL</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($contactosSocial as $contacto)
                                        <tr>
                                            <td class="px-4 py-4">
                                                @if($contacto->icono)
                                                    <img src="{{ asset($contacto->icono) }}" alt="Icono" class="w-6 h-6 object-cover">
                                                @else
                                                    <span class="text-xs text-gray-400">Sin icono</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $contacto->descripcion }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ Str::limit($contacto->contenido, 50) }}</td>
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <div class="relative group">
                                                        <button onclick="editContacto({{ $contacto->id }})" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Editar
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="relative group">
                                                        <form action="{{ route('admin.contacto.destroy', $contacto->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <div class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Eliminar
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
                            <p class="mt-1 text-sm text-gray-300">No hay redes sociales cargadas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Contacto -->
    <div id="contactoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white">Agregar Dato</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="contactoForm" action="{{ route('admin.contacto.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="contactoId" name="contacto_id" value="">
                    <input type="hidden" id="methodField" name="_method" value="">
                    <input type="hidden" id="modalTipo" name="tipo" value="info">
            
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
                            <label class="block text-xs font-medium text-gray-300 mb-1">Descripción <span class="text-red-400">*</span></label>
                            <input type="text" id="modalDescripcion" name="descripcion" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Teléfono, Instagram">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Contenido <span class="text-red-400">*</span></label>
                            <input type="text" id="modalContenido" name="contenido" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: 0810-..., https://...">
                        </div>

                        <div id="divIcono" class="hidden">
                            <label class="block text-xs font-medium text-gray-300 mb-1">Icono</label>
                            <input type="file" id="modalIcono" name="icono" accept="image/*" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        </div>
                    </div>
            
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="resetModal(); closeModal();" 
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="modalSubmitBtn"
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-sm">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(tipo) {
            resetModal();
            document.getElementById('contactoModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = tipo === 'info' ? 'Agregar Información' : 'Agregar Red Social';
            document.getElementById('modalTipo').value = tipo;
            
            if (tipo === 'social') {
                document.getElementById('divIcono').classList.remove('hidden');
            } else {
                document.getElementById('divIcono').classList.add('hidden');
            }
            
            document.getElementById('methodField').value = 'POST';
            document.getElementById('contactoForm').action = '{{ route("admin.contacto.store") }}';
        }

        function closeModal() {
            document.getElementById('contactoModal').classList.add('hidden');
            resetModal();
        }

        function resetModal() {
            document.getElementById('contactoForm').reset();
            document.getElementById('contactoId').value = '';
            document.getElementById('divIcono').classList.add('hidden');
        }

        function editContacto(id) {
            resetModal();
            
            fetch(`/admin/contacto/${id}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Editar Dato';
                    document.getElementById('methodField').value = 'PUT';
                    document.getElementById('contactoId').value = data.id;
                    document.getElementById('modalTipo').value = data.tipo;
                    document.getElementById('contactoForm').action = `/admin/contacto/${data.id}`;
                    
                    document.getElementById('modalDescripcion').value = data.descripcion;
                    document.getElementById('modalContenido').value = data.contenido;
                    
                    if (data.tipo === 'social') {
                        document.getElementById('divIcono').classList.remove('hidden');
                    }
                    
                    document.getElementById('contactoModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar datos');
                });
        }

        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar este dato?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        function handleFormSubmit(event) {
            // Validaciones básicas si fueran necesarias
            return true;
        }

        // Mensajes flash
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
