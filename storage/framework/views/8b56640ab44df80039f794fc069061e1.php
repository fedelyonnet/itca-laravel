<nav class="flex items-center space-x-6">
    <a href="<?php echo e(route('admin.dashboard')); ?>" 
       class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
        Dashboard
    </a>
    
    <a href="<?php echo e(route('admin.carreras')); ?>" 
       class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
        Carreras
    </a>
    
    <div class="relative inline-flex items-center" 
         x-data="{ open: false }" 
         @click.away="open = false" 
         @keydown.escape.window="open = false">
        
        <button @click="open = !open" 
                @keydown.enter.prevent="open = !open"
                @keydown.space.prevent="open = !open"
                :aria-expanded="open"
                aria-haspopup="true"
                class="inline-flex items-center text-white hover:text-gray-300 px-2 py-1">
            Edición CMS
            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>

        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute left-0 top-full mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-50"
             role="menu">
            
            <div class="py-1">
                <a href="<?php echo e(route('admin.edit-hero')); ?>" 
                   @keydown.enter="window.location.href = '<?php echo e(route('admin.edit-hero')); ?>'"
                   class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición Hero + Sticky Bar
                </a>
                
                <a href="<?php echo e(route('admin.beneficios')); ?>" 
                   @keydown.enter="window.location.href = '<?php echo e(route('admin.beneficios')); ?>'"
                   class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición Beneficios
                </a>
                
                <a href="<?php echo e(route('admin.sedes')); ?>" 
                   @keydown.enter="window.location.href = '<?php echo e(route('admin.sedes')); ?>'"
                   class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición Sedes
                </a>
                
                <a href="<?php echo e(route('admin.testimonios')); ?>" 
                   @keydown.enter="window.location.href = '<?php echo e(route('admin.testimonios')); ?>'"
                   class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición Testimonios
                </a>
                
                <a href="<?php echo e(route('admin.partners')); ?>" 
                   @keydown.enter="window.location.href = '<?php echo e(route('admin.partners')); ?>'"
                   class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición Partners
                </a>
                
                <a href="<?php echo e(route('admin.en-accion')); ?>" 
                   @keydown.enter="window.location.href = '<?php echo e(route('admin.en-accion')); ?>'"
                   class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out"
                   role="menuitem">
                    Edición En Acción
                </a>
            </div>
        </div>
    </div>
</nav><?php /**PATH /home/fede/Desktop/itca-laravel/resources/views/components/navbar.blade.php ENDPATH**/ ?>