<x-app-layout>
    
    @vite(['resources/css/backoffice.css', 'resources/js/backoffice.js'])

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <h2 class="text-2xl font-bold mb-6">Ordenar filtros</h2>
                    
                    <!-- Dropdown para seleccionar categoría -->
                    <div class="mb-6">
                        <label for="categoria-select" class="block text-sm font-medium text-gray-300 mb-2">
                            Seleccionar categoría de filtro
                        </label>
                        <select id="categoria-select" 
                                class="bg-gray-700 border border-gray-600 text-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full max-w-xs">
                            <option value="">-- Seleccione una categoría --</option>
                            <option value="carrera">Carrera</option>
                            <option value="sede">Sede</option>
                            <option value="modalidad">Modalidad</option>
                            <option value="turno">Turno</option>
                            <option value="dia">Día</option>
                        </select>
                    </div>

                    <!-- Grilla para reordenar filtros -->
                    <div id="filtros-container" class="hidden">
                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-semibold" id="categoria-titulo"></h3>
                            <button id="guardar-orden-btn" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150">
                                Guardar orden
                            </button>
                        </div>
                        
                        <div class="bg-gray-700 rounded-lg p-4">
                            <p class="text-sm text-gray-400 mb-4">
                                Arrastra y suelta los elementos para cambiar su orden. El orden se aplicará en la página de preinscripción.
                            </p>
                            <ul id="filtros-lista" class="space-y-2">
                                <!-- Los filtros se cargarán aquí dinámicamente -->
                            </ul>
                        </div>
                    </div>

                    <!-- Mensaje de éxito/error -->
                    <div id="mensaje-container" class="mt-4 hidden">
                        <div id="mensaje" class="p-4 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        let sortableInstance = null;
        let categoriaActual = '';

        document.getElementById('categoria-select').addEventListener('change', function() {
            const categoria = this.value;
            if (!categoria) {
                document.getElementById('filtros-container').classList.add('hidden');
                if (sortableInstance) {
                    sortableInstance.destroy();
                    sortableInstance = null;
                }
                return;
            }

            categoriaActual = categoria;
            cargarFiltros(categoria);
        });

        function cargarFiltros(categoria) {
            fetch(`/admin/carreras/ordenar-filtros/get?categoria=${categoria}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    mostrarMensaje(data.error, 'error');
                    return;
                }

                const lista = document.getElementById('filtros-lista');
                lista.innerHTML = '';

                data.filtros.forEach((filtro, index) => {
                    const li = document.createElement('li');
                    li.className = 'bg-gray-600 hover:bg-gray-500 rounded-lg p-3 cursor-move transition duration-150 flex items-center';
                    li.dataset.valor = filtro.valor;
                    
                    const handle = document.createElement('span');
                    handle.className = 'mr-3 text-gray-400';
                    handle.innerHTML = '☰';
                    
                    const texto = document.createElement('span');
                    texto.className = 'text-gray-100 flex-1';
                    texto.textContent = filtro.display;
                    
                    const numero = document.createElement('span');
                    numero.className = 'text-gray-400 text-sm ml-2';
                    numero.textContent = `#${index + 1}`;
                    
                    li.appendChild(handle);
                    li.appendChild(texto);
                    li.appendChild(numero);
                    lista.appendChild(li);
                });

                document.getElementById('categoria-titulo').textContent = `Ordenar: ${categoria.charAt(0).toUpperCase() + categoria.slice(1)}`;
                document.getElementById('filtros-container').classList.remove('hidden');

                // Inicializar Sortable
                if (sortableInstance) {
                    sortableInstance.destroy();
                }

                sortableInstance = Sortable.create(lista, {
                    animation: 150,
                    handle: '.cursor-move',
                    onEnd: function() {
                        actualizarNumeros();
                    }
                });

                actualizarNumeros();
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al cargar los filtros', 'error');
            });
        }

        function actualizarNumeros() {
            const items = document.querySelectorAll('#filtros-lista li');
            items.forEach((item, index) => {
                const numero = item.querySelector('span:last-child');
                if (numero) {
                    numero.textContent = `#${index + 1}`;
                }
            });
        }

        document.getElementById('guardar-orden-btn').addEventListener('click', function() {
            if (!categoriaActual) {
                mostrarMensaje('Por favor seleccione una categoría', 'error');
                return;
            }

            const items = document.querySelectorAll('#filtros-lista li');
            const orden = Array.from(items).map(item => item.dataset.valor);

            fetch('/admin/carreras/ordenar-filtros/guardar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    categoria: categoriaActual,
                    orden: orden
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    mostrarMensaje(data.error, 'error');
                } else {
                    mostrarMensaje('Orden guardado correctamente', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al guardar el orden', 'error');
            });
        });

        function mostrarMensaje(mensaje, tipo) {
            const container = document.getElementById('mensaje-container');
            const mensajeDiv = document.getElementById('mensaje');
            
            container.classList.remove('hidden');
            mensajeDiv.className = 'p-4 rounded-lg';
            
            if (tipo === 'success') {
                mensajeDiv.className += ' bg-green-600 text-white';
            } else {
                mensajeDiv.className += ' bg-red-600 text-white';
            }
            
            mensajeDiv.textContent = mensaje;
            
            setTimeout(() => {
                container.classList.add('hidden');
            }, 3000);
        }
    </script>

</x-app-layout>




