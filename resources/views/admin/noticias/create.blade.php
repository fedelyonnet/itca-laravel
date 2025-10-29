<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Agregar Nueva Noticia</h1>
                            <p class="text-sm text-gray-400 mt-1">Completá los campos para crear una nueva noticia</p>
                        </div>
                        <a href="{{ route('admin.noticias') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                            ← Volver a Noticias
                        </a>
                    </div>

                    <form action="{{ route('admin.noticias.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate onsubmit="return validateForm(event)">
                        @csrf
                        
                        <!-- Primer renglón: Título, Fecha, Visible -->
                        <div class="grid grid-cols-12 gap-6">
                            <!-- Título -->
                            <div class="col-span-6">
                                <label for="titulo" class="block text-sm font-medium text-gray-300 mb-2">
                                    Título de la Noticia <span class="text-red-400">*</span>
                                </label>
                                <input type="text" 
                                       id="titulo" 
                                       name="titulo" 
                                       value="{{ old('titulo') }}"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('titulo') border-red-500 @enderror"
                                       placeholder="Ingresá el título de la noticia (usá &lt;strong&gt;&lt;/strong&gt; para texto en negrita)"
                                       required>
                                @error('titulo')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fecha -->
                            <div class="col-span-3">
                                <label for="fecha_publicacion" class="block text-sm font-medium text-gray-300 mb-2">
                                    Fecha <span class="text-red-400">*</span>
                                </label>
                                <input type="text" 
                                       id="fecha_publicacion" 
                                       name="fecha_publicacion" 
                                       value="{{ old('fecha_publicacion', now()->format('d/m/Y')) }}"
                                       placeholder="dd/mm/aaaa"
                                       pattern="\d{2}/\d{2}/\d{4}"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fecha_publicacion') border-red-500 @enderror"
                                       required>
                                @error('fecha_publicacion')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Visible -->
                            <div class="col-span-3 flex items-end">
                                <div class="flex items-center h-10">
                                    <input type="checkbox" 
                                           id="visible" 
                                           name="visible" 
                                           value="1"
                                           {{ old('visible', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <label for="visible" class="ml-2 text-sm text-gray-300">
                                        Visible
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Segundo renglón: Contenido -->
                        <div>
                            <label for="contenido" class="block text-sm font-medium text-gray-300 mb-2">
                                Contenido de la Noticia <span class="text-red-400">*</span>
                            </label>
                            <textarea id="contenido" 
                                      name="contenido" 
                                      rows="8"
                                      class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contenido') border-red-500 @enderror"
                                      placeholder="Escribí el contenido completo de la noticia..."
                                      required>{{ old('contenido') }}</textarea>
                            @error('contenido')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tercer renglón: Imagen -->
                        <div>
        <label for="imagen" class="block text-sm font-medium text-gray-300 mb-2">
            Imagen de la Noticia <span class="text-red-400">*</span>
        </label>
                            <input type="file" 
                                   id="imagen" 
                                   name="imagen" 
                                   accept="image/*"
                                   data-no-auto-submit>
                            <p class="mt-1 text-xs text-gray-400">
                                Formatos permitidos: JPG, PNG, GIF, WebP. Tamaño máximo: 2MB - Tamaño recomendado: 653x215px
                            </p>
                            @error('imagen')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="{{ route('admin.noticias') }}" 
                               class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors">
                                Crear Noticia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-resize textarea
        document.getElementById('contenido').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Mostrar errores de validación usando el modal de confirmación
        @if($errors->any())
            @foreach($errors->all() as $error)
                showValidationModal('Error', '{{ $error }}');
            @endforeach
        @endif

        // Función para validar el formulario
        function validateForm(event) {
            event.preventDefault();
            
            const titulo = document.querySelector('input[name="titulo"]').value.trim();
            const contenido = document.querySelector('textarea[name="contenido"]').value.trim();
            const fechaPublicacion = document.querySelector('input[name="fecha_publicacion"]').value.trim();
            const imagen = document.querySelector('input[name="imagen"]').files.length;
            
            // Validar campos obligatorios
            if (!titulo) {
                showValidationModal('Error', 'El título es obligatorio');
                return false;
            }
            if (!contenido) {
                showValidationModal('Error', 'El contenido es obligatorio');
                return false;
            }
            if (!fechaPublicacion) {
                showValidationModal('Error', 'La fecha de publicación es obligatoria');
                return false;
            }
            if (imagen === 0) {
                showValidationModal('Error', 'La imagen es obligatoria');
                return false;
            }
            
            // Si todo está bien, enviar el formulario
            event.target.submit();
            return false;
        }

        // Función para mostrar modal de validación
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
                    confirmButton.className = confirmButton.className.replace('bg-red-600', 'bg-blue-600').replace('hover:bg-red-700', 'hover:bg-blue-700');
                }
            } else {
                // Fallback to native alert
                alert(title + ": " + message);
            }
        }
    </script>
</x-app-layout>
