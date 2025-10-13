<x-app-layout>
    
    <link rel="stylesheet" href="{{ asset('build/assets/backoffice-eMmMkJb6.css') }}">
    <script src="{{ asset('build/assets/backoffice-C_PPKbx3.js') }}"></script>

    <div class="py-12">
        <div class="w-full px-6">
            <div class="bg-gray-800 p-6 rounded-lg">
                <h1 class="text-2xl font-bold mb-6 text-white">Editar Sección Hero + Sticky Bar</h1>
                
                @if(session('success'))
                    <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-all duration-500 ease-in-out">
                        {{ session('success') }}
                    </div>
                    <script>
                        // Esperar un poco para que se renderice
                        setTimeout(() => {
                            const toast = document.getElementById('toast');
                            // Mostrar toast (deslizar desde la derecha)
                            toast.classList.remove('translate-x-full');
                            
                            // Ocultar toast después de 3 segundos
                            setTimeout(() => {
                                toast.classList.add('translate-x-full');
                                // Remover del DOM después de la animación
                                setTimeout(() => {
                                    toast.remove();
                                }, 500);
                            }, 3000);
                        }, 100);
                    </script>
                @endif
                
                <!-- ESTRUCTURA: Desktop izquierda, Mobile derecha arriba, Sticky Bar derecha abajo -->
                <div style="display: flex; gap: 20px;">
                    <!-- PANEL 1: Desktop (Izquierda) -->
                    <div style="flex: 1; background: #374151; padding: 20px; border-radius: 8px;">
                        <h2 class="text-lg font-semibold text-white mb-4">Desktop</h2>
                        
                        @php
                            $desktopImg1 = $heroes->where('version', 'desktop')->where('type', 'img1')->first();
                            $desktopImg2 = $heroes->where('version', 'desktop')->where('type', 'img2')->first();
                            $desktopVideo = $heroes->where('version', 'desktop')->where('type', 'video')->first();
                            
                            // Configuración de badges
                            $badges = [
                                'desktop' => [
                                    'img1' => ['title' => 'Imagen Principal', 'size' => '768 × 206 px'],
                                    'img2' => ['title' => 'Imagen Secundaria', 'size' => '376 × 418 px'],
                                    'video' => ['title' => 'Video Principal', 'size' => '376 × 418 px']
                                ],
                                'mobile' => [
                                    'img1' => ['title' => 'Imagen 1', 'size' => '417 × 462 px'],
                                    'img2' => ['title' => 'Imagen 2', 'size' => '417 × 462 px'],
                                    'video' => ['title' => 'Video Mobile', 'size' => '417 × 462 px']
                                ]
                            ];
                            @endphp
                            
                        <!-- Estructura del hero -->
                        <div class="space-y-6">
                            <!-- Fila 1: img1 (768x206) -->
                            <div class="w-full bg-gray-600 rounded relative desktop-hero-img1">
                                <!-- Badge flotante -->
                                <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
                                    <div class="font-medium">{{ $badges['desktop']['img1']['title'] }}</div>
                                    <div class="text-gray-300">{{ $badges['desktop']['img1']['size'] }}</div>
                                </div>
                                
                                @if($desktopImg1 && $desktopImg1->url)
                                    <img src="{{ asset('storage/' . $desktopImg1->url) }}" alt="img1" class="w-full h-full object-cover rounded">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center hero-content">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="text-gray-300 text-xs mt-2 text-center">
                                                <div class="font-semibold">768 × 206 px</div>
                                                <div class="text-gray-400">Imagen horizontal</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                <!-- Barra blanca en el bottom -->
                                <div class="hero-bottom-bar">
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.hero.update', $desktopImg1 ? $desktopImg1->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                @csrf
                                                <input type="hidden" name="version" value="desktop">
                                            <input type="hidden" name="type" value="img1">
                                            <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                                                Browse
                                                <input type="file" name="file" accept="image/*" class="hidden" onchange="this.form.submit()">
                                            </label>
                                            </form>
                                        @if($desktopImg1)
                                            <form action="{{ route('admin.hero.delete', $desktopImg1->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                                            </form>
                                        @else
                                            <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
                                        @endif
                                    </div>
                                </div>
                        </div>
                            
                            <!-- Fila 2: img2 y video (376x418) -->
                            <div class="flex gap-4">
                                <!-- img2 -->
                                <div class="flex-1 bg-gray-600 rounded relative desktop-hero-img2">
                                    <!-- Badge flotante -->
                                    <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
                                        <div class="font-medium">{{ $badges['desktop']['img2']['title'] }}</div>
                                        <div class="text-gray-300">{{ $badges['desktop']['img2']['size'] }}</div>
                                    </div>
                                    
                                    @if($desktopImg2 && $desktopImg2->url)
                                        <img src="{{ asset('storage/' . $desktopImg2->url) }}" alt="img2" class="w-full h-full object-cover rounded">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center hero-content">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="text-gray-300 text-xs mt-2 text-center">
                                                <div class="font-semibold">376 × 418 px</div>
                                                <div class="text-gray-400">Imagen vertical</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Barra blanca en el bottom -->
                                    <div class="hero-bottom-bar">
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.hero.update', $desktopImg2 ? $desktopImg2->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                @csrf
                                                <input type="hidden" name="version" value="desktop">
                                                <input type="hidden" name="type" value="img2">
                                                <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                                                    Browse
                                                    <input type="file" name="file" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                </label>
                                            </form>
                                            @if($desktopImg2)
                                                <form action="{{ route('admin.hero.delete', $desktopImg2->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                                                </form>
                                            @else
                                                <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- video -->
                                <div class="flex-1 bg-gray-600 rounded relative desktop-hero-video">
                                    <!-- Badge flotante -->
                                    <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
                                        <div class="font-medium">{{ $badges['desktop']['video']['title'] }}</div>
                                        <div class="text-gray-300">{{ $badges['desktop']['video']['size'] }}</div>
                                    </div>
                                    
                                    @if($desktopVideo && $desktopVideo->url)
                                        <video class="w-full h-full object-cover rounded" muted loop autoplay>
                                            <source src="{{ asset('storage/' . $desktopVideo->url) }}" type="video/mp4">
                                            Tu navegador no soporta videos.
                                        </video>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center hero-content">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="text-gray-300 text-xs mt-2 text-center">
                                                <div class="font-semibold">376 × 418 px</div>
                                                <div class="text-gray-400">Video vertical</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Barra blanca en el bottom -->
                                    <div class="hero-bottom-bar">
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.hero.update', $desktopVideo ? $desktopVideo->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                @csrf
                                                <input type="hidden" name="version" value="desktop">
                                                <input type="hidden" name="type" value="video">
                                                <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                                                    Browse
                                                    <input type="file" name="file" accept="video/*" class="hidden" onchange="this.form.submit()">
                                                </label>
                                            </form>
                                            @if($desktopVideo)
                                                <form action="{{ route('admin.hero.delete', $desktopVideo->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                                                </form>
                                            @else
                                                <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                            </div>
                                        
                    <!-- COLUMNA DERECHA: Mobile arriba, Sticky Bar abajo -->
                    <div style="flex: 1; display: flex; flex-direction: column; gap: 20px;">
                        <!-- PANEL 2: Mobile (Derecha Arriba) -->
                        <div style="background: #374151; padding: 20px; border-radius: 8px;">
                        <h2 class="text-lg font-semibold text-white mb-4">Mobile</h2>
                        
                        @php
                            $mobileImg1 = $heroes->where('version', 'mobile')->where('type', 'img1')->first();
                            $mobileImg2 = $heroes->where('version', 'mobile')->where('type', 'img2')->first();
                            $mobileVideo = $heroes->where('version', 'mobile')->where('type', 'video')->first();
                        @endphp
                        
                        <!-- Estructura del hero mobile -->
                        <div class="grid grid-cols-3 gap-4">
                            <!-- img1 -->
                            <div class="w-full bg-gray-600 rounded relative mobile-hero-container">
                                <!-- Badge flotante -->
                                <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
                                    <div class="font-medium">{{ $badges['mobile']['img1']['title'] }}</div>
                                    <div class="text-gray-300">{{ $badges['mobile']['img1']['size'] }}</div>
                                </div>
                                
                                @if($mobileImg1 && $mobileImg1->url)
                                    <img src="{{ asset('storage/' . $mobileImg1->url) }}" alt="img1" class="w-full h-full object-cover rounded">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center hero-content">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-xs mt-2 text-center">
                                            <div class="font-semibold">417 × 462 px</div>
                                            <div class="text-gray-400">Imagen mobile</div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Barra blanca en el bottom -->
                                <div class="hero-bottom-bar">
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.hero.update', $mobileImg1 ? $mobileImg1->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                                            @csrf
                                            <input type="hidden" name="version" value="mobile">
                                            <input type="hidden" name="type" value="img1">
                                            <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                                                Browse
                                                <input type="file" name="file" accept="image/*" class="hidden" onchange="this.form.submit()">
                                            </label>
                                        </form>
                                        @if($mobileImg1)
                                            <form action="{{ route('admin.hero.delete', $mobileImg1->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                                            </form>
                                        @else
                                            <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- img2 -->
                            <div class="w-full bg-gray-600 rounded relative mobile-hero-container">
                                <!-- Badge flotante -->
                                <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
                                    <div class="font-medium">{{ $badges['mobile']['img2']['title'] }}</div>
                                    <div class="text-gray-300">{{ $badges['mobile']['img2']['size'] }}</div>
                                </div>
                                
                                @if($mobileImg2 && $mobileImg2->url)
                                    <img src="{{ asset('storage/' . $mobileImg2->url) }}" alt="img2" class="w-full h-full object-cover rounded">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center hero-content">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-xs mt-2 text-center">
                                            <div class="font-semibold">417 × 462 px</div>
                                            <div class="text-gray-400">Imagen mobile</div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Barra blanca en el bottom -->
                                <div class="hero-bottom-bar">
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.hero.update', $mobileImg2 ? $mobileImg2->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                                            @csrf
                                            <input type="hidden" name="version" value="mobile">
                                            <input type="hidden" name="type" value="img2">
                                            <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                                                Browse
                                                <input type="file" name="file" accept="image/*" class="hidden" onchange="this.form.submit()">
                                            </label>
                                        </form>
                                        @if($mobileImg2)
                                            <form action="{{ route('admin.hero.delete', $mobileImg2->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                                            </form>
                                        @else
                                            <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- video -->
                            <div class="w-full bg-gray-600 rounded relative mobile-hero-container">
                                <!-- Badge flotante -->
                                <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
                                    <div class="font-medium">{{ $badges['mobile']['video']['title'] }}</div>
                                    <div class="text-gray-300">{{ $badges['mobile']['video']['size'] }}</div>
                                </div>
                                
                                @if($mobileVideo && $mobileVideo->url)
                                    <video class="w-full h-full object-cover rounded" muted loop autoplay>
                                        <source src="{{ asset('storage/' . $mobileVideo->url) }}" type="video/mp4">
                                        Tu navegador no soporta videos.
                                    </video>
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center hero-content">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-xs mt-2 text-center">
                                            <div class="font-semibold">417 × 462 px</div>
                                            <div class="text-gray-400">Video mobile</div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Barra blanca en el bottom -->
                                <div class="hero-bottom-bar">
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.hero.update', $mobileVideo ? $mobileVideo->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                                            @csrf
                                            <input type="hidden" name="version" value="mobile">
                                            <input type="hidden" name="type" value="video">
                                            <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                                                Browse
                                                <input type="file" name="file" accept="video/*" class="hidden" onchange="this.form.submit()">
                                            </label>
                                        </form>
                                        @if($mobileVideo)
                                            <form action="{{ route('admin.hero.delete', $mobileVideo->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                                            </form>
                                        @else
                                            <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                        <!-- PANEL 3: Sticky Bar (Derecha Abajo) -->
                        <div style="background: #374151; padding: 20px; border-radius: 8px; height: fit-content;">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-white">Sticky Bar</h2>
                                
                                <!-- Checkbox Visible -->
                                <form action="{{ route('admin.sticky-bar.update') }}" method="POST" class="inline">
                                    @csrf
                                    <div class="flex items-center">
                                        <input type="checkbox" name="visible" id="visible" value="1" 
                                               {{ $stickyBar->visible ? 'checked' : '' }}
                                               class="mr-2" onchange="this.form.submit()">
                                        <label for="visible" class="text-white text-sm">Visible</label>
                                        <input type="hidden" name="texto" value="{{ $stickyBar->texto }}">
                                        <input type="hidden" name="color" value="{{ $stickyBar->color }}">
                                    </div>
                                </form>
                            </div>
                            
                            
                            <!-- Formulario Sticky Bar -->
                            <form action="{{ route('admin.sticky-bar.update') }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <!-- Input hidden para el estado del checkbox visible -->
                                <input type="hidden" name="visible" id="visibleHidden" value="{{ $stickyBar->visible ? '1' : '0' }}">
                                
                                <!-- Fila 1: Texto del sticky -->
                                <div class="space-y-1">
                                    <label class="text-white text-sm">Texto del sticky:</label>
                                    <input type="text" name="texto" id="texto" 
                                           class="w-full p-2 rounded text-gray-800 text-sm"
                                           placeholder="¡Oferta especial! 🎉 **50% descuento** //hasta agotar stock//"
                                           value="{{ $stickyBar->texto }}">
                                </div>
                                
                                <!-- Fila 2: Texto del URL -->
                                <div class="space-y-1">
                                    <label class="text-white text-sm">Texto del URL:</label>
                                    <input type="text" name="texto_url" id="texto_url" 
                                           class="w-full p-2 rounded text-gray-800 text-sm"
                                           placeholder="más info"
                                           value="{{ $stickyBar->texto_url }}">
                                </div>
                                
                                <!-- Fila 3: URL del enlace -->
                                <div class="space-y-1">
                                    <label class="text-white text-sm">URL del enlace (opcional):</label>
                                    <input type="url" name="url" id="url" 
                                           class="w-full p-2 rounded text-gray-800 text-sm"
                                           placeholder="https://ejemplo.com"
                                           value="{{ $stickyBar->url }}">
                                </div>
                                
                                <!-- Fila 2: Selector de Color (80%) + Color Seleccionado (20%) -->
                                <div class="space-y-2">
                                    <label class="text-white text-sm">Selección de color:</label>
                                    <div class="flex gap-4">
                                        <!-- Selector de color (80%) -->
                                        <div class="flex-1" style="width: 80%;">
                                            <div class="relative">
                                                <!-- Barra del espectro de colores -->
                                                <div id="colorSlider" 
                                                     class="w-full h-8 rounded-lg overflow-hidden cursor-pointer relative" 
                                                     style="background: linear-gradient(to right, #ff0000, #ff8000, #ffff00, #80ff00, #00ff00, #00ff80, #00ffff, #0080ff, #0000ff, #8000ff, #ff00ff, #ff0080, #ff0000);">
                                                    
                                                    <!-- Indicador circular -->
                                                    <div id="colorIndicator" 
                                                         class="absolute top-1/2 w-4 h-4 bg-white border-2 border-gray-800 rounded-full transform -translate-y-1/2 pointer-events-none"
                                                         style="left: 50%; box-shadow: 0 0 0 1px rgba(0,0,0,0.3);">
                                                    </div>
                                                </div>
                                                
                                                <!-- Input hidden para el valor del color -->
                                                <input type="hidden" name="color" id="selectedColor" value="{{ $stickyBar->color }}">
                                            </div>
                                        </div>
                                        
                                        <!-- Color seleccionado (20%) -->
                                        <div class="flex flex-col justify-center" style="width: 20%;">
                                            <div class="w-full h-8 rounded-lg border border-gray-400 relative flex items-center justify-center" 
                                                 id="colorPreview"
                                                 style="background-color: {{ $stickyBar->color }};">
                                                <div class="text-white text-xs font-mono font-bold" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">{{ $stickyBar->color }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botón Guardar -->
                                <div class="pt-2">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                            
                            <script>
                                let isDragging = false;
                                
                                // Sincronizar checkbox del header con input hidden del formulario
                                function syncVisibleCheckbox() {
                                    const headerCheckbox = document.getElementById('visible');
                                    const hiddenInput = document.getElementById('visibleHidden');
                                    
                                    if (headerCheckbox && hiddenInput) {
                                        hiddenInput.value = headerCheckbox.checked ? '1' : '0';
                                    }
                                }
                                
                                // Sincronizar cuando cambia el checkbox del header
                                document.addEventListener('DOMContentLoaded', function() {
                                    const headerCheckbox = document.getElementById('visible');
                                    if (headerCheckbox) {
                                        headerCheckbox.addEventListener('change', syncVisibleCheckbox);
                                        // Sincronizar estado inicial
                                        syncVisibleCheckbox();
                                    }
                                });
                                
                                // Función para obtener el color del gradiente en una posición específica
                                function getColorFromPosition(x, width) {
                                    const percentage = x / width;
                                    
                                    // Colores del gradiente en orden
                                    const colors = [
                                        {pos: 0, r: 255, g: 0, b: 0},     // Rojo
                                        {pos: 0.083, r: 255, g: 128, b: 0}, // Naranja
                                        {pos: 0.167, r: 255, g: 255, b: 0}, // Amarillo
                                        {pos: 0.25, r: 128, g: 255, b: 0},  // Verde amarillo
                                        {pos: 0.333, r: 0, g: 255, b: 0},   // Verde
                                        {pos: 0.417, r: 0, g: 255, b: 128}, // Verde cian
                                        {pos: 0.5, r: 0, g: 255, b: 255},   // Cian
                                        {pos: 0.583, r: 0, g: 128, b: 255}, // Azul cian
                                        {pos: 0.667, r: 0, g: 0, b: 255},   // Azul
                                        {pos: 0.75, r: 128, g: 0, b: 255},  // Violeta
                                        {pos: 0.833, r: 255, g: 0, b: 255}, // Magenta
                                        {pos: 0.917, r: 255, g: 0, b: 128}, // Rosa
                                        {pos: 1, r: 255, g: 0, b: 0}        // Rojo
                                    ];
                                    
                                    // Encontrar los dos colores entre los que está el porcentaje
                                    for (let i = 0; i < colors.length - 1; i++) {
                                        if (percentage >= colors[i].pos && percentage <= colors[i + 1].pos) {
                                            const t = (percentage - colors[i].pos) / (colors[i + 1].pos - colors[i].pos);
                                            const r = Math.round(colors[i].r + t * (colors[i + 1].r - colors[i].r));
                                            const g = Math.round(colors[i].g + t * (colors[i + 1].g - colors[i].g));
                                            const b = Math.round(colors[i].b + t * (colors[i + 1].b - colors[i].b));
                                            return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
                                        }
                                    }
                                    
                                    return '#ff0000'; // Fallback
                                }
                                
                                function updateColorFromSlider(event) {
                                    const slider = document.getElementById('colorSlider');
                                    const rect = slider.getBoundingClientRect();
                                    const x = event.clientX - rect.left;
                                    const width = rect.width;
                                    
                                    // Limitar x entre 0 y width
                                    const clampedX = Math.max(0, Math.min(x, width));
                                    
                                    // Actualizar posición del indicador
                                    const indicator = document.getElementById('colorIndicator');
                                    const percentage = (clampedX / width) * 100;
                                    indicator.style.left = percentage + '%';
                                    
                                    // Obtener color del gradiente
                                    const color = getColorFromPosition(clampedX, width);
                                    
                                    // Actualizar valores
                                    document.getElementById('selectedColor').value = color;
                                    document.getElementById('colorPreview').style.backgroundColor = color;
                                    document.querySelector('#colorPreview .text-xs.font-mono').textContent = color;
                                }
                                
                                // Event listeners para el slider
                                document.addEventListener('DOMContentLoaded', function() {
                                    const slider = document.getElementById('colorSlider');
                                    
                                    // Mouse down para iniciar drag
                                    slider.addEventListener('mousedown', function(e) {
                                        e.preventDefault();
                                        isDragging = true;
                                        updateColorFromSlider(e);
                                    });
                                    
                                    // Mouse move durante drag (solo cuando está arrastrando)
                                    document.addEventListener('mousemove', function(e) {
                                        if (isDragging) {
                                            e.preventDefault();
                                            updateColorFromSlider(e);
                                        }
                                    });
                                    
                                    // Mouse up para terminar drag
                                    document.addEventListener('mouseup', function(e) {
                                        if (isDragging) {
                                            isDragging = false;
                                        }
                                    });
                                    
                                    // Click para posicionar (sin drag)
                                    slider.addEventListener('click', function(e) {
                                        if (!isDragging) {
                                            updateColorFromSlider(e);
                                        }
                                    });
                                    
                                    // Posicionar indicador según color actual
                                    const currentColor = '{{ $stickyBar->color }}';
                                    if (currentColor) {
                                        // Convertir color hex a RGB
                                        const hex = currentColor.replace('#', '');
                                        const r = parseInt(hex.substr(0, 2), 16);
                                        const g = parseInt(hex.substr(2, 2), 16);
                                        const b = parseInt(hex.substr(4, 2), 16);
                                        
                                        // Calcular posición aproximada basada en el color
                                        let position = 50; // Default center
                                        
                                        // Lógica para determinar posición basada en RGB
                                        if (r > 200 && g < 100 && b < 100) {
                                            // Rojo
                                            position = 0;
                                        } else if (r > 200 && g > 100 && b < 100) {
                                            // Naranja/Amarillo
                                            position = 15;
                                        } else if (r > 200 && g > 200 && b < 100) {
                                            // Amarillo
                                            position = 25;
                                        } else if (r < 100 && g > 200 && b < 100) {
                                            // Verde
                                            position = 40;
                                        } else if (r < 100 && g > 200 && b > 200) {
                                            // Cian
                                            position = 50;
                                        } else if (r < 100 && g < 200 && b > 200) {
                                            // Azul
                                            position = 70;
                                        } else if (r > 200 && g < 100 && b > 200) {
                                            // Magenta
                                            position = 85;
                                        } else if (r > 200 && g < 200 && b < 200) {
                                            // Rosa
                                            position = 95;
                                        }
                                        
                                        const indicator = document.getElementById('colorIndicator');
                                        indicator.style.left = position + '%';
                                        
                                        // Actualizar el preview del color
                                        document.getElementById('colorPreview').style.backgroundColor = currentColor;
                                        document.querySelector('#colorPreview .text-xs.font-mono').textContent = currentColor;
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Habilitar botón Delete cuando se sube una imagen
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButton = document.querySelector('button[type="submit"]:not([disabled])');
            const deleteButtonDisabled = document.querySelector('button[disabled]');
            
            // Si hay un toast de éxito, significa que se subió una imagen
            if (document.getElementById('toast')) {
                // Habilitar el botón Delete
                if (deleteButtonDisabled) {
                    deleteButtonDisabled.classList.remove('bg-gray-400', 'text-gray-600', 'cursor-not-allowed');
                    deleteButtonDisabled.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white');
                    deleteButtonDisabled.disabled = false;
                    deleteButtonDisabled.innerHTML = 'Delete';
                }
            }
        });
    </script>

</x-app-layout>