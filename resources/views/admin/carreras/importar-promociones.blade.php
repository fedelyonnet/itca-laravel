<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js', 'resources/js/importacion-promociones.js'])

    <!-- Toast Messages Container - Completely outside layout -->
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

    <div class="py-12" x-data="{ modalImportarOpen: false, modalResultadoOpen: false, resultadoImportacion: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold">Importación promociones</h1>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button"
                                @click="modalImportarOpen = true"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors text-sm font-medium"
                            >
                                Importar
                            </button>
                        </div>
                    </div>

                    <!-- Grilla de promociones -->
                    <div class="overflow-x-auto" style="max-height: calc(100vh - 250px);">
                        <table id="tablaPromociones" class="min-w-full border-collapse bg-gray-700">
                            <thead class="bg-gray-600 sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 200px;" data-sort="Codigo_Promocion">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Codigo_Promocion
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 200px; width: 300px;" data-sort="Promocion_Descripcion">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Promocion_Descripcion
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Porcentaje">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Porcentaje
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 200px;" data-sort="codigo_web">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                codigo_web
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaPromocionesBody" class="bg-gray-700 text-gray-200">
                                @forelse($descuentos as $descuento)
                                    <tr class="hover:bg-gray-600">
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $descuento->Codigo_Promocion ?? '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $descuento->Promocion_Descripcion ?? '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $descuento->Porcentaje ? number_format($descuento->Porcentaje, 2, ',', '.') . '%' : '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $descuento->codigo_web ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border border-gray-500 px-3 py-8 text-center text-gray-400">
                                            No hay promociones importadas. Haz clic en "Importar" para cargar datos desde un archivo Excel.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para importar Excel -->
        <div 
            x-show="modalImportarOpen" 
            x-cloak
            @click.away="modalImportarOpen = false"
            @keydown.escape.window="modalImportarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center"
            style="display: none;"
        >
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-xl font-bold text-white">Importar Planilla Excel</h3>
                    <p class="text-sm text-gray-400 mt-1">Selecciona el archivo Excel (.xlsx, .xls) con los datos de las promociones</p>
                    <div class="mt-3 p-3 bg-yellow-900 bg-opacity-50 border border-yellow-700 rounded-md">
                        <p class="text-sm text-yellow-300 flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span><strong>Importante:</strong> Al importar, se eliminarán todos los registros actuales y se reemplazarán con los datos del archivo Excel.</span>
                        </p>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="formImportarExcel">
                        <input 
                            type="file" 
                            id="archivoExcel" 
                            accept=".xlsx,.xls" 
                            class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 file:cursor-pointer"
                        >
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-700 flex justify-end gap-3">
                    <button 
                        type="button"
                        @click="modalImportarOpen = false"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-sm font-medium"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="button"
                        id="btnImportarExcel"
                        data-route="{{ route('admin.carreras.importar-promociones.store') }}"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-sm font-medium"
                    >
                        Importar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de resultado -->
        <div 
            x-show="modalResultadoOpen" 
            x-cloak
            @click.away="modalResultadoOpen = false"
            @keydown.escape.window="modalResultadoOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center"
            style="display: none;"
        >
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-xl font-bold text-white" x-text="resultadoImportacion && resultadoImportacion.success ? 'Importación Exitosa' : 'Error en la Importación'"></h3>
                </div>
                
                <div class="p-6">
                    <div x-show="resultadoImportacion && resultadoImportacion.success" class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-gray-200 mb-3" x-text="resultadoImportacion && resultadoImportacion.message"></p>
                                <div class="space-y-1">
                                    <p class="text-gray-300" x-show="resultadoImportacion && resultadoImportacion.imported !== undefined && resultadoImportacion.imported !== null">
                                        <span class="text-green-400" x-text="resultadoImportacion?.imported || 0"></span>
                                        <span> registros importados correctamente</span>
                                    </p>
                                    <p class="text-gray-300" x-show="resultadoImportacion && resultadoImportacion.errors && Array.isArray(resultadoImportacion.errors) && resultadoImportacion.errors.length > 0">
                                        <span class="text-red-400" x-text="resultadoImportacion?.errors?.length || 0"></span>
                                        <span> errores encontrados</span>
                                    </p>
                                </div>
                                <div x-show="resultadoImportacion && resultadoImportacion.errors && Array.isArray(resultadoImportacion.errors) && resultadoImportacion.errors.length > 0" class="mt-3">
                                    <div class="bg-red-900 bg-opacity-50 border border-red-700 rounded-md p-3 max-h-40 overflow-y-auto">
                                        <template x-for="(error, index) in (resultadoImportacion?.errors || [])" :key="index">
                                            <p class="text-sm text-red-300 mb-1" x-text="`Fila ${error.fila}: ${error.mensaje}`"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="resultadoImportacion && !resultadoImportacion.success" class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-gray-200" x-text="resultadoImportacion && resultadoImportacion.message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-700 flex justify-end">
                    <button 
                        type="button"
                        @click="modalResultadoOpen = false; if(resultadoImportacion && resultadoImportacion.success) { window.location.reload(); }"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors text-sm font-medium"
                    >
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toast messages animation
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            
            if (successMsg) {
                setTimeout(() => {
                    successMsg.classList.remove('translate-x-full');
                }, 100);
                setTimeout(() => {
                    successMsg.classList.add('translate-x-full');
                }, 3000);
            }
            
            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.classList.remove('translate-x-full');
                }, 100);
                setTimeout(() => {
                    errorMsg.classList.add('translate-x-full');
                }, 5000);
            }
        });
    </script>
</x-app-layout>
