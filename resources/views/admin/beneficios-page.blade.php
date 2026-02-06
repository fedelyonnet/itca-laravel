
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edición Página Beneficios') }}
        </h2>
    </x-slot>

    <!-- Toast Messages -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400 flex items-center justify-between min-w-[300px]">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-green-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center justify-between min-w-[300px]">
                    <span>{{ $error }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-red-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endforeach
        @endif
    </div>

    <div class="py-12" x-data="{ 
        activeTab: '{{ session('active_tab', 'header') }}',
        isUploading: false,
        setActiveTab(tab) {
            this.activeTab = tab;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                
                <!-- TABS NAVIGATION -->
                <div class="flex border-b border-gray-700 bg-gray-900/50">
                    <button @click="setActiveTab('header')" :class="activeTab === 'header' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        1) Header
                    </button>
                    <button @click="setActiveTab('club_itca')" :class="activeTab === 'club_itca' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        2) Club ITCA
                    </button>
                    <button @click="setActiveTab('bolsa_laboral')" :class="activeTab === 'bolsa_laboral' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        3) Bolsa Laboral
                    </button>
                    <button @click="setActiveTab('productos')" :class="activeTab === 'productos' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        4) Productos y herramientas
                    </button>
                    <button @click="setActiveTab('competencia_itca')" :class="activeTab === 'competencia_itca' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        5) Competencia ITCA
                    </button>
                    <button @click="setActiveTab('charlas')" :class="activeTab === 'charlas' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        6) Charlas
                    </button>
                    <button @click="setActiveTab('manuales')" :class="activeTab === 'manuales' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        7) Manuales
                    </button>
                </div>

                <div class="p-6">
                    
                    <!-- TAB 1: HEADER -->
                    <div x-show="activeTab === 'header'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col items-center">

                        <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active_tab" value="header">
                            
                                <!-- Imagen Hero -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700 w-full">
                                    <label class="block text-sm font-bold text-gray-300 mb-2 uppercase text-center">Imagen Hero Principal (Fondo)</label>
                                    <div class="relative group max-w-2xl mx-auto">
                                        <input type="file" name="hero_image" id="hero_image" accept="image/*" class="hidden" onchange="this.form.submit()">
                                        <label for="hero_image" class="relative block w-full h-48 bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                            @if(isset($content->hero_image) && $content->hero_image)
                                                <img id="hero_preview" src="{{ asset('storage/' . $content->hero_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <img id="hero_preview" class="w-full h-full object-cover hidden">
                                            @endif
                                            <div id="hero_placeholder" class="{{ (isset($content->hero_image) && $content->hero_image) ? 'hidden' : '' }} absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none">
                                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs uppercase font-bold">Cambiar Imagen Hero</span>
                                            </div>
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-2 text-center font-mono uppercase">Se guarda automáticamente al seleccionar</p>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: CLUB ITCA -->
                    <div x-show="activeTab === 'club_itca'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Video Column -->
                            <div>
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="club_itca">
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Video Club ITCA</label>
                                        <div class="relative group h-64">
                                            <input type="file" name="club_itca_video" id="club_itca_video" accept="video/*" class="hidden" 
                                                onchange="isUploading = true; this.form.submit()">
                                            <label for="club_itca_video" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                                @if(isset($content->club_itca_video) && $content->club_itca_video)
                                                    <video src="{{ asset('storage/' . $content->club_itca_video) }}" class="w-full h-full object-cover" muted></video>
                                                @endif
                                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none bg-gray-900/40">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs uppercase font-bold text-center">Subir/Cambiar Video</span>
                                                </div>

                                                <!-- Loading Overlay -->
                                                <div x-show="isUploading" class="absolute inset-0 bg-gray-900/80 flex flex-col items-center justify-center z-50">
                                                    <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span class="text-blue-400 text-xs font-bold uppercase animate-pulse">Subiendo video...</span>
                                                </div>
                                            </label>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-2 text-center font-mono uppercase">Se guarda automáticamente al seleccionar</p>
                                    </div>
                                </form>
                            </div>

                            <!-- Text Column -->
                            <div>
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" class="bg-gray-900 p-4 rounded border border-gray-700 h-fit">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="club_itca">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Texto Club ITCA</label>
                                        <textarea name="club_itca_texto" rows="8" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono custom-scrollbar" placeholder="Ingresa el texto aquí...">{{ $content->club_itca_texto }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Usa */texto/* para poner en negrita</p>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">URL Botón / Enlace</label>
                                        <input type="text" name="club_itca_button_url" value="{{ $content->club_itca_button_url }}" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="https://...">
                                    </div>

                                    <div class="flex justify-center">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                            GUARDAR TEXTOS
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- TAB 3: BOLSA LABORAL -->
                    <div x-show="activeTab === 'bolsa_laboral'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Image Column -->
                            <div>
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="bolsa_laboral">
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Imagen Lateral</label>
                                        <div class="relative group h-64">
                                            <input type="file" name="bolsa_work_image" id="bolsa_work_image" accept="image/*" class="hidden" 
                                                onchange="this.form.submit()">
                                            <label for="bolsa_work_image" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                                @if(isset($content->bolsa_work_image) && $content->bolsa_work_image)
                                                    <img src="{{ asset('storage/' . $content->bolsa_work_image) }}" class="w-full h-full object-cover">
                                                @else
                                                    <img class="w-full h-full object-cover hidden">
                                                @endif
                                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none {{ (isset($content->bolsa_work_image) && $content->bolsa_work_image) ? 'opacity-0 group-hover:opacity-100 bg-black/40' : '' }}">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs uppercase font-bold text-center">Cambiar Imagen</span>
                                                </div>
                                            </label>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-2 text-center font-mono uppercase">Se guarda automáticamente al seleccionar</p>
                                    </div>
                                </form>
                            </div>

                            <!-- Text Column -->
                            <div>
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" class="bg-gray-900 p-4 rounded border border-gray-700 h-fit">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="bolsa_laboral">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Texto Descriptivo</label>
                                        <textarea name="bolsa_work_text" rows="8" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono custom-scrollbar" placeholder="Accedé a las mejores oportunidades...">{{ $content->bolsa_work_text }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Usa */texto/* para poner en negrita</p>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">URL Botón</label>
                                        <input type="text" name="bolsa_work_button_url" value="{{ $content->bolsa_work_button_url }}" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="https://...">
                                    </div>

                                    <div class="flex justify-center">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                            GUARDAR TEXTOS
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- TAB 4: PRODUCTOS -->
                    <div x-show="activeTab === 'productos'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                         
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Left: Texts & URL -->
                            <div class="space-y-6">
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" class="bg-gray-900 p-4 rounded border border-gray-700 h-fit">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="productos">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Texto Descriptivo (Tienda)</label>
                                        <textarea name="tienda_text" rows="8" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono custom-scrollbar" placeholder="En nuestra tienda online encontrarás...">{{ $content->tienda_text }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Usa */texto/* para poner en negrita</p>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">URL Botón Tienda</label>
                                        <input type="text" name="tienda_button_url" value="{{ $content->tienda_button_url }}" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="https://...">
                                    </div>

                                    <div class="flex justify-center">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                            GUARDAR TEXTOS
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Right: Carousel Images (Instalaciones Style) -->
                            <div class="space-y-6">
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                                        <label class="text-sm font-bold text-gray-300 uppercase">Carrusel de Productos</label>
                                        <button onclick="document.getElementById('modalProducto').classList.remove('hidden')" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-[10px] font-bold uppercase transition-all shadow-md">
                                            + Agregar Foto
                                        </button>
                                    </div>
                                    @if(isset($content->productos) && $content->productos->count() > 0)
                                        <div id="sortable-productos" class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                                            @foreach($content->productos as $producto)
                                                <div data-id="{{ $producto->id }}" class="producto-draggable flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 group hover:border-blue-500/50 cursor-move">
                                                    <div class="flex items-center gap-3">
                                                        <!-- Drag Handle -->
                                                        <svg style="cursor: move;" class="w-4 h-4 text-gray-600 drag-handle group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                                        
                                                        <!-- Icon & Preview Container -->
                                                        <div class="relative group/preview flex items-center">
                                                            <!-- Thumbnail Icon -->
                                                            <img src="{{ asset('storage/' . $producto->image_path) }}" class="h-8 w-8 rounded object-cover border border-gray-600 cursor-pointer group-hover/preview:border-blue-400 transition-colors">
                                                            
                                                            <!-- Hover Image Preview -->
                                                            <div class="fixed hidden group-hover/preview:block z-[9999] w-64 bg-gray-900 border border-gray-600 rounded-lg shadow-2xl overflow-hidden pointer-events-none" style="transform: translate(20px, -50%);">
                                                                <img src="{{ asset('storage/' . $producto->image_path) }}" class="w-full h-auto object-cover">
                                                            </div>
                                                        </div>

                                                        <div class="flex flex-col">
                                                            <span class="text-xs text-gray-400">ID: {{ $producto->id }}</span>
                                                        </div>
                                                    </div>

                                                    <form action="{{ route('admin.beneficios.producto.destroy', $producto->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta imagen?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-12 flex flex-col items-center border border-dashed border-gray-700 rounded bg-gray-800/20 text-gray-600 text-xs italic">
                                            Sin fotos cargadas
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: COMPETENCIA ITCA -->
                    <div x-show="activeTab === 'competencia_itca'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Video Column -->
                            <div>
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="competencia_itca">
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Video Competencia ITCA</label>
                                        <div class="relative group h-64">
                                            <input type="file" name="competencia_itca_video" id="competencia_itca_video" accept="video/*" class="hidden" 
                                                onchange="isUploading = true; this.form.submit()">
                                            <label for="competencia_itca_video" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                                @if(isset($content->competencia_itca_video) && $content->competencia_itca_video)
                                                    <video src="{{ asset('storage/' . $content->competencia_itca_video) }}" class="w-full h-full object-cover" muted></video>
                                                @endif
                                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none bg-gray-900/40">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs uppercase font-bold text-center">Subir/Cambiar Video</span>
                                                </div>

                                                <!-- Loading Overlay -->
                                                <div x-show="isUploading" class="absolute inset-0 bg-gray-900/80 flex flex-col items-center justify-center z-50">
                                                    <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span class="text-blue-400 text-xs font-bold uppercase animate-pulse">Subiendo video...</span>
                                                </div>
                                            </label>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-2 text-center font-mono uppercase">Se guarda automáticamente al seleccionar</p>
                                    </div>
                                </form>
                            </div>

                            <!-- Text Column -->
                            <div>
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" class="bg-gray-900 p-4 rounded border border-gray-700 h-fit">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="competencia_itca">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Texto Competencia ITCA</label>
                                        <textarea name="competencia_itca_texto" rows="8" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono custom-scrollbar" placeholder="Ingresa el texto aquí...">{{ $content->competencia_itca_texto }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Usa */texto/* para poner en negrita</p>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">URL Botón / Enlace</label>
                                        <input type="text" name="competencia_itca_button_url" value="{{ $content->competencia_itca_button_url }}" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="https://...">
                                    </div>

                                    <div class="flex justify-center">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                            GUARDAR TEXTOS
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- TAB 6: CHARLAS Y VISITAS TÉCNICAS -->
                    <div x-show="activeTab === 'charlas'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active_tab" value="charlas">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                @for($i = 1; $i <= 4; $i++)
                                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700 shadow-inner flex flex-col h-full">
                                    <h4 class="text-blue-400 font-bold mb-4 uppercase text-[10px] tracking-widest border-b border-gray-700 pb-2">Card #{{$i}}</h4>
                                    
                                    <div class="space-y-4 flex-grow">
                                        <!-- Imagen -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase text-center">Imagen (Frente)</label>
                                            <div class="relative group aspect-square">
                                                <input type="file" name="charla{{$i}}_img" id="charla{{$i}}_img" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                <label for="charla{{$i}}_img" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all flex flex-col items-center justify-center group shadow-inner">
                                                    @if(isset($content->{'charla'.$i.'_img'}) && $content->{'charla'.$i.'_img'})
                                                        <img id="charla{{$i}}_preview" src="{{ asset('storage/' . $content->{'charla'.$i.'_img'}) }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @else
                                                        <div id="charla{{$i}}_placeholder" class="flex flex-col items-center justify-center text-gray-600">
                                                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                            <span class="text-[10px] font-bold">Subir</span>
                                                        </div>
                                                        <img id="charla{{$i}}_preview" class="w-full h-full object-cover hidden">
                                                    @endif
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Título -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Título (Front/Back)</label>
                                            <input type="text" name="charla{{$i}}_title" value="{{ old('charla'.$i.'_title', $content->{'charla'.$i.'_title'}) }}" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-xs focus:border-blue-500 focus:ring-0"
                                                placeholder="Ej: VISITA TÉCNICA">
                                        </div>

                                        <!-- Fecha -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Fecha (Atrás - Arriba)</label>
                                            <input type="text" name="charla{{$i}}_fecha" value="{{ old('charla'.$i.'_fecha', $content->{'charla'.$i.'_fecha'}) }}" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-xs focus:border-blue-500 focus:ring-0"
                                                placeholder="Ej: 15 de marzo de 2026">
                                        </div>

                                        <!-- Descripción -->
                                        <div class="flex-grow">
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Descripción (Atrás)</label>
                                            <textarea name="charla{{$i}}_text" rows="5" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-[11px] focus:border-blue-500 focus:ring-0 leading-tight"
                                                placeholder="Texto que aparece al girar...">{{ old('charla'.$i.'_text', $content->{'charla'.$i.'_text'}) }}</textarea>
                                            <p class="text-[10px] text-gray-500 mt-1">Usa */texto/* para poner en negrita</p>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <div class="flex justify-center pt-8 border-t border-gray-700">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-12 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        GUARDAR CHARLAS
                                    </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- TAB 7: MATERIAL DIDACTICO / MANUALES -->
                    <div x-show="activeTab === 'manuales'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Left: Images -->
                            <div class="space-y-6">
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="manuales">
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-4 uppercase italic">Imágenes Secundarias (Manuales)</label>
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Imagen 1 -->
                                            <div>
                                                <label class="block text-[10px] text-gray-500 mb-1 uppercase font-bold text-center">Imagen 1</label>
                                                <div class="relative group aspect-square">
                                                    <input type="file" name="manuales_img1" id="manuales_img1" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                    <label for="manuales_img1" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all flex flex-col items-center justify-center group shadow-inner">
                                                        @if(isset($content->manuales_img1) && $content->manuales_img1)
                                                            <img src="{{ asset('storage/' . $content->manuales_img1) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="flex flex-col items-center justify-center text-gray-600">
                                                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                                <span class="text-[10px] font-bold">Subir</span>
                                                            </div>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Imagen 2 -->
                                            <div>
                                                <label class="block text-[10px] text-gray-500 mb-1 uppercase font-bold text-center">Imagen 2</label>
                                                <div class="relative group aspect-square">
                                                    <input type="file" name="manuales_img2" id="manuales_img2" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                    <label for="manuales_img2" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all flex flex-col items-center justify-center group shadow-inner">
                                                        @if(isset($content->manuales_img2) && $content->manuales_img2)
                                                            <img src="{{ asset('storage/' . $content->manuales_img2) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="flex flex-col items-center justify-center text-gray-600">
                                                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                                <span class="text-[10px] font-bold">Subir</span>
                                                            </div>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-2 text-center font-mono uppercase italic">Se guardan automáticamente al seleccionar</p>
                                    </div>
                                </form>
                            </div>

                            <!-- Right: Text and Button -->
                            <div class="space-y-6">
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" class="bg-gray-900 p-4 rounded border border-gray-700 h-fit">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="manuales">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Texto Descriptivo (Manuales)</label>
                                        <textarea name="manuales_texto" rows="8" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono custom-scrollbar" placeholder="Accedé a nuestros manuales técnicos especializados...">{{ $content->manuales_texto }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Usa */texto/* para poner en negrita</p>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">URL Botón / Enlace</label>
                                        <input type="text" name="manuales_button_url" value="{{ $content->manuales_button_url }}" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="https://...">
                                    </div>

                                    <div class="flex justify-center">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                            GUARDAR CONTENIDO
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide Toast Messages
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('#toast-container > div');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(20px)';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            });

            // Sortable for Productos
            const productosList = document.getElementById('sortable-productos');
            if (productosList) {
                new Sortable(productosList, {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        const items = Array.from(productosList.children);
                        const orderedIds = items.map(item => item.getAttribute('data-id'));
                        
                        // Send Reorder Request
                        fetch('{{ route('admin.beneficios.producto.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ orden: orderedIds })
                        }).then(response => {
                            if (!response.ok) {
                                alert('Error al reordenar items');
                            }
                        });
                    }
                });
            }
        });
    </script>

    <!-- MODAL ADD PRODUCTO -->
    <div id="modalProducto" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 border border-gray-700 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4 uppercase border-b border-gray-700 pb-2">Agregar Producto</h3>
            <form action="{{ route('admin.beneficios.producto.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4 bg-gray-900 p-6 rounded border border-gray-700 border-dashed text-center">
                    <label class="block text-sm font-medium text-gray-400 mb-4 uppercase">Subir archivo</label>
                    <input type="file" name="image" accept="image/*" required class="w-full text-gray-400 text-xs font-mono">
                    <p class="text-[10px] text-gray-600 mt-2 italic">Formatos: JPG, PNG, WEBP</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalProducto').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-gray-300 rounded text-xs font-bold uppercase hover:bg-gray-600 transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded text-xs font-bold uppercase hover:bg-green-500 shadow-lg shadow-green-900/20 transition-all">Subir Imagen</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</x-app-layout>
