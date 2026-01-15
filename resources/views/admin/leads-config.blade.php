<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Configuración de Leads</h1>
                    
                    @if(session('success'))
                        <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="bg-gray-700/50 p-6 rounded-lg border border-gray-600">
                        <h2 class="text-lg font-semibold mb-4 text-blue-200">Notificaciones por Email</h2>
                        
                        <form action="{{ route('admin.leads.config.update') }}" method="POST" class="max-w-xl">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="notification_email" class="block text-sm font-medium text-gray-300 mb-2">
                                    Email para recibir notificaciones
                                </label>
                                <div class="flex gap-2">
                                    <input type="email" 
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
                                    Cada vez que alguien complete el formulario de contacto/inscripción, se enviará un correo a esta dirección.
                                </p>
                                @error('notification_email')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>