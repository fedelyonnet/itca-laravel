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
                        <div>
                            <h1 class="text-2xl font-bold">Gestión de Noticias</h1>
                        </div>
                        <a href="{{ route('admin.noticias.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Noticia
                        </a>
                    </div>

                    @if($noticias->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-2 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-16">Orden</th>
                                        <th class="px-2 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-20">Imagen</th>
                                        <th class="px-2 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Título</th>
                                        <th class="px-2 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-24">Fecha</th>
                                        <th class="px-2 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-20">Estado</th>
                                        <th class="px-2 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider w-32">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($noticias as $noticia)
                                        <tr class="hover:bg-gray-700 transition-colors">
                                            <!-- Orden -->
                                            <td class="px-2 py-4 text-center">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverNoticia({{ $noticia->id }}, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            {{ $loop->first ? 'disabled' : '' }}>
                                                        ↑
                                                    </button>
                                                    <button onclick="moverNoticia({{ $noticia->id }}, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            {{ $loop->last ? 'disabled' : '' }}>
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap">
                                                @if($noticia->imagen)
                                                    <img src="{{ asset('storage/' . $noticia->imagen) }}" alt="Imagen" class="h-12 w-12 object-cover rounded">
                                                @else
                                                    <div class="h-12 w-12 bg-gray-600 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-2 py-4">
                                                <div class="text-sm font-medium text-white">{!! Str::limit($noticia->titulo, 50) !!}</div>
                                                <div class="text-sm text-gray-400">{{ Str::limit($noticia->contenido, 100) }}</div>
                                                <div class="mt-1">
                                                    <a href="{{ $noticia->slug }}" class="text-xs text-blue-400 hover:text-blue-300 underline">
                                                        Ver más
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $noticia->fecha_publicacion->format('d/m/Y') }}
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap">
                                                <div class="relative group">
                                                    <button onclick="toggleVisibility({{ $noticia->id }})" 
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $noticia->visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} {{ $noticia->destacada ? 'cursor-not-allowed opacity-50' : 'hover:opacity-80' }}"
                                                            {{ $noticia->destacada ? 'disabled' : '' }}>
                                                        {{ $noticia->visible ? 'Visible' : 'Oculta' }}
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                        @if($noticia->destacada)
                                                            No se puede ocultar (destacada)
                                                        @else
                                                            @if($noticia->visible)
                                                                Ocultar Noticia
                                                            @else
                                                                Mostrar Noticia
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4">
                                                <div class="flex space-x-1">
                                                    <!-- Editar -->
                                                    <div class="relative group">
                                                        <a href="{{ route('admin.noticias.edit', $noticia->id) }}" 
                                                           class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm transition-colors inline-block">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Editar Noticia
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Destacada toggle -->
                                                    <div class="relative group">
                                                        <form action="{{ route('admin.noticias.toggle-destacada', $noticia->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                    class="{{ $noticia->destacada ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-400 hover:bg-gray-500' }} text-white px-2 py-1 rounded text-sm transition-colors">
                                                                @if($noticia->destacada)
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
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            @if($noticia->destacada)
                                                                Quitar del Home
                                                            @else
                                                                Destacar en el Home
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Eliminar -->
                                                    <div class="relative group">
                                                        <form action="{{ route('admin.noticias.destroy', $noticia->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <div class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                            Eliminar Noticia
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
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay noticias</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando una nueva noticia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal placeholder - se implementará después -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-white">Modal de Noticias</h3>
                <p class="text-gray-400">Funcionalidad en desarrollo</p>
                <button onclick="closeModal()" class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Función de confirmación para eliminar
        function confirmDelete(event) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta noticia?')) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Función para cambiar visibilidad
        function toggleVisibility(id) {
            // Verificar si el botón está deshabilitado (noticia destacada)
            const button = event.target;
            if (button.disabled) {
                return;
            }
            
            fetch(`/admin/noticias/${id}/toggle-visibility`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error al cambiar la visibilidad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar la visibilidad');
            });
        }

        // Función para mover noticias
        function moverNoticia(id, direccion) {
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            // Crear formulario y enviar
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/noticias/mover';
            
            // Token CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);
            
            // ID
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;
            form.appendChild(idInput);
            
            // Dirección
            const dirInput = document.createElement('input');
            dirInput.type = 'hidden';
            dirInput.name = 'direccion';
            dirInput.value = direccion;
            form.appendChild(dirInput);
            
            // Enviar formulario
            document.body.appendChild(form);
            form.submit();
        }


        // Auto-hide toast messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.remove('translate-x-full');
                    setTimeout(() => {
                        successMessage.classList.add('translate-x-full');
                    }, 3000);
                }, 100);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.remove('translate-x-full');
                    setTimeout(() => {
                        errorMessage.classList.add('translate-x-full');
                    }, 3000);
                }, 100);
            }
        });
    </script>
</x-app-layout>
