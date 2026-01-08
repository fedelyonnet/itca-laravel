<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Inscripción - {{ $curso->nombre }} - ITCA">
    <title>Inscripción - {{ $curso->nombre }} - ITCA</title>
    <!-- Fonts - Preconnect para mejorar velocidad -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet"></noscript>
    <!-- Styles -->
    @vite(['resources/css/public.css', 'resources/css/inscripcion-mobile.css', 'resources/js/app.js'])
    <!-- Swiper CSS - Cargar de forma no bloqueante -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>
    <!-- Slick Carousel CSS - Cargar de forma no bloqueante -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/></noscript>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/></noscript>
</head>
<body class="inscripcion-page">
    <!-- Contenedor de notificaciones -->
    <div id="notificaciones-container" class="cursada-notificaciones-container"></div>
    
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

    <!-- Modal de Filtros Mobile -->
    <div id="filtros-modal-mobile" class="filtros-modal-mobile">
        <div class="filtros-modal-overlay"></div>
        <div class="filtros-modal-content">
            <div class="filtros-modal-header">
                <button class="filtros-modal-close" id="filtros-modal-close">×</button>
            </div>
            <div class="filtros-modal-body">
                <div class="inscripcion-filtros-recuadro">
                    <!-- Filtro: Carrera -->
                    <div class="inscripcion-filtro-seccion">
                        <h4 class="inscripcion-filtro-subtitulo">
                            <img src="/images/desktop/wrench_g.png" alt="Carrera" class="inscripcion-filtro-icono">
                            <span>Carrera</span>
                        </h4>
                        <div id="filtro-carrera-opciones-modal">
                            @foreach($carreras as $carreraItem)
                                @php
                                    $nombreCarrera = $carreraItem->carrera ?? 'N/A';
                                    $nombreCorregido = corregirNombreCarrera($nombreCarrera);
                                    // Comparar con la carrera seleccionada encontrada en el controlador (comparación flexible)
                                    $esSeleccionado = false;
                                    if (isset($carreraSeleccionada) && $carreraSeleccionada && $nombreCarrera) {
                                        $nombreCarreraNormalizado = mb_strtolower(trim($nombreCarrera), 'UTF-8');
                                        $carreraSeleccionadaNormalizada = mb_strtolower(trim($carreraSeleccionada), 'UTF-8');
                                        $esSeleccionado = $nombreCarreraNormalizado === $carreraSeleccionadaNormalizada;
                                    }
                                @endphp
                                <span class="filtro-opcion" 
                                      data-tipo="carrera" 
                                      data-valor="{{ $nombreCarrera }}"
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
                        <div id="filtro-sede-opciones-modal">
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
                        <div id="filtro-modalidad-opciones-modal">
                            @foreach($modalidades as $modalidadItem)
                                @php
                                    $modalidadDisplay = $modalidadItem['combinacion'] ?? $modalidadItem;
                                    // Corregir "Sempresencial" a "Semipresencial" en el display
                                    if (is_string($modalidadDisplay) && stripos($modalidadDisplay, 'Sempresencial') !== false) {
                                        $modalidadDisplay = str_ireplace('Sempresencial', 'Semipresencial', $modalidadDisplay);
                                    } elseif (isset($modalidadItem['combinacion'])) {
                                        $modalidadDisplay = str_ireplace('Sempresencial', 'Semipresencial', $modalidadItem['combinacion']);
                                    }
                                    
                                    // Agregar duración según modalidad y régimen
                                    $modalidad = $modalidadItem['modalidad'] ?? '';
                                    $regimen = $modalidadItem['regimen'] ?? '';
                                    $duracion = '';
                                    if (stripos($modalidad, 'Presencial') !== false || stripos($modalidad, 'Semipresencial') !== false) {
                                        if (stripos($regimen, 'Regular') !== false) {
                                            $duracion = '10 Meses';
                                        } elseif (stripos($regimen, 'Intensivo') !== false) {
                                            $duracion = '5 Meses';
                                        }
                                    }
                                    
                                    // Construir el texto completo con duración
                                    if ($duracion) {
                                        $modalidadDisplay = $modalidadDisplay . ' : ' . $duracion;
                                    }
                                    
                                    $valorModalidad = isset($modalidadItem['valor']) ? $modalidadItem['valor'] : $modalidadItem;
                                @endphp
                                <span class="filtro-opcion" 
                                      data-tipo="modalidad" 
                                      data-valor="{{ $valorModalidad }}"
                                      data-modalidad="{{ $modalidadItem['modalidad'] ?? '' }}"
                                      data-regimen="{{ $modalidadItem['regimen'] ?? '' }}"
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
                        <div id="filtro-turno-opciones-modal">
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
                        <div id="filtro-dia-opciones-modal">
                            @foreach($dias as $dia)
                                @php
                                    // Convertir a nombres completos usando la función helper
                                    $diaDisplay = convertirDiasCompletos($dia);
                                @endphp
                                <span class="filtro-opcion" 
                                      data-tipo="dia" 
                                      data-valor="{{ $dia }}"
                                      data-seleccionado="false">
                                    {{ $diaDisplay }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Filtro: Promociones -->
                    <div class="inscripcion-filtro-seccion">
                        <h4 class="inscripcion-filtro-subtitulo">
                            <img src="/images/desktop/fire.png" alt="Promociones" class="inscripcion-filtro-icono">
                            <span>Promociones</span>
                        </h4>
                        <div id="filtro-promocion-opciones-modal">
                            <span class="filtro-opcion" 
                                  data-tipo="promocion" 
                                  data-valor="con_descuento"
                                  data-seleccionado="false">
                                Cuotas con descuento
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Línea separadora y acciones -->
                <div class="filtros-modal-acciones">
                    <div class="filtros-modal-linea"></div>
                    <div class="filtros-modal-acciones-container">
                        <a href="#" id="limpiar-filtros-modal" class="filtros-modal-limpiar">Limpiar filtros</a>
                        <button type="button" id="ver-resultados-modal" class="filtros-modal-ver-resultados">Ver <span id="contador-resultados-modal">0</span> resultados</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <span class="inscripcion-mobile-subtitle">Aplicá los filtros para descubrir la cursada ideal para vos</span>
                    </h2>
                </div>
            </div>
        </section>

        <!-- Inscripción Section -->
        <section class="inscripcion-section">
            <!-- Contenedor sticky para divider y chips - debe estar en la misma sección que el listado -->
            <div class="inscripcion-mobile-sticky-wrapper">
                <div class="inscripcion-mobile-divider">
                    <div class="inscripcion-mobile-divider-left">
                        <span class="inscripcion-mobile-ordenar">
                            Ordenar por <span class="inscripcion-mobile-ordenar-destacado">Mayor descuento</span> <span class="inscripcion-mobile-ordenar-chevron">▼</span>
                        </span>
                    </div>
                    <div class="inscripcion-mobile-divider-right">
                        <span class="inscripcion-mobile-filtros-dropdown">
                            Filtros (<span id="contador-filtros-mobile">0</span>) <span class="inscripcion-mobile-filtros-chevron">▼</span>
                        </span>
                    </div>
                </div>
                <!-- Chips de filtros aplicados en mobile -->
                <div id="filtros-aplicados-mobile" class="inscripcion-filtros-chips-mobile">
                    <!-- Los chips se agregarán dinámicamente con JavaScript -->
                </div>
            </div>
            <div class="inscripcion-container">
                <div class="inscripcion-content">
                    <!-- Línea horizontal superior: Aplicá los filtros, Borrar todo, Resultados, Chips -->
                    <div class="inscripcion-filtros-bar">
                        <div class="inscripcion-filtros-bar-columna-izq">
                            <div class="inscripcion-filtros-bar-texto-wrapper">
                                <div class="inscripcion-filtros-bar-texto-linea-1">
                                    <span class="inscripcion-filtros-bar-texto-highlight">Aplicá los filtros</span> para descubrir
                                </div>
                                <div class="inscripcion-filtros-bar-texto-linea-2">
                                    la cursada ideal para vos:
                                </div>
                            </div>
                        </div>
                        <div class="inscripcion-filtros-bar-columna-der">
                            <div class="inscripcion-filtros-bar-der-wrapper">
                                <div class="inscripcion-filtros-borrar-resultados-grupo">
                                    <a href="#" id="borrar-todo-filtros" class="inscripcion-filtros-borrar-todo">Borrar todo</a>
                                    <span class="inscripcion-filtros-resultados">Resultados: <span id="contador-resultados">0</span></span>
                                </div>
                                <div id="filtros-aplicados" class="inscripcion-filtros-chips">
                                    <!-- Los chips se agregarán dinámicamente con JavaScript -->
                                </div>
                            </div>
                            <div class="inscripcion-filtros-ordenar">
                                Ordenar por <span class="inscripcion-filtros-ordenar-destacado">Mayor descuento</span> <span class="inscripcion-filtros-ordenar-chevron">▼</span>
                            </div>
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
                                        @foreach($carreras as $carreraItem)
                                            @php
                                                $nombreCarrera = $carreraItem->carrera ?? 'N/A';
                                                $nombreCorregido = corregirNombreCarrera($nombreCarrera);
                                                // Comparar con la carrera seleccionada encontrada en el controlador (comparación flexible)
                                                $esSeleccionado = false;
                                                if (isset($carreraSeleccionada) && $carreraSeleccionada && $nombreCarrera) {
                                                    $nombreCarreraNormalizado = mb_strtolower(trim($nombreCarrera), 'UTF-8');
                                                    $carreraSeleccionadaNormalizada = mb_strtolower(trim($carreraSeleccionada), 'UTF-8');
                                                    $esSeleccionado = $nombreCarreraNormalizado === $carreraSeleccionadaNormalizada;
                                                }
                                            @endphp
                                            <span class="filtro-opcion" 
                                                  data-tipo="carrera" 
                                                  data-valor="{{ $nombreCarrera }}"
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
                                        @foreach($modalidades as $modalidadItem)
                                            @php
                                                $modalidadDisplay = $modalidadItem['combinacion'] ?? $modalidadItem;
                                                // Corregir "Sempresencial" a "Semipresencial" en el display
                                                if (is_string($modalidadDisplay) && stripos($modalidadDisplay, 'Sempresencial') !== false) {
                                                    $modalidadDisplay = str_ireplace('Sempresencial', 'Semipresencial', $modalidadDisplay);
                                                } elseif (isset($modalidadItem['combinacion'])) {
                                                    $modalidadDisplay = str_ireplace('Sempresencial', 'Semipresencial', $modalidadItem['combinacion']);
                                                }
                                                
                                                // Agregar duración según modalidad y régimen
                                                $modalidad = $modalidadItem['modalidad'] ?? '';
                                                $regimen = $modalidadItem['regimen'] ?? '';
                                                $duracion = '';
                                                if (stripos($modalidad, 'Presencial') !== false || stripos($modalidad, 'Semipresencial') !== false) {
                                                    if (stripos($regimen, 'Regular') !== false) {
                                                        $duracion = '10 Meses';
                                                    } elseif (stripos($regimen, 'Intensivo') !== false) {
                                                        $duracion = '5 Meses';
                                                    }
                                                }
                                                
                                                // Construir el texto completo con duración
                                                if ($duracion) {
                                                    $modalidadDisplay = $modalidadDisplay . ' : ' . $duracion;
                                                }
                                                
                                                $valorModalidad = isset($modalidadItem['valor']) ? $modalidadItem['valor'] : $modalidadItem;
                                            @endphp
                                            <span class="filtro-opcion" 
                                                  data-tipo="modalidad" 
                                                  data-valor="{{ $valorModalidad }}"
                                                  data-modalidad="{{ $modalidadItem['modalidad'] ?? '' }}"
                                                  data-regimen="{{ $modalidadItem['regimen'] ?? '' }}"
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
                                            @php
                                                // Convertir a nombres completos usando la función helper
                                                $diaDisplay = convertirDiasCompletos($dia);
                                            @endphp
                                            <span class="filtro-opcion" 
                                                  data-tipo="dia" 
                                                  data-valor="{{ $dia }}"
                                                  data-seleccionado="false">
                                                {{ $diaDisplay }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Filtro: Promociones -->
                                <div class="inscripcion-filtro-seccion">
                                    <h4 class="inscripcion-filtro-subtitulo">
                                        <img src="/images/desktop/fire.png" alt="Promociones" class="inscripcion-filtro-icono">
                                        <span>Promociones</span>
                                    </h4>
                                    <div id="filtro-promocion-opciones">
                                        <span class="filtro-opcion" 
                                              data-tipo="promocion" 
                                              data-valor="con_descuento"
                                              data-seleccionado="false">
                                            Cuotas con descuento
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Panel derecho: Listado de cursadas -->
                        <div class="inscripcion-listado-panel">
                            <div id="cursadas-container">
                                <!-- Las cursadas se cargarán vía AJAX aquí -->
                                <div id="cursadas-loading">
                                    Cargando cursadas...
                                </div>
                            </div>
                            <!-- Template para renderizar cursadas (oculto) -->
                            <template id="cursada-template">
                                @php
                                    // Usar una cursada de ejemplo para el template
                                    $cursadaEjemplo = (object)[
                                        'id' => 'TEMPLATE_ID',
                                        'carrera' => 'TEMPLATE_CARRERA',
                                        'sede' => 'TEMPLATE_SEDE',
                                        'xModalidad' => 'TEMPLATE_MODALIDAD',
                                        'Régimen' => 'TEMPLATE_REGIMEN',
                                        'xTurno' => 'TEMPLATE_TURNO',
                                        'xDias' => 'TEMPLATE_DIAS',
                                        'Fecha_Inicio' => null,
                                        'Horario' => 'TEMPLATE_HORARIO',
                                        'Vacantes' => 0,
                                        'Matric_Base' => 0,
                                        'Cta_Web' => 0,
                                        'Sin_IVA_cta' => 0,
                                        'Dto_Cuota' => 0,
                                        'cuotas' => 12,
                                        'Promo_Mat_logo' => '',
                                        'pre_calculado' => [
                                            'vacantes' => 0,
                                            'sinVacantes' => true,
                                            'tieneDescuento' => false,
                                            'dtoCuotaValue' => 0,
                                            'diaCompleto' => 'TEMPLATE_DIA',
                                            'diaMayusculas' => 'TEMPLATE_DIA',
                                            'turno' => 'TEMPLATE_TURNO',
                                            'turnoMayusculas' => 'TEMPLATE_TURNO',
                                            'horarioFormateado' => 'TEMPLATE_HORARIO',
                                            'fechaFormateada' => 'N/A',
                                            'mesNombre' => '',
                                            'modalidadCompleta' => 'TEMPLATE_MODALIDAD',
                                            'sedeCompleta' => 'TEMPLATE_SEDE',
                                            'sedeSimplificada' => 'TEMPLATE_SEDE',
                                        ]
                                    ];
                                    $cursada = $cursadaEjemplo;
                                    $cursadaId = 'TEMPLATE_ID';
                                    $pre = $cursada->pre_calculado;
                                @endphp
                                <div class="cursada-item" 
                                     data-carrera="{{ $cursada->carrera }}"
                                     data-sede="{{ $cursada->sede }}"
                                     data-modalidad="{{ $cursada->xModalidad }}"
                                     data-regimen="{{ $cursada->Régimen }}"
                                     data-turno="{{ $cursada->xTurno }}"
                                     data-dia="{{ $cursada->xDias }}"
                                     data-promocion="{{ $pre['tieneDescuento'] ? 'con_descuento' : 'sin_descuento' }}">
                                    <div class="cursada-item-grid">
                                        <!-- Desktop: Estructura original con columnas -->
                                        <div class="cursada-item-columna-izq">
                                            <!-- Nuevo Item 0: DIA | TURNO x (horario) -->
                                            <div class="cursada-item-dia-turno">
                                                <span class="cursada-dia-turno-texto">{{ $pre['diaMayusculas'] }}</span>
                                                <span class="cursada-dia-turno-separador">|</span>
                                                <span class="cursada-dia-turno-texto">TURNO {{ $pre['turnoMayusculas'] }}</span>
                                                @if(!empty($pre['horarioFormateado']))
                                                    <span class="cursada-dia-turno-horario"> ({{ $pre['horarioFormateado'] }})</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Item 1: Inicio (mes año) -->
                                            <div>
                                                <strong class="cursada-item-label">Inicia:</strong>
                                                <span class="cursada-item-value">{{ $pre['fechaFormateada'] }}</span>
                                            </div>
                                            
                                            <!-- Item 2: Modalidad -->
                                            <div>
                                                <strong class="cursada-item-label">Modalidad:</strong>
                                                <span class="cursada-item-value">{{ $pre['modalidadCompleta'] }}</span>
                                            </div>
                                            
                                            <!-- Item 4: Sede -->
                                            <div>
                                                <strong class="cursada-item-label">Sede:</strong>
                                                <span class="cursada-item-value">{{ $pre['sedeSimplificada'] }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="cursada-item-columna-medio">
                                            <div class="cursada-lugares-texto">
                                                ¡Quedan <strong>{{ $cursada->Vacantes }}</strong> lugares!
                                            </div>
                                            <!-- Wrapper siempre presente para que JavaScript pueda mostrarlo/ocultarlo -->
                                            <div class="cursada-descuento-wrapper" style="display: none;">
                                                <div class="cursada-badge-descuento">
                                                    {{ number_format(abs($pre['dtoCuotaValue'] ?? 0), 0) }}% OFF
                                                </div>
                                                <div class="cursada-texto-cuotas">
                                                    en todas las cuotas
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="cursada-item-columna-der">
                                            <!-- Badge siempre presente en template para que JavaScript pueda mostrarlo/ocultarlo -->
                                            <img src="" alt="Promo Mat Logo" class="cursada-promo-badge">
                                            <button class="cursada-btn-ver-valores" 
                                                    data-cursada-id="{{ $cursadaId }}">Ver valores</button>
                                        </div>
                                        
                                        <!-- Mobile: Nueva estructura con 3 filas -->
                                        <!-- FILA 1: Día/Turno -->
                                        <div class="cursada-item-fila-1">
                                            <div class="cursada-item-dia-turno">
                                                <span class="cursada-dia-turno-texto">{{ $pre['diaMayusculas'] }}</span>
                                                <span class="cursada-dia-turno-separador">|</span>
                                                <span class="cursada-dia-turno-texto">TURNO {{ $pre['turnoMayusculas'] }}</span>
                                                @if(!empty($pre['horarioFormateado']))
                                                    <span class="cursada-dia-turno-horario"> ({{ $pre['horarioFormateado'] }})</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- FILA 2: Información (izq) + Badge Promo (der) -->
                                        <div class="cursada-item-fila-2">
                                            <div class="cursada-item-fila-2-izq">
                                                <div class="cursada-item-fila-2-item">
                                                    <span class="cursada-item-fila-2-label">Inicia:</span>
                                                    <span class="cursada-item-fila-2-value">{{ $pre['fechaFormateada'] }}</span>
                                                </div>
                                                <div class="cursada-item-fila-2-item">
                                                    <span class="cursada-item-fila-2-label">Modalidad:</span>
                                                    <span class="cursada-item-fila-2-value">{{ $pre['modalidadCompleta'] }}</span>
                                                </div>
                                                <div class="cursada-item-fila-2-item">
                                                    <span class="cursada-item-fila-2-label">Sede:</span>
                                                    <span class="cursada-item-fila-2-value">{{ $pre['sedeSimplificada'] }}</span>
                                                </div>
                                            </div>
                                            <div class="cursada-item-fila-2-der">
                                                <img src="" alt="Promo Mat Logo" class="cursada-promo-badge">
                                            </div>
                                        </div>
                                        
                                        <!-- FILA 3: Descuento/Lugares (izq) + Botón (der) -->
                                        <div class="cursada-item-fila-3">
                                            <div class="cursada-item-fila-3-izq">
                                                <div class="cursada-descuento-wrapper" style="display: none;">
                                                    <div class="cursada-badge-descuento">
                                                        {{ number_format(abs($pre['dtoCuotaValue'] ?? 0), 0) }}% OFF
                                                    </div>
                                                    <div class="cursada-texto-cuotas">
                                                        en todas las cuotas
                                                    </div>
                                                </div>
                                                <div class="cursada-lugares-texto">
                                                    ¡Quedan <strong>{{ $cursada->Vacantes }}</strong> lugares!
                                                </div>
                                            </div>
                                            <div class="cursada-item-fila-3-der">
                                                <button class="cursada-btn-ver-valores" 
                                                        data-cursada-id="{{ $cursadaId }}">Ver valores</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Panel de valores (oculto por defecto) -->
                                    <div class="cursada-valores-panel panel-hidden" id="panel-{{ $cursadaId }}" data-promo-mat-logo="{{ $cursada->Promo_Mat_logo ?? '' }}">
                                        <div class="cursada-valores-grid">
                                            <div class="cursada-valores-columna-izq">
                                                <div class="cursada-formulario-container">
                                                    <div class="cursada-formulario-titulo">
                                                        <img src="/images/desktop/student-v.png" alt="Student" class="cursada-formulario-icono">
                                                        <span class="cursada-formulario-texto">Completá <span class="cursada-formulario-texto-semibold">tus datos</span> para poder ver los valores:</span>
                                                    </div>
                                                    <form class="cursada-formulario" id="formulario-{{ $cursadaId }}">
                                                        <div class="cursada-formulario-grid">
                                                            <div class="cursada-formulario-columna-izq">
                                                                <div class="cursada-formulario-campo">
                                                                    <input type="text" name="nombre" id="nombre-{{ $cursadaId }}" placeholder="Nombre *" class="cursada-formulario-input" tabindex="1">
                                                                    <span class="cursada-formulario-error" id="error-nombre-{{ $cursadaId }}"></span>
                                                                </div>
                                                                <div class="cursada-formulario-campo">
                                                                    <input type="text" name="dni" id="dni-{{ $cursadaId }}" placeholder="DNI *" class="cursada-formulario-input" maxlength="8" pattern="[0-9]{7,8}" inputmode="numeric" tabindex="3">
                                                                    <span class="cursada-formulario-error" id="error-dni-{{ $cursadaId }}"></span>
                                                                </div>
                                                                <div class="cursada-formulario-campo cursada-formulario-campo-telefono">
                                                                    <div class="cursada-telefono-wrapper">
                                                                        <div class="cursada-telefono-prefijo-container">
                                                                            <select name="telefono_prefijo" id="telefono-prefijo-{{ $cursadaId }}" class="cursada-telefono-prefijo" tabindex="5">
                                                                                <option value="+54" selected>+54</option>
                                                                                <option value="+1">+1</option>
                                                                                <option value="+52">+52</option>
                                                                                <option value="+55">+55</option>
                                                                                <option value="+34">+34</option>
                                                                                <option value="+33">+33</option>
                                                                                <option value="+39">+39</option>
                                                                                <option value="+49">+49</option>
                                                                                <option value="+44">+44</option>
                                                                                <option value="+7">+7</option>
                                                                                <option value="+86">+86</option>
                                                                                <option value="+81">+81</option>
                                                                                <option value="+82">+82</option>
                                                                                <option value="+91">+91</option>
                                                                                <option value="+61">+61</option>
                                                                                <option value="+64">+64</option>
                                                                                <option value="+27">+27</option>
                                                                                <option value="+20">+20</option>
                                                                                <option value="+971">+971</option>
                                                                                <option value="+966">+966</option>
                                                                                <option value="+90">+90</option>
                                                                                <option value="+31">+31</option>
                                                                                <option value="+32">+32</option>
                                                                                <option value="+41">+41</option>
                                                                                <option value="+43">+43</option>
                                                                                <option value="+46">+46</option>
                                                                                <option value="+47">+47</option>
                                                                                <option value="+45">+45</option>
                                                                                <option value="+358">+358</option>
                                                                                <option value="+351">+351</option>
                                                                                <option value="+353">+353</option>
                                                                                <option value="+30">+30</option>
                                                                                <option value="+48">+48</option>
                                                                                <option value="+420">+420</option>
                                                                                <option value="+36">+36</option>
                                                                                <option value="+40">+40</option>
                                                                                <option value="+56">+56</option>
                                                                                <option value="+57">+57</option>
                                                                                <option value="+51">+51</option>
                                                                                <option value="+58">+58</option>
                                                                                <option value="+593">+593</option>
                                                                                <option value="+595">+595</option>
                                                                                <option value="+598">+598</option>
                                                                                <option value="+591">+591</option>
                                                                                <option value="+506">+506</option>
                                                                                <option value="+507">+507</option>
                                                                                <option value="+502">+502</option>
                                                                                <option value="+504">+504</option>
                                                                                <option value="+505">+505</option>
                                                                                <option value="+503">+503</option>
                                                                                <option value="+501">+501</option>
                                                                                <option value="+592">+592</option>
                                                                                <option value="+597">+597</option>
                                                                                <option value="+594">+594</option>
                                                                                <option value="+596">+596</option>
                                                                                <option value="+1-242">+1-242</option>
                                                                                <option value="+1-246">+1-246</option>
                                                                                <option value="+1-264">+1-264</option>
                                                                                <option value="+1-268">+1-268</option>
                                                                                <option value="+1-284">+1-284</option>
                                                                                <option value="+1-340">+1-340</option>
                                                                                <option value="+1-345">+1-345</option>
                                                                                <option value="+1-441">+1-441</option>
                                                                                <option value="+1-473">+1-473</option>
                                                                                <option value="+1-649">+1-649</option>
                                                                                <option value="+1-670">+1-670</option>
                                                                                <option value="+1-671">+1-671</option>
                                                                                <option value="+1-758">+1-758</option>
                                                                                <option value="+1-767">+1-767</option>
                                                                                <option value="+1-784">+1-784</option>
                                                                                <option value="+1-849">+1-849</option>
                                                                                <option value="+1-868">+1-868</option>
                                                                                <option value="+1-869">+1-869</option>
                                                                                <option value="+1-876">+1-876</option>
                                                                            </select>
                                                                            <span class="cursada-telefono-chevron">▼</span>
                                                                        </div>
                                                                        <input type="tel" name="telefono" id="telefono-{{ $cursadaId }}" placeholder="Teléfono *" class="cursada-formulario-input cursada-telefono-input" maxlength="14" pattern="[0-9]{8,14}" inputmode="numeric" tabindex="6">
                                                                    </div>
                                                                    <span class="cursada-formulario-error" id="error-telefono-{{ $cursadaId }}"></span>
                                                                </div>
                                                            </div>
                                                            <div class="cursada-formulario-columna-der">
                                                                <div class="cursada-formulario-campo">
                                                                    <input type="text" name="apellido" id="apellido-{{ $cursadaId }}" placeholder="Apellido *" class="cursada-formulario-input" tabindex="2">
                                                                    <span class="cursada-formulario-error" id="error-apellido-{{ $cursadaId }}"></span>
                                                                </div>
                                                                <div class="cursada-formulario-campo">
                                                                    <input type="email" name="correo" id="correo-{{ $cursadaId }}" placeholder="Correo electrónico *" class="cursada-formulario-input" tabindex="4">
                                                                    <span class="cursada-formulario-error" id="error-correo-{{ $cursadaId }}"></span>
                                                                </div>
                                                                <div class="cursada-formulario-boton-continuar-container">
                                                                    <img src="/images/mobile/arrow-insc.png" alt="Arrow" class="cursada-arrow-insc">
                                                                    <button type="button" class="cursada-btn-continuar" data-cursada-id="{{ $cursadaId }}">Continuar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    
                                                    <!-- Sección de información de cuota (oculta inicialmente) -->
                                                    <div class="cursada-cuota-info" id="cuota-info-{{ $cursadaId }}" data-cta-web="{{ $cursada->Cta_Web }}" data-dto-cuota="{{ $cursada->Dto_Cuota }}" data-cuotas="{{ $cursada->cuotas }}" data-matric-base="{{ $cursada->Matric_Base }}" data-sin-iva-mat="{{ $cursada->Sin_iva_Mat ?? 0 }}">
                                                        <p class="cursada-cuota-linea-1">
                                                            El valor de la cuota por mes te saldrá: <span class="cursada-cuota-valor">${{ number_format($cursada->Cta_Web, 2, ',', '.') }}<span class="cursada-cuota-asterisco">*</span></span>
                                                        </p>
                                                        @php
                                                            $dtoCuotaValue = 0;
                                                            if (!empty($cursada->Dto_Cuota) && $cursada->Dto_Cuota != null && floatval($cursada->Dto_Cuota) != 0) {
                                                                $dtoCuotaValue = abs(floatval($cursada->Dto_Cuota));
                                                            }
                                                            $cantidadCuotas = $cursada->cuotas ?? 12;
                                                        @endphp
                                                        <p class="cursada-cuota-linea-2">
                                                            @if($dtoCuotaValue > 0)
                                                                <span class="cursada-cuota-descuento">¡Descuento del {{ number_format($dtoCuotaValue, 0) }}% Aplicado! - </span>
                                                            @endif
                                                            <span class="cursada-cuota-cantidad-label">Cantidad de cuotas:</span> <span class="cursada-cuota-cantidad-valor">{{ $cantidadCuotas }}</span>
                                                        </p>
                                                        <p class="cursada-cuota-linea-3">
                                                            Valor cuota sin impuestos nacionales: <span class="cursada-cuota-precio-total">${{ number_format($cursada->Sin_IVA_cta, 2, ',', '.') }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="cursada-valores-separador"></div>
                                            
                                            <div class="cursada-valores-columna-der">
                                                <div class="cursada-valores-info-wrapper">
                                                    <div class="cursada-valores-renglon-1">
                                                        <div class="cursada-valores-matricula-wrapper">
                                                            <div class="cursada-valor-matricula">
                                                                Valor de matrícula: <strong class="cursada-valor-matricula-valor" id="valor-matricula-{{ $cursadaId }}">n/d</strong>
                                                            </div>
                                                            <div class="cursada-precio-total-matricula">
                                                                Valor matrícula sin impuestos nacionales: <span class="cursada-precio-total-matricula-valor" id="precio-total-matricula-{{ $cursadaId }}">n/d</span>
                                                            </div>
                                                        </div>
                                                        <div class="cursada-codigo-descuento-wrapper">
                                                            <a href="#" class="cursada-link-codigo-descuento cursada-link-disabled" data-cursada-id="{{ $cursadaId }}" id="link-codigo-{{ $cursadaId }}">¡Tengo Código de descuento!</a>
                                                            <div class="cursada-codigo-descuento-input-container" id="codigo-input-{{ $cursadaId }}">
                                                                <input type="text" class="cursada-codigo-descuento-input" placeholder="Ingresá tu código" data-cursada-id="{{ $cursadaId }}" id="codigo-input-field-{{ $cursadaId }}">
                                                                <button type="button" class="cursada-codigo-descuento-btn" data-cursada-id="{{ $cursadaId }}">Aplicar</button>
                                                            </div>
                                                        </div>
                                                        <div class="cursada-descuento-aplicado" id="descuento-aplicado-{{ $cursadaId }}">
                                                            <img src="" alt="Promo Mat Logo" class="cursada-promo-badge-descuento" id="promo-badge-descuento-TEMPLATE_ID">
                                                            Descuento: <strong class="cursada-descuento-valor" id="descuento-valor-{{ $cursadaId }}">n/d</strong>
                                                        </div>
                                                    </div>
                                                    <div class="cursada-total-aplicado" id="total-aplicado-{{ $cursadaId }}">
                                                        Total: <strong class="cursada-total-valor" id="total-valor-{{ $cursadaId }}">n/d</strong>
                                                    </div>
                                                    <div class="cursada-texto-pago-matricula" id="texto-pago-matricula-{{ $cursadaId }}">
                                                        <div class="cursada-texto-pago-linea-1">Ahora <strong>sólo debés pagar la matrícula</strong></div>
                                                        <div class="cursada-texto-pago-linea-2">para poder reservar tu vacante</div>
                                                    </div>
                                                    <div class="cursada-checkbox-terminos-container" id="checkbox-terminos-{{ $cursadaId }}">
                                                        <input type="checkbox" id="acepto-terminos-{{ $cursadaId }}" class="cursada-checkbox-terminos-input" data-cursada-id="{{ $cursadaId }}" disabled>
                                                        <label for="acepto-terminos-{{ $cursadaId }}" class="cursada-checkbox-terminos-label cursada-checkbox-disabled">
                                                            <span class="cursada-checkbox-terminos-custom"></span>
                                                            <span class="cursada-checkbox-terminos-texto">Acepto Términos y Condiciones <a href="/terminos.pdf" target="_blank" class="cursada-terminos-link-ver cursada-link-disabled" id="link-ver-{{ $cursadaId }}">(ver)</a></span>
                                                        </label>
                                                    </div>
                                                    <button type="button" class="cursada-btn-reservar" data-cursada-id="{{ $cursadaId }}" disabled>Ir a pagar matrícula</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Texto informativo entre panel y línea del item -->
                                    <div class="cursada-info-texto panel-hidden" id="info-{{ $cursadaId }}">
                                        <p class="cursada-info-texto-content">
                                            <span class="cursada-info-texto-destacado">*Valor de cuota actual, vigente hasta el fin del presente mes.</span> Los valores de cuotas se ajustan y actualizan mes a mes según IPC publicado por el INDEC. Cuotas totales a abonar en el 1er año: <span class="cursada-cuotas-totales">{{ $cursada->cuotas ?? 12 }}</span>. Consultar por promociones y descuentos aplicables sobre adelantamientos de cuotas.
                                        </p>
                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            </div>
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
                        
                        <!-- Redes Sociales (visible solo en desktop) -->
                        <h4 class="contacto-redes-title contacto-redes-desktop">Redes Sociales</h4>
                        <div class="contacto-redes-icons contacto-redes-desktop">
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

    <!-- Scripts - Cargar de forma asíncrona para no bloquear el renderizado -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <!-- Slick Carousel JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js" defer></script>
    
    <!-- JavaScript para desplegable de sedes y filtrado de cursadas -->
    <script>
        // Configuración global para el JavaScript de inscripción
        window.inscripcionConfig = {
            cursoId: {{ $curso->id }},
            buscarDescuentoUrl: '{{ route("buscar.descuento") }}',
            csrfToken: '{{ csrf_token() }}',
            leadsStoreUrl: '{{ route("leads.store") }}'
        };
    </script>
    @vite('resources/js/inscripcion.js')
    
    <!-- Modal para mobile (oculto por defecto) -->
    <div class="cursada-modal-overlay" id="cursada-modal-overlay">
        <div class="cursada-modal-container">
            <div class="cursada-modal-header">
                <div class="cursada-modal-header-content" id="cursada-modal-header-content"></div>
                <button class="cursada-modal-close" id="cursada-modal-close">×</button>
            </div>
            <div class="cursada-modal-body" id="cursada-modal-body">
                <!-- El contenido del panel se copiará aquí -->
            </div>
        </div>
    </div>
</body>
</html>

