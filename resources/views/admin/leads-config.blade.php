<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Configuración de Leads</h1>
                    
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6 transition-opacity duration-500" x-transition:leave.duration.500ms>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Panel Izquierdo: Notificaciones Email -->
                        <div class="bg-gray-700/50 p-6 rounded-lg border border-gray-600 h-full">
                            <h2 class="text-lg font-semibold mb-4 text-blue-200">Notificaciones por Email</h2>
                            
                            <form action="{{ route('admin.leads.config.update') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="notification_email" class="block text-sm font-medium text-gray-300 mb-2">
                                        Email para recibir notificaciones
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               name="notification_email" 
                                               id="notification_email" 
                                               value="{{ old('notification_email', $currentEmail) }}"
                                               class="bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                               placeholder="ejemplo@itca.com.ar" 
                                               required>
                                        
                                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-800 font-medium rounded-lg text-sm w-auto sm:w-auto px-5 py-2.5 text-center">
                                            Guardar
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-400">
                                        Cada vez que alguien complete el formulario de contacto/inscripción, se enviará un correo a esta dirección. Mail a tecnom: wc+itca_web@tecnom.cloud
                                    </p>
                                    @error('notification_email')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <!-- Panel Derecho: Configuración de Delay -->
                        <div class="bg-gray-700/50 p-6 rounded-lg border border-gray-600 h-full">
                            <h2 class="text-lg font-semibold mb-4 text-blue-200">Recuperación de Carrito</h2>
                            
                            <form action="{{ route('admin.leads.config.update') }}" method="POST">
                                @csrf
                                <!-- Necesitamos enviar el email también o hacerlo nullable en validate -->
                                <input type="hidden" name="notification_email" value="{{ $currentEmail }}">
                                
                                <div class="mb-4">
                                    <label for="abandoned_cart_delay_seconds" class="block text-sm font-medium text-gray-300 mb-2">
                                        Delay (segundos)
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="number" 
                                               name="abandoned_cart_delay_seconds" 
                                               id="abandoned_cart_delay_seconds" 
                                               value="{{ old('abandoned_cart_delay_seconds', $abandonedCartDelay) }}"
                                               class="bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                               placeholder="600" 
                                               min="0">
                                        
                                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-800 font-medium rounded-lg text-sm w-auto sm:w-auto px-5 py-2.5 text-center">
                                            Guardar
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-400">
                                        Tiempo de espera antes de enviar el email (600s = 10min).
                                    </p>
                                    @error('abandoned_cart_delay_seconds')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>