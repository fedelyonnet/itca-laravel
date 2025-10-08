<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/backoffice.css', 'resources/js/backoffice.js']); ?>
    

    <!-- Toast Messages Container - Completely outside layout -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        <?php if(session('success')): ?>
            <div id="success-message" class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-green-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            <div id="error-message" class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border-l-4 border-red-400 flex items-center transform translate-x-full transition-transform duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Gestión de Sedes</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Paneles visibles: <?php echo e($sedes->count()); ?>/8
                            </p>
                        </div>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                            Agregar Sede
                        </button>
                    </div>

                    <?php if($sedes->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Orden</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Desktop</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Mobile</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Dirección</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Teléfono</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-[10px] font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <!-- Orden -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col items-center space-y-1">
                                                    <button onclick="moverSede(<?php echo e($sede->id); ?>, 'up')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover arriba">
                                                        ↑
                                                    </button>
                                                    <button onclick="moverSede(<?php echo e($sede->id); ?>, 'down')" 
                                                            class="mover-btn bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors disabled:bg-gray-500 disabled:cursor-not-allowed"
                                                            title="Mover abajo">
                                                        ↓
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            <!-- Desktop Thumbnail -->
                                            <td class="px-4 py-4">
                                                <div class="relative group" 
                                                     x-data="{ showTooltip: false }" 
                                                     @mouseenter="showTooltip = true" 
                                                     @mouseleave="showTooltip = false">
                                                    <div class="w-16 h-16 bg-gray-500 rounded overflow-hidden mx-auto cursor-pointer">
                                                        <?php if($sede->imagen_desktop): ?>
                                                            <img src="<?php echo e(asset('storage/' . $sede->imagen_desktop)); ?>" 
                                                                 alt="Desktop" 
                                                                 class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Tooltip con imagen -->
                                                    <div x-show="showTooltip" 
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-95"
                                                         class="fixed bottom-auto left-1/2 transform -translate-x-1/2 mb-2 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden pointer-events-none"
                                                         style="z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                        <?php if($sede->imagen_desktop): ?>
                                                            <img src="<?php echo e(asset('storage/' . $sede->imagen_desktop)); ?>" 
                                                                 alt="Desktop Preview" 
                                                                 class="max-w-xs max-h-64 object-contain">
                                                        <?php else: ?>
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen desktop</div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Mobile Thumbnail -->
                                            <td class="px-4 py-4">
                                                <div class="relative group" 
                                                     x-data="{ showTooltip: false }" 
                                                     @mouseenter="showTooltip = true" 
                                                     @mouseleave="showTooltip = false">
                                                    <div class="w-16 h-16 bg-gray-500 rounded overflow-hidden mx-auto cursor-pointer">
                                                        <?php if($sede->imagen_mobile): ?>
                                                            <img src="<?php echo e(asset('storage/' . $sede->imagen_mobile)); ?>" 
                                                                 alt="Mobile" 
                                                                 class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Tooltip con imagen -->
                                                    <div x-show="showTooltip" 
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-95"
                                                         class="fixed bottom-auto left-1/2 transform -translate-x-1/2 mb-2 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden pointer-events-none"
                                                         style="z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                        <?php if($sede->imagen_mobile): ?>
                                                            <img src="<?php echo e(asset('storage/' . $sede->imagen_mobile)); ?>" 
                                                                 alt="Mobile Preview" 
                                                                 class="max-w-xs max-h-64 object-contain">
                                                        <?php else: ?>
                                                            <div class="w-64 h-40 bg-gray-100 flex items-center justify-center">
                                                                <div class="text-center text-gray-500">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <div class="text-sm">Sin imagen mobile</div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Nombre -->
                                            <td class="px-4 py-4 text-sm text-white">
                                                <div class="font-medium"><?php echo e($sede->nombre); ?></div>
                                            </td>
                                            
                                            <!-- Dirección -->
                                            <td class="px-4 py-4 text-sm text-gray-300">
                                                <div class="max-w-xs truncate" title="<?php echo e($sede->direccion); ?>">
                                                    <?php echo e($sede->direccion); ?>

                                                </div>
                                            </td>
                                            
                                            <!-- Teléfono -->
                                            <td class="px-4 py-4 text-sm text-gray-300">
                                                <?php echo e($sede->telefono); ?>

                                            </td>
                                            
                                            <!-- Estado -->
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($sede->disponible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                    <?php echo e($sede->disponible ? 'Disponible' : 'No disponible'); ?>

                                                </span>
                                            </td>
                                            
                                            <!-- Acciones -->
                                            <td class="px-4 py-4">
                                                <div class="flex space-x-2">
                                                    <!-- Editar -->
                                                    <button onclick="editSede(<?php echo e($sede->id); ?>)" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Eliminar -->
                                                    <form action="<?php echo e(route('admin.sedes.destroy', $sede->id)); ?>" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No hay sedes</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando una nueva sede.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Sede -->
    <div id="sedeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-gray-800 rounded-lg p-1 sm:p-6 w-full max-w-[240px] sm:max-w-md">
                <div class="flex justify-between items-center mb-1 sm:mb-4">
                    <h3 id="modalTitle" class="text-sm sm:text-lg font-semibold text-white">Agregar Nueva Sede</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="sedeForm" action="<?php echo e(route('admin.sedes.store')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="sedeId" name="sede_id" value="">
                    <input type="hidden" id="methodField" name="_method" value="">
            
            <?php if($errors->any()): ?>
                <div class="bg-red-600 text-white px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Diseño Ultra Compacto -->
            <div class="space-y-4">
                <!-- Primera fila: Nombre y Teléfono -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre <span class="text-red-400">*</span></label>
                        <input type="text" id="modalNombre" name="nombre" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="Ej: Sede Central">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Teléfono <span class="text-red-400">*</span></label>
                        <input type="text" id="modalTelefono" name="telefono" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                               placeholder="Ej: +54 11 1234-5678">
                    </div>
                </div>

                <!-- Segunda fila: Dirección -->
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Dirección <span class="text-red-400">*</span></label>
                    <textarea id="modalDireccion" name="direccion" rows="2" 
                              class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm resize-none"
                              placeholder="Escribe la dirección completa..."></textarea>
                </div>

                <!-- Tercera fila: Imágenes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Imagen Desktop <span class="text-red-400" id="desktopRequired">*</span></label>
                        <input type="file" id="modalImagenDesktop" name="imagen_desktop" accept="image/*" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        <p class="text-xs text-gray-400 mt-1" id="desktopHelp">377 × 196 px</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Imagen Mobile <span class="text-red-400" id="mobileRequired">*</span></label>
                        <input type="file" id="modalImagenMobile" name="imagen_mobile" accept="image/*" 
                               class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-500 file:text-white">
                        <p class="text-xs text-gray-400 mt-1" id="mobileHelp">388 × 65 px</p>
                    </div>
                </div>

                <!-- Cuarta fila: Tipo de Título -->
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Tipo de Título</label>
                    <select id="modalTipoTitulo" name="tipo_titulo" 
                            class="w-full px-2 py-1.5 bg-gray-600 border border-gray-500 rounded text-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                        <option value="normal">Normal (una línea)</option>
                        <option value="dos_lineas">Dos líneas (dividir por espacio)</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Si eliges "Dos líneas", el título se dividirá automáticamente en la primera palabra y el resto</p>
                </div>

                <!-- Quinta fila: Disponible -->
                <div class="flex items-center">
                    <label class="flex items-center text-xs text-gray-300">
                        <input type="checkbox" id="modalDisponible" name="disponible" value="1" checked
                               class="mr-2 text-blue-600 focus:ring-blue-500">
                        Sede disponible
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-1 sm:space-x-3 mt-2 sm:mt-4">
                <button type="button" onclick="resetModal(); closeModal();" 
                        class="px-2 py-1 sm:px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors text-xs sm:text-sm">
                    Cancelar
                </button>
                <button type="submit" id="modalSubmitBtn"
                        class="px-2 py-1 sm:px-4 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors text-xs sm:text-sm">
                    Agregar Sede
                </button>
            </div>
        </form>
            </div>
        </div>
    </div>


    <script>
        let isEditMode = false;

        function openModal() {
            resetModal();
            document.getElementById('sedeModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Agregar Nueva Sede';
            document.getElementById('modalSubmitBtn').textContent = 'Agregar Sede';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('sedeForm').action = '<?php echo e(route("admin.sedes.store")); ?>';
            isEditMode = false;
        }

        function closeModal() {
            document.getElementById('sedeModal').classList.add('hidden');
            resetModal();
        }

        function resetModal() {
            document.getElementById('sedeForm').reset();
            document.getElementById('sedeId').value = '';
            document.getElementById('modalDisponible').checked = true;
            isEditMode = false;
        }

        function editSede(sedeId) {
            resetModal();
            isEditMode = true;
            
            fetch(`/admin/sedes/${sedeId}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Editar Sede';
                    document.getElementById('modalSubmitBtn').textContent = 'Actualizar Sede';
                    document.getElementById('methodField').value = 'PUT';
                    document.getElementById('sedeId').value = data.id;
                    document.getElementById('sedeForm').action = `/admin/sedes/${data.id}`;
                    
                    // Llenar campos
                    document.getElementById('modalNombre').value = data.nombre;
                    document.getElementById('modalDireccion').value = data.direccion;
                    document.getElementById('modalTelefono').value = data.telefono;
                    document.getElementById('modalDisponible').checked = data.disponible;
                    document.getElementById('modalTipoTitulo').value = data.tipo_titulo || 'normal';
                    
                    // Actualizar ayuda de imágenes para modo edición
                    document.getElementById('desktopHelp').textContent = 'Opcional - mantiene la imagen actual si no se selecciona';
                    document.getElementById('mobileHelp').textContent = 'Opcional - mantiene la imagen actual si no se selecciona';
                    document.getElementById('desktopRequired').textContent = '';
                    document.getElementById('mobileRequired').textContent = '';
                    
                    document.getElementById('sedeModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error al cargar los datos de la sede:', error);
                    showMessage('Error al cargar los datos de la sede', 'error');
                });
        }

        function deleteSede(sedeId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta sede?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/sedes/${sedeId}`;
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Sistema de botones arriba/abajo
        function moverSede(sedeId, direccion) {
            console.log('Moviendo sede:', { sedeId, direccion });
            const botones = document.querySelectorAll('.mover-btn');
            botones.forEach(btn => btn.disabled = true);
            
            const requestData = {
                id: sedeId,
                direccion: direccion
            };
            
            console.log('Enviando datos:', requestData);
            
            fetch(`/admin/sedes/mover`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Error al mover la sede');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(error.message, 'error');
            })
            .finally(() => {
                botones.forEach(btn => btn.disabled = false);
            });
        }

        function confirmDelete(event) {
            event.preventDefault();
            if (confirm('¿Estás seguro de eliminar esta sede?')) {
                event.target.closest('form').submit();
            }
            return false;
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Detectar si estamos en modo edición
            const isEditMode = document.getElementById('methodField').value === 'PUT';
            
            // Validar campos básicos
            const nombre = document.querySelector('input[name="nombre"]').value.trim();
            const direccion = document.querySelector('textarea[name="direccion"]').value.trim();
            const telefono = document.querySelector('input[name="telefono"]').value.trim();
            const imagenDesktop = document.querySelector('input[name="imagen_desktop"]').files.length;
            const imagenMobile = document.querySelector('input[name="imagen_mobile"]').files.length;
            
            // Validar campos obligatorios
            if (!nombre) {
                showValidationModal("Error", "El nombre es obligatorio");
                return false;
            }
            if (!direccion) {
                showValidationModal("Error", "La dirección es obligatoria");
                return false;
            }
            if (!telefono) {
                showValidationModal("Error", "El teléfono es obligatorio");
                return false;
            }
            
            // Solo validar imágenes si NO estamos en modo edición
            if (!isEditMode) {
                if (imagenDesktop === 0) {
                    showValidationModal("Error", "La imagen desktop es obligatoria");
                    return false;
                }
                if (imagenMobile === 0) {
                    showValidationModal("Error", "La imagen mobile es obligatoria");
                    return false;
                }
            }
            
            // Si todo está bien, enviar el formulario
            document.getElementById('sedeForm').submit();
            return false;
        }

        function showValidationModal(title, message) {
            const modal = document.getElementById('confirmation-modal');
            if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
                modal._x_dataStack[0].title = title;
                modal._x_dataStack[0].message = message;
                modal._x_dataStack[0].onConfirm = () => modal._x_dataStack[0].open = false;
                modal._x_dataStack[0].open = true;
                
                // Hide cancel button and modify confirm button for validation
                const cancelButton = modal.querySelector('button[class*="bg-white"]');
                const confirmButton = modal.querySelector('button[class*="bg-blue-600"]');
                
                if (cancelButton) {
                    cancelButton.style.display = 'none';
                }
                
                if (confirmButton) {
                    confirmButton.textContent = 'Aceptar';
                }
            } else {
                // Fallback to native alert
                alert(title + ": " + message);
            }
        }

        function showMessage(message, type) {
            const container = document.getElementById('toast-container');
            const messageDiv = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const borderColor = type === 'success' ? 'border-green-400' : 'border-red-400';
            
            messageDiv.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg border-l-4 ${borderColor} flex items-center transform translate-x-full transition-transform duration-300 ease-in-out`;
            
            const icon = type === 'success' 
                ? '<svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                : '<svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            
            messageDiv.innerHTML = icon + message;
            container.appendChild(messageDiv);
            
            // Animar entrada
            setTimeout(() => {
                messageDiv.classList.remove('translate-x-full');
            }, 100);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                messageDiv.classList.add('translate-x-full');
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.parentNode.removeChild(messageDiv);
                    }
                }, 300);
            }, 5000);
        }

        // Auto-hide success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add('translate-x-full');
                }, 3000);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.add('translate-x-full');
                }, 5000);
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/fede/Desktop/itca-laravel/resources/views/admin/sedes.blade.php ENDPATH**/ ?>