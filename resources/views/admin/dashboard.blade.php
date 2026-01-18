<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">Panel de administración</h1>
                    <p class="mb-6">Versión subida el: 18/01/2026 13:05</p>
                    
                    <div class="bg-blue-900/30 dark:bg-blue-900/30 p-4 rounded-lg mb-6 border border-blue-700">
                        <h2 class="text-lg font-semibold mb-2 text-blue-200">Panel de administración</h2>
                        <p class="text-blue-100">Panel de administración - En construcción</p>
                    </div>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Panel
                        </a>
                        <a href="{{ url('/') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Ver sitio público
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
