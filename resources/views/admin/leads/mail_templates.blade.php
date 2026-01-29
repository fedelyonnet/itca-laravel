<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mails de Seguimiento (Gestión)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- CONTENEDOR PRINCIPAL -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-100">

                <!-- 1. SELECTOR Y ACCIONES -->
                <div class="mb-8 p-4 bg-gray-900 rounded-lg border border-gray-700 flex flex-col md:flex-row items-end md:items-center justify-between gap-4">
                    <div class="w-full md:w-1/2">
                        <label class="block text-lg font-bold mb-2 text-blue-400">Paso 1: Selecciona la Carrera</label>
                        <select id="courseSelect" class="w-full p-2 bg-gray-700 border-gray-600 rounded text-white" onchange="loadCourseData()">
                            <option value="">-- Seleccionar --</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ACCIONES SUPERIORES (Ocultas por defecto) -->
                    <div id="topActions" class="hidden flex flex-col md:flex-row gap-3 items-center">
                        <span id="loadingIndicator" class="hidden text-blue-400 flex items-center font-bold text-sm mr-2">
                            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Guardando...
                        </span>
                        
                        <button id="previewBtn" type="button" onclick="openPreview()" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 rounded text-white font-bold shadow transition flex items-center gap-2 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            PREVIEW
                        </button>

                        <button id="testMailBtn" type="button" onclick="sendTestEmail()" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 rounded text-white font-bold shadow transition flex items-center gap-2 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            TEST EMAIL
                        </button>
                        
                        <button id="submitBtn" type="button" onclick="saveData()" class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-white font-bold shadow transition text-xs">
                            GUARDAR CAMBIOS
                        </button>
                    </div>
                </div>

                <!-- 2. FORMULARIO (Oculto hasta seleccionar) -->
                <div id="formContainer" class="hidden">
                    <div id="mainForm">
                        
                        <!-- IMÁGENES -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            
                            <!-- COLUMNA IZQUIERDA -->
                            <div class="space-y-6">
                                
                                <!-- Group 1: Header & Footer -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <h4 class="text-sm font-bold mb-3 text-blue-400 uppercase">General</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @include('admin.leads.partials.image_field', ['field' => 'header_image', 'label' => 'Header'])
                                        @include('admin.leads.partials.image_field', ['field' => 'bottom_image', 'label' => 'Footer'])
                                    </div>
                                </div>

                                <!-- Group 2: Main & Certificate (Requested Pair) -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <h4 class="text-sm font-bold mb-3 text-blue-400 uppercase">Contenido Principal</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @include('admin.leads.partials.image_field', ['field' => 'main_illustration', 'label' => 'Ilust. Principal'])
                                        @include('admin.leads.partials.image_field', ['field' => 'certificate_image', 'label' => 'Certificado'])
                                    </div>
                                </div>

                                <!-- Group 3: Banners -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <h4 class="text-sm font-bold mb-3 text-blue-400 uppercase">Banners Extra</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        @include('admin.leads.partials.image_field', ['field' => 'utn_banner_image', 'label' => 'UTN Banner<br><span class="normal-case text-gray-500">Ancho recomendado: 1200px</span>'])
                                        @include('admin.leads.partials.image_field', ['field' => 'partners_image', 'label' => 'Partners<br><span class="normal-case text-gray-500">Ancho recomendado: 1200px</span>'])
                                    </div>
                                </div>
                            </div>

                            <!-- COLUMNA DERECHA -->
                            <div class="space-y-6">
                                
                                <!-- BENEFICIOS (Grid Compacto - Arriba) -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <h3 class="text-lg font-bold mb-4 text-blue-400 border-b border-gray-700 pb-2">Imágenes Beneficios</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach(['benefit_1_image', 'benefit_2_image', 'benefit_3_image', 'benefit_4_image'] as $index => $field)
                                            <div class="flex flex-col">
                                                @include('admin.leads.partials.image_field', ['field' => $field, 'label' => 'Ben. ' . ($index + 1)])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- ILUSTRACIONES (Grid Compacto - Abajo) -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <h3 class="text-lg font-bold mb-4 text-blue-400 border-b border-gray-700 pb-2">Ilustraciones Extra</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach(['illustration_2', 'illustration_3', 'illustration_4', 'illustration_5'] as $index => $field)
                                            <div class="flex flex-col">
                                                @include('admin.leads.partials.image_field', ['field' => $field, 'label' => 'Ilust. ' . ($index + 2)])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- PDFs (Planes de Estudio) -->
                                <div class="bg-gray-900 p-4 rounded border border-gray-700">
                                    <h3 class="text-lg font-bold mb-4 text-blue-400 border-b border-gray-700 pb-2">Planes de Estudio (PDF)</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @foreach(['syllabus_year_1', 'syllabus_year_2', 'syllabus_year_3'] as $field)
                                            <div class="relative">
                                                <!-- Simplified layout for narrow column -->
                                                <div class="relative w-full">
                                                    <input type="file" 
                                                           name="{{ $field }}" 
                                                           id="input_{{ $field }}" 
                                                           accept=".pdf" 
                                                           class="hidden" 
                                                           onchange="updateFileName(this, 'label_{{ $field }}')">
                                                           
                                                    <label for="input_{{ $field }}" class="flex flex-col items-center justify-center w-full py-4 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-800 hover:bg-gray-750 hover:border-blue-500 transition-all group">
                                                        <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-blue-400 transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                        </svg>
                                                        <p id="label_{{ $field }}" class="text-xs text-gray-500 group-hover:text-gray-400 px-1 truncate max-w-[100px]">PDF {{ substr($field, -1) }}</p>
                                                    </label>
                                                </div>
                                                
                                                <!-- Link para ver actual -->
                                                <a id="link_{{ $field }}" href="#" target="_blank" class="hidden mt-2 inline-flex items-center gap-1 px-2 py-1 bg-gray-700 hover:bg-gray-600 text-green-400 text-[10px] rounded transition-colors w-full justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> 
                                                    Ver
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- TOAST CONTAINER -->
                        <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>


                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JAVASCRIPT DIRECTO Y SIMPLE -->
    <script>
        const API_URL = "{{ url('/admin/leads/mail-templates') }}";
        const PREVIEW_URL = "{{ route('mail.preview.abandoned-cart') }}";
        const TEST_SEND_URL = "{{ route('admin.leads.mail-templates.send-test') }}";
        let currentCursoId = null;

        async function sendTestEmail() {
            if (!currentCursoId) return;

            const email = prompt("Ingresa el email para enviar la prueba:", "fedelyonnet@gmail.com");
            if (!email) return; // Cancelado

            const btn = document.getElementById('testMailBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Enviando...';
            btn.disabled = true;

            try {
                const response = await fetch(TEST_SEND_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        email: email,
                        cursada_id: currentCursoId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Email enviado con éxito ✓', 'success');
                } else {
                    throw new Error(result.message);
                }

            } catch (error) {
                console.error(error);
                showToast(error.message || 'Error al enviar mail', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
        
        
        function updateFileName(input, labelId) {
            const label = document.getElementById(labelId);
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                label.classList.remove('text-gray-500');
                if(label.classList.contains('text-xs')) { // Logic specific for image/pdf differentiation if needed, or generic
                     label.classList.add('text-blue-400', 'font-semibold');
                } else {
                     label.classList.add('text-blue-400', 'font-semibold');
                }
            } else {
                // Check if it's the PDF label (starts with label_) or Image label (btn_label_)
                if(labelId.startsWith('label_')) {
                     label.textContent = 'PDF';
                } else {
                     label.textContent = '';
                }
                
                label.classList.remove('text-blue-400', 'font-semibold');
                label.classList.add('text-gray-500');
            }
        }

        function openPreview() {
            if (!currentCursoId) {
                alert('Por favor selecciona una carrera primero');
                return;
            }
            window.open(PREVIEW_URL + '?cursada_id=' + currentCursoId, '_blank');
        }

        async function loadCourseData() {
            const select = document.getElementById('courseSelect');
            const formContainer = document.getElementById('formContainer');
            const topActions = document.getElementById('topActions');
            currentCursoId = select.value;

            if (!currentCursoId) {
                formContainer.classList.add('hidden');
                topActions.classList.add('hidden');
                return;
            }

            // Reset UI
            document.querySelectorAll('input[type="file"]').forEach(i => i.value = '');
            document.querySelectorAll('.preview-img').forEach(img => { img.src = ''; img.classList.add('hidden'); });
            document.querySelectorAll('.preview-placeholder').forEach(p => p.classList.remove('hidden'));
            document.querySelectorAll('span[id^="btn_label_"]').forEach(s => s.textContent = '');
            document.querySelectorAll('a[id^="link_"]').forEach(a => a.classList.add('hidden'));

            // Show form loading
            formContainer.classList.remove('hidden');
            topActions.classList.remove('hidden');

            try {
                const response = await fetch(`${API_URL}/${currentCursoId}`);
                const result = await response.json();
                
                if (result.exists && result.data) {
                    fillForm(result.data);
                }

            } catch (error) {
                console.error("Error loading data:", error);
                alert("Error al cargar datos del servidor.");
            }
        }

        function fillForm(data) {
            // Fill Images
            const imageFields = [
                'header_image', 'main_illustration', 'certificate_image',
                'benefit_1_image', 'benefit_2_image', 'benefit_3_image', 'benefit_4_image',
                'utn_banner_image', 'partners_image',
                'illustration_2', 'illustration_3', 'illustration_4', 'illustration_5',
                'bottom_image'
            ];

            imageFields.forEach(field => {
                if (data[field]) {
                    const img = document.getElementById(`preview_${field}`);
                    const placeholder = document.getElementById(`placeholder_${field}`);
                    
                    if (img && placeholder) {
                        img.src = '/storage/' + data[field];
                        img.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                }
            });

            // Fill PDFs links
            ['syllabus_year_1', 'syllabus_year_2', 'syllabus_year_3'].forEach(field => {
                if (data[field]) {
                    const link = document.getElementById(`link_${field}`);
                    if (link) {
                        link.href = '/storage/' + data[field];
                        link.classList.remove('hidden');
                        link.innerHTML = `<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Ver Archivo Guardado`;
                    }
                }
            });
        }

        function previewLocalImage(input, field) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(`preview_${field}`);
                    const placeholder = document.getElementById(`placeholder_${field}`);
                    
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            const icon = type === 'success' 
                ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                ${icon}
                <span class="flex-1 font-semibold">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        async function saveData() {
            console.log("Saving data for course:", currentCursoId);
            
            const btn = document.getElementById('submitBtn');
            const indicator = document.getElementById('loadingIndicator');

            btn.disabled = true;
            btn.classList.add('opacity-50');
            indicator.classList.remove('hidden');

            // MANUALLY BUILD FORMDATA SINCE WE HAVE NO FORM TAG
            const formData = new FormData();
            
            // Append inputs from our div
            const mainDiv = document.getElementById('mainForm');
            const inputs = mainDiv.querySelectorAll('input');
            
            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files[0]) {
                        formData.append(input.name, input.files[0]);
                    }
                } else {
                    formData.append(input.name, input.value);
                }
            });

            try {
                const response = await fetch(`${API_URL}/${currentCursoId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                });

                const contentType = response.headers.get("content-type");
                if (!response.ok || !contentType || !contentType.includes("application/json")) {
                    const text = await response.text();
                    console.error("Server raw response:", text);
                    throw new Error(`Error del servidor: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    showToast('¡Guardado con éxito!', 'success');
                    loadCourseData();
                } else {
                    throw new Error(result.message || 'Error desconocido');
                }

            } catch (error) {
                console.error(error);
                showToast(error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
                indicator.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
