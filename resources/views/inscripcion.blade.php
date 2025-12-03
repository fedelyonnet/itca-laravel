<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Inscripción - {{ $curso->nombre }} - ITCA">
    <title>Inscripción - {{ $curso->nombre }} - ITCA</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">
    <!-- Styles -->
    @vite(['resources/css/public.css', 'resources/js/app.js'])
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body class="inscripcion-page">
    <!-- Sticky Bar -->
    @if($stickyBar && $stickyBar->visible == true)
    <div class="sticky-bar" style="background-color: {{ $stickyBar->color }} !important;">
        <div class="container">
            <div class="sticky-bar-content">
                <div class="sticky-bar-text-container">
                    <span class="sticky-bar-text">{!! $stickyBar->getFormattedTextAttribute() !!}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <header class="header {{ $stickyBar && $stickyBar->visible == true ? 'header-with-sticky' : '' }}">
        <div class="container">
            <nav class="nav">
                <!-- Logo -->
                <a href="/" class="logo">ITCA</a>
                
                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li><a href="/" class="nav-link">Somos ITCA</a></li>
                    <li><a href="/carreras" class="nav-link">Carreras</a></li>
                    <li><a href="#" class="nav-link">Beneficios</a></li>
                    <li><a href="#" class="nav-link">Contacto</a></li>
                </ul>
                
                <!-- Mobile Hamburger Button -->
                <button class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Header lista-carreras con breadcrumb (sin título) -->
        <section class="lista-carreras lista-carreras--show carrera-show-header">
            <div class="lista-carreras-container carrera-show-container">
                <div class="lista-carreras-header carrera-show-header-inner">
                    <div class="lista-carreras-breadcrumb carrera-show-breadcrumb">
                        <a href="/" class="lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Inicio</a>
                        <span class="lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                        <a href="/carreras" class="lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Carreras</a>
                        <span class="lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                        <a href="{{ route('carreras.show', $curso->id) }}" class="lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">
                            @php
                                $nombre = $curso->nombre;
                                $palabras = explode(' ', $nombre);
                                $mitad = ceil(count($palabras) / 2);
                                $primeraParte = implode(' ', array_slice($palabras, 0, $mitad));
                                $segundaParte = implode(' ', array_slice($palabras, $mitad));
                            @endphp
                            <span class="carrera-show-breadcrumb-part1">{{ $primeraParte }}</span>
                            @if($segundaParte)
                                <span class="carrera-show-breadcrumb-part2">{{ $segundaParte }}</span>
                            @endif
                        </a>
                        <span class="lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                        <span class="lista-carreras-breadcrumb-current carrera-show-breadcrumb-current">
                            Pre-Inscripción
                        </span>
                    </div>
                    <h2 class="lista-carreras-title">
                        <span class="lista-carreras-title-text-1">Convertí</span>
                        <span class="lista-carreras-title-text-2">tu pasión en profesión</span>
                        <span class="lista-carreras-title-text-3">¡Inscribite hoy!</span>
                    </h2>
                </div>
            </div>
        </section>

        <!-- Inscripción Section -->
        <section class="inscripcion-section">
            <div class="inscripcion-container">
                <div class="inscripcion-content">
                    <!-- Línea horizontal superior: Filtra por, Borrar todo, Resultados, Chips -->
                    <div class="inscripcion-filtros-bar">
                        <span class="inscripcion-filtros-bar-texto">Filtra por:</span>
                        <a href="#" id="borrar-todo-filtros" class="inscripcion-filtros-borrar-todo">Borrar todo</a>
                        <span class="inscripcion-filtros-resultados">Resultados: <span id="contador-resultados">0</span></span>
                        <div id="filtros-aplicados" class="inscripcion-filtros-chips">
                            <!-- Los chips se agregarán dinámicamente con JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Dos paneles verticales: Izquierda filtros, Derecha listado -->
                    <div class="inscripcion-paneles-container">
                        <!-- Panel izquierdo de filtros -->
                        <div class="inscripcion-filtros-panel">
                            <div class="inscripcion-filtros-recuadro">
                                <!-- Filtro: Carrera -->
                                <div class="inscripcion-filtro-seccion">
                                    <h4 class="inscripcion-filtro-subtitulo">
                                        <img src="/images/desktop/wrench_g.png" alt="Carrera" class="inscripcion-filtro-icono">
                                        <span>Carrera</span>
                                    </h4>
                                    <div id="filtro-carrera-opciones">
                                        @foreach($carreras as $carrera)
                                            @php
                                                $nombreCorregido = corregirNombreCarrera($carrera->nombre_curso);
                                                $esSeleccionado = $carrera->nombre_curso == $curso->nombre;
                                            @endphp
                                            <span class="filtro-opcion" 
                                                  data-tipo="carrera" 
                                                  data-valor="{{ $carrera->nombre_curso }}"
                                                  data-seleccionado="{{ $esSeleccionado ? 'true' : 'false' }}">
                                                {{ $nombreCorregido }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Filtro: Sede -->
                                <div class="inscripcion-filtro-seccion">
                                    <h4 class="inscripcion-filtro-subtitulo">
                                        <img src="/images/desktop/sede_g.png" alt="Sede" class="inscripcion-filtro-icono">
                                        <span>Sede</span>
                                    </h4>
                                    <div id="filtro-sede-opciones">
                                        @foreach($sedesFiltro as $sede)
                                            @php
                                                $sedeCorregida = corregirNombreSede($sede);
                                            @endphp
                                            <span class="filtro-opcion" 
                                                  data-tipo="sede" 
                                                  data-valor="{{ $sede }}"
                                                  data-seleccionado="false">
                                                {{ $sedeCorregida }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Filtro: Modalidad -->
                                <div class="inscripcion-filtro-seccion">
                                    <h4 class="inscripcion-filtro-subtitulo">
                                        <img src="/images/desktop/gear_g.png" alt="Modalidad" class="inscripcion-filtro-icono">
                                        <span>Modalidad</span>
                                    </h4>
                                    <div id="filtro-modalidad-opciones">
                                        @foreach($modalidades as $modalidad)
                                            @php
                                                $modalidadDisplay = $modalidad;
                                                // Corregir "Sempresencial" a "Semipresencial" en el display
                                                if (stripos($modalidadDisplay, 'Sempresencial') !== false) {
                                                    $modalidadDisplay = str_ireplace('Sempresencial', 'Semipresencial', $modalidadDisplay);
                                                }
                                            @endphp
                                            <span class="filtro-opcion" 
                                                  data-tipo="modalidad" 
                                                  data-valor="{{ $modalidad }}"
                                                  data-seleccionado="false">
                                                {{ $modalidadDisplay }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Filtro: Turno -->
                                <div class="inscripcion-filtro-seccion">
                                    <h4 class="inscripcion-filtro-subtitulo">
                                        <img src="/images/desktop/clock_g.png" alt="Turno" class="inscripcion-filtro-icono">
                                        <span>Turno</span>
                                    </h4>
                                    <div id="filtro-turno-opciones">
                                        @foreach($turnos as $turno)
                                            <span class="filtro-opcion" 
                                                  data-tipo="turno" 
                                                  data-valor="{{ $turno }}"
                                                  data-seleccionado="false">
                                                {{ $turno }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Filtro: Día -->
                                <div class="inscripcion-filtro-seccion">
                                    <h4 class="inscripcion-filtro-subtitulo">
                                        <img src="/images/desktop/calendar_g.png" alt="Día" class="inscripcion-filtro-icono">
                                        <span>Día</span>
                                    </h4>
                                    <div id="filtro-dia-opciones">
                                        @foreach($dias as $dia)
                                            <span class="filtro-opcion" 
                                                  data-tipo="dia" 
                                                  data-valor="{{ $dia }}"
                                                  data-seleccionado="false">
                                                {{ $dia }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Panel derecho: Listado de cursadas -->
                        <div class="inscripcion-listado-panel">
                            @if($cursadas->count() > 0)
                                <div id="cursadas-container">
                                    @foreach($cursadas as $cursada)
                                        <div class="cursada-item" 
                                             data-carrera="{{ $cursada->nombre_curso }}"
                                             data-sede="{{ $cursada->sede ?? '' }}"
                                             data-modalidad="{{ $cursada->x_modalidad ?? '' }}"
                                             data-turno="{{ $cursada->x_turno ?? '' }}"
                                             data-dia="{{ $cursada->dias ?? '' }}">
                                            <div class="cursada-item-grid">
                                                <!-- Columna 1: Información de la cursada -->
                                                <div class="cursada-item-columna-izq">
                                                    <!-- Item 1: Inicio (mes año) -->
                                                    <div>
                                                        <strong class="cursada-item-label">Inicia:</strong>
                                                        <span class="cursada-item-value">
                                                            @if($cursada->mes_inicio && $cursada->fecha_inicio)
                                                                @php
                                                                    $meses = [
                                                                        '1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril',
                                                                        '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto',
                                                                        '9' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
                                                                    ];
                                                                    $mesTexto = $meses[$cursada->mes_inicio] ?? $cursada->mes_inicio;
                                                                @endphp
                                                                {{ ucfirst($mesTexto) }} {{ $cursada->fecha_inicio->format('Y') }}
                                                            @elseif($cursada->fecha_inicio)
                                                                {{ $cursada->fecha_inicio->format('F Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Item 2: Horario -->
                                                    <div>
                                                        <strong class="cursada-item-label">Horario:</strong>
                                                        <span class="cursada-item-value">
                                                            @if($cursada->hora_inicio && $cursada->hora_fin)
                                                                {{ \Carbon\Carbon::parse($cursada->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($cursada->hora_fin)->format('H:i') }}
                                                            @elseif($cursada->horario)
                                                                {{ $cursada->horario }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Item 3: Modalidad -->
                                                    <div>
                                                        <strong class="cursada-item-label">Modalidad:</strong>
                                                        <span class="cursada-item-value">
                                                            @php
                                                                $modalidad = $cursada->x_modalidad ?? 'N/A';
                                                                // Corregir "Sempresencial" a "Semipresencial"
                                                                if (stripos($modalidad, 'Sempresencial') !== false) {
                                                                    $modalidad = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                                                                }
                                                            @endphp
                                                            {{ $modalidad }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Item 4: Sede -->
                                                    <div>
                                                        <strong class="cursada-item-label">Sede:</strong>
                                                        <span class="cursada-item-value">
                                                            @php
                                                                $sedeCompleta = corregirNombreSede($cursada->sede ?? 'N/A');
                                                                $sedeSimplificada = simplificarNombreSede($sedeCompleta);
                                                            @endphp
                                                            {{ $sedeSimplificada }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Columna 2: Lugares disponibles y badge -->
                                                <div class="cursada-item-columna-medio">
                                                    <div class="cursada-lugares-texto">
                                                        ¡ Quedan <strong>{{ $cursada->vacantes ?? 0 }}</strong> lugares!
                                                    </div>
                                                    <div class="cursada-badge-descuento">
                                                        20%off
                                                    </div>
                                                </div>
                                                
                                                <!-- Columna 3: Botón ver valores -->
                                                <div class="cursada-item-columna-der">
                                                    <button class="cursada-btn-ver-valores">Ver valores</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p id="mensaje-no-resultados">No hay cursadas disponibles para esta carrera.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contacto Section -->
        <section class="contacto-section">
            <div class="contacto-content">
                <!-- Tercio Izquierdo - Sedes -->
                <div class="contacto-tercio contacto-sedes">
                    <h3 class="contacto-tercio-title">Sedes</h3>
                    <div class="contacto-sedes-content">
                        @foreach($sedes->where('disponible', true)->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE']) as $sede)
                            <div class="contacto-sede-row" data-sede="{{ Str::slug($sede->nombre) }}">
                                <div class="contacto-sede-text">{{ $sede->nombre }}</div>
                                <div class="contacto-sede-plus">+</div>
                            </div>
                            <div class="contacto-sede-content" id="{{ Str::slug($sede->nombre) }}-content">
                                <div class="contacto-sede-direccion">{{ $sede->direccion }}</div>
                                <div class="contacto-sede-contacto">Contacto: {{ $sede->telefono }}</div>
                                
                                @if($sede->link_google_maps)
                                    <div class="contacto-sede-link">
                                        <a href="{{ $sede->link_google_maps }}" target="_blank" class="contacto-sede-link-maps">
                                            📍 Ver en Maps
                                        </a>
                                    </div>
                                @endif
                                
                                @if($sede->link_whatsapp)
                                    <div class="contacto-sede-link">
                                        <a href="{{ $sede->link_whatsapp }}" target="_blank" class="contacto-sede-link-whatsapp">
                                            💬 WhatsApp
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Tercio Centro - Contacto -->
                <div class="contacto-tercio contacto-contacto">
                    <div class="contacto-contacto-wrapper">
                        <h3 class="contacto-tercio-title">Contacto</h3>
                        <div class="contacto-info-content">
                            <div class="contacto-info-item">
                                <span class="contacto-info-label">Tel: </span>
                                <span class="contacto-info-value">0810-220-4822</span>
                            </div>
                            <div class="contacto-info-item">
                                <span class="contacto-info-label">WhatsApp: </span>
                                <span class="contacto-info-value">11-2267-4822</span>
                            </div>
                            <div class="contacto-info-item">
                                <span class="contacto-info-label">Mail: </span>
                                <span class="contacto-info-value"><a href="mailto:inscripciones@itca.edu.ar">inscripciones@itca.edu.ar</a></span>
                            </div>
                        </div>
                        
                        <h4 class="contacto-redes-title">Redes Sociales</h4>
                        <div class="contacto-redes-icons">
                            <a href="https://www.instagram.com/itca.oficial/?hl=en" target="_blank" class="contacto-redes-link">
                                <img src="/images/social/ig.png" alt="Instagram" class="contacto-redes-icon">
                            </a>
                            <a href="https://www.tiktok.com/@itca.oficial" target="_blank" class="contacto-redes-link">
                                <img src="/images/social/tik.png" alt="TikTok" class="contacto-redes-icon">
                            </a>
                            <a href="https://www.facebook.com/ITCAoficial/" target="_blank" class="contacto-redes-link">
                                <img src="/images/social/fb.png" alt="Facebook" class="contacto-redes-icon">
                            </a>
                            <a href="https://www.linkedin.com/school/itca-oficial/" target="_blank" class="contacto-redes-link">
                                <img src="/images/social/lin.png" alt="LinkedIn" class="contacto-redes-icon">
                            </a>
                            <a href="https://www.youtube.com/canalITCAoficial" target="_blank" class="contacto-redes-link">
                                <img src="/images/social/yt.png" alt="YouTube" class="contacto-redes-icon">
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Tercio Redes Sociales (solo visible en mobile) -->
                <div class="contacto-tercio contacto-redes">
                    <h4 class="contacto-redes-title">Redes Sociales</h4>
                    <div class="contacto-redes-icons">
                        <a href="https://www.instagram.com/itca.oficial/?hl=en" target="_blank" class="contacto-redes-link">
                            <img src="/images/social/ig.png" alt="Instagram" class="contacto-redes-icon">
                        </a>
                        <a href="https://www.tiktok.com/@itca.oficial" target="_blank" class="contacto-redes-link">
                            <img src="/images/social/tik.png" alt="TikTok" class="contacto-redes-icon">
                        </a>
                        <a href="https://www.facebook.com/ITCAoficial/" target="_blank" class="contacto-redes-link">
                            <img src="/images/social/fb.png" alt="Facebook" class="contacto-redes-icon">
                        </a>
                        <a href="https://www.linkedin.com/school/itca-oficial/" target="_blank" class="contacto-redes-link">
                            <img src="/images/social/lin.png" alt="LinkedIn" class="contacto-redes-icon">
                        </a>
                        <a href="https://www.youtube.com/canalITCAoficial" target="_blank" class="contacto-redes-link">
                            <img src="/images/social/yt.png" alt="YouTube" class="contacto-redes-icon">
                        </a>
                    </div>
                </div>
                
                <!-- Tercio Derecho - ¿Querés más info? -->
                <div class="contacto-tercio contacto-info">
                    <div class="contacto-info-wrapper">
                        <h3 class="contacto-tercio-title">¿Querés más info?</h3>
                        <p class="contacto-info-text">
                            <strong>Suscribite</strong> y mantente al día con las últimas noticias, ofertas exclusivas y recursos útiles.
                        </p>
                        
                        <form class="contacto-form">
                            <input type="text" placeholder="Tu nombre" class="contacto-input" required>
                            <input type="email" placeholder="Tu email" class="contacto-input" required>
                            <input type="tel" placeholder="Tu número de celular" class="contacto-input" required>
                            <button type="submit" class="contacto-submit-btn">Enviar datos</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Slick Carousel JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <!-- JavaScript para desplegable de sedes y filtrado de cursadas -->
    <script>
        // Funcionalidad de sedes - Acordeón
        document.addEventListener('DOMContentLoaded', function() {
            const sedeRows = document.querySelectorAll('.contacto-sede-row[data-sede]');
            
            sedeRows.forEach(row => {
                row.addEventListener('click', function() {
                    const sedeId = this.getAttribute('data-sede');
                    const content = document.getElementById(sedeId + '-content');
                    
                    // Cerrar todos los otros contenidos
                    document.querySelectorAll('.contacto-sede-content').forEach(otherContent => {
                        if (otherContent !== content) {
                            otherContent.classList.remove('active');
                        }
                    });
                    
                    // Toggle del contenido actual
                    if (content) {
                        content.classList.toggle('active');
                    }
                });
            });
            
            // Funcionalidad de filtrado de cursadas
            const cursadasItems = document.querySelectorAll('.cursada-item');
            const contadorResultados = document.getElementById('contador-resultados');
            const filtrosAplicadosContainer = document.getElementById('filtros-aplicados');
            const borrarTodoBtn = document.getElementById('borrar-todo-filtros');
            const opcionesFiltro = document.querySelectorAll('.filtro-opcion');
            
            // Estado de los filtros seleccionados
            let filtrosSeleccionados = {
                carrera: '',
                sede: '',
                modalidad: '',
                turno: '',
                dia: ''
            };
            
            // Inicializar filtro de carrera si hay uno pre-seleccionado
            const carreraPreSeleccionada = document.querySelector('.filtro-opcion[data-tipo="carrera"][data-seleccionado="true"]');
            if (carreraPreSeleccionada) {
                filtrosSeleccionados.carrera = carreraPreSeleccionada.getAttribute('data-valor');
            }
            
            // Función para actualizar los colores de las opciones de filtro
            function actualizarColoresFiltros() {
                opcionesFiltro.forEach(opcion => {
                    const tipo = opcion.getAttribute('data-tipo');
                    const valor = opcion.getAttribute('data-valor');
                    const estaSeleccionado = filtrosSeleccionados[tipo] === valor;
                    
                    opcion.style.color = estaSeleccionado ? '#65E09C' : 'var(--text-white)';
                    opcion.setAttribute('data-seleccionado', estaSeleccionado ? 'true' : 'false');
                });
            }
            
            // Función para obtener el texto de visualización de un filtro
            function obtenerTextoFiltro(tipo, valor) {
                if (!valor) return null;
                
                // Buscar la opción correspondiente para obtener su texto
                const opcion = document.querySelector(`.filtro-opcion[data-tipo="${tipo}"][data-valor="${valor}"]`);
                if (opcion) {
                    return opcion.textContent.trim();
                }
                
                // Fallback: corregir modalidad si es necesario
                if (tipo === 'modalidad') {
                    return valor.replace(/Sempresencial/gi, 'Semipresencial');
                }
                
                // Fallback: corregir sede si es necesario
                if (tipo === 'sede') {
                    const conversiones = {
                        'constituyentes': 'Villa Urquiza - Av. Constituyentes 4631',
                        'congreso': 'Villa Urquiza - Av. Congreso 5672',
                        'moron': 'Morón - E. Grant 301',
                        'morón': 'Morón - E. Grant 301',
                        'banfield': 'Banfield - Av. Hipólito Yrigoyen 7536',
                        'san isidro': 'San Isidro - Camino de la Ribera Nte. 150',
                        'beiró': 'Villa Devoto - Bermudez 3192',
                        'beiro': 'Villa Devoto - Bermudez 3192'
                    };
                    const valorLower = valor.toLowerCase().trim();
                    if (conversiones[valorLower]) {
                        return conversiones[valorLower];
                    }
                    for (const [key, value] of Object.entries(conversiones)) {
                        if (valorLower.includes(key)) {
                            return value;
                        }
                    }
                }
                
                return valor;
            }
            
            // Función para actualizar los chips de filtros aplicados
            function actualizarChipsFiltros() {
                // Limpiar chips existentes
                filtrosAplicadosContainer.innerHTML = '';
                
                Object.keys(filtrosSeleccionados).forEach(tipo => {
                    const valor = filtrosSeleccionados[tipo];
                    if (valor) {
                        const texto = obtenerTextoFiltro(tipo, valor);
                        if (texto) {
                            const chip = document.createElement('span');
                            chip.className = 'filtro-chip';
                            
                            const textoChip = document.createElement('span');
                            textoChip.textContent = texto;
                            
                            const btnEliminar = document.createElement('span');
                            btnEliminar.className = 'filtro-chip-eliminar';
                            btnEliminar.textContent = 'X';
                            btnEliminar.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                eliminarFiltro(tipo);
                            });
                            
                            chip.appendChild(textoChip);
                            chip.appendChild(btnEliminar);
                            filtrosAplicadosContainer.appendChild(chip);
                        }
                    }
                });
            }
            
            // Función para eliminar un filtro específico
            function eliminarFiltro(tipo) {
                filtrosSeleccionados[tipo] = '';
                actualizarColoresFiltros();
                filtrarCursadas();
            }
            
            // Función para borrar todos los filtros
            function borrarTodosFiltros() {
                filtrosSeleccionados = {
                    carrera: '',
                    sede: '',
                    modalidad: '',
                    turno: '',
                    dia: ''
                };
                actualizarColoresFiltros();
                filtrarCursadas();
            }
            
            function filtrarCursadas() {
                const carreraSeleccionada = filtrosSeleccionados.carrera;
                const sedeSeleccionada = filtrosSeleccionados.sede;
                const modalidadSeleccionada = filtrosSeleccionados.modalidad;
                const turnoSeleccionado = filtrosSeleccionados.turno;
                const diaSeleccionado = filtrosSeleccionados.dia;
                
                // Re-obtener todas las cursadas del DOM (por si hay cambios)
                const todasLasCursadas = document.querySelectorAll('.cursada-item');
                
                let visibleCount = 0;
                
                todasLasCursadas.forEach(item => {
                    const carrera = item.getAttribute('data-carrera') || '';
                    const sede = item.getAttribute('data-sede') || '';
                    const modalidad = item.getAttribute('data-modalidad') || '';
                    const turno = item.getAttribute('data-turno') || '';
                    const dia = item.getAttribute('data-dia') || '';
                    
                    // Verificar si coincide con los filtros
                    // Si el filtro está vacío (''), mostrar todas las opciones
                    const coincideCarrera = carreraSeleccionada === '' || carrera === carreraSeleccionada;
                    const coincideSede = sedeSeleccionada === '' || sede === sedeSeleccionada;
                    const coincideModalidad = modalidadSeleccionada === '' || modalidad === modalidadSeleccionada;
                    const coincideTurno = turnoSeleccionado === '' || turno === turnoSeleccionado;
                    const coincideDia = diaSeleccionado === '' || dia === diaSeleccionado;
                    
                    if (coincideCarrera && coincideSede && coincideModalidad && coincideTurno && coincideDia) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Actualizar contador de resultados
                if (contadorResultados) {
                    contadorResultados.textContent = visibleCount;
                }
                
                // Actualizar chips de filtros aplicados
                actualizarChipsFiltros();
                
                // Mostrar mensaje si no hay resultados
                const container = document.getElementById('cursadas-container');
                let mensajeNoResultados = document.getElementById('mensaje-no-resultados');
                
                if (visibleCount === 0) {
                    if (!mensajeNoResultados && container) {
                        mensajeNoResultados = document.createElement('p');
                        mensajeNoResultados.id = 'mensaje-no-resultados';
                        mensajeNoResultados.textContent = 'No hay cursadas que coincidan con los filtros seleccionados.';
                        container.parentNode.insertBefore(mensajeNoResultados, container);
                    }
                    if (container) container.style.display = 'none';
                } else {
                    if (mensajeNoResultados) {
                        mensajeNoResultados.remove();
                    }
                    if (container) container.style.display = 'flex';
                }
            }
            
            // Agregar event listeners a las opciones de filtro
            opcionesFiltro.forEach(opcion => {
                opcion.addEventListener('click', function() {
                    const tipo = this.getAttribute('data-tipo');
                    const valor = this.getAttribute('data-valor');
                    
                    // Si ya está seleccionado, deseleccionarlo
                    if (filtrosSeleccionados[tipo] === valor) {
                        filtrosSeleccionados[tipo] = '';
                    } else {
                        // Si es carrera, deseleccionar todas las demás opciones de carrera primero
                        if (tipo === 'carrera') {
                            filtrosSeleccionados.carrera = valor;
                        } else {
                            // Para otros filtros, si el valor está vacío, deseleccionar
                            if (valor === '') {
                                filtrosSeleccionados[tipo] = '';
                            } else {
                                filtrosSeleccionados[tipo] = valor;
                            }
                        }
                    }
                    
                    actualizarColoresFiltros();
                    filtrarCursadas();
                });
            });
            
            // Event listener para borrar todos los filtros
            if (borrarTodoBtn) {
                borrarTodoBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    borrarTodosFiltros();
                });
            }
            
            // Inicializar colores y filtros al cargar la página
            actualizarColoresFiltros();
            filtrarCursadas();
        });
    </script>
</body>
</html>

