<x-app-layout>
    
    <link rel="stylesheet" href="{{ asset('build/assets/backoffice-DQsvN77u.css') }}">
    <script src="{{ asset('build/assets/backoffice-C_PPKbx3.js') }}"></script>

    <div class="py-12">
        <div class="w-full px-6">
            <div class="bg-gray-800 p-6 rounded-lg">
                <h1 class="text-2xl font-bold mb-6 text-white">Editar Hero</h1>
                
                <div class="flex gap-4">
                    <!-- Panel Desktop -->
                    <div class="flex-1 bg-gray-700 p-4 rounded">
                        <h2 class="text-lg font-semibold text-white mb-4">Desktop</h2>
                        
                        @php
                            $desktopImg1 = $heroes->where('version', 'desktop')->where('type', 'img1')->first();
                            $desktopImg2 = $heroes->where('version', 'desktop')->where('type', 'img2')->first();
                            $desktopVideo = $heroes->where('version', 'desktop')->where('type', 'video')->first();
                            @endphp
                            
                        <!-- Estructura del hero -->
                        <div class="space-y-6">
                            <!-- Fila 1: img1 (768x206) -->
                            <div class="w-full bg-gray-600 rounded relative desktop-hero-img1">
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
                                        
                    <!-- Panel Mobile -->
                    <div class="flex-1 bg-gray-700 p-4 rounded mobile-panel">
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
                </div>
            </div>
        </div>
    </div>

</x-app-layout>