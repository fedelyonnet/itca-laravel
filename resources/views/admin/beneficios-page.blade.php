
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
