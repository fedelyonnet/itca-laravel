<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Gestión de Noticias</h1>
                    <p class="text-gray-400 mt-1">Administrá el contenido, autores y categorías de tu blog.</p>
                </div>
                <a href="{{ route('admin.noticias.create') }}" 
                   class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center gap-2 transition-all transform hover:-translate-y-1 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    NUEVA NOTICIA
                </a>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-700">
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/50 border-b border-gray-700">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-20">Imagen</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Noticia</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Categorías</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Estado</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($noticias as $noticia)
                                <tr class="hover:bg-gray-700/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="w-12 h-12 rounded bg-gray-900 border border-gray-600 overflow-hidden">
                                            @if($noticia->imagen_thumb)
                                                <img src="{{ asset('storage/' . $noticia->imagen_thumb) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-700">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-white">{{ $noticia->titulo }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono uppercase mt-1">
                                            {{ $noticia->autor_nombre ?? 'Sin Autor' }} • {{ $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('d/m/Y') : 'Borrador' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($noticia->categorias as $cat)
                                                <span class="px-2 py-0.5 bg-blue-900/30 text-blue-400 text-[10px] font-bold rounded border border-blue-800/50 uppercase">
                                                    {{ $cat->nombre }}
                                                </span>
                                            @empty
                                                <span class="text-gray-600 text-[10px] italic">Sin etiquetas</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="flex h-2 w-2 rounded-full {{ $noticia->visible ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-gray-600' }}"></span>
                                            <span class="text-[10px] font-bold {{ $noticia->visible ? 'text-green-500' : 'text-gray-500' }} uppercase tracking-tighter">
                                                {{ $noticia->visible ? 'Visible' : 'Oculta' }}
                                            </span>
                                            @if($noticia->destacada)
                                                <span class="text-[10px] text-yellow-500 font-black uppercase">★ Destacada</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="#" class="p-2 text-gray-400 hover:text-white hover:bg-gray-600 rounded-lg transition-all" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <button class="p-2 text-gray-400 hover:text-red-500 hover:bg-gray-600 rounded-lg transition-all" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM12 11l2 2m-2 0l-2-2m2 2v4m0-10V7"></path></svg>
                                            <p class="text-gray-500 text-lg">Todavía no hay noticias publicadas.</p>
                                            <a href="{{ route('admin.noticias.create') }}" class="text-blue-500 font-bold hover:underline mt-2">¡Empezá haciendo clic acá para crear la primera!</a>
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
</x-app-layout>
