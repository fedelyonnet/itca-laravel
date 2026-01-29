@props(['field', 'label'])

<div class="flex flex-col gap-2 p-2 border border-gray-700 rounded bg-gray-900/40 hover:bg-gray-900/60 transition-colors">
    <div class="flex justify-between items-center mb-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{!! $label !!}</label>
        <span id="btn_label_{{ $field }}" class="text-[9px] text-blue-400 font-medium truncate max-w-[80px]"></span>
    </div>
    
    <!-- Clickable Header/Preview Area -->
    <div class="relative group">
        <input type="file" 
               name="{{ $field }}" 
               id="input_{{ $field }}"
               accept="image/*" 
               class="hidden"
               onchange="updateFileName(this, 'btn_label_{{ $field }}'); previewLocalImage(this, '{{ $field }}')"/>
               
        <label for="input_{{ $field }}" class="relative block w-full h-24 bg-gray-800 rounded overflow-hidden border border-gray-700 border-dashed hover:border-blue-500 cursor-pointer transition-all group shadow-inner">
            <!-- Imagen Preview -->
            <img id="preview_{{ $field }}" src="" class="preview-img object-contain h-full w-full hidden z-10 relative">
            
            <!-- Placeholder / Sin Imagen -->
            <div id="placeholder_{{ $field }}" class="preview-placeholder absolute inset-0 flex flex-col items-center justify-center text-gray-500 group-hover:text-blue-400 transition-colors p-4 text-center">
                <svg class="w-6 h-6 mb-1 opacity-50 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span class="text-[10px] uppercase font-bold tracking-tighter">Click para subir</span>
            </div>

            <!-- Hover Overlay (visible only when there IS an image) -->
            <div id="overlay_{{ $field }}" class="absolute inset-0 bg-blue-600/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
        </label>
    </div>
</div>
