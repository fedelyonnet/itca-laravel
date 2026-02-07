<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <!-- Header -->
                <div class="border-b border-gray-700 bg-gray-900/50 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-white">Editar Noticia</h2>
                        <a href="{{ route('admin.noticias.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver a la grilla
                        </a>
                    </div>
                </div>
                
                <form id="noticiaForm" action="{{ route('admin.noticias.update', $noticia->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="flex flex-row gap-6 p-6" style="display: flex; flex-direction: row; gap: 1.5rem;">
                        
                        <!-- COLUMNA PRINCIPAL (70% - 2 cols) -->
                        <div class="w-2/3 flex-shrink-0 space-y-6" style="width: 66.666%; flex-shrink: 0;">
                            
                            <!-- 📝 CONTENIDO PRINCIPAL -->
                            <div class="bg-gray-900 rounded-lg border border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <h3 class="text-sm font-bold text-white uppercase">Contenido Principal</h3>
                                </div>
                                
                                <div class="p-4 space-y-4">
                                    <!-- Título -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-bold text-gray-300">Título <span class="text-red-400">*</span></label>
                                            <span id="titulo-count" class="text-xs text-gray-500">0/255</span>
                                        </div>
                                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $noticia->titulo) }}" required maxlength="255"
                                               class="w-full bg-gray-800 border-gray-600 text-gray-100 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-base px-4 py-2.5"
                                               placeholder="Ej: Nueva alianza entre ITCA y Royal Enfield">
                                        @error('titulo')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Extracto -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-bold text-gray-300 flex items-center gap-2">
                                                Extracto
                                                <span class="text-xs text-gray-500 font-normal italic">(opcional)</span>
                                            </label>
                                            <span id="extracto-count" class="text-xs text-gray-500">0/500</span>
                                        </div>
                                        <textarea name="extracto" id="extracto" rows="2" maxlength="500"
                                                  class="w-full bg-gray-800 border-gray-600 text-gray-100 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm px-4 py-2.5 resize-none"
                                                  placeholder="Resumen breve que aparecerá en el listado de noticias...">{{ old('extracto', $noticia->extracto) }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1 font-bold">Este texto se mostrará en las tarjetas de vista previa y en la vista previa del home si es destacada</p>
                                    </div>

                                    <!-- Contenido -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-bold text-gray-300">Contenido <span class="text-red-400">*</span></label>
                                            <button type="button" onclick="document.getElementById('formatHelp').classList.toggle('hidden')" 
                                                    class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Ayuda de formato
                                            </button>
                                        </div>
                                        
                                        <div id="formatHelp" class="hidden mb-3 bg-blue-900/20 border border-blue-700/30 rounded p-3">
                                            <p class="text-xs font-bold text-blue-300 mb-2">Etiquetas de formato:</p>
                                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-400">
                                                <div><code class="text-blue-400 bg-blue-900/30 px-2 py-0.5 rounded">*/texto/*</code> = Negrita</div>
                                                <div><code class="text-blue-400 bg-blue-900/30 px-2 py-0.5 rounded">&lt;titulo&gt;</code> = Subtítulo</div>
                                                <div><code class="text-blue-400 bg-blue-900/30 px-2 py-0.5 rounded">#</code> = Salto de línea</div>
                                                <div><code class="text-blue-400 bg-blue-900/30 px-2 py-0.5 rounded">http://...</code> = Link automático</div>
                                            </div>
                                        </div>

                                        <textarea name="contenido" id="contenido" rows="16" required
                                                  class="w-full bg-gray-800 border-gray-600 text-gray-100 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm px-4 py-3 font-mono leading-relaxed"
                                                  placeholder="Escribe el contenido completo de la noticia aquí...">{{ old('contenido', $noticia->contenido) }}</textarea>
                                        @error('contenido')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- 🖼️ IMÁGENES -->
                            <div class="bg-gray-900 rounded-lg border border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <h3 class="text-sm font-bold text-white uppercase">Imágenes</h3>
                                </div>
                                
                                <div class="p-4">
                                    <div class="grid grid-cols-3 gap-4">
                                        <!-- Hero -->
                                        <div>
                                            <div class="flex items-center justify-center gap-1 mb-2">
                                                <label class="text-xs font-bold text-gray-400 uppercase">Principal</label>
                                            </div>
                                            <div class="group">
                                                <input type="file" name="imagen_hero" id="imagen_hero" accept="image/*" class="hidden" data-no-auto-submit="true" onchange="previewImage(this, 'heroPreview', 'heroPlaceholder')">
                                                <label for="imagen_hero" class="relative flex flex-col items-center justify-center w-full py-10 bg-gray-800 rounded-lg overflow-hidden border border-gray-600 hover:border-green-500 hover:bg-gray-750 cursor-pointer transition-all">
                                                <img id="heroPreview" src="{{ $noticia->imagen_hero ? asset('storage/' . $noticia->imagen_hero) : '' }}" class="w-full h-48 object-cover rounded-md {{ $noticia->imagen_hero ? '' : 'hidden' }}">
                                                <div id="heroPlaceholder" class="flex flex-col items-center justify-center text-gray-500 group-hover:text-green-400 transition-colors {{ $noticia->imagen_hero ? 'hidden' : '' }}">
                                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    <span class="text-sm font-bold">Subir Imagen</span>
                                                    <span class="text-xs mt-1 text-gray-400">1920x1080</span>
                                                </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Thumb -->
                                        <div>
                                            <div class="flex items-center justify-center gap-1 mb-2">
                                                <label class="text-xs font-bold text-gray-400 uppercase">Miniatura</label>
                                            </div>
                                            <div class="group">
                                                <input type="file" name="imagen_thumb" id="imagen_thumb" accept="image/*" class="hidden" data-no-auto-submit="true" onchange="previewImage(this, 'thumbPreview', 'thumbPlaceholder')">
                                                <label for="imagen_thumb" class="relative flex flex-col items-center justify-center w-full py-10 bg-gray-800 rounded-lg overflow-hidden border border-gray-600 hover:border-green-500 hover:bg-gray-750 cursor-pointer transition-all">
                                                <img id="thumbPreview" src="{{ $noticia->imagen_thumb ? asset('storage/' . $noticia->imagen_thumb) : '' }}" class="w-full h-48 object-cover rounded-md {{ $noticia->imagen_thumb ? '' : 'hidden' }}">
                                                <div id="thumbPlaceholder" class="flex flex-col items-center justify-center text-gray-500 group-hover:text-green-400 transition-colors {{ $noticia->imagen_thumb ? 'hidden' : '' }}">
                                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    <span class="text-sm font-bold">Subir Imagen</span>
                                                    <span class="text-xs mt-1 text-gray-400">800x800</span>
                                                </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Banner -->
                                        <div>
                                            <div class="flex items-center justify-center gap-1 mb-2">
                                                <label class="text-xs font-bold text-gray-400 uppercase">Banner</label>
                                            </div>
                                            <div class="group">
                                                <input type="file" name="banner_publicitario" id="banner_publicitario" accept="image/*" class="hidden" data-no-auto-submit="true" onchange="previewImage(this, 'bannerPreview', 'bannerPlaceholder')">
                                                <label for="banner_publicitario" class="relative flex flex-col items-center justify-center w-full py-10 bg-gray-800 rounded-lg overflow-hidden border border-gray-600 hover:border-green-500 hover:bg-gray-750 cursor-pointer transition-all">
                                                <img id="bannerPreview" src="{{ $noticia->banner_publicitario ? asset('storage/' . $noticia->banner_publicitario) : '' }}" class="w-full h-48 object-cover rounded-md {{ $noticia->banner_publicitario ? '' : 'hidden' }}">
                                                <div id="bannerPlaceholder" class="flex flex-col items-center justify-center text-gray-500 group-hover:text-green-400 transition-colors {{ $noticia->banner_publicitario ? 'hidden' : '' }}">
                                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    <span class="text-sm font-bold">Subir Banner</span>
                                                    <span class="text-xs mt-1 text-gray-400">Opcional</span>
                                                </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- SIDEBAR (30% - 1 col) -->
                        <div class="w-1/3 flex-shrink-0 space-y-6" style="width: 33.333%; flex-shrink: 0;">
                            
                            <!-- 📅 PUBLICACIÓN -->
                            <div class="bg-gray-900 rounded-lg border border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <h3 class="text-sm font-bold text-white uppercase">Publicación</h3>
                                </div>
                                
                                <div class="p-4 space-y-4">
                                    <!-- Fecha -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Fecha <span class="text-red-400">*</span></label>
                                        <input type="date" name="fecha_publicacion" value="{{ old('fecha_publicacion', $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('Y-m-d') : date('Y-m-d')) }}" required
                                               class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 px-3 py-2">
                                    </div>

                                    <!-- Visible -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Visible en el sitio</label>
                                        <div class="flex gap-3">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="visible" value="1" {{ old('visible', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-green-500 bg-gray-700 border-gray-600 focus:ring-green-500">
                                                <span class="ml-2 text-sm text-gray-300">Sí</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="visible" value="0" {{ old('visible', '1') == '0' ? 'checked' : '' }} class="w-4 h-4 text-green-500 bg-gray-700 border-gray-600 focus:ring-green-500">
                                                <span class="ml-2 text-sm text-gray-300">No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Destacada -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Destacada en home</label>
                                        <div class="flex gap-3">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="destacada" value="1" {{ old('destacada', '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-green-500 bg-gray-700 border-gray-600 focus:ring-green-500">
                                                <span class="ml-2 text-sm text-gray-300">Sí</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="destacada" value="0" {{ old('destacada', '0') == '0' ? 'checked' : '' }} class="w-4 h-4 text-green-500 bg-gray-700 border-gray-600 focus:ring-green-500">
                                                <span class="ml-2 text-sm text-gray-300">No</span>
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2 font-bold">Solo una noticia puede estar destacada</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 🏷️ CATEGORÍAS -->
                            <div class="bg-gray-900 rounded-lg border border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-700 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        <h3 class="text-sm font-bold text-white uppercase">Categorías</h3>
                                    </div>
                                    <button type="button" id="btn-add-cat" class="text-xs bg-gray-800 hover:bg-gray-700 text-green-400 border border-gray-600 rounded px-2 py-1 transition-colors flex items-center gap-1" title="Agregar Categoría">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                                <div class="p-4">
                                    <!-- Input para nueva categoría (Oculto por defecto) -->
                                    <div id="new-cat-container" class="hidden mb-3">
                                        <div class="flex gap-2">
                                            <input type="text" id="new-cat-name" class="flex-1 bg-gray-800 border-gray-600 rounded text-xs text-gray-100 px-2 py-1" placeholder="Nombre categoría...">
                                            <button type="button" id="btn-save-cat" class="bg-green-600 hover:bg-green-500 text-white text-xs px-2 py-1 rounded">OK</button>
                                        </div>
                                    </div>

                                    <!-- Listado de categorías -->
                                    <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar p-1" id="categorias-list">
                                        @forelse($categorias as $categoria)
                                            <label class="flex items-center space-x-2 cursor-pointer group">
                                                <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}" 
                                                    {{ in_array($categoria->id, old('categorias', $noticia->categorias->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    class="w-4 h-4 text-green-500 bg-gray-700 border-gray-600 rounded focus:ring-green-500">
                                                <span class="text-sm text-gray-300 group-hover:text-white transition-colors" id="cat-text-{{ $categoria->id }}">{{ $categoria->nombre }}</span>
                                                
                                                <div class="ml-auto flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button type="button" onclick="editCategory({{ $categoria->id }}, '{{ $categoria->nombre }}')" class="p-1 text-gray-500 hover:text-blue-400" title="Editar">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <button type="button" onclick="deleteCategory({{ $categoria->id }}, this)" class="p-1 text-gray-500 hover:text-red-400" title="Eliminar">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </label>
                                        @empty
                                            <p class="text-xs text-gray-500 italic text-center py-2" id="no-cats-msg">No hay categorías créadas aún.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- ✍️ AUTOR -->
                            <div class="bg-gray-900 rounded-lg border border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <h3 class="text-sm font-bold text-white uppercase">Autor</h3>
                                    <span class="ml-auto text-xs text-gray-500">Opcional</span>
                                </div>
                                
                                <div class="p-4 space-y-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Nombre</label>
                                        <input type="text" name="autor_nombre" value="{{ old('autor_nombre', $noticia->autor_nombre) }}"
                                               class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500 px-3 py-2"
                                               placeholder="Ej: María García">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Cargo</label>
                                        <input type="text" name="autor_puesto" value="{{ old('autor_puesto', $noticia->autor_puesto) }}"
                                               class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500 px-3 py-2"
                                               placeholder="Ej: Instructora">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- BOTONES -->
                    <div class="border-t border-gray-700 bg-gray-900/30 px-6 py-4">
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('admin.noticias.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-10 rounded-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                CANCELAR
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-12 rounded-lg shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                GUARDAR CAMBIOS
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Manejo de envío AJAX
        document.getElementById('noticiaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Limpiar errores previos
            document.querySelectorAll('.js-error-msg').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
            
            // Mostrar estado de carga (opcional: deshabilitar botón)
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...';

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                
                if (response.status === 422) {
                    // Errores de validación
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    Object.keys(data.errors).forEach(field => {
                        // Buscar el input correspondiente
                        let input = document.querySelector(`[name="${field}"]`);
                        
                        // Si no lo encuentra directo (casos especiales como arrays), intentar buscar variantes
                        if (!input && field.includes('.')) {
                             // Lógica para campos anidados si fuera necesario
                        }

                        if (input) {
                            // Marcar borde rojo
                            input.classList.add('border-red-500');
                            
                            // Crear mensaje de error
                            const errorMsg = document.createElement('p');
                            errorMsg.classList.add('text-red-400', 'text-xs', 'mt-1', 'js-error-msg');
                            errorMsg.innerText = data.errors[field][0];
                            
                            // Insertar mensaje. 
                            // Para inputs de archivo en contenedores especiales, buscar el padre adecuado
                            if (input.type === 'file' && input.closest('.group')) {
                                input.closest('.group').appendChild(errorMsg);
                            } else {
                                input.parentElement.appendChild(errorMsg);
                            }
                        }
                    });
                    
                    // Mostrar alerta flotante
                    if (window.showValidationModal) {
                        window.showValidationModal('Error de validación', 'Por favor revisa los campos marcados en rojo.');
                    } else {
                        alert('Por favor revisa los errores en el formulario.');
                    }
                    
                    // Scroll al primer error
                    const firstError = document.querySelector('.js-error-msg');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } 
                else if (data.success) {
                    // Éxito: Redirigir
                    window.location.href = data.redirect;
                } else {
                    // Otro error
                    throw new Error('Error desconocido');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                alert('Ocurrió un error inesperado. Por favor intenta nuevamente.');
            });
        });

        // Preview de imágenes
        function previewImage(input, previewId, placeholderId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                    document.getElementById(previewId).classList.remove('hidden');
                    document.getElementById(placeholderId).classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Contador de caracteres para título
        const tituloInput = document.getElementById('titulo');
        const tituloCount = document.getElementById('titulo-count');
        if (tituloInput) {
            tituloInput.addEventListener('input', function() {
                tituloCount.textContent = this.value.length + '/255';
            });
            // Inicializar contador
            tituloCount.textContent = tituloInput.value.length + '/255';
        }

        // Contador de caracteres para extracto
        const extractoInput = document.getElementById('extracto');
        const extractoCount = document.getElementById('extracto-count');
        if (extractoInput) {
            extractoInput.addEventListener('input', function() {
                extractoCount.textContent = this.value.length + '/500';
            });
            // Inicializar contador
            extractoCount.textContent = extractoInput.value.length + '/500';
        }
        // Lógica de Categorías
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica de Categorías (Integrada)
            const btnAddCat = document.getElementById('btn-add-cat');
            const catContainer = document.getElementById('new-cat-container');
            const catInput = document.getElementById('new-cat-name');
            const btnSaveCat = document.getElementById('btn-save-cat');
            const catList = document.getElementById('categorias-list');
            
            // Estado para edición
            let editingCatId = null;

            if (btnAddCat) {
                // Alternar visibilidad del input
                btnAddCat.addEventListener('click', () => {
                    catContainer.classList.toggle('hidden');
                    if (!catContainer.classList.contains('hidden')) {
                        catInput.focus();
                        // Resetear estado si estaba editando
                        if (editingCatId) {
                            editingCatId = null;
                            catInput.value = '';
                            btnSaveCat.innerText = 'OK';
                        }
                    }
                });

                // Guardar (Crear o Editar)
                btnSaveCat.addEventListener('click', handleSaveCategory);
                catInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        handleSaveCategory();
                    }
                });

                function handleSaveCategory() {
                    const nombre = catInput.value.trim();
                    if (!nombre) return;

                    const url = editingCatId 
                        ? `{{ url('admin/noticias/categorias') }}/${editingCatId}`
                        : '{{ route("admin.noticias.categorias.store") }}';
                    
                    const method = editingCatId ? 'PUT' : 'POST';

                    // Bloquear UI
                    btnSaveCat.disabled = true;
                    btnSaveCat.innerText = '...';

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ nombre: nombre })
                    })
                    .then(res => res.json())
                    .then(data => {
                        btnSaveCat.disabled = false;
                        btnSaveCat.innerText = 'OK';

                        if (data.success) {
                            if (editingCatId) {
                                // ACTUALIZAR DOM existente
                                const textSpan = document.getElementById(`cat-text-${editingCatId}`);
                                if (textSpan) textSpan.innerText = data.categoria.nombre;
                                
                                // Resetear
                                editingCatId = null;
                                catInput.value = '';
                                catContainer.classList.add('hidden');
                            } else {
                                // CREAR nuevo elemento en la lista
                                const newLabel = document.createElement('label');
                                newLabel.className = 'flex items-center space-x-2 cursor-pointer group bg-gray-800/30 p-2 rounded hover:bg-gray-800 transition-colors';
                                newLabel.id = `cat-item-${data.categoria.id}`;
                                newLabel.innerHTML = `
                                    <input type="checkbox" name="categorias[]" value="${data.categoria.id}" checked
                                        class="w-4 h-4 text-green-500 bg-gray-700 border-gray-600 rounded focus:ring-green-500">
                                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors flex-1" id="cat-text-${data.categoria.id}">${data.categoria.nombre}</span>
                                    
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" onclick="editCategory(${data.categoria.id}, '${data.categoria.nombre}')" class="p-1 text-gray-500 hover:text-blue-400" title="Editar">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button type="button" onclick="deleteCategory(${data.categoria.id})" class="p-1 text-gray-500 hover:text-red-400" title="Eliminar">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                `;
                                
                                // Eliminar mensaje de "no hay categorías" si existe
                                const noCatsMsg = document.getElementById('no-cats-msg');
                                if (noCatsMsg) noCatsMsg.remove();
                                
                                catList.prepend(newLabel);
                                catInput.value = '';
                                catContainer.classList.add('hidden');
                            }
                        } else {
                            if (window.showAlertModal) {
                                showAlertModal('Atención', data.message || 'Error desconocido');
                            } else {
                                alert('Error: ' + (data.message || 'Error desconocido'));
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        btnSaveCat.disabled = false;
                        btnSaveCat.innerText = 'OK';
                        if (window.showAlertModal) {
                            showAlertModal('Error', 'Error de conexión al servidor');
                        } else {
                            alert('Error de conexión');
                        }
                    });
                }

                // Funciones Globales para los botones inyectados
                window.editCategory = function(id, nombre) {
                    editingCatId = id;
                    catInput.value = nombre;
                    btnSaveCat.innerText = 'Guardar';
                    catContainer.classList.remove('hidden');
                    catInput.focus();
                };

                window.deleteCategory = function(id) {
                    const performDelete = () => {
                        fetch(`{{ url('admin/noticias/categorias') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const item = document.getElementById(`cat-item-${id}`) || document.querySelector(`input[value="${id}"]`).closest('label');
                                if (item) item.remove();
                            } else {
                                if (window.showAlertModal) {
                                    showAlertModal('Error', 'No se pudo eliminar la categoría.');
                                } else {
                                    alert('No se pudo eliminar la categoría.');
                                }
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            if (window.showAlertModal) {
                                showAlertModal('Error', 'Error de conexión al eliminar');
                            } else {
                                alert('Error de conexión');
                            }
                        });
                    };

                    if (window.showConfirmModal) {
                        showConfirmModal(
                            '¿Estás seguro?', 
                            '¿Estás seguro de eliminar esta categoría? Las noticias asociadas dejarán de mostrarla, pero no se borrarán.',
                            performDelete
                        );
                    } else {
                        if (confirm('¿Estás seguro de eliminar esta categoría?')) {
                            performDelete();
                        }
                    }
                };
            }
        });
    </script>
</x-app-layout>
