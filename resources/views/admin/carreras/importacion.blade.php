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
                        <div class="flex items-center gap-3">
                            <div class="inline-flex items-center gap-2 text-gray-200 text-sm font-medium">
                                <span>Promo Mat Logo:</span>
                            </div>
                            <!-- Botón Badge Promo -->
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="promoBadgeInput" 
                                    accept="image/jpeg,image/jpg,image/png" 
                                    class="hidden"
                                >
                                <button 
                                    type="button"
                                    onclick="document.getElementById('promoBadgeInput').click()"
                                    class="p-0 border-0 bg-transparent hover:opacity-80 transition-opacity cursor-pointer"
                                    style="width: 75px; height: 60px;"
                                    title="Click para cargar badge promocional"
                                >
                                    @if($promoBadge && $promoBadge->archivo)
                                        <img 
                                            src="{{ asset('images/badges-promo/' . $promoBadge->archivo) }}" 
                                            alt="Badge Promoción" 
                                            class="w-full h-full object-contain"
                                            style="width: 75px; height: 60px;"
                                            id="promoBadgeImage"
                                        >
                                    @else
                                        <div class="w-full h-full bg-gray-600 border border-gray-500 rounded flex items-center justify-center" style="width: 75px; height: 60px;" id="promoBadgePlaceholder">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </button>
                                @if($promoBadge && $promoBadge->archivo)
                                    <button 
                                        type="button"
                                        onclick="eliminarPromoBadge()"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs"
                                        title="Eliminar badge"
                                    >
                                        ×
                                    </button>
                                @endif
                            </div>
                            <div class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Mostrando en la web:</span>
                                <span class="font-bold">{{ $registrosVisibles }} de {{ $totalRegistros }}</span>
                            </div>
                            <button 
                                type="button"
                                @click="modalImportarOpen = true"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors text-sm font-medium"
                            >
                                Importar
                            </button>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="mb-4 p-4 bg-gray-700 rounded-lg border border-gray-600">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Carrera:</label>
                                <select id="filtroCarrera" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todas</option>
                                    @foreach($cursadas->pluck('carrera')->unique()->filter()->sort() as $carrera)
                                        <option value="{{ $carrera }}">{{ $carrera }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Sede:</label>
                                <select id="filtroSede" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todas</option>
                                    @foreach($cursadas->pluck('sede')->unique()->filter()->sort() as $sede)
                                        <option value="{{ $sede }}">{{ $sede }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Modalidad:</label>
                                <select id="filtroModalidad" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todas</option>
                                    @foreach($cursadas->pluck('xModalidad')->unique()->filter()->sort() as $modalidad)
                                        @php
                                            $modalidadDisplay = $modalidad;
                                            if (stripos($modalidad, 'Sempresencial') !== false) {
                                                $modalidadDisplay = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                                            }
                                        @endphp
                                        <option value="{{ $modalidad }}">{{ $modalidadDisplay }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Turno:</label>
                                <select id="filtroTurno" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todos</option>
                                    @foreach($cursadas->pluck('xTurno')->unique()->filter()->sort() as $turno)
                                        <option value="{{ $turno }}">{{ $turno }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Ver curso:</label>
                                <select id="filtroVerCurso" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todos</option>
                                    <option value="ver">Ver</option>
                                    <option value="no ver">No ver</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Días:</label>
                                <select id="filtroDias" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todos</option>
                                    @foreach($cursadas->pluck('xDias')->unique()->filter()->sort() as $dia)
                                        <option value="{{ $dia }}">{{ convertirDiasCompletos($dia) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Régimen:</label>
                                <select id="filtroRegimen" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todos</option>
                                    @foreach($cursadas->pluck('Régimen')->unique()->filter()->sort() as $regimen)
                                        <option value="{{ $regimen }}">{{ $regimen }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-300 whitespace-nowrap">Vacantes:</label>
                                <select id="filtroVacantes" class="px-3 py-1.5 bg-gray-600 border border-gray-500 rounded-md text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Todas</option>
                                    <option value="con">Con vacantes</option>
                                    <option value="sin">Sin vacantes</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-4 ml-auto">
                                <div class="text-sm text-gray-300">
                                    <span class="font-semibold">Total:</span> <span id="contadorTotal">{{ $cursadas->count() }}</span>
                                </div>
                                <div class="text-sm text-gray-300">
                                    <span class="font-semibold">Mostrados en web:</span> <span id="contadorWeb">{{ $cursadas->filter(function($c) { return strtolower(trim($c->ver_curso ?? '')) === 'ver'; })->count() }}</span>
                                </div>
                                <button 
                                    type="button"
                                    id="btnLimpiarFiltros"
                                    class="px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-sm font-medium"
                                >
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Grilla de cursadas -->
                    <div class="overflow-x-auto" style="max-height: calc(100vh - 350px);">
                        <table id="tablaCursadas" class="min-w-full border-collapse bg-gray-700">
                            <thead class="bg-gray-600 sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="ID_Curso">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                ID_Curso
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 150px; width: 200px;" data-sort="carrera">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                carrera
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 80px; width: 100px;" data-sort="Cod1">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Cod1
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Fecha_Inicio">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Fecha Inicio
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="xDias">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                xDias
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="xModalidad">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                xModalidad
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Régimen">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Régimen
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="xTurno">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                xTurno
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 120px; width: 150px;" data-sort="Horario">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Horario
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 80px; width: 100px;" data-sort="Vacantes">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Vacantes
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 120px; width: 140px;" data-sort="Matric_Base">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Matric Base
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Sin_iva_Mat">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Sin iva Mat
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Cta_Web">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Cta.Web
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Sin_IVA_cta">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Sin IVA cta
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Dto_Cuota">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Dto.Cuota
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="cuotas">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                cuotas
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="sede">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                sede
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="Promo_Mat_logo">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                Promo Mat-logo-
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                    <th class="border border-gray-500 px-3 py-2 text-left text-xs font-semibold text-gray-200 whitespace-nowrap resizable-header sortable-header" style="min-width: 100px; width: 120px;" data-sort="ver_curso">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-1">
                                                ver curso
                                                <span class="sort-indicator"></span>
                                            </span>
                                            <div class="resizer"></div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tablaCursadasBody" class="bg-gray-700 text-gray-200">
                                @forelse($cursadas as $cursada)
                                    <tr class="hover:bg-gray-600">
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->ID_Curso }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->carrera }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->Cod1 }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->Fecha_Inicio ? \Carbon\Carbon::parse($cursada->Fecha_Inicio)->format('d/m/Y') : '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm" data-dia-original="{{ $cursada->xDias ?? '' }}">{{ convertirDiasCompletos($cursada->xDias) }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">
                                            @php
                                                $modalidad = $cursada->xModalidad ?? 'N/A';
                                                // Corregir "Sempresencial" a "Semipresencial"
                                                if (stripos($modalidad, 'Sempresencial') !== false) {
                                                    $modalidad = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                                                }
                                            @endphp
                                            {{ $modalidad }}
                                        </td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->Régimen }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->xTurno }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->Horario }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->Vacantes }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->Matric_Base ?? 0, 2, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->Sin_iva_Mat ?? 0, 2, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->Cta_Web ?? 0, 2, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">${{ number_format($cursada->Sin_IVA_cta ?? 0, 2, ',', '.') }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ number_format($cursada->Dto_Cuota ?? 0, 2, ',', '.') }}%</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->cuotas ?? '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->sede }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->Promo_Mat_logo ?? '' }}</td>
                                        <td class="border border-gray-500 px-3 py-2 text-sm">{{ $cursada->ver_curso ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="22" class="border border-gray-500 px-3 py-8 text-center text-gray-400">
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
                                    <p class="text-gray-300" x-show="resultadoImportacion && resultadoImportacion.imported !== undefined && resultadoImportacion.imported !== null">
                                        <span class="font-semibold">Cursadas importadas:</span> 
                                        <span class="text-green-400" x-text="resultadoImportacion?.imported || 0"></span>
                                    </p>
                                    <p class="text-gray-300" x-show="resultadoImportacion && resultadoImportacion.errors && Array.isArray(resultadoImportacion.errors) && resultadoImportacion.errors.length > 0">
                                        <span class="font-semibold">Errores encontrados:</span> 
                                        <span class="text-red-400" x-text="resultadoImportacion?.errors?.length || 0"></span>
                                    </p>
                                </div>
                                
                                <!-- Lista de errores si hay -->
                                <div x-show="resultadoImportacion && resultadoImportacion.errors && Array.isArray(resultadoImportacion.errors) && resultadoImportacion.errors.length > 0" class="mt-3">
                                    <p class="text-xs font-semibold text-gray-400 mb-2">Detalles de errores:</p>
                                    <div class="bg-gray-700 rounded p-3 max-h-32 overflow-y-auto">
                                        <ul class="space-y-1 text-xs text-gray-300">
                                            <template x-for="(error, index) in (resultadoImportacion?.errors || [])" :key="index">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputFile = document.getElementById('promoBadgeInput');
            
            if (inputFile) {
                inputFile.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    // Validar tipo de archivo
                    if (!file.type.match('image/jpeg') && !file.type.match('image/jpg') && !file.type.match('image/png')) {
                        if (typeof mostrarToast !== 'undefined') {
                            mostrarToast('Por favor, selecciona una imagen JPG o PNG', 'error');
                        } else {
                            alert('Por favor, selecciona una imagen JPG o PNG');
                        }
                        return;
                    }
                    
                    // Validar tamaño (2MB)
                    if (file.size > 2048 * 1024) {
                        if (typeof mostrarToast !== 'undefined') {
                            mostrarToast('La imagen no debe superar los 2MB', 'error');
                        } else {
                            alert('La imagen no debe superar los 2MB');
                        }
                        return;
                    }
                    
                    // Crear FormData y enviar
                    const formData = new FormData();
                    formData.append('archivo', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    
                    // Mostrar loading
                    const button = inputFile.closest('div').querySelector('button');
                    const originalContent = button.innerHTML;
                    button.innerHTML = '<div class="w-full h-full bg-gray-700 border border-gray-500 rounded flex items-center justify-center" style="width: 75px; height: 60px;"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div></div>';
                    button.disabled = true;
                    
                    fetch('{{ route("admin.promo-badge.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Error en la respuesta del servidor');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (typeof mostrarToast !== 'undefined') {
                                mostrarToast('Badge cargado correctamente', 'success');
                            }
                            // Recargar la página para mostrar el nuevo badge
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        } else {
                            const errorMsg = data.message || 'No se pudo cargar el badge';
                            if (typeof mostrarToast !== 'undefined') {
                                mostrarToast('Error: ' + errorMsg, 'error');
                            } else {
                                alert('Error: ' + errorMsg);
                            }
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        const errorMsg = error.message || 'Error al cargar el badge. Verifica los permisos del directorio.';
                        if (typeof mostrarToast !== 'undefined') {
                            mostrarToast(errorMsg, 'error');
                        } else {
                            alert(errorMsg);
                        }
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    });
                });
            }
        });
        
        function eliminarPromoBadge() {
            // Usar el modal de confirmación del proyecto
            if (typeof showConfirmModal !== 'undefined') {
                showConfirmModal(
                    'Eliminar Badge Promocional',
                    '¿Estás seguro de que deseas eliminar el badge promocional? Esta acción no se puede deshacer.',
                    function() {
                        ejecutarEliminacionBadge();
                    }
                );
            } else {
                // Fallback a confirm nativo si no está disponible
                if (confirm('¿Estás seguro de que deseas eliminar el badge promocional?')) {
                    ejecutarEliminacionBadge();
                }
            }
        }
        
        function ejecutarEliminacionBadge() {
            const button = document.querySelector('[onclick="eliminarPromoBadge()"]');
            if (!button) return;
            
            const originalContent = button.innerHTML;
            button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>';
            button.disabled = true;
            
            fetch('{{ route("admin.promo-badge.destroy") }}', {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof mostrarToast !== 'undefined') {
                        mostrarToast('Badge eliminado correctamente', 'success');
                    }
                    // Recargar la página
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    const errorMsg = data.message || 'No se pudo eliminar el badge';
                    if (typeof mostrarToast !== 'undefined') {
                        mostrarToast('Error: ' + errorMsg, 'error');
                    } else {
                        alert('Error: ' + errorMsg);
                    }
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMsg = 'Error al eliminar el badge';
                if (typeof mostrarToast !== 'undefined') {
                    mostrarToast(errorMsg, 'error');
                } else {
                    alert(errorMsg);
                }
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        }
    </script>
</x-app-layout>

