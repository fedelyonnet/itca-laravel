<x-app-layout>
    @push('head')
        @vite(['resources/js/carreras-test.js'])
    @endpush
    
    <!-- Variable global para pasar datos desde Blade a Alpine -->
    <script>
        window.carreraSeleccionadaData = @json($carreraSeleccionada);
        window.featuredCount = {{ $featuredCount ?? 0 }};
        
        // Definir rutas para el JavaScript
        window.routes = {
            admin: {
                carreras: {
                    store: '{{ route("admin.carreras.store") }}',
                    update: '{{ route("admin.carreras.update", ":id") }}',
                    destroy: '{{ route("admin.carreras.destroy", ":id") }}',
                    test: '{{ route("admin.carreras.test") }}'
                },
                programas: {
                    anios: {
                        store: '{{ route("admin.programas.anios.store") }}',
                        data: '{{ route("admin.programas.anios.data", ":id") }}',
                        update: '{{ route("admin.programas.anios.update", ":id") }}',
                        destroy: '{{ route("admin.programas.anios.destroy", ":id") }}'
                    },
                    unidades: {
                        store: '{{ route("admin.programas.unidades.store") }}',
                        data: '{{ route("admin.programas.unidades.data", ":id") }}',
                        update: '{{ route("admin.programas.unidades.update", ":id") }}',
                        destroy: '{{ route("admin.programas.unidades.destroy", ":id") }}'
                    }
                },
                modalidades: {
                    toggleActivo: '{{ route("admin.modalidades.toggle-activo", ":id") }}',
                    store: '{{ route("admin.modalidades.store") }}',
                    data: '{{ route("admin.modalidades.data", ":id") }}',
                    update: '{{ route("admin.modalidades.update", ":id") }}',
                    destroy: '{{ route("admin.modalidades.destroy", ":id") }}',
                    tipos: {
                        store: '{{ route("admin.modalidades.tipos.store") }}',
                        update: '{{ route("admin.modalidades.tipos.update", ":id") }}',
                        destroy: '{{ route("admin.modalidades.tipos.destroy", ":id") }}'
                    }
                }
            }
        };
    </script>
    
    <!-- Toast Messages Container - Igual que el resto del sitio -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div id="success-message" class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div id="error-message" class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100" 
                     x-data="carreraManager()" 
                     x-init="init(); initToasts()">
                    <!-- Header con selector de carrera -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold mb-2">Gestión de Carreras</h1>
                            <p class="text-sm text-gray-400">Crea y edita carreras con todas sus configuraciones</p>
                        </div>
                        <div class="flex gap-3">
                            <form method="GET" action="{{ route('admin.carreras.test') }}" class="flex gap-3">
                                <select 
                                    name="curso_id"
                                    onchange="this.form.submit()"
                                    class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[300px]"
                                >
                                    <option value="">-- Seleccione una carrera --</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->id }}" {{ request('curso_id') == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <button 
                                @click="nuevaCarrera()"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors"
                            >
                                + Nueva Carrera
                            </button>
                        </div>
                    </div>

                    <!-- Pestañas -->
                    <div x-show="carreraId || modoCrear" x-cloak>
                        <div class="border-b border-gray-700 mb-6">
                            <nav class="flex space-x-8" aria-label="Tabs">
                                <button
                                    @click="tabActiva = 'basico'"
                                    :class="tabActiva === 'basico' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                >
                                    Información Básica
                                </button>
                                <button
                                    @click="tabActiva = 'imagenes'"
                                    :class="tabActiva === 'imagenes' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                >
                                    Imágenes
                                </button>
                                <button
                                    @click="tabActiva = 'sedes'"
                                    :class="tabActiva === 'sedes' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                >
                                    Sedes
                                </button>
                                <button
                                    @click="tabActiva = 'programa'"
                                    :class="tabActiva === 'programa' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                >
                                    Programa
                                </button>
                                <button
                                    @click="tabActiva = 'modalidades'"
                                    :class="tabActiva === 'modalidades' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                                >
                                    Modalidades
                                </button>
                            </nav>
                        </div>

                        <!-- Contenido de las pestañas -->
                        <div class="mt-6">
                            <!-- Pestaña: Información Básica -->
                            <div x-show="tabActiva === 'basico'" x-cloak>
                                <form id="formBasico" @submit.prevent="guardarBasico()">
                                    @csrf
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Nombre de la Carrera <span class="text-red-400">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                x-model="formData.nombre"
                                                required
                                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="Ej: Ingeniería en Sistemas"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Descripción
                                            </label>
                                            <textarea 
                                                x-model="formData.descripcion"
                                                rows="4"
                                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="Descripción de la carrera..."
                                            ></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                                    Fecha de Inicio <span class="text-red-400">*</span>
                                                </label>
                                                <input 
                                                    type="date" 
                                                    x-model="formData.fecha_inicio"
                                                    required
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                                    Orden
                                                </label>
                                                <input 
                                                    type="number" 
                                                    x-model="formData.orden"
                                                    min="1"
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-3">
                                                Badge de Modalidades <span class="text-red-400">*</span>
                                            </label>
                                            <div class="space-y-2">
                                                <label class="flex items-center">
                                                    <input 
                                                        type="checkbox" 
                                                        x-model="formData.modalidad_online"
                                                        class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500"
                                                    >
                                                    <span class="ml-2 text-gray-300">Online</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input 
                                                        type="checkbox" 
                                                        x-model="formData.modalidad_presencial"
                                                        class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500"
                                                    >
                                                    <span class="ml-2 text-gray-300">Presencial</span>
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1">Selecciona al menos una modalidad</p>
                                        </div>

                                        <div>
                                            <label class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    x-model="formData.featured"
                                                    @change="validarFeatured($event)"
                                                    :disabled="!puedeMarcarDestacada"
                                                    class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                <span class="ml-2 text-gray-300">
                                                    Carrera Destacada (visible en home)
                                                    <span class="text-xs text-gray-400 block mt-1" x-show="featuredCount >= 2 && !formData.featured">
                                                        Máximo 2 carreras destacadas (<span x-text="featuredCount"></span>/2)
                                                    </span>
                                                </span>
                                            </label>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <button 
                                                type="button"
                                                @click="eliminarCarrera()"
                                                x-show="carreraId && !modoCrear"
                                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md transition-colors"
                                            >
                                                Eliminar Carrera
                                            </button>
                                            <button 
                                                type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors ml-auto"
                                            >
                                                Guardar Información Básica
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Pestaña: Imágenes -->
                            <div x-show="tabActiva === 'imagenes'" x-cloak>
                                <form id="formImagenes" @submit.prevent="guardarImagenes()" enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Ilustración Desktop -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Ilustración Desktop <span class="text-red-400">*</span>
                                            </label>
                                            <p class="text-xs text-gray-400 mb-2">Tamaño recomendado: 768x418</p>
                                            <div class="mb-2">
                                                <input 
                                                    type="file" 
                                                    name="ilustracion_desktop"
                                                    @change="previewImagen($event, 'ilustracion_desktop')"
                                                    accept="image/*"
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                            </div>
                                            <div class="w-full h-48 bg-gray-700 rounded-md overflow-hidden border border-gray-600">
                                                <img 
                                                    :src="previews.ilustracion_desktop || (formData.imagenes?.ilustracion_desktop ? '/storage/' + formData.imagenes.ilustracion_desktop : '')"
                                                    alt="Preview Desktop"
                                                    class="w-full h-full object-contain"
                                                    x-show="previews.ilustracion_desktop || formData.imagenes?.ilustracion_desktop"
                                                >
                                                <div x-show="!previews.ilustracion_desktop && !formData.imagenes?.ilustracion_desktop" class="flex items-center justify-center h-full text-gray-400">
                                                    <span>Sin imagen</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ilustración Mobile -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Ilustración Mobile <span class="text-red-400">*</span>
                                            </label>
                                            <p class="text-xs text-gray-400 mb-2">Tamaño recomendado: 388x430</p>
                                            <div class="mb-2">
                                                <input 
                                                    type="file" 
                                                    name="ilustracion_mobile"
                                                    @change="previewImagen($event, 'ilustracion_mobile')"
                                                    accept="image/*"
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                            </div>
                                            <div class="w-full h-48 bg-gray-700 rounded-md overflow-hidden border border-gray-600">
                                                <img 
                                                    :src="previews.ilustracion_mobile || (formData.imagenes?.ilustracion_mobile ? '/storage/' + formData.imagenes.ilustracion_mobile : '')"
                                                    alt="Preview Mobile"
                                                    class="w-full h-full object-contain"
                                                    x-show="previews.ilustracion_mobile || formData.imagenes?.ilustracion_mobile"
                                                >
                                                <div x-show="!previews.ilustracion_mobile && !formData.imagenes?.ilustracion_mobile" class="flex items-center justify-center h-full text-gray-400">
                                                    <span>Sin imagen</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Imagen Show Desktop -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Imagen carrera individual desktop
                                            </label>
                                            <p class="text-xs text-gray-400 mb-2">Tamaño recomendado: 386x168</p>
                                            <div class="mb-2">
                                                <input 
                                                    type="file" 
                                                    name="imagen_show_desktop"
                                                    @change="previewImagen($event, 'imagen_show_desktop')"
                                                    accept="image/*"
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                            </div>
                                            <div class="w-full h-48 bg-gray-700 rounded-md overflow-hidden border border-gray-600">
                                                <img 
                                                    :src="previews.imagen_show_desktop || (formData.imagenes?.imagen_show_desktop ? '/storage/' + formData.imagenes.imagen_show_desktop : '')"
                                                    alt="Preview Show Desktop"
                                                    class="w-full h-full object-contain"
                                                    x-show="previews.imagen_show_desktop || formData.imagenes?.imagen_show_desktop"
                                                >
                                                <div x-show="!previews.imagen_show_desktop && !formData.imagenes?.imagen_show_desktop" class="flex items-center justify-center h-full text-gray-400">
                                                    <span>Sin imagen</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Imagen Show Mobile -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                Imagen carrera individual mobile
                                            </label>
                                            <p class="text-xs text-gray-400 mb-2">Tamaño recomendado: 386x168</p>
                                            <div class="mb-2">
                                                <input 
                                                    type="file" 
                                                    name="imagen_show_mobile"
                                                    @change="previewImagen($event, 'imagen_show_mobile')"
                                                    accept="image/*"
                                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                            </div>
                                            <div class="w-full h-48 bg-gray-700 rounded-md overflow-hidden border border-gray-600">
                                                <img 
                                                    :src="previews.imagen_show_mobile || (formData.imagenes?.imagen_show_mobile ? '/storage/' + formData.imagenes.imagen_show_mobile : '')"
                                                    alt="Preview Show Mobile"
                                                    class="w-full h-full object-contain"
                                                    x-show="previews.imagen_show_mobile || formData.imagenes?.imagen_show_mobile"
                                                >
                                                <div x-show="!previews.imagen_show_mobile && !formData.imagenes?.imagen_show_mobile" class="flex items-center justify-center h-full text-gray-400">
                                                    <span>Sin imagen</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button 
                                            type="submit"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors"
                                        >
                                            Guardar Imágenes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Pestaña: Sedes -->
                            <div x-show="tabActiva === 'sedes'" x-cloak>
                                <form id="formSedes" @submit.prevent="guardarSedes()">
                                    @csrf
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold mb-4">Selecciona las sedes donde se cursa esta carrera</h3>
                                        <div class="space-y-2 max-h-96 overflow-y-auto">
                                            @foreach($sedes as $sede)
                                                <label class="flex items-center p-3 bg-gray-700 rounded-md hover:bg-gray-600 transition-colors cursor-pointer">
                                                    <input 
                                                        type="checkbox" 
                                                        :value="{{ $sede->id }}"
                                                        x-model="formData.sedes"
                                                        class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500"
                                                    >
                                                    <span class="ml-3 text-gray-300">{{ $sede->nombre }}</span>
                                                    @if($sede->zona)
                                                        <span class="ml-2 px-2 py-1 bg-purple-600 text-white text-xs rounded">{{ $sede->zona }}</span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <button 
                                            type="submit"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors"
                                        >
                                            Guardar Sedes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Pestaña: Programa -->
                            <div x-show="tabActiva === 'programa'" x-cloak>
                                <div class="mb-4 flex justify-between items-center">
                                    <h3 class="text-lg font-semibold">Años del Programa</h3>
                                    <button 
                                        @click="abrirModalAnio()"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors"
                                    >
                                        + Agregar Año
                                    </button>
                                </div>

                                <div class="space-y-4" x-show="formData.anios && formData.anios.length > 0">
                                    <template x-for="(anio, index) in formData.anios" :key="anio.id || index">
                                        <div class="bg-gray-700 rounded-lg p-4" x-data="{ expanded: false }">
                                            <div class="flex items-center justify-between">
                                                <!-- Información del Año -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-lg font-bold text-white" x-text="anio.año + '° AÑO'"></span>
                                                        <span class="text-sm text-gray-300" x-text="anio.titulo"></span>
                                                        <span class="px-2 py-1 bg-purple-600 text-white text-xs rounded" x-text="anio.nivel"></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center space-x-2">
                                                    <button 
                                                        @click="expanded = !expanded"
                                                        class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-2 rounded text-sm transition-colors"
                                                    >
                                                        <span x-show="!expanded">Ver Unidades</span>
                                                        <span x-show="expanded">Ocultar</span>
                                                    </button>
                                                    <button 
                                                        @click="editarAnio(anio.id)"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                    >
                                                        Editar
                                                    </button>
                                                    <button 
                                                        @click="eliminarAnio(anio.id)"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                    >
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Unidades del año -->
                                            <div x-show="expanded" class="mt-4 space-y-2">
                                                <template x-for="(unidad, uIndex) in anio.unidades" :key="unidad.id || uIndex">
                                                    <div class="bg-gray-600 rounded p-3 ml-4">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <span class="font-semibold text-white" x-text="unidad.numero"></span>
                                                                <span class="ml-2 text-gray-300" x-text="unidad.titulo"></span>
                                                                <span class="ml-2 text-sm text-gray-400" x-text="unidad.subtitulo"></span>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <button 
                                                                    @click="editarUnidad(unidad.id)"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors"
                                                                >
                                                                    Editar
                                                                </button>
                                                                <button 
                                                                    @click="eliminarUnidad(unidad.id)"
                                                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors"
                                                                >
                                                                    Eliminar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <button 
                                                    @click="abrirModalUnidad(anio.id)"
                                                    class="ml-4 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                >
                                                    + Agregar Unidad
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="!formData.anios || formData.anios.length === 0" class="text-center py-8 text-gray-400">
                                    No hay años agregados. Haz clic en "Agregar Año" para comenzar.
                                </div>
                            </div>

                            <!-- Pestaña: Modalidades -->
                            <div x-show="tabActiva === 'modalidades'" x-cloak>
                                <div class="mb-4 flex justify-between items-center">
                                    <h3 class="text-lg font-semibold">Modalidades de Cursado</h3>
                                    <button 
                                        @click="abrirModalModalidad()"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors"
                                    >
                                        + Agregar Modalidad
                                    </button>
                                </div>

                                <div class="space-y-4" x-show="formData.modalidades && formData.modalidades.length > 0">
                                    <template x-for="(modalidad, index) in formData.modalidades" :key="modalidad.id || index">
                                        <div class="bg-gray-700 rounded-lg p-4" x-data="{ expanded: false }">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-white" x-text="modalidad.nombre_linea1"></h4>
                                                    <p class="text-sm text-gray-300" x-text="modalidad.nombre_linea2 || ''"></p>
                                                    <p class="text-xs text-gray-400 mt-1" x-text="modalidad.texto_info || ''"></p>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <button 
                                                        @click="toggleModalidadActivo(modalidad.id, modalidad.activo !== undefined ? modalidad.activo : true)"
                                                        :class="(modalidad.activo !== undefined ? modalidad.activo : true) ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                                                        class="text-white px-3 py-2 rounded text-sm transition-colors font-medium"
                                                    >
                                                        <span x-show="modalidad.activo !== undefined ? modalidad.activo : true">Visible</span>
                                                        <span x-show="modalidad.activo !== undefined && !modalidad.activo">Oculto</span>
                                                    </button>
                                                    <button 
                                                        @click="expanded = !expanded"
                                                        class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-2 rounded text-sm transition-colors"
                                                    >
                                                        <span x-show="!expanded">Ver Detalles</span>
                                                        <span x-show="expanded">Ocultar</span>
                                                    </button>
                                                    <button 
                                                        @click="editarModalidad(modalidad.id)"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                    >
                                                        Editar
                                                    </button>
                                                    <button 
                                                        @click="eliminarModalidad(modalidad.id)"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                    >
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Detalles de la modalidad - Grilla de Tipos -->
                                            <div x-show="expanded" class="mt-4 space-y-4" x-data="modalidadManager(modalidad)">
                                                <!-- Selección de Columnas (Checkboxes) - Fijas para todas las modalidades -->
                                                <div class="bg-gray-600 rounded-lg p-4">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <h5 class="text-sm font-semibold text-gray-300">Seleccionar Columnas a Mostrar (Arrastra para reordenar):</h5>
                                                    </div>
                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                        <template x-for="(columna, index) in columnasOrdenadas" :key="columna.campo_dato">
                                                            <div 
                                                                :class="{
                                                                    'opacity-50': draggedIndex !== null && getSelectedIndex(columna.campo_dato) === draggedIndex,
                                                                    'border-blue-500 bg-blue-900': draggedIndex !== null && getSelectedIndex(columna.campo_dato) !== -1 && getSelectedIndex(columna.campo_dato) !== draggedIndex && isDraggingOver(getSelectedIndex(columna.campo_dato))
                                                                }"
                                                                :draggable="columnasSeleccionadas.includes(columna.campo_dato)"
                                                                @dragstart="dragStart($event, getSelectedIndex(columna.campo_dato))"
                                                                @dragover.prevent="dragOver($event, getSelectedIndex(columna.campo_dato))"
                                                                @dragleave="dragLeave($event)"
                                                                @drop="drop($event, getSelectedIndex(columna.campo_dato))"
                                                                @dragend="dragEnd($event)"
                                                                class="flex items-center space-x-2 border border-transparent rounded-md p-2 transition-colors"
                                                                :class="columnasSeleccionadas.includes(columna.campo_dato) ? 'cursor-move hover:border-blue-400' : ''"
                                                            >
                                                                <label class="flex items-center space-x-2 cursor-pointer flex-1" @mousedown.stop @click.stop>
                                                                    <input type="checkbox" 
                                                                           :value="columna.campo_dato" 
                                                                           x-model="columnasSeleccionadas"
                                                                           @change="validarSeleccionColumnas(columna.campo_dato, columnasSeleccionadas.includes(columna.campo_dato)); sincronizarColumnas()"
                                                                           class="rounded border-gray-500 bg-gray-700 text-blue-600 focus:ring-blue-500">
                                                                    <span class="text-sm text-gray-300 flex items-center gap-1">
                                                                        <span x-text="columna.nombre"></span>
                                                                        <svg x-show="columnasSeleccionadas.includes(columna.campo_dato)" class="w-4 h-4 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                                        </svg>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <!-- Edición de Horarios -->
                                                <div class="bg-gray-600 rounded-lg p-4">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <h5 class="text-sm font-semibold text-gray-300">Horarios de Cursado</h5>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-3">
                                                        <template x-for="(horario, hIndex) in horarios" :key="hIndex">
                                                            <div class="flex flex-col items-center bg-gray-700 rounded-md p-3">
                                                                <div class="flex-shrink-0 mb-2">
                                                                    <img :src="horario.icono" :alt="horario.nombre" class="w-8 h-8 mx-auto">
                                                                </div>
                                                                <div class="w-full">
                                                                    <label class="block text-xs text-gray-400 mb-1 text-center" x-text="horario.nombre"></label>
                                                                    <input type="text" 
                                                                           x-model="horario.horas"
                                                                           @blur="guardarHorarios()"
                                                                           @keydown.enter.prevent="$event.target.blur()"
                                                                           class="w-full px-2 py-1 bg-gray-600 border border-gray-500 rounded text-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 text-center"
                                                                           placeholder="Ej: 9:00 a 12:30hs">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <!-- Grilla de Tipos -->
                                                <div class="bg-gray-600 rounded-lg p-4">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <h5 class="text-sm font-semibold text-gray-300">Tipos de Modalidad</h5>
                                                        <button @click="agregarTipo()" 
                                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                                            + Agregar Tipo
                                                        </button>
                                                    </div>

                                                    <!-- Tabla/Grilla -->
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-sm">
                                                            <thead>
                                                                <tr class="border-b border-gray-500">
                                                                    <th class="text-left py-2 px-2 text-gray-300 font-semibold">Tipo</th>
                                                                    <template x-for="columna in columnasSeleccionadas" :key="columna">
                                                                        <th class="text-left py-2 px-2 text-gray-300 font-semibold" x-text="getNombreColumna(columna)"></th>
                                                                    </template>
                                                                    <th class="text-center py-2 px-2 text-gray-300 font-semibold">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <template x-for="(tipo, tIndex) in tipos" :key="tipo._tempId || tipo.id || `temp-${tIndex}`">
                                                                    <tr class="border-b border-gray-500 hover:bg-gray-500">
                                                                        <td class="py-2 px-2">
                                                                            <input type="text" 
                                                                                   x-model="tipo.nombre" 
                                                                                   @focusout="guardarTipo(tipo, $event)"
                                                                                   @keydown.enter.prevent="$event.target.blur()"
                                                                                   @keydown.tab="handleTabKey($event, tipo)"
                                                                                   class="w-full px-2 py-1 bg-gray-700 border border-gray-500 rounded text-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                                                   placeholder="Ej: REGULAR">
                                                                        </td>
                                                                        <template x-for="columna in columnasSeleccionadas" :key="columna">
                                                                            <td class="py-2 px-2">
                                                                                <input type="text" 
                                                                                       x-model="tipo[columna]" 
                                                                                       @focusout="guardarTipo(tipo, $event)"
                                                                                       @keydown.enter.prevent="$event.target.blur()"
                                                                                       @keydown.tab="handleTabKey($event, tipo)"
                                                                                       class="w-full px-2 py-1 bg-gray-700 border border-gray-500 rounded text-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                                                       :placeholder="'Valor para ' + getNombreColumna(columna)">
                                                                            </td>
                                                                        </template>
                                                                        <td class="py-2 px-2 text-center">
                                                                            <button @click="eliminarTipo(tipo.id, tIndex)" 
                                                                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                                                                Eliminar
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                                <tr x-show="tipos.length === 0">
                                                                    <td :colspan="columnasSeleccionadas.length + 2" class="py-4 text-center text-gray-400 text-sm">
                                                                        No hay tipos. Haz clic en "+ Agregar Tipo" para comenzar.
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="!formData.modalidades || formData.modalidades.length === 0" class="text-center py-8 text-gray-400">
                                    No hay modalidades agregadas. Haz clic en "Agregar Modalidad" para comenzar.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje cuando no hay carrera seleccionada -->
                    <div x-show="!carreraId && !modoCrear" class="text-center py-12 text-gray-400">
                        <p class="text-lg">Selecciona una carrera del dropdown o crea una nueva para comenzar</p>
                    </div>

                    <!-- Mensaje cuando está en modo crear pero no hay nombre -->
                    <div x-show="modoCrear && !formData.nombre" class="text-center py-12 text-gray-400">
                        <p class="text-lg">Completa el formulario de Información Básica para comenzar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <h3 id="confirmTitle" class="text-sm sm:text-lg font-semibold text-white mb-2"></h3>
                <p id="confirmMessage" class="text-gray-300 mb-6"></p>
                <div class="flex justify-end space-x-1 sm:space-x-3">
                    <button onclick="closeConfirmModal()" class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                        Cancelar
                    </button>
                    <button id="confirmButton" onclick="executeConfirm()" class="px-2 py-1 sm:px-4 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Año -->
    <div id="anioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="anioModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Año</h3>
                    <button onclick="closeAnioModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="anioForm" action="{{ route('admin.programas.anios.store') }}" method="POST" onsubmit="return handleAnioFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="anioId" name="anio_id" value="">
                    <input type="hidden" id="anioMethodField" name="_method" value="">
                    <input type="hidden" id="anioCursoId" name="curso_id" value="">
            
                    <div id="anioErrors" class="bg-red-600 text-white px-4 py-3 rounded mb-4 hidden">
                        <ul class="list-disc list-inside" id="anioErrorsList"></ul>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Año <span class="text-red-400">*</span></label>
                            <input type="number" id="anioAnio" name="año" min="1" max="10" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: 1, 2, 3" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Título <span class="text-red-400">*</span></label>
                            <input type="text" id="anioTitulo" name="titulo" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Analista técnico de motores" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nivel <span class="text-red-400">*</span></label>
                            <select id="anioNivel" name="nivel" 
                                    class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm" required>
                                <option value="">Selecciona un nivel</option>
                                <option value="Inicial">Inicial</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Avanzado">Avanzado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeAnioModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="anioSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Año
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Modalidad -->
    <div id="modalidadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="modalidadModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Modalidad</h3>
                    <button onclick="closeModalidadModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="modalidadForm" action="{{ route('admin.modalidades.store') }}" method="POST" onsubmit="return handleModalidadFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="modalidadId" name="modalidad_id" value="">
                    <input type="hidden" id="modalidadMethodField" name="_method" value="">
                    <input type="hidden" id="modalidadCursoId" name="curso_id" value="">
            
                    <div id="modalidadErrors" class="bg-red-600 text-white px-4 py-3 rounded mb-4 hidden">
                        <ul class="list-disc list-inside" id="modalidadErrorsList"></ul>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre Línea 1 <span class="text-red-400">*</span></label>
                            <input type="text" id="modalidadNombreLinea1" name="nombre_linea1" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: PRESENCIAL" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Nombre Línea 2</label>
                            <input type="text" id="modalidadNombreLinea2" name="nombre_linea2" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: SEMI-PRESENCIAL">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Texto Informativo</label>
                            <textarea id="modalidadTextoInfo" name="texto_info" rows="3" 
                                      class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                                      placeholder="Ej: Podes elegir un día entre martes y sábados..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeModalidadModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="modalidadSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Modalidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Unidad -->
    <div id="unidadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="unidadModalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Unidad</h3>
                    <button onclick="closeUnidadModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="unidadForm" action="{{ route('admin.programas.unidades.store') }}" method="POST" onsubmit="return handleUnidadFormSubmit(event)">
                    @csrf
                    <input type="hidden" id="unidadId" name="unidad_id" value="">
                    <input type="hidden" id="unidadMethodField" name="_method" value="">
                    <input type="hidden" id="unidadCursoAnioId" name="curso_anio_id" value="">
            
                    <div id="unidadErrors" class="bg-red-600 text-white px-4 py-3 rounded mb-4 hidden">
                        <ul class="list-disc list-inside" id="unidadErrorsList"></ul>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Número <span class="text-red-400">*</span></label>
                            <input type="text" id="unidadNumero" name="numero" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Unidad 1, Unidad 2" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Título <span class="text-red-400">*</span></label>
                            <input type="text" id="unidadTitulo" name="titulo" 
                                   class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                                   placeholder="Ej: Introducción al Taller y sus elementos" required>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Subtítulo <span class="text-red-400">*</span></label>
                            <textarea id="unidadSubtitulo" name="subtitulo" rows="3" 
                                      class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                                      placeholder="Ej: Normas de seguridad e higiene. Herramientas e instrumental." required></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                        <button type="button" onclick="closeUnidadModal()" 
                                class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Cancelar
                        </button>
                        <button type="submit" id="unidadSubmitBtn"
                                class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                            Agregar Unidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('head')
        @vite(['resources/js/carreras-test.js'])
    @endpush
</x-app-layout>
