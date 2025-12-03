<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js', 'resources/js/importacion-cursos.js'])

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
        <div class="w-full px-6">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold">Importación cursos</h1>
                        <button 
                            type="button"
                            @click="modalImportarOpen = true"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors text-sm font-medium"
                        >
                            Importar
                        </button>
                    </div>

                    <!-- Grilla de cursadas -->
                    <div class="overflow-x-auto" style="max-height: calc(100vh - 250px);">
                        <table id="tablaCursadas" class="min-w-full border-collapse bg-gray-700">
                            <thead class="bg-gray-600 sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="id_curso">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                ID Curso
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 200px;" data-sort="nombre_curso">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Nombre Curso
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 80px; width: 100px;" data-sort="vacantes">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Vacantes
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="sede">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Sede
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="x_modalidad">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Modalidad
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Días</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Turno</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 120px; width: 140px;">
                                        <div class="flex items-center justify-between">
                                            <span>Matrícula Base</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 120px; width: 160px;">
                                        <div class="flex items-center justify-between">
                                            <span>Matrícula 50% Dcto</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Cant. Cuotas</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Valor Cuota</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 150px; width: 200px;">
                                        <div class="flex items-center justify-between">
                                            <span>Descripción</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 80px; width: 100px;">
                                        <div class="flex items-center justify-between">
                                            <span>Cod1</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 80px; width: 100px;">
                                        <div class="flex items-center justify-between">
                                            <span>Cod2</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Duración</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Fecha Inicio</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Fecha Fin</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Mes Inicio</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Mes Fin</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 120px; width: 150px;">
                                        <div class="flex items-center justify-between">
                                            <span>Horario</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Hora Inicio</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Hora Fin</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 80px; width: 100px;">
                                        <div class="flex items-center justify-between">
                                            <span>ID Aula</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Tipo</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 100px; width: 120px;">
                                        <div class="flex items-center justify-between">
                                            <span>Nivel</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header" style="min-width: 80px; width: 100px;">
                                        <div class="flex items-center justify-between">
                                            <span>xCod1</span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaCursadasBody" class="bg-gray-700 text-gray-200">
                                @forelse($cursadas as $cursada)
                                    <tr class="hover:bg-gray-600">
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->id_curso }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ corregirNombreCarrera($cursada->nombre_curso) }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->vacantes }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->sede }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">
                                            @php
                                                $modalidad = $cursada->x_modalidad ?? 'N/A';
                                                // Corregir "Sempresencial" a "Semipresencial"
                                                if (stripos($modalidad, 'Sempresencial') !== false) {
                                                    $modalidad = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                                                }
                                            @endphp
                                            {{ $modalidad }}
                                        </td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->dias }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->x_turno }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->matricula_base, 0, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->matricula_con_50_dcto, 0, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->cantidad_cuotas }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->valor_cuota, 0, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ Str::limit($cursada->descr, 50) }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->cod1 }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->cod2 }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->duracion }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->fecha_inicio ? \Carbon\Carbon::parse($cursada->fecha_inicio)->format('d/m/Y') : '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->fecha_fin ? \Carbon\Carbon::parse($cursada->fecha_fin)->format('d/m/Y') : '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">
                                            @php
                                                $meses = [
                                                    '1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril',
                                                    '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto',
                                                    '9' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
                                                ];
                                                $mesTexto = $meses[$cursada->mes_inicio] ?? $cursada->mes_inicio;
                                            @endphp
                                            {{ ucfirst($mesTexto) }}
                                        </td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">
                                            @php
                                                $mesTextoFin = $meses[$cursada->mes_fin] ?? $cursada->mes_fin;
                                            @endphp
                                            {{ ucfirst($mesTextoFin) }}
                                        </td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->horario }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">
                                            @if($cursada->hora_inicio)
                                                {{ \Carbon\Carbon::parse($cursada->hora_inicio)->format('H:i') }}
                                            @endif
                                        </td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">
                                            @if($cursada->hora_fin)
                                                {{ \Carbon\Carbon::parse($cursada->hora_fin)->format('H:i') }}
                                            @endif
                                        </td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->id_aula }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->x_tipo }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->x_nivel }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->x_cod1 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="26" class="border border-gray-500 px-3 py-8 text-center text-gray-400">
                                            No hay cursadas importadas. Haz clic en "Importar" para cargar datos desde un archivo Excel.
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
                    <p class="text-sm text-gray-400 mt-1">Selecciona el archivo Excel (.xlsx, .xls) con los datos de las cursadas</p>
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
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Archivo Excel
                            </label>
                            <input 
                                type="file" 
                                name="archivo_excel"
                                id="archivo_excel"
                                accept=".xlsx,.xls"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-500 file:text-white hover:file:bg-green-600"
                            >
                            <p class="text-xs text-gray-400 mt-1">Formatos soportados: .xlsx, .xls</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-700 flex justify-end gap-3">
                    <button 
                        type="button"
                        @click="modalImportarOpen = false"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="button"
                        id="btnImportarExcel"
                        data-route="{{ route('admin.carreras.importacion.store') }}"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors"
                    >
                        Importar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de resultado de importación -->
        <div 
            x-show="modalResultadoOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="modalResultadoOpen = false"
            @click.away="modalResultadoOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            style="display: none;"
        >
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4" @click.stop>
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-xl font-bold text-white" x-text="resultadoImportacion && resultadoImportacion.success ? 'Importación Exitosa' : 'Error en la Importación'"></h3>
                </div>
                
                <div class="p-6">
                    <div x-show="resultadoImportacion && resultadoImportacion.success" class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-gray-200 mb-3" x-text="resultadoImportacion && resultadoImportacion.message"></p>
                                <div class="space-y-1 text-sm">
                                    <p class="text-gray-300" x-show="resultadoImportacion && resultadoImportacion.imported !== undefined">
                                        <span class="font-semibold">Cursadas importadas:</span> 
                                        <span class="text-green-400" x-text="resultadoImportacion.imported"></span>
                                    </p>
                                    <p class="text-gray-300" x-show="resultadoImportacion && resultadoImportacion.errors && resultadoImportacion.errors.length > 0">
                                        <span class="font-semibold">Errores encontrados:</span> 
                                        <span class="text-red-400" x-text="resultadoImportacion.errors.length"></span>
                                    </p>
                                </div>
                                
                                <!-- Lista de errores si hay -->
                                <div x-show="resultadoImportacion && resultadoImportacion.errors && resultadoImportacion.errors.length > 0" class="mt-3">
                                    <p class="text-xs font-semibold text-gray-400 mb-2">Detalles de errores:</p>
                                    <div class="bg-gray-700 rounded p-3 max-h-32 overflow-y-auto">
                                        <ul class="space-y-1 text-xs text-gray-300">
                                            <template x-for="(error, index) in resultadoImportacion.errors" :key="index">
                                                <li x-text="error" class="text-red-300"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="resultadoImportacion && !resultadoImportacion.success" class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-gray-200" x-text="resultadoImportacion && resultadoImportacion.message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-700 flex justify-end gap-3">
                    <button 
                        type="button"
                        @click="modalResultadoOpen = false; if(resultadoImportacion && resultadoImportacion.success) { window.location.reload(); }"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors"
                    >
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para columnas redimensionables */
        .resizable-header {
            position: relative;
            border-right: 2px solid #4b5563 !important;
        }
        
        .resizable-header:hover {
            border-right-color: #6b7280 !important;
        }
        
        .resizer {
            position: absolute;
            top: 0;
            right: -1px;
            width: 6px;
            height: 100%;
            cursor: col-resize;
            user-select: none;
            background: transparent;
            z-index: 2;
            border-right: 2px solid transparent;
        }
        
        .resizer:hover {
            background: rgba(59, 130, 246, 0.3);
            border-right: 2px solid rgba(59, 130, 246, 0.8);
        }
        
        .resizer.resizing {
            background: rgba(59, 130, 246, 0.5);
            border-right: 2px solid rgba(59, 130, 246, 1);
        }
        
        #tablaCursadas {
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        #tablaCursadas th,
        #tablaCursadas td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            border-right: 2px solid #4b5563;
            box-sizing: border-box;
        }
        
        #tablaCursadas th.resizable-header {
            position: relative;
        }
        
        #tablaCursadas th:last-child,
        #tablaCursadas td:last-child {
            border-right: none;
        }
        
        #tablaCursadas tbody tr:hover {
            background-color: #4b5563;
        }
        
        #tablaCursadas tbody tr:hover td {
            border-right-color: #6b7280;
        }
        
        /* Estilos para headers ordenables */
        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
        }
        
        .sortable-header:hover {
            background-color: #4b5563;
        }
        
        .sort-indicator {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 4px;
            opacity: 0.3;
            font-size: 10px;
        }
        
        .sortable-header.sort-asc .sort-indicator::after {
            content: '▲';
            color: #60a5fa;
            opacity: 1;
            font-size: 10px;
        }
        
        .sortable-header.sort-desc .sort-indicator::after {
            content: '▼';
            color: #60a5fa;
            opacity: 1;
            font-size: 10px;
        }
    </style>
</x-app-layout>

