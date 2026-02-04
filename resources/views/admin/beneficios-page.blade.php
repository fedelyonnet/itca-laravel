
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Video Column -->
                            <div class="flex flex-col items-center">
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="club_itca">
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700 h-full">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase text-center">Video Club ITCA</label>
                                        <div class="relative group h-64">
                                            <input type="file" name="club_itca_video" id="club_itca_video" accept="video/*" class="hidden" 
                                                onchange="isUploading = true; this.form.submit()">
                                            <label for="club_itca_video" class="relative block w-full h-full bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
                                                @if(isset($content->club_itca_video) && $content->club_itca_video)
                                                    <video src="{{ asset('storage/' . $content->club_itca_video) }}" class="w-full h-full object-cover" muted></video>
                                                @endif
                                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 pointer-events-none bg-gray-900/40">
                                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
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
                                <form action="{{ route('admin.beneficios.page.update') }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active_tab" value="club_itca">
                                    
                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">Texto Club ITCA</label>
                                        <textarea name="club_itca_texto" rows="8" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono custom-scrollbar" placeholder="Ingresa el texto aquí...">{{ $content->club_itca_texto }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Usa */texto/* para poner en negrita</p>
                                    </div>

                                    <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                        <label class="block text-sm font-bold text-gray-300 mb-2 uppercase italic">URL Botón / Enlace</label>
                                        <input type="text" name="club_itca_button_url" value="{{ $content->club_itca_button_url }}" class="w-full bg-gray-800 border-gray-700 text-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="https://...">
                                    </div>

                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition-colors shadow-lg active:scale-95 flex items-center justify-center gap-2 uppercase text-sm tracking-wider">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        Guardar Textos
                                    </button>
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
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</x-app-layout>
