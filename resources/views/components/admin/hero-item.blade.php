@props(['item', 'version', 'type', 'title', 'size', 'isVideo' => false, 'containerClass' => '', 'placeholderText' => 'Imagen'])

<div class="bg-gray-600 rounded relative {{ $containerClass }}">
    <!-- Badge flotante -->
    <div class="absolute top-2 right-2 text-white px-2 py-1 rounded text-xs hero-badge" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="font-medium">{{ $title }}</div>
        <div class="text-gray-300">{{ $size }}</div>
    </div>
    
    @if($item && $item->url)
        @if($isVideo)
            <video class="w-full h-full object-cover rounded" muted loop controls>
                <source src="{{ asset('storage/' . $item->url) }}" type="video/mp4">
                Tu navegador no soporta videos.
            </video>
        @else
            <img src="{{ asset('storage/' . $item->url) }}" alt="{{ $type }}" class="w-full h-full object-cover rounded">
        @endif
    @else
        <div class="w-full h-full flex flex-col items-center justify-center hero-content">
            <svg class="{{ $containerClass == 'desktop-hero-img1' ? 'w-16 h-16' : 'w-12 h-12' }} text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 @if($isVideo)
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                @else
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                @endif
            </svg>
            <div class="text-gray-300 text-xs mt-2 text-center">
                <div class="font-semibold">{{ $size }}</div>
                <div class="text-gray-400">{{ $placeholderText }}</div>
            </div>
        </div>
    @endif
    
    <!-- Barra blanca en el bottom -->
    <div class="hero-bottom-bar">
        <div class="flex gap-2">
            <form action="{{ route('admin.hero.update', $item ? $item->id : 0) }}" method="POST" enctype="multipart/form-data" class="inline">
                @csrf
                <input type="hidden" name="version" value="{{ $version }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <label class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors cursor-pointer">
                    Browse
                    <input type="file" name="file" accept="{{ $isVideo ? 'video/*' : 'image/*' }}" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
            @if($item)
                <form action="{{ route('admin.hero.delete', $item->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">Borrar</button>
                </form>
            @else
                <button class="bg-gray-400 text-gray-600 px-3 py-1 rounded text-xs cursor-not-allowed" disabled>Borrar</button>
            @endif
        </div>
    </div>
</div>
