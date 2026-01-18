<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js'])

    <div class="py-12">
        <div class="w-full px-6">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold">Listado de Inscripciones</h1>
                        <!-- <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar Grilla
                        </a> -->
                    </div>

                    <!-- Grilla de inscriptos -->
                    <div class="overflow-hidden rounded-xl border border-gray-700 shadow-lg">
                        <div class="overflow-auto" style="max-height: calc(100vh - 250px);">
                            <table id="tablaInscriptos" class="min-w-full border-collapse bg-gray-900 text-left text-sm text-gray-300">
                                <thead class="bg-gray-800 text-xs font-medium uppercase tracking-wider text-gray-400 sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 w-40 text-center">
                                            Estado Pago
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hover:text-white transition-colors group" data-sort="cursada">
                                            <div class="flex items-center gap-1">
                                                Cursada
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hover:text-white transition-colors group" data-sort="usuario">
                                            <div class="flex items-center gap-1">
                                                Alumno
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hover:text-white transition-colors group" data-sort="monto">
                                            <div class="flex items-center gap-1">
                                                Monto
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-right cursor-pointer hover:text-white transition-colors group" data-sort="created_at">
                                            <div class="flex items-center justify-end gap-1">
                                                Fecha
                                                <svg class="w-3 h-3 text-gray-600 group-hover:text-blue-400 transition-colors opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 24 24"><path d="M8 16H4l6 6V2H8zm6-11v17h2V8h4l-6-6z"/></svg>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaInscriptosBody" class="divide-y divide-gray-800 border-t border-gray-800">
                                    @forelse($inscripciones as $inscripcion)
                                        <tr class="hover:bg-gray-800 transition-colors duration-150 cursor-pointer group" onclick="toggleAccordion('{{ $inscripcion->id }}')">
                                            <td class="px-4 py-3 text-center align-middle">
                                                <div class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold border 
                                                    {{ ($inscripcion->estado === 'pagado' || $inscripcion->estado === 'approved') ? 'bg-green-900/30 text-green-400 border-green-800' : 
                                                       ($inscripcion->estado === 'pendiente' || $inscripcion->estado === 'pending' ? 'bg-yellow-900/30 text-yellow-400 border-yellow-800' : 
                                                       'bg-red-900/30 text-red-400 border-red-800') }}">
                                                    {{ ucfirst($inscripcion->estado) }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-blue-300 font-medium">{{ $inscripcion->cursada_id }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="text-white font-medium text-sm">
                                                        {{ $inscripcion->lead ? $inscripcion->lead->nombre . ' ' . $inscripcion->lead->apellido : 'Sin Lead' }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $inscripcion->lead ? $inscripcion->lead->dni : '' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 font-mono text-gray-300">
                                                ${{ number_format($inscripcion->monto_matricula, 2, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex flex-col items-end">
                                                    <span class="text-sm text-gray-300 font-medium">
                                                        {{ $inscripcion->created_at ? $inscripcion->created_at->format('d/m/Y H:i') : 'N/A' }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Fila Secundaria (Detalle Técnico - Oculta) -->
                                        <tr id="accordion-{{ $inscripcion->id }}" class="hidden bg-gray-900 shadow-inner">
                                            <td colspan="5" class="px-0 py-0 border-t border-gray-800">
                                                <div class="px-6 py-4 bg-gray-900/50 shadow-inner border-y border-gray-800">
                                                    <div class="grid grid-cols-2 gap-4 text-xs text-gray-400">
                                                        <div>
                                                            <strong class="text-gray-500 block mb-1">Mercado Pago Collection ID:</strong>
                                                            <span class="font-mono text-gray-300">{{ $inscripcion->collection_id ?? 'N/A' }}</span>
                                                        </div>
                                                        <div>
                                                            <strong class="text-gray-500 block mb-1">Preference ID:</strong>
                                                            <span class="font-mono text-gray-300">{{ $inscripcion->preference_id ?? 'N/A' }}</span>
                                                        </div>
                                                        <div>
                                                            <strong class="text-gray-500 block mb-1">Tipo de Pago:</strong>
                                                            <span class="font-mono text-gray-300">{{ $inscripcion->payment_type ?? 'N/A' }}</span>
                                                        </div>
                                                        <div>
                                                            <strong class="text-gray-500 block mb-1">Email del Lead:</strong>
                                                            <span class="text-gray-300">{{ $inscripcion->lead ? $inscripcion->lead->correo : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center text-gray-500">
                                                    <span class="text-lg font-medium">No hay inscripciones registradas aún</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAccordion(id) {
            const row = document.getElementById('accordion-' + id);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                row.previousElementSibling.classList.add('bg-gray-800');
            } else {
                row.classList.add('hidden');
                row.previousElementSibling.classList.remove('bg-gray-800');
            }
        }
    </script>
</x-app-layout>
