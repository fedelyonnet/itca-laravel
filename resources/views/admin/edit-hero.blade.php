<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js'])

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg">
                <h1 class="text-2xl font-bold mb-6 text-white">Editar Sección Hero + Sticky Bar</h1>
                
                @if(session('success'))
                    <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-all duration-500 ease-in-out">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(() => {
                            const toast = document.getElementById('toast');
                            toast.classList.remove('translate-x-full');
                            setTimeout(() => {
                                toast.classList.add('translate-x-full');
                                setTimeout(() => { toast.remove(); }, 500);
                            }, 3000);
                        }, 100);
                    </script>
                @endif
                
                <!-- Tabs Navigation -->
                <div x-data="{ activeTab: 'desktop' }">
                    <div class="flex border-b border-gray-700 mb-6">
                        <button 
                            @click="activeTab = 'desktop'" 
                            class="px-6 py-3 font-medium text-sm transition-colors focus:outline-none border-b-2"
                            :class="activeTab === 'desktop' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-600'"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Desktop
                            </span>
                        </button>
                        <button 
                            @click="activeTab = 'mobile'" 
                            class="px-6 py-3 font-medium text-sm transition-colors focus:outline-none border-b-2"
                            :class="activeTab === 'mobile' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-600'"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Mobile
                            </span>
                        </button>
                        <button 
                            @click="activeTab = 'sticky'" 
                            class="px-6 py-3 font-medium text-sm transition-colors focus:outline-none border-b-2"
                            :class="activeTab === 'sticky' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-600'"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                Sticky Bar
                            </span>
                        </button>
                    </div>

                    <!-- TAB 1: DESKTOP -->
                    <div x-show="activeTab === 'desktop'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-gray-700/50 p-6 rounded-xl border border-gray-600">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-white">Vista de Escritorio</h2>
                                <span class="bg-blue-900/50 text-blue-200 text-xs px-3 py-1 rounded-full border border-blue-700/50">Resolución recomendada: 1366px+</span>
                            </div>
                            
                            <div class="grid grid-cols-12 gap-6">
                                <!-- Hero Principal (Ocupa todo el ancho superior de la grilla visual) -->
                                <div class="col-span-12">
                                    <h3 class="text-gray-400 text-sm mb-2 font-medium">Imagen Principal (Izquierda)</h3>
                                    <x-admin.hero-item 
                                        version="desktop" 
                                        type="img1" 
                                        :item="$heroes->where('version', 'desktop')->where('type', 'img1')->first()" 
                                        title="Principal" 
                                        size="768 × 206 px"
                                        placeholderText="Banner Horizontal"
                                        containerClass="w-full desktop-hero-img1 shadow-lg"
                                    />
                                </div>
                                
                                <!-- Secundarios -->
                                <div class="col-span-12 md:col-span-6">
                                    <h3 class="text-gray-400 text-sm mb-2 font-medium">Imagen Secundaria (Centro)</h3>
                                    <x-admin.hero-item 
                                        version="desktop" 
                                        type="img2" 
                                        :item="$heroes->where('version', 'desktop')->where('type', 'img2')->first()" 
                                        title="Secundaria" 
                                        size="376 × 418 px"
                                        placeholderText="Vertical"
                                        containerClass="w-full desktop-hero-img2 shadow-lg"
                                    />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <h3 class="text-gray-400 text-sm mb-2 font-medium">Video (Derecha)</h3>
                                    <x-admin.hero-item 
                                        version="desktop" 
                                        type="video" 
                                        :item="$heroes->where('version', 'desktop')->where('type', 'video')->first()" 
                                        title="Video" 
                                        size="376 × 418 px"
                                        placeholderText="Video Loop"
                                        containerClass="w-full desktop-hero-video shadow-lg"
                                        :isVideo="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: MOBILE -->
                    <div x-show="activeTab === 'mobile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="bg-gray-700/50 p-6 rounded-xl border border-gray-600">
                             <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-white">Vista Móvil</h2>
                                <span class="bg-purple-900/50 text-purple-200 text-xs px-3 py-1 rounded-full border border-purple-700/50">Visible en celulares</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <h3 class="text-gray-400 text-sm mb-2 font-medium">Imagen 1</h3>
                                    <x-admin.hero-item 
                                        version="mobile" 
                                        type="img1" 
                                        :item="$heroes->where('version', 'mobile')->where('type', 'img1')->first()" 
                                        title="Carrousel 1" 
                                        size="417 × 462 px"
                                        placeholderText="Mobile 1"
                                        containerClass="w-full mobile-hero-container shadow-lg"
                                    />
                                </div>

                                <div>
                                    <h3 class="text-gray-400 text-sm mb-2 font-medium">Imagen 2</h3>
                                    <x-admin.hero-item 
                                        version="mobile" 
                                        type="img2" 
                                        :item="$heroes->where('version', 'mobile')->where('type', 'img2')->first()" 
                                        title="Carrousel 2" 
                                        size="417 × 462 px"
                                        placeholderText="Mobile 2"
                                        containerClass="w-full mobile-hero-container shadow-lg"
                                    />
                                </div>
                                
                                <div>
                                    <h3 class="text-gray-400 text-sm mb-2 font-medium">Video</h3>
                                    <x-admin.hero-item 
                                        version="mobile" 
                                        type="video" 
                                        :item="$heroes->where('version', 'mobile')->where('type', 'video')->first()" 
                                        title="Carrousel Video" 
                                        size="417 × 462 px"
                                        placeholderText="Video Mobile"
                                        containerClass="w-full mobile-hero-container shadow-lg"
                                        :isVideo="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: STICKY BAR -->
                    <div x-show="activeTab === 'sticky'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="bg-gray-700/50 p-6 rounded-xl border border-gray-600 max-w-2xl mx-auto">
                            <div class="flex items-center justify-between mb-6 border-b border-gray-600 pb-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-white">Sticky Bar</h2>
                                    <p class="text-gray-400 text-sm mt-1">Barra de anuncios fijada en el borde inferior</p>
                                </div>
                                
                                <!-- Checkbox Visible -->
                                <form action="{{ route('admin.sticky-bar.update') }}" method="POST" class="inline">
                                    @csrf
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="visible" id="visible" value="1" 
                                               {{ $stickyBar->visible ? 'checked' : '' }}
                                               class="sr-only peer" onchange="this.form.submit()">
                                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                        <span class="ml-3 text-sm font-medium text-white">Activa</span>
                                        
                                        <!-- Inputs ocultos para mantener valores al hacer toggle -->
                                        <input type="hidden" name="texto" value="{{ $stickyBar->texto }}">
                                        <input type="hidden" name="color" value="{{ $stickyBar->color }}">
                                        <input type="hidden" name="text_color" value="{{ $stickyBar->text_color ?? '#ffffff' }}">
                                    </label>
                                </form>
                            </div>
                            
                            <!-- Formulario Sticky Bar -->
                            <form action="{{ route('admin.sticky-bar.update') }}" method="POST" class="space-y-5">
                                @csrf
                                <input type="hidden" name="visible" id="visibleHidden" value="{{ $stickyBar->visible ? '1' : '0' }}">
                                
                                <div class="space-y-2">
                                    <label class="block text-gray-300 text-sm font-medium">Contenido del anuncio</label>
                                    <div class="relative">
                                        <input type="text" name="texto" id="texto" 
                                               class="w-full pl-4 pr-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                               placeholder="Ej: ¡Oferta especial! text para bold"
                                               value="{{ $stickyBar->texto }}">
                                    </div>
                                    <p class="text-xs text-gray-500">Usa **texto** para negrita y //texto// para cursiva.</p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="block text-gray-300 text-sm font-medium">Texto del botón</label>
                                        <input type="text" name="texto_url" id="texto_url" 
                                               class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-blue-500 transition-all"
                                               placeholder="Ej: Ver más"
                                               value="{{ $stickyBar->texto_url }}">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-gray-300 text-sm font-medium">Enlace (URL)</label>
                                        <input type="url" name="url" id="url" 
                                               class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-blue-500 transition-all"
                                               placeholder="https://..."
                                               value="{{ $stickyBar->url }}">
                                    </div>
                                </div>
                                
                                <div class="bg-gray-800 p-4 rounded-lg border border-gray-600 mt-2">
                                    <h4 class="text-sm font-medium text-gray-300 mb-3">Apariencia</h4>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-gray-400 text-xs uppercase tracking-wider">Fondo</label>
                                            <div class="flex items-center gap-3">
                                                <div class="relative w-10 h-10 rounded-full overflow-hidden border border-gray-500 shadow-sm cursor-pointer hover:scale-105 transition-transform">
                                                    <input type="color" name="color" id="bgColorInput" 
                                                        value="{{ $stickyBar->color }}" 
                                                        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[150%] h-[150%] p-0 cursor-pointer border-0"
                                                        oninput="document.getElementById('bgColorHex').textContent = this.value">
                                                </div>
                                                <span id="bgColorHex" class="text-gray-300 text-sm font-mono">{{ $stickyBar->color }}</span>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-gray-400 text-xs uppercase tracking-wider">Texto</label>
                                            <div class="flex items-center gap-3">
                                                <div class="relative w-10 h-10 rounded-full overflow-hidden border border-gray-500 shadow-sm cursor-pointer hover:scale-105 transition-transform">
                                                    <input type="color" name="text_color" id="textColorInput" 
                                                        value="{{ $stickyBar->text_color ?? '#ffffff' }}" 
                                                        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[150%] h-[150%] p-0 cursor-pointer border-0"
                                                        oninput="document.getElementById('textColorHex').textContent = this.value">
                                                </div>
                                                <span id="textColorHex" class="text-gray-300 text-sm font-mono">{{ $stickyBar->text_color ?? '#ffffff' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="pt-4 border-t border-gray-700 flex justify-end">
                                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-medium px-6 py-2.5 rounded-lg text-sm shadow-lg shadow-green-900/20 transition-all transform active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                            
                            <script>
                                function syncVisibleCheckbox() {
                                    const headerCheckbox = document.getElementById('visible');
                                    const hiddenInput = document.getElementById('visibleHidden');
                                    if (headerCheckbox && hiddenInput) {
                                        hiddenInput.value = headerCheckbox.checked ? '1' : '0';
                                    }
                                }
                                document.addEventListener('DOMContentLoaded', function() {
                                    const headerCheckbox = document.getElementById('visible');
                                    if (headerCheckbox) {
                                        headerCheckbox.addEventListener('change', syncVisibleCheckbox);
                                        // Initial sync
                                        syncVisibleCheckbox();
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>