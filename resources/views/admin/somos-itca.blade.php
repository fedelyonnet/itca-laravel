
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edición Somos ITCA') }}
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
        @if(session('error'))
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center justify-between min-w-[300px]">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-red-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    </div>

    <div class="py-12" x-data="{ 
        activeTab: '{{ session('active_tab', 'header') }}',
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
                    <button @click="setActiveTab('que-es-itca')" :class="activeTab === 'que-es-itca' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        2) Qué es ITCA
                    </button>
                    <button @click="setActiveTab('porque')" :class="activeTab === 'porque' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        3) Por qué elegirnos
                    </button>
                    <button @click="setActiveTab('instalaciones')" :class="activeTab === 'instalaciones' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        4) Instalaciones
                    </button>
                    <button @click="setActiveTab('formadores')" :class="activeTab === 'formadores' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        5) Formadores
                    </button>
                    <button @click="setActiveTab('metricas')" :class="activeTab === 'metricas' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        6) Métricas
                    </button>
                    <button @click="setActiveTab('categorias')" :class="activeTab === 'categorias' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'" class="px-6 py-4 text-sm font-bold uppercase transition-all outline-none">
                        7) Categorías
                    </button>
                </div>

                <div class="p-6">
                    
                    <!-- TAB 1: HEADER -->
                    <div x-show="activeTab === 'header'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col items-center">

                        <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active_tab" value="header">
                            
                                <!-- Imagen Hero -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700 w-full">
                                    <label class="block text-sm font-bold text-gray-300 mb-2 uppercase">Imagen Hero Principal (Fondo)</label>
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
                                        <p class="text-[10px] text-gray-500 mt-2 text-center font-mono">Recomendado: 1920x1080px. El título superior se mantiene fijo por diseño.</p>
                                </div>
                        </form>
                    </div>

                    <!-- TAB 2: QUÉ ES ITCA -->
                    <div x-show="activeTab === 'que-es-itca'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">

                        <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active_tab" value="que-es-itca">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Video -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <label class="block text-sm font-bold text-gray-300 mb-2 uppercase">Video de Presentación</label>
                                    <div class="relative group">
                                        <input type="file" name="video_file" id="video_file_tab2" accept="video/mp4,video/webm" class="hidden" onchange="this.form.submit()">
                                        <label for="video_file_tab2" class="relative block w-full h-48 bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner flex flex-col items-center justify-center">
                                            @if(isset($content->video_url) && $content->video_url)
                                                <video id="video_preview_tab2" src="{{ asset('storage/' . $content->video_url) }}" class="w-full h-full object-cover" muted loop></video>
                                                <!-- Overlay para cambiar video -->
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs font-bold uppercase">Cambiar Video</span>
                                                </div>
                                            @else
                                                <video id="video_preview_tab2" class="w-full h-full object-cover hidden" muted loop></video>
                                                <div id="video_placeholder_tab2" class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs uppercase font-bold">Subir Video (MP4)</span>
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-1 text-center uppercase font-bold">Máx: 50MB</p>
                                </div>

                                <!-- Texto -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <label for="que_es_itca" class="block text-sm font-bold text-gray-300 mb-2 uppercase">Texto Descriptivo</label>
                                    <p class="text-xs text-gray-400 mb-2 italic">Tip: Usá <code class="bg-gray-800 px-1 rounded text-blue-400">*/texto/*</code> para resaltar en negrita.</p>
                                    <textarea name="que_es_itca" id="que_es_itca" rows="8" 
                                        class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded focus:border-blue-500 focus:ring-0 p-3"
                                        placeholder="Escribí aquí el texto...">{{ old('que_es_itca', $content->que_es_itca) }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-center pt-8 border-t border-gray-700">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        GUARDAR TEXTO
                                    </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 3: POR QUÉ ELEGIRNOS -->
                    <div x-show="activeTab === 'porque'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">

                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Update Image and manage general info -->
                            <div class="space-y-6">
                                <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="porque">
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase">Imagen Lateral</label>
                                        <div class="relative group">
                                            <input type="file" name="img_por_que" id="img_por_que_tab3" accept="image/*" class="hidden" onchange="this.form.submit()">
                                            <label for="img_por_que_tab3" class="relative block w-full h-64 bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                                @if(isset($content->img_por_que) && $content->img_por_que)
                                                    <img id="img_preview_tab3" src="{{ asset('storage/' . $content->img_por_que) }}" class="w-full h-full object-cover">
                                                @else
                                                    <img id="img_preview_tab3" class="w-full h-full object-cover hidden">
                                                @endif
                                                <div id="img_placeholder_tab3" class="{{ (isset($content->img_por_que) && $content->img_por_que) ? 'hidden' : '' }} absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs uppercase font-bold">Cambiar Imagen</span>
                                                </div>
                                            </label>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-1 text-center">800x1200px (Vertical recomendado)</p>
                                    </div>
                                </form>
                            </div>

                            <!-- Manage List Items -->
                            <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                                    <label class="text-sm font-bold text-gray-300 uppercase">Items de la lista</label>
                                    <button onclick="openModalPorQue()" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs font-bold transition-all shadow-md">
                                        + AGREGAR ITEM
                                    </button>
                                </div>

                                @if(isset($porQueItems) && $porQueItems->count() > 0)
                                    <div id="porQueList" class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($porQueItems as $item)
                                            <div data-id="{{ $item->id }}" class="por-que-draggable flex items-center justify-between bg-gray-800 p-3 rounded border border-gray-700 hover:border-gray-500 transition-all cursor-move group">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-400 drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                                    <span class="text-sm text-gray-200">{{ $item->descripcion }}</span>
                                                </div>
                                                <form action="{{ route('admin.somos-itca.porque.destroy', $item->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Item', '¿Seguro que querés borrar este item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-12 flex flex-col items-center border border-dashed border-gray-700 rounded bg-gray-800/20">
                                        <svg class="w-12 h-12 text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span class="text-gray-600 text-sm">No hay items cargados</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: INSTALACIONES -->
                    <div x-show="activeTab === 'instalaciones'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">

                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left: Star Items -->
                            <div class="space-y-6">
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                                        <label class="text-sm font-bold text-gray-300 uppercase">Items Destacados (Estrellas)</label>
                                        <button onclick="document.getElementById('modalInstalacionItem').classList.remove('hidden')" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-[10px] font-bold uppercase transition-all shadow-md">
                                            + Agregar destacada
                                        </button>
                                    </div>
                                    @if(isset($instalacionItems) && $instalacionItems->count() > 0)
                                        <div id="instalacionItemsList" class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                                            @foreach($instalacionItems as $item)
                                                <div data-id="{{ $item->id }}" class="instalacion-item-draggable flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 group hover:border-blue-500/50 cursor-move">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-4 h-4 text-gray-600 drag-handle group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                                        <span class="text-sm text-gray-200">{{ $item->descripcion }}</span>
                                                    </div>
                                                    <form action="{{ route('admin.somos-itca.instalacion-items.destroy', $item->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Item', '¿Seguro?');">
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
                                        <div class="py-6 flex flex-col items-center border border-dashed border-gray-700 rounded bg-gray-800/20 text-gray-600 text-xs italic">
                                            Sin items cargados
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Photos -->
                            <div class="space-y-6">
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                                        <label class="text-sm font-bold text-gray-300 uppercase">Carrousel de instalaciones</label>
                                        <button onclick="openModalInstalacion()" class="bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded text-[10px] font-bold uppercase transition-all shadow-md">
                                            + Agregar Foto
                                        </button>
                                    </div>
                                    @if(isset($instalaciones) && $instalaciones->count() > 0)
                                        <div id="instalacionesList" class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                                            @foreach($instalaciones as $inst)
                                                <div data-id="{{ $inst->id }}" class="instalacion-draggable flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 group hover:border-blue-500/50 cursor-move">
                                                    <div class="flex items-center gap-3">
                                                        <!-- Drag Handle -->
                                                        <svg class="w-4 h-4 text-gray-600 drag-handle group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                                        
                                                        <!-- Icon & Preview Container -->
                                                        <div class="relative group/preview flex items-center">
                                                            <!-- Thumbnail Icon -->
                                                            <img src="{{ asset('storage/' . $inst->image_path) }}" class="h-8 w-8 rounded object-cover border border-gray-600 cursor-pointer group-hover/preview:border-blue-400 transition-colors">
                                                            
                                                            <!-- Hover Image Preview -->
                                                            <div class="fixed hidden group-hover/preview:block z-[9999] w-64 bg-gray-900 border border-gray-600 rounded-lg shadow-2xl overflow-hidden pointer-events-none" style="transform: translate(20px, -50%);">
                                                                <img src="{{ asset('storage/' . $inst->image_path) }}" class="w-full h-auto object-cover">
                                                            </div>
                                                        </div>

                                                        <span class="text-xs text-gray-400">Imagen ID: {{ $inst->id }}</span>
                                                    </div>

                                                    <form action="{{ route('admin.somos-itca.instalaciones.destroy', $inst->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar', '¿Borrar esta imagen?');">
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

                    <!-- TAB 5: FORMADORES -->
                    <div x-show="activeTab === 'formadores'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left: Intro Text -->
                            <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" class="bg-gray-900 p-4 rounded border border-gray-700 h-fit">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="active_tab" value="formadores">
                                <label for="formadores_texto" class="block text-sm font-bold text-gray-300 mb-2 uppercase">Texto Introducción</label>
                                <p class="text-xs text-gray-400 mb-3 italic">Usa <code class="bg-gray-800 px-1 rounded text-blue-400">*/texto/*</code> para resaltar.</p>
                                <textarea name="formadores_texto" id="formadores_texto" rows="6" 
                                    class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded focus:border-blue-500 focus:ring-0 p-3 mb-4"
                                    placeholder="Quienes enseñan en nuestra institución son profesionales...">{{ old('formadores_texto', $content->formadores_texto) }}</textarea>
                                <div class="flex justify-center mt-6">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        GUARDAR TEXTO
                                    </button>
                                </div>
                            </form>

                            <!-- Right: List of Trainers -->
                            <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                                    <label class="text-sm font-bold text-gray-300 uppercase">Nuestros Formadores</label>
                                    <button onclick="openModalFormador()" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs font-bold transition-all shadow-md">
                                        + AGREGAR FORMADOR
                                    </button>
                                </div>
                                @if(isset($formadores) && $formadores->count() > 0)
                                    <div id="formadoresList" class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($formadores as $formador)
                                            <div data-id="{{ $formador->id }}" class="formador-draggable flex items-center justify-between bg-gray-800 p-2 rounded border border-gray-700 hover:border-gray-500 group cursor-move">
                                                <div class="flex items-center space-x-3 truncate">
                                                    <svg class="w-4 h-4 text-gray-600 drag-handle group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                                    <img src="{{ asset('storage/' . $formador->image_path) }}" class="h-8 w-8 rounded-full object-cover border border-gray-600 flex-shrink-0">
                                                    <span class="text-xs font-medium text-gray-200 truncate">{{ $formador->nombre }}</span>
                                                </div>
                                                <form action="{{ route('admin.somos-itca.formadores.destroy', $formador->id) }}" method="POST" onsubmit="return confirmSubmission(event, 'Eliminar Formador', '¿Borrar a {{ $formador->nombre }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-12 flex flex-col items-center border border-dashed border-gray-700 rounded bg-gray-800/20 text-gray-600 text-xs italic">
                                        Sin formadores cargados
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: MÉTRICAS -->
                    <div x-show="activeTab === 'metricas'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" class="space-y-8">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active_tab" value="metricas">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @for($i = 1; $i <= 4; $i++)
                                <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700 shadow-inner relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 p-2 text-gray-800 font-black text-4xl group-hover:text-gray-700/50 transition-colors pointer-events-none">0{{$i}}</div>
                                    <h4 class="text-blue-400 font-bold mb-4 uppercase text-sm tracking-wider">Métrica #{{$i}}</h4>
                                    
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Número / Dato principal</label>
                                            <input type="text" name="m{{$i}}_number" value="{{ old('m'.$i.'_number', $content->{'m'.$i.'_number'}) }}" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-sm focus:border-blue-500 focus:ring-0"
                                                placeholder="Ej: +20, 1500, etc.">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Título</label>
                                            <input type="text" name="m{{$i}}_title" value="{{ old('m'.$i.'_title', $content->{'m'.$i.'_title'}) }}" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-sm focus:border-blue-500 focus:ring-0"
                                                placeholder="Ej: Años de experiencia">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Descripción corta</label>
                                            <textarea name="m{{$i}}_text" rows="3" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-xs focus:border-blue-500 focus:ring-0"
                                                placeholder="Breve descripción...">{{ old('m'.$i.'_text', $content->{'m'.$i.'_text'}) }}</textarea>
                                            <p class="text-[9px] text-gray-600 mt-1 italic">Usa */texto/* para negrita.</p>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <div class="flex justify-center pt-8 border-t border-gray-700">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-12 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    GUARDAR MÉTRICAS
                                </button>
                            </div>
                        </form>
                    </div>


                    <!-- TAB 7: CATEGORÍAS -->
                    <div x-show="activeTab === 'categorias'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <form action="{{ route('admin.somos-itca.update-content') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active_tab" value="categorias">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                @for($i = 1; $i <= 4; $i++)
                                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700 shadow-inner flex flex-col h-full">
                                    <h4 class="text-blue-400 font-bold mb-4 uppercase text-[10px] tracking-widest border-b border-gray-700 pb-2">Card #{{$i}}</h4>
                                    
                                    <div class="space-y-4 flex-grow">
                                        <!-- Imagen -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase text-center">Imagen (Frente)</label>
                                            <div class="relative group aspect-square">
                                                <input type="file" name="cat{{$i}}_img" id="cat{{$i}}_img" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                <label for="cat{{$i}}_img" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all flex flex-col items-center justify-center group shadow-inner">
                                                    @if(isset($content->{'cat'.$i.'_img'}) && $content->{'cat'.$i.'_img'})
                                                        <img id="cat{{$i}}_preview" src="{{ asset('storage/' . $content->{'cat'.$i.'_img'}) }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @else
                                                        <div id="cat{{$i}}_placeholder" class="flex flex-col items-center justify-center text-gray-600">
                                                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                            <span class="text-[10px] font-bold">Subir</span>
                                                        </div>
                                                        <img id="cat{{$i}}_preview" class="w-full h-full object-cover hidden">
                                                    @endif
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Título -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Título (Front/Back)</label>
                                            <input type="text" name="cat{{$i}}_title" value="{{ old('cat'.$i.'_title', $content->{'cat'.$i.'_title'}) }}" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-xs focus:border-blue-500 focus:ring-0"
                                                placeholder="Ej: MOTORES">
                                        </div>

                                        <!-- Descripción -->
                                        <div class="flex-grow">
                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Descripción (Atrás)</label>
                                            <textarea name="cat{{$i}}_text" rows="5" 
                                                class="w-full bg-gray-800 border-gray-600 rounded text-gray-100 text-[11px] focus:border-blue-500 focus:ring-0 leading-tight"
                                                placeholder="Texto que aparece al girar...">{{ old('cat'.$i.'_text', $content->{'cat'.$i.'_text'}) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <div class="flex justify-center pt-8 border-t border-gray-700">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-12 rounded-full shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        GUARDAR TEXTOS
                                    </button>
                            </div>
                        </form>
                    </div>

    <!-- MODALS (Keeping them for item creation) -->
    
    <!-- MODAL INSTALACIÓN IMAGEN -->
    <div id="modalInstalacion" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 border border-gray-700 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4 uppercase border-b border-gray-700 pb-2">Agregar Imagen Instalación</h3>
            <form action="{{ route('admin.somos-itca.instalaciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4 bg-gray-900 p-6 rounded border border-gray-700 border-dashed text-center">
                    <label class="block text-sm font-medium text-gray-400 mb-4 uppercase">Subir archivo</label>
                    <input type="file" name="image" accept="image/*" required class="w-full text-gray-400 text-xs font-mono">
                    <p class="text-[10px] text-gray-600 mt-2 italic">Formatos: JPG, PNG, WEBP</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalInstalacion').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-gray-300 rounded text-xs font-bold uppercase hover:bg-gray-600 transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded text-xs font-bold uppercase hover:bg-green-500 shadow-lg shadow-green-900/20 transition-all">Subir Imagen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL INSTALACION ITEM (ESTRELLAS) -->
    <div id="modalInstalacionItem" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 border border-gray-700 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4 uppercase border-b border-gray-700 pb-2">Agregar Item Destacado</h3>
            <form action="{{ route('admin.somos-itca.instalacion-items.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2 uppercase">Descripción del item</label>
                    <textarea name="descripcion" rows="3" required class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded text-gray-200 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all" placeholder="Ej: Equipamiento */full/* para formación."></textarea>
                    <p class="text-[10px] text-gray-500 mt-1">Usa */texto/* para poner en negrita.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalInstalacionItem').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-gray-300 rounded text-xs font-bold uppercase hover:bg-gray-600 transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded text-xs font-bold uppercase hover:bg-green-500 shadow-lg shadow-green-900/20 transition-all">Guardar Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FORMADOR -->
    <div id="modalFormador" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 border border-gray-700 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4 uppercase border-b border-gray-700 pb-2">Agregar Formador</h3>
            <form action="{{ route('admin.somos-itca.formadores.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase">Nombre y Apellido</label>
                        <input type="text" name="nombre" required class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded text-gray-100 text-sm focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase">Fotografía</label>
                        <input type="file" name="image" accept="image/*" required class="w-full text-gray-400 text-xs font-mono bg-gray-900 p-2 rounded border border-gray-700">
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="document.getElementById('modalFormador').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-gray-300 rounded text-xs font-bold uppercase hover:bg-gray-600 transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded text-xs font-bold uppercase hover:bg-green-500 shadow-lg shadow-green-900/20 transition-all">Crear Formador</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POR QUÉ ITEM -->
    <div id="modalPorQue" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 border border-gray-700 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4 uppercase border-b border-gray-700 pb-2">Agregar Razón / Beneficio</h3>
            <form action="{{ route('admin.somos-itca.porque.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2 uppercase">Descripción breve</label>
                    <textarea name="descripcion" rows="3" required class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded text-gray-100 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalPorQue').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-gray-300 rounded text-xs font-bold uppercase hover:bg-gray-600 transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded text-xs font-bold uppercase hover:bg-green-500 shadow-lg shadow-green-900/20 transition-all">Agregar Razón</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalInstalacion() { document.getElementById('modalInstalacion').classList.remove('hidden'); }
        function openModalFormador() { document.getElementById('modalFormador').classList.remove('hidden'); }
        function openModalPorQue() { document.getElementById('modalPorQue').classList.remove('hidden'); }

        // Previewers for different tabs
        function previewHero(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.getElementById('hero_preview');
                    const placeholder = document.getElementById('hero_placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewImageTab3(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.getElementById('img_preview_tab3');
                    const placeholder = document.getElementById('img_placeholder_tab3');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewVideoTab2(input) {
            if (input.files && input.files[0]) {
                if(input.files[0].size > 50 * 1024 * 1024) {
                    alert("El archivo es demasiado grande (Máx 50MB)");
                    input.value = "";
                    return;
                }
                const url = URL.createObjectURL(input.files[0]);
                const video = document.getElementById('video_preview_tab2');
                const placeholder = document.getElementById('video_placeholder_tab2');
                video.src = url;
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        /* Sistema de Confirmación usando el Modal del Layout */
        function confirmSubmission(event, title, message) {
            event.preventDefault();
            const form = event.target;
            const modal = document.getElementById('confirmation-modal');
            if (modal && modal._x_dataStack) {
                const modalData = modal._x_dataStack[0];
                modalData.title = title;
                modalData.message = message;
                modalData.onConfirm = () => { form.submit(); };
                modalData.open = true;
            } else {
                if (confirm(message)) { form.submit(); }
            }
            return false;
        }

        // Close modals on escape
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed.inset-0').forEach(el => el.classList.add('hidden'));
            }
        });
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sortable Por Que
            var elPQ = document.getElementById('porQueList');
            if(elPQ){
                new Sortable(elPQ, {
                    animation: 150,
                    ghostClass: 'bg-gray-700',
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        var orden = [];
                        document.querySelectorAll('#porQueList .por-que-draggable').forEach(item => orden.push(item.getAttribute('data-id')));
                        fetch("{{ route('admin.somos-itca.porque.reorder') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ orden: orden })
                        });
                    },
                });
            }

            // Sortable Instalacion Items
            var elInstItems = document.getElementById('instalacionItemsList');
            if(elInstItems){
                new Sortable(elInstItems, {
                    animation: 150,
                    ghostClass: 'bg-gray-700',
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        var orden = [];
                        document.querySelectorAll('#instalacionItemsList .instalacion-item-draggable').forEach(item => orden.push(item.getAttribute('data-id')));
                        fetch("{{ route('admin.somos-itca.instalacion-items.reorder') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ orden: orden })
                        });
                    },
                });
            }

            // Sortable Instalaciones (Grid/List content)
            var elInstalaciones = document.getElementById('instalacionesList');
            if(elInstalaciones){
                new Sortable(elInstalaciones, {
                    animation: 150,
                    ghostClass: 'bg-gray-700',
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        var orden = [];
                        document.querySelectorAll('#instalacionesList .instalacion-draggable').forEach(item => orden.push(item.getAttribute('data-id')));
                        fetch("{{ route('admin.somos-itca.instalaciones.reorder') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ orden: orden })
                        });
                    },
                });
            }

            // Sortable Formadores
            var elFormadores = document.getElementById('formadoresList');
            if(elFormadores){
                new Sortable(elFormadores, {
                    animation: 150,
                    ghostClass: 'bg-gray-700',
                    handle: '.drag-handle',
                    onEnd: function (evt) {
                        var orden = [];
                        document.querySelectorAll('#formadoresList .formador-draggable').forEach(item => orden.push(item.getAttribute('data-id')));
                        fetch("{{ route('admin.somos-itca.formadores.reorder') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ orden: orden })
                        });
                    },
                });
            }

            // Auto-hide Toast Messages
            const toasts = document.querySelectorAll('#toast-container > div');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(20px)';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
    <script>
        function previewCat(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('cat' + id + '_preview');
                    const placeholder = document.getElementById('cat' + id + '_placeholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
