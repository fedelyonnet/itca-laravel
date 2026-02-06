@php
    $isActivePanel = request()->routeIs('admin.dashboard');
    
    $carrerasRoutes = [
        'admin.carreras.index',
        'admin.carreras.multimedia',
        'admin.carreras.importacion',
        'admin.carreras.ordenar-filtros',
        'admin.carreras.importar-promociones',
    ];
    $isActiveCarreras = request()->routeIs($carrerasRoutes);
    
    $cmsRoutes = [
        'admin.edit-hero',
        'admin.beneficios',
        'admin.dudas.index',
        'admin.sedes',
        'admin.testimonios',
        'admin.partners',
        'admin.en-accion',
        'admin.contacto.index',
    ];
    $isActiveCMS = request()->routeIs($cmsRoutes);

    $cmsPaginasRoutes = [
        'admin.somos-itca-page.index',
        'admin.beneficios-page.index',
    ];
    $isActiveCMSPaginas = request()->routeIs($cmsPaginasRoutes);
    
    $leadsRoutes = [
        'admin.leads',
        'admin.leads.config',
        'admin.leads.mail-templates',
    ];
    $isActiveLeads = request()->routeIs($leadsRoutes);
    // $isActiveLeads = false;
    
    $isActiveInscriptos = request()->routeIs('admin.inscriptos');
    // $isActiveInscriptos = false;
    $isActiveNoticias = request()->routeIs('admin.noticias.index');
@endphp

<nav class="flex items-center space-x-6">
    <a href="{{ route('admin.dashboard') }}" 
       class="relative inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
        Panel
        @if($isActivePanel)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif
    </a>

    
    
    <div class="relative inline-flex items-center" 
         x-data="{ open: false }" 
         @click.away="open = false" 
         @keydown.escape.window="open = false">
        
        <button @click="open = !open" 
                @keydown.enter.prevent="open = !open"
                @keydown.space.prevent="open = !open"
                :aria-expanded="open"
                aria-haspopup="true"
                class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
            Carreras
            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
        @if($isActiveCarreras)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif

        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute left-0 top-full mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-50"
             role="menu">
            
            <div class="py-1">
                <a href="{{ route('admin.carreras.index') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.carreras.index') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Gestión de Carreras
                </a>
                
                <a href="{{ route('admin.carreras.multimedia') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.carreras.multimedia') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición multimedia
                </a>
                
                <a href="{{ route('admin.carreras.importacion') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.carreras.importacion') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Importación cursos
                </a>
                
                <a href="{{ route('admin.carreras.ordenar-filtros') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.carreras.ordenar-filtros') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Ordenar filtros
                </a>
                
                <a href="{{ route('admin.carreras.importar-promociones') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.carreras.importar-promociones') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Importar promociones
                </a>
            </div>
        </div>
    </div>
    
    <div class="relative inline-flex items-center" 
         x-data="{ open: false }" 
         @click.away="open = false" 
         @keydown.escape.window="open = false">
        
        <button @click="open = !open" 
                @keydown.enter.prevent="open = !open"
                @keydown.space.prevent="open = !open"
                :aria-expanded="open"
                aria-haspopup="true"
                class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
            Edición CMS Home
            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
        @if($isActiveCMS)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif

        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute left-0 top-full mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-50"
             role="menu">
            
            <div class="py-1">
                <a href="{{ route('admin.edit-hero') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.edit-hero') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Hero + Sticky Bar
                </a>

                <a href="{{ route('admin.beneficios') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.beneficios') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Beneficios
                </a>

                <a href="{{ route('admin.dudas.index') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.dudas.index') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    FAQs
                </a>
                
                <a href="{{ route('admin.sedes') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.sedes') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Sedes
                </a>
                
                <a href="{{ route('admin.testimonios') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.testimonios') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Testimonios
                </a>
                
                <a href="{{ route('admin.partners') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.partners') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Partners
                </a>
                
                <a href="{{ route('admin.en-accion') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.en-accion') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    En Acción
                </a>
                
                
                <a href="{{ route('admin.contacto.index') }}" 
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Contacto
                </a>
            </div>
        </div>
    </div>

    <!-- New Dropdown: Edición CMS Páginas -->
    <div class="relative inline-flex items-center" 
         x-data="{ open: false }" 
         @click.away="open = false" 
         @keydown.escape.window="open = false">
        
        <button @click="open = !open" 
                @keydown.enter.prevent="open = !open"
                @keydown.space.prevent="open = !open"
                :aria-expanded="open"
                aria-haspopup="true"
                class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
            Edición CMS Páginas
            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
        @if($isActiveCMSPaginas)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif

        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute left-0 top-full mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-50"
             role="menu">
            
            <div class="py-1">
                <a href="{{ route('admin.somos-itca-page.index') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.somos-itca-page.index') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Página Somos ITCA
                </a>

                <a href="{{ route('admin.beneficios-page.index') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.beneficios-page.index') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Página Beneficios
                </a>
        
            </div>
        </div>
    </div>
    
    <div class="relative inline-flex items-center" 
         x-data="{ open: false }" 
         @click.away="open = false" 
         @keydown.escape.window="open = false">
        
        <button @click="open = !open" 
                @keydown.enter.prevent="open = !open"
                @keydown.space.prevent="open = !open"
                :aria-expanded="open"
                aria-haspopup="true"
                class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
            Leads
            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
        @if($isActiveLeads)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif

        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute left-0 top-full mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-50"
             role="menu">
            
            <div class="py-1">
                <a href="{{ route('admin.leads') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.leads') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Grilla de Leads
                </a>
                
                <a href="{{ route('admin.leads.config') }}" 
                   @keydown.enter="window.location.href = '{{ route('admin.leads.config') }}'"
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Configuración Leads
                </a>

                <a href="{{ route('admin.leads.mail-templates') }}" 
                   class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Mails de Seguimiento
                </a>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.inscriptos') }}" 
       class="relative inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
        Inscriptos
        @if($isActiveInscriptos)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif
    </a>

    <a href="{{ route('admin.noticias.index') }}" 
       class="relative inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
        Noticias
        @if($isActiveNoticias)
            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        @endif
    </a>
</nav>