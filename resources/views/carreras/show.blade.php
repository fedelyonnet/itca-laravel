<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="{{ $curso->nombre }} - ITCA">
    <title>{{ $curso->nombre }} - ITCA</title>
    <!-- Fonts (match home and carreras) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">
    @vite(['resources/css/public.css', 'resources/js/app.js'])
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <!-- Logo -->
                <a href="/" class="logo">ITCA</a>
                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li><a href="/" class="nav-link">Somos ITCA</a></li>
                    <li><a href="/carreras" class="nav-link">Carreras</a></li>
                    <li><a href="/" class="nav-link">Beneficios</a></li>
                    <li><a href="/" class="nav-link">Contacto</a></li>
                </ul>
                <!-- Mobile Hamburger Button -->
                <button class="hamburger"><span></span><span></span><span></span></button>
            </nav>
        </div>
    </header>

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
                        <span class="lista-carreras-breadcrumb-current carrera-show-breadcrumb-current">{{ $curso->nombre }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido de la carrera (estilos propios del show) -->
        <section class="carrera-show-section">
            <div class="lista-carreras-container carrera-show-body-container">
                <div class="carrera-container carrera-show-container carrera-{{ Str::slug($curso->nombre) }}">
                    <div class="carrera-left carrera-show-left">
                        <div class="carrera-title carrera-show-title">
                            <h3>
                                <div class="carrera-title-line1 carrera-show-title-line1">Carrera de</div>
                                <div class="carrera-title-line2 carrera-show-title-line2">
                                    @php
                                        $palabras = explode(' ', $curso->nombre);
                                        $ultimaPalabra = array_pop($palabras);
                                        $resto = implode(' ', $palabras);
                                    @endphp
                                    {{ $resto }} <span class="carrera-title-highlight carrera-show-title-highlight">{{ $ultimaPalabra }}</span>
                                </div>
                            </h3>
                        </div>
                        <div class="carrera-text carrera-show-text">
                            <p>{{ $curso->descripcion }}</p>
                        </div>
                        <div class="carrera-buttons carrera-show-buttons">
                            <img src="/images/desktop/arrow.png" alt="Flecha" class="carrera-btn-arrow carrera-show-btn-arrow">
                            <a href="#contacto" class="carrera-show-cta">
                                <span class="carrera-show-cta-line1">Aprovechá esta oportunidad</span>
                                <span class="carrera-show-cta-line2">y asegurá tu lugar</span>
                            </a>
                            <button class="carrera-btn-inscribir carrera-show-btn-inscribir">¡Inscribirme ahora!</button>
                        </div>
                    </div>
                    <div class="carrera-right carrera-show-right">
                        <div class="carrera-image carrera-show-image">
                            @if($curso->ilustracion_desktop)
                                <img src="{{ asset('storage/' . $curso->ilustracion_desktop) }}" alt="{{ $curso->nombre }}" class="carreras-image-desktop carrera-show-image-desktop" />
                            @endif
                            @if($curso->ilustracion_mobile)
                                <img src="{{ asset('storage/' . $curso->ilustracion_mobile) }}" alt="{{ $curso->nombre }}" class="carreras-image-mobile carrera-show-image-mobile" />
                            @endif
                            <div class="carreras-modalidad-badge carreras-modalidad-badge-desktop carrera-show-modalidad-badge">
                                <span class="carreras-modalidad-text carrera-show-modalidad-text">Modalidad: <strong>
                                    @if($curso->modalidad_online && $curso->modalidad_presencial)
                                        Presencial / Online
                                    @elseif($curso->modalidad_presencial)
                                        Presencial
                                    @else
                                        Online
                                    @endif
                                </strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Requisitos Section (show-only) -->
        <section class="requisitos-section">
            <div class="requisitos-container">
                <div class="requisitos-row">
                    <div class="requisitos-left">
                        <div class="requisitos-title-wrapper">
                            <h3 class="requisitos-title"><span class="requisitos-title-text">Requisitos</span></h3>
                        </div>
                        <div class="requisitos-content">
                            <div class="requisitos-card">
                                <div class="requisitos-icons">
                                    <img src="/images/desktop/hat.png" alt="Secundario" class="requisito-icon" />
                                    <img src="/images/desktop/dni.png" alt="DNI" class="requisito-icon" />
                                    <img src="/images/desktop/cruz.png" alt="Edad mínima" class="requisito-icon" />
                                    <img src="/images/desktop/tick.png" alt="Conocimientos" class="requisito-icon" />
                                </div>
                                <div class="requisitos-texts">
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Secundario</div>
                                        <div class="requisito-line2">No se requiere</div>
                                    </div>
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Presentar</div>
                                        <div class="requisito-line2">DNI</div>
                                    </div>
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Mayor de</div>
                                        <div class="requisito-line2">16 años</div>
                                    </div>
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Conocimientos</div>
                                        <div class="requisito-line2">no se requieren</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="requisitos-right">
                        <div class="requisitos-subtitle-wrapper">
                            <h3 class="requisitos-subtitle"><span class="requisitos-subtitle-text">Podés cursar en zona</span></h3>
                        </div>
                        <div class="requisitos-cursar">
                            <div class="requisitos-cursar-card">
                                <div class="cursar-grid">
                                    <div class="cursar-item">
                                        <span class="cursar-label">SUR</span>
                                        <div class="cursar-hover"><span>Banfield</span></div>
                                    </div>
                                    <div class="cursar-item">
                                        <span class="cursar-label">NORTE</span>
                                        <div class="cursar-hover"><span>San Isidro</span></div>
                                    </div>
                                    <div class="cursar-item">
                                        <span class="cursar-label">CABA</span>
                                        <div class="cursar-hover cursar-hover-multi">
                                            <span>Villa Devoto</span>
                                            <span>Villa Urquiza</span>
                                        </div>
                                    </div>
                                    <div class="cursar-item">
                                        <span class="cursar-label">OESTE</span>
                                        <div class="cursar-hover"><span>Morón</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Modalidades -->
        <section class="modalidades-section">
            <div class="modalidades-single">
                <h3 class="modalidades-title"><span class="modalidades-title-text">Modalidades</span></h3>
                <div class="modalidades-dropdown modalidades-dropdown-presencial">
                    <div class="modalidades-dropdown-grid">
                        <!-- Fila 1: Header -->
                        <div class="modalidad-header-row modalidad-header-row-presencial">
                            <div class="modalidad-left">
                                <div class="modalidad-label">PRESENCIAL</div>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/clock.png" alt="Duración" class="modalidad-icon">
                                <span class="modalidad-icon-text">Duración</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/gear.png" alt="Dedicación" class="modalidad-icon">
                                <span class="modalidad-icon-text">Dedicación</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/student.png" alt="Clases" class="modalidad-icon">
                                <span class="modalidad-icon-text">Clases</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/wrench.png" alt="Teoría y Práctica" class="modalidad-icon">
                                <span class="modalidad-icon-text">Teoría y Práctica</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/calendar.png" alt="Mes de Inicio" class="modalidad-icon">
                                <span class="modalidad-icon-text">Mes de Inicio</span>
                            </div>
                            <div class="modalidad-right">
                                <img src="/images/desktop/chevron.png" alt="Chevron" class="modalidad-chevron" id="modalidad-chevron">
                            </div>
                        </div>
                        <!-- Fila 2: REGULAR -->
                        <div class="modalidad-content-row">
                                <div class="modalidad-label-cell">
                                    <span class="modalidad-content-text">REGULAR</span>
                                </div>
                                <div class="modalidad-data-cell">
                                    <span class="modalidad-left-text">10 meses</span>
                                </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">3hs y media</span>
                                <span class="modalidad-left-text">cada clase</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">1 x semana</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">110hs</span>
                                <span class="modalidad-left-text">presenciales</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">Marzo</span>
                            </div>
                        </div>
                        <!-- Fila 3: INTENSIVO -->
                        <div class="modalidad-content-row modalidad-content-row-presencial">
                                <div class="modalidad-label-cell">
                                    <span class="modalidad-content-text">INTENSIVO</span>
                                </div>
                                <div class="modalidad-data-cell">
                                    <span class="modalidad-left-text">5 meses</span>
                                </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">7hs</span>
                                <span class="modalidad-left-text">cada clase</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">1 x semana</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">110hs</span>
                                <span class="modalidad-left-text">presenciales</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">Agosto</span>
                            </div>
                        </div>
                        <!-- Fila 4: DESFASADO -->
                        <div class="modalidad-content-row modalidad-content-row-presencial">
                                <div class="modalidad-label-cell">
                                    <span class="modalidad-content-text">DESFASADO</span>
                                </div>
                                <div class="modalidad-data-cell">
                                    <span class="modalidad-left-text">9 meses</span>
                                </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">3hs y media</span>
                                <span class="modalidad-left-text">cada clase</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">1 x semana</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">110hs</span>
                                <span class="modalidad-left-text">presenciales</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">Mayo</span>
                            </div>
                        </div>
                    </div>
                    <!-- Fila 5: Información adicional - fuera del grid principal -->
                    <div class="modalidad-special-row">
                        <div class="modalidad-special-cell modalidad-special-cell-left">
                            <span class="modalidad-special-text">
                                Podes elegir un día entre <strong>martes y sábados</strong>
                            </span>
                            <span class="modalidad-special-text modalidad-special-text-italic">
                                (según la disponibilidad de cada sede)
                            </span>
                        </div>
                        <div class="modalidad-special-cell modalidad-special-cell-right">
                            <img src="/images/desktop/morning.png" alt="Mañana" class="modalidad-special-icon">
                            <div class="modalidad-special-time">
                                <span class="modalidad-special-time-label">Mañana</span>
                                <span class="modalidad-special-time-hours">(9 a 12.30hs)</span>
                            </div>
                        </div>
                        <div class="modalidad-special-cell modalidad-special-cell-right">
                            <img src="/images/desktop/sun.png" alt="Tarde" class="modalidad-special-icon">
                            <div class="modalidad-special-time">
                                <span class="modalidad-special-time-label">Tarde</span>
                                <span class="modalidad-special-time-hours">(14 a 17.30hs)</span>
                            </div>
                        </div>
                        <div class="modalidad-special-cell modalidad-special-cell-right">
                            <img src="/images/desktop/night.png" alt="Noche" class="modalidad-special-icon">
                            <div class="modalidad-special-time">
                                <span class="modalidad-special-time-label">Noche</span>
                                <span class="modalidad-special-time-hours">(19 a 22.30hs)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Segundo dropdown: Semipresencial -->
                <div class="modalidades-dropdown modalidades-dropdown-semipresencial">
                    <div class="modalidades-dropdown-grid">
                        <!-- Fila 1: Header -->
                        <div class="modalidad-header-row modalidad-header-row-semipresencial">
                            <div class="modalidad-left">
                                <div class="modalidad-label">
                                    <span class="modalidad-label-line">SEMI-</span>
                                    <span class="modalidad-label-line">PRESENCIAL</span>
                                </div>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/clock.png" alt="Duración" class="modalidad-icon">
                                <span class="modalidad-icon-text">Duración</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/gear.png" alt="Dedicación" class="modalidad-icon">
                                <span class="modalidad-icon-text">Dedicación</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/student.png" alt="Clases" class="modalidad-icon">
                                <span class="modalidad-icon-text">Clases</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/video.png" alt="Teoría" class="modalidad-icon">
                                <span class="modalidad-icon-text">Teoría</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/wrench.png" alt="Práctica" class="modalidad-icon">
                                <span class="modalidad-icon-text">Práctica</span>
                            </div>
                            <div class="modalidad-icon-item">
                                <img src="/images/desktop/calendar.png" alt="Mes de Inicio" class="modalidad-icon">
                                <span class="modalidad-icon-text">Mes de Inicio</span>
                            </div>
                            <div class="modalidad-right">
                                <img src="/images/desktop/chevron.png" alt="Chevron" class="modalidad-chevron">
                            </div>
                        </div>
                        <!-- Fila 2: REGULAR -->
                        <div class="modalidad-content-row modalidad-content-row-semipresencial">
                            <div class="modalidad-label-cell">
                                <span class="modalidad-content-text">REGULAR</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">10 meses</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">3hs y media</span>
                                <span class="modalidad-left-text">cada clase</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">1 x semana</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">70hs</span>
                                <span class="modalidad-left-text">virtuales</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">110hs</span>
                                <span class="modalidad-left-text">presenciales</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">Marzo</span>
                            </div>
                        </div>
                        <!-- Fila 3: INTENSIVO -->
                        <div class="modalidad-content-row modalidad-content-row-semipresencial">
                            <div class="modalidad-label-cell">
                                <span class="modalidad-content-text">INTENSIVO</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">5 meses</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">7hs</span>
                                <span class="modalidad-left-text">cada clase</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">1 x semana</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">70hs</span>
                                <span class="modalidad-left-text">virtuales</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">110hs</span>
                                <span class="modalidad-left-text">presenciales</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">Agosto</span>
                            </div>
                        </div>
                        <!-- Fila 4: DESFASADO -->
                        <div class="modalidad-content-row modalidad-content-row-semipresencial">
                            <div class="modalidad-label-cell">
                                <span class="modalidad-content-text">DESFASADO</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">9 meses</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">3hs y media</span>
                                <span class="modalidad-left-text">cada clase</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">1 x semana</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">70hs</span>
                                <span class="modalidad-left-text">virtuales</span>
                            </div>
                            <div class="modalidad-data-cell modalidad-data-cell-multiline">
                                <span class="modalidad-left-text">110hs</span>
                                <span class="modalidad-left-text">presenciales</span>
                            </div>
                            <div class="modalidad-data-cell">
                                <span class="modalidad-left-text">Mayo</span>
                            </div>
                        </div>
                    </div>
                    <!-- Fila 5: Información adicional - fuera del grid principal -->
                    <div class="modalidad-special-row">
                        <div class="modalidad-special-cell modalidad-special-cell-left">
                            <span class="modalidad-special-text">
                                Podés elegir entre martes, jueves o viernes,
                            </span>
                            <span class="modalidad-special-text">
                                según <strong>el turno:</strong> <span class="modalidad-special-turno">Mañana, Tarde o Noche</span>.
                            </span>
                        </div>
                        <div class="modalidad-special-cell modalidad-special-cell-right">
                            <img src="/images/desktop/morning.png" alt="Mañana" class="modalidad-special-icon">
                            <div class="modalidad-special-time">
                                <span class="modalidad-special-time-label">Martes: Mañana</span>
                                <span class="modalidad-special-time-hours">(9 a 11.30hs)</span>
                            </div>
                        </div>
                        <div class="modalidad-special-cell modalidad-special-cell-right">
                            <img src="/images/desktop/sun.png" alt="Tarde" class="modalidad-special-icon">
                            <div class="modalidad-special-time">
                                <span class="modalidad-special-time-label">Jueves: Tarde</span>
                                <span class="modalidad-special-time-hours">(14 a 16.30hs)</span>
                            </div>
                        </div>
                        <div class="modalidad-special-cell modalidad-special-cell-right">
                            <img src="/images/desktop/night.png" alt="Noche" class="modalidad-special-icon">
                            <div class="modalidad-special-time">
                                <span class="modalidad-special-time-label">Viernes: Noche</span>
                                <span class="modalidad-special-time-hours">(19 a 21.30hs)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cursada -->
        <section class="cursada">
            <div class="cursada-container">
                <h2 class="cursada-title">
                    <span class="cursada-title-text-1">Descubrí</span>
                    <span class="cursada-title-text-2">que vas a cursar</span>
                    <span class="cursada-title-text-1">en cada año</span>
                </h2>
                
                <!-- Dropdown de Año -->
                <div class="cursada-dropdown">
                    <div class="cursada-dropdown-grid">
                        <!-- Header -->
                        <div class="cursada-header-row">
                            <div class="cursada-left">
                                <div class="cursada-label">1° AÑO</div>
                            </div>
                            <div class="cursada-content">
                                <div class="cursada-info-line">
                                    <span class="cursada-info-label">Título:</span>
                                    <span class="cursada-info-value">Analista técnico de motores</span>
                                </div>
                                <div class="cursada-info-line">
                                    <span class="cursada-info-label">Nivel:</span>
                                    <span class="cursada-info-value">Inicial</span>
                                </div>
                            </div>
                            <div class="cursada-right">
                                <img src="/images/desktop/chevron.png" alt="Chevron" class="cursada-chevron">
                            </div>
                        </div>
                        <!-- Fila 1: Unidad 1 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 1</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Introducción al Taller y sus elementos</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Normas de seguridad e higiene. Herramientas e instrumental.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 2: Unidad 2 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 2</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Configuración del motor</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Concepto de motores, clasificación y partes.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 3: Unidad 3 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 3</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Electricidad del Automóvil</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Circuitos, materiales, conexiones y redes eléctricas.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 4: Unidad 4 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 4</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Tipos de Sistemas</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Encendido, carburación, combustión, refrigeración, lubricación y distribución.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 5: Unidad 5 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 5</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Análisis y diagnóstico integral del motor</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Armado y desarmado de motores.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 6: Botones Inscribirme y Descargar -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell cursada-label-cell-with-btn">
                                <button class="cursada-inscribirme-btn">¡Inscribirme ahora!</button>
                            </div>
                            <div class="cursada-content-cell-with-btn">
                                <button class="cursada-descargar-btn">Descargar el programa del 1° Año Completo</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dropdown de Año Segundo -->
                <div class="cursada-dropdown segundo">
                    <div class="cursada-dropdown-grid">
                        <!-- Header -->
                        <div class="cursada-header-row">
                            <div class="cursada-left">
                                <div class="cursada-label">2° AÑO</div>
                            </div>
                            <div class="cursada-content">
                                <div class="cursada-info-line">
                                    <span class="cursada-info-label">Título:</span>
                                    <span class="cursada-info-value">Analista Técnico de Sistemas Mecánicos y Electrónicos</span>
                                </div>
                                <div class="cursada-info-line">
                                    <span class="cursada-info-label">Nivel:</span>
                                    <span class="cursada-info-value">Intermedio</span>
                                </div>
                            </div>
                            <div class="cursada-right">
                                <img src="/images/desktop/chevron.png" alt="Chevron" class="cursada-chevron">
                            </div>
                        </div>
                        <!-- Fila 1: Unidad 1 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 1</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Sistema de Frenos</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Componentes, funciones y ubicación.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 2: Unidad 2 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 2</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Sistema de Suspensión, Dirección y Alineación.</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Función y componentes del sistema.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 3: Unidad 3 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 3</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Sistema de Transmisión</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Sistema de embrague y caja de velocidad/transferencia/automática.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 4: Unidad 4 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 4</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Tipos de Sistemas</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Encendido, carburación, combustión, refrigeración, lubricación y distribución.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 5: Unidad 5 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 5</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Sistema de Inyección</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Sensores y actuadores. Componentes y funciones de combustible.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 6: Botones Chatear con SAE y Descargar -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell cursada-label-cell-with-btn">
                                <button class="cursada-inscribirme-btn">Chatear con SAE</button>
                            </div>
                            <div class="cursada-content-cell-with-btn">
                                <button class="cursada-descargar-btn">Descargar el programa del 2° Año Completo</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dropdown de Año Tercero -->
                <div class="cursada-dropdown tercero">
                    <div class="cursada-dropdown-grid">
                        <!-- Header -->
                        <div class="cursada-header-row">
                            <div class="cursada-left">
                                <div class="cursada-label">3° AÑO</div>
                                <div class="cursada-subtitle">
                                    <span class="cursada-subtitle-regular">Especialización</span>
                                    <span class="cursada-subtitle-bold">opcional</span>
                                </div>
                            </div>
                            <div class="cursada-content">
                                <div class="cursada-info-line">
                                    <span class="cursada-info-label">Título:</span>
                                    <span class="cursada-info-value">Especialista en Diagnóstico Electrónico</span>
                                </div>
                                <div class="cursada-info-line">
                                    <span class="cursada-info-label">Nivel:</span>
                                    <span class="cursada-info-value">Avanzado</span>
                                </div>
                            </div>
                            <div class="cursada-right">
                                <img src="/images/desktop/chevron.png" alt="Chevron" class="cursada-chevron">
                            </div>
                        </div>
                        <!-- Fila 1: Unidad 1 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 1</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Introducción al Taller y sus elementos</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Normas de seguridad e higiene. Herramientas e instrumental.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 2: Unidad 2 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 2</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Configuración del motor</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Concepto de motores, clasificación y partes.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 3: Unidad 3 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 3</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Electricidad del Automóvil</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Circuitos, materiales, conexiones y redes eléctricas.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 4: Unidad 4 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 4</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Tipos de Sistemas</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Encendido, carburación, combustión, refrigeración, lubricación y distribución.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 5: Unidad 5 -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell">
                                <span class="cursada-unidad-text">Unidad 5</span>
                            </div>
                            <div class="cursada-content-cell">
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-top">Análisis y diagnóstico integral del motor</span>
                                </div>
                                <div class="cursada-unidad-line">
                                    <span class="cursada-unidad-bottom">Armado y desarmado de motores.</span>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 6: Botones Chatear con SAE y Descargar -->
                        <div class="cursada-content-row">
                            <div class="cursada-label-cell cursada-label-cell-with-btn">
                                <button class="cursada-inscribirme-btn">Chatear con SAE</button>
                            </div>
                            <div class="cursada-content-cell-with-btn">
                                <button class="cursada-descargar-btn">Descargar el programa del 3° Año Completo</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Certificación -->
        <section class="certificacion">
            <div class="certificacion-container">
                <div class="certificacion-titles-wrapper">
                    <h2 class="certificacion-title">
                        <span class="certificacion-title-text-1">¿Qué</span>
                        <span class="certificacion-title-text-2">vas a aprender</span>
                        <span class="certificacion-title-text-1">en esta carrera?</span>
                    </h2>
                    <h2 class="certificacion-card-main-title">Certificación Oficial ITCA y UTN</h2>
                </div>
                
                <div class="certificacion-content">
                    <div class="certificacion-card certificacion-card-left">
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star1.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text">Comprender <strong>desde cero el funcionamiento</strong> mecánico y electrónico de un automóvil.</p>
                        </div>
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star2.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text">Reconocer y analizar los distintos <strong>sistemas que componen un vehículo.</strong></p>
                        </div>
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star1.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text"><strong>Detectar y diagnosticar fallas</strong> en cada uno de esos sistemas.</p>
                        </div>
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star2.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text"><strong>Aplicar procedimientos</strong> para la resolución de problemas mecánicos y electrónicos.</p>
                        </div>
                    </div>
                    
                    <div class="certificacion-card certificacion-card-right">
                        <div class="certificacion-cert-item">
                            <img src="/images/desktop/cert-itca.png" alt="Certificado ITCA" class="certificacion-cert-img">
                            <p class="certificacion-cert-text">Tu diploma será entregado en el <span class="certificacion-cert-highlight">Acto de Graduación</span>, donde celebraremos juntos tu logro.</p>
                        </div>
                        <div class="certificacion-cert-item">
                            <img src="/images/desktop/cert-utn.png" alt="Certificado UTN" class="certificacion-cert-img">
                            <p class="certificacion-cert-text">Tu <span class="certificacion-cert-highlight">certificación UTN</span> será entregada por correo electrónico.</p>
                        </div>
                    </div>
                    
                    <div class="certificacion-card certificacion-card-full">
                        <div class="certificacion-card-col-1">
                            <div class="certificacion-list-content">
                                <div class="certificacion-list-row" data-item="objetivo-estudio">
                                    <div class="certificacion-list-text">Objetivo de Estudio</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="objetivo-estudio-content">
                                    <div class="certificacion-list-expanded-text">
                                        Formar técnicos especializados en el diagnóstico y reparación de sistemas automotrices, con conocimientos sólidos en mecánica, electrónica y nuevas tecnologías del sector. El egresado será capaz de identificar, analizar y resolver problemas técnicos en vehículos modernos.
                                    </div>
                                </div>
                                <div class="certificacion-list-row" data-item="dirigido-a">
                                    <div class="certificacion-list-text">Dirigido a</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="dirigido-a-content">
                                    <div class="certificacion-list-expanded-text">
                                        Personas interesadas en el sector automotriz, técnicos que buscan actualización, emprendedores del rubro y estudiantes que quieren desarrollar una carrera profesional en mecánica y electrónica automotriz. No se requieren conocimientos previos.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="certificacion-card-col-2">
                            <div class="certificacion-list-content">
                                <div class="certificacion-list-row" data-item="niveles">
                                    <div class="certificacion-list-text">Niveles</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="niveles-content">
                                    <div class="certificacion-list-expanded-text">
                                        La carrera se estructura en módulos progresivos que cubren desde fundamentos de mecánica hasta sistemas avanzados de diagnóstico electrónico. Incluye práctica en talleres equipados con tecnología moderna y herramientas especializadas.
                                    </div>
                                </div>
                                <div class="certificacion-list-row" data-item="salida-laboral">
                                    <div class="certificacion-list-text">Salida Laboral</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="salida-laboral-content">
                                    <div class="certificacion-list-expanded-text">
                                        Talleres mecánicos, concesionarias, servicios técnicos oficiales, centros de diagnóstico automotriz, empresas de flotas, emprendimientos propios y sector industrial. Alta demanda laboral en el mercado automotriz actual.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fotos Section -->
        <section class="fotos-section">
            <div class="fotos-container">
                <h2 class="fotos-title">
                    <span class="fotos-title-text-1">Algunas</span>
                    <span class="fotos-title-text-2">fotografías</span>
                    <span class="fotos-title-text-1">de nuestras clases</span>
                </h2>
                
                <div class="fotos-desktop">
                    <div class="fotos-carousel-section">
                        <div class="swiper fotos-swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide fotos-carousel-slide">
                                    <img src="/images/slide-carrera/img1.png" alt="Foto clase 1" class="fotos-slide-img" />
                                </div>
                                <div class="swiper-slide fotos-carousel-slide">
                                    <img src="/images/slide-carrera/img2.png" alt="Foto clase 2" class="fotos-slide-img" />
                                </div>
                                <div class="swiper-slide fotos-carousel-slide">
                                    <img src="/images/slide-carrera/img3.png" alt="Foto clase 3" class="fotos-slide-img" />
                                </div>
                                <div class="swiper-slide fotos-carousel-slide">
                                    <img src="/images/slide-carrera/img4.png" alt="Foto clase 4" class="fotos-slide-img" />
                                </div>
                                <div class="swiper-slide fotos-carousel-slide">
                                    <img src="/images/slide-carrera/img5.png" alt="Foto clase 5" class="fotos-slide-img" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Controles de navegación -->
                        <div class="fotos-carousel-controls">
                            <!-- Barra de progreso -->
                            <div class="fotos-progress-bar fotos-desktop-progress-bar">
                                <div class="fotos-progress-track"></div>
                                <div class="fotos-progress-indicator fotos-desktop-progress-indicator"></div>
                            </div>
                            <div class="fotos-controls-wrapper">
                                <button class="fotos-carousel-btn fotos-carousel-btn-prev">
                                    <img src="/images/desktop/beneficios/arrow-b.png" alt="Anterior" class="fotos-arrow-left" />
                                </button>
                                <button class="fotos-carousel-btn fotos-carousel-btn-next">
                                    <img src="/images/desktop/beneficios/arrow-b.png" alt="Siguiente" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonios Carrera Section -->
        <section class="testimonios-carrera-section">
            <div class="testimonios-carrera-container">
                <h2 class="testimonios-carrera-title">
                    <span class="testimonios-carrera-title-text-1"></span>
                    <span class="testimonios-carrera-title-text-2">Alumnos y Egresados</span>
                    <span class="testimonios-carrera-title-text-1">comparten su experiencia</span>
                </h2>
                
                <div class="testimonios-carrera-grid">
                    @php
                        $testimoniosArray = $testimonios->toArray();
                        $testimoniosCount = count($testimoniosArray);
                        $testimonioIndex = 0;
                    @endphp
                    
                    @for($gridIndex = 0; $gridIndex < 8; $gridIndex++)
                        @if($gridIndex == 2)
                            <!-- Columna 3: Video que ocupa 2 filas -->
                            <div class="testimonios-carrera-grid-item testimonios-carrera-grid-item-video">
                                <div class="testimonios-carrera-grid-video">
                                    <video class="testimonios-carrera-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                        <source src="/images/mediacontent/ytmobile.mp4" type="video/mp4">
                                        Tu navegador no soporta el elemento video.
                                    </video>
                                    <button class="testimonios-carrera-play-button">
                                        <img src="/images/desktop/play.png" alt="Play" class="testimonios-carrera-play-icon" />
                                    </button>
                                    <a href="#" class="testimonios-carrera-ver-mas-btn">
                                        Ver más testimonios
                                    </a>
                                </div>
                            </div>
                        @elseif($gridIndex == 6)
                            <!-- Columna 3 fila 2: Ocupada por el video, saltamos este slot -->
                            @continue
                        @else
                            @if($testimonioIndex < $testimoniosCount)
                                @php $testimonio = $testimonios[$testimonioIndex]; $testimonioIndex++; @endphp
                                <div class="testimonios-carrera-grid-item">
                                    <div class="testimonios-carrera-grid-image">
                                        <!-- Header -->
                                        <div class="testimonios-carrera-card-header">
                                            <img src="{{ asset('storage/' . $testimonio->avatar) }}" 
                                                 alt="Avatar" class="testimonios-carrera-card-avatar" loading="lazy">
                                            <div class="testimonios-carrera-card-info">
                                                <p class="testimonios-carrera-card-sede">{{ $testimonio->sede }}</p>
                                                <div class="testimonios-carrera-card-wrapper">
                                                    <p class="testimonios-carrera-card-nombre">{{ $testimonio->nombre }}</p>
                                                    <p class="testimonios-carrera-card-tiempo">hace {{ $testimonio->tiempo_testimonio }} meses</p>
                                                </div>
                                                <p class="testimonios-carrera-card-carrera">{{ $testimonio->carrera }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Main -->
                                        <div class="testimonios-carrera-card-main">
                                            <p class="testimonios-carrera-card-texto">{{ $testimonio->texto }}</p>
                                        </div>
                                        
                                        <!-- Bottom -->
                                        <div class="testimonios-carrera-card-bottom">
                                            <img src="/images/desktop/comunidad/stars.png" alt="Stars" class="testimonios-carrera-card-stars" loading="lazy">
                                            <img src="{{ asset('storage/' . $testimonio->icono) }}" 
                                                 alt="Icono" class="testimonios-carrera-card-google" loading="lazy">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endfor
                </div>
            </div>
        </section>

        <!-- Dudas Section -->
        <section class="dudas-section">
            <div class="dudas-container">
                <h2 class="dudas-title">
                    <span class="dudas-title-text-1">¡Resolvé</span>
                    <span class="dudas-title-text-2">algunas dudas</span>
                    <span class="dudas-title-text-1">ya!</span>
                </h2>
                
                <div class="faqs-card faqs-card-full">
                    <div class="faqs-card-col-1">
                        <div class="faqs-list-content">
                            <div class="faqs-list-row" data-item="faqs-quienes-pueden">
                                <div class="faqs-list-text">¿Quiénes pueden cursar en el Instituto?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-quienes-pueden-content">
                                <div class="faqs-list-expanded-text">
                                    Pueden cursar todas las personas interesadas en el sector automotriz, sin importar su edad o nivel educativo previo. No se requieren conocimientos técnicos previos, ya que nuestras carreras están diseñadas para comenzar desde lo básico. Está dirigido a estudiantes, trabajadores del sector que buscan especialización, emprendedores que quieren iniciar su propio negocio, y cualquier persona con pasión por la mecánica automotriz.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-titulo">
                                <div class="faqs-list-text">¿Qué título me entregan al finalizar la carrera?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-titulo-content">
                                <div class="faqs-list-expanded-text">
                                    Al finalizar la carrera, recibirás un título técnico profesional emitido por el Instituto, reconocido y válido para ejercer en el ámbito laboral. Además, podrás acceder a la certificación oficial de la UTN (Universidad Tecnológica Nacional) que complementa tu formación y amplía tus oportunidades profesionales. El título te habilita para trabajar en talleres, concesionarias, servicios técnicos oficiales y otros espacios del sector automotriz.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-otros-niveles">
                                <div class="faqs-list-text">¿Una vez terminada la carrera, hay otros niveles?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-otros-niveles-content">
                                <div class="faqs-list-expanded-text">
                                    Sí, contamos con programas de especialización y cursos avanzados que te permiten profundizar en áreas específicas como diagnóstico electrónico avanzado, gestión de talleres, sistemas híbridos y eléctricos, entre otros. También ofrecemos cursos de actualización tecnológica para mantenerte al día con las últimas innovaciones del sector automotriz.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-certificacion-utn">
                                <div class="faqs-list-text">¿Cómo se accede a la certificación de la UTN?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-certificacion-utn-content">
                                <div class="faqs-list-expanded-text">
                                    La certificación de la UTN está disponible para todos los estudiantes que completen exitosamente la carrera. Debes cumplir con los requisitos de asistencia, aprobar todos los módulos y proyectos requeridos. Una vez finalizado, el Instituto gestiona automáticamente tu inscripción para la certificación. No requiere trámites adicionales de tu parte, solo cumplir con el programa académico.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-recuperar-clase">
                                <div class="faqs-list-text">Si falté a una clase ¿la puedo recuperar?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-recuperar-clase-content">
                                <div class="faqs-list-expanded-text">
                                    Sí, contamos con un sistema de recuperación de clases. Si faltaste por alguna razón justificada, puedes coordinar con el área académica para asistir a la misma clase en otro horario o grupo. También ofrecemos material complementario y sesiones de consulta para que puedas ponerte al día. Es importante comunicar tu ausencia con anticipación cuando sea posible.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="faqs-card-col-2">
                        <div class="faqs-list-content">
                            <div class="faqs-list-row" data-item="faqs-cambiar-horario">
                                <div class="faqs-list-text">¿Puedo cambiar el día y horario de cursada?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-cambiar-horario-content">
                                <div class="faqs-list-expanded-text">
                                    Sí, es posible cambiar el día y horario de cursada si hay disponibilidad en otros grupos. Debes comunicarte con el área de administración académica con al menos una semana de anticipación para coordinar el cambio. Esto dependerá de la disponibilidad de cupos en el horario que desees. Hacemos lo posible por acomodar las necesidades de nuestros estudiantes.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-cursada-semipresencial">
                                <div class="faqs-list-text">¿Cómo es la cursada semipresencial?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-cursada-semipresencial-content">
                                <div class="faqs-list-expanded-text">
                                    La modalidad semipresencial combina clases teóricas online que puedes realizar desde tu casa en el horario que prefieras, con prácticas presenciales en nuestros talleres equipados. Las clases online incluyen videos, material interactivo y foros de consulta. Las prácticas presenciales son obligatorias y se realizan en horarios fijos, donde trabajas directamente con herramientas y equipos profesionales bajo la supervisión de instructores especializados.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-multiples-carreras">
                                <div class="faqs-list-text">¿Puedo cursar más de una carrera a la vez?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-multiples-carreras-content">
                                <div class="faqs-list-expanded-text">
                                    Sí, puedes cursar más de una carrera simultáneamente si organizas bien tus horarios y crees que puedes manejar la carga académica. Sin embargo, te recomendamos que primero completes una carrera para tener una base sólida antes de comenzar otra. Si decides hacerlo, nuestro equipo académico puede ayudarte a coordinar los horarios para que no se superpongan.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-materiales">
                                <div class="faqs-list-text">¿Hay que pagar aparte los materiales?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-materiales-content">
                                <div class="faqs-list-expanded-text">
                                    No, todos los materiales de práctica, herramientas y equipos necesarios para las clases están incluidos en la matrícula. El Instituto provee todo lo necesario para las prácticas en los talleres. Solo necesitarás traer elementos básicos de protección personal como guantes y gafas de seguridad, que te indicaremos al inicio del curso. Los materiales didácticos y bibliografía también están incluidos.
                                </div>
                            </div>
                            <div class="faqs-list-row" data-item="faqs-donde-trabajar">
                                <div class="faqs-list-text">¿Dónde podré trabajar al finalizar mis estudios?</div>
                                <div class="faqs-list-plus">+</div>
                            </div>
                            <div class="faqs-list-content-expanded" id="faqs-donde-trabajar-content">
                                <div class="faqs-list-expanded-text">
                                    Con tu título podrás trabajar en una amplia variedad de lugares: talleres mecánicos independientes, concesionarias oficiales, servicios técnicos de marcas, centros de diagnóstico automotriz, empresas de flotas de vehículos, compañías de seguros (como peritos), empresas de transporte, y también podrás iniciar tu propio emprendimiento. El sector automotriz tiene una alta demanda laboral y múltiples oportunidades de crecimiento profesional.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partners Section (reutilizada) -->
        <section class="partners-section">
            <div class="partners-content">
                <h2 class="partners-title">
                    <span class="partners-title-text-1">Nos acompañan en la</span>
                    <span class="partners-title-text-2">formación profesional</span>
                </h2>
                <div class="partners-carousel">
                    <div class="partners-slider">
                        @if($partners->count() > 0)
                            @foreach($partners as $partner)
                                <div>
                                    <a href="{{ $partner->url }}" target="_blank" class="partners-logo">
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->url }}" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                            @foreach($partners as $partner)
                                <div>
                                    <a href="{{ $partner->url }}" target="_blank" class="partners-logo">
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->url }}" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Contacto Section (reutilizada) -->
        <section class="contacto-section" id="contacto">
            <div class="contacto-content">
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
                                        <a href="{{ $sede->link_google_maps }}" target="_blank" class="contacto-sede-link-maps">📍 Ver en Maps</a>
                                    </div>
                                @endif
                                @if($sede->link_whatsapp)
                                    <div class="contacto-sede-link">
                                        <a href="{{ $sede->link_whatsapp }}" target="_blank" class="contacto-sede-link-whatsapp">💬 WhatsApp</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="contacto-tercio contacto-contacto">
                    <div class="contacto-contacto-wrapper">
                        <h3 class="contacto-tercio-title">Contacto</h3>
                        <div class="contacto-info-content">
                            <div class="contacto-info-item"><span class="contacto-info-label">Tel: </span><span class="contacto-info-value">0810-220-4822</span></div>
                            <div class="contacto-info-item"><span class="contacto-info-label">WhatsApp: </span><span class="contacto-info-value">11-2267-4822</span></div>
                            <div class="contacto-info-item"><span class="contacto-info-label">Mail: </span><span class="contacto-info-value"><a href="mailto:inscripciones@itca.edu.ar">inscripciones@itca.edu.ar</a></span></div>
                        </div>
                        <h4 class="contacto-redes-title">Redes Sociales</h4>
                        <div class="contacto-redes-icons">
                            <a href="https://www.instagram.com/itca.oficial/?hl=en" target="_blank" class="contacto-redes-link"><img src="/images/social/ig.png" alt="Instagram" class="contacto-redes-icon"></a>
                            <a href="https://www.tiktok.com/@itca.oficial" target="_blank" class="contacto-redes-link"><img src="/images/social/tik.png" alt="TikTok" class="contacto-redes-icon"></a>
                            <a href="https://www.facebook.com/ITCAoficial/" target="_blank" class="contacto-redes-link"><img src="/images/social/fb.png" alt="Facebook" class="contacto-redes-icon"></a>
                            <a href="https://www.linkedin.com/school/itca-oficial/" target="_blank" class="contacto-redes-link"><img src="/images/social/lin.png" alt="LinkedIn" class="contacto-redes-icon"></a>
                            <a href="https://www.youtube.com/canalITCAoficial" target="_blank" class="contacto-redes-link"><img src="/images/social/yt.png" alt="YouTube" class="contacto-redes-icon"></a>
                        </div>
                    </div>
                </div>

                <div class="contacto-tercio contacto-info">
                    <div class="contacto-info-wrapper">
                        <h3 class="contacto-tercio-title">¿Querés más info?</h3>
                        <p class="contacto-info-text"><strong>Suscribite</strong> y mantente al día con las últimas noticias, ofertas exclusivas y recursos útiles.</p>
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
</body>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        // Inicialización local por si app.js no encuentra jQuery a tiempo
        document.addEventListener('DOMContentLoaded', function() {
            if (window.jQuery) {
                var $ = window.jQuery;
                if ($('.partners-slider').length && typeof $('.partners-slider').slick === 'function') {
                    $('.partners-slider').not('.slick-initialized').slick({
                        slidesToShow: 6,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 0,
                        speed: 5000,
                        cssEase: 'linear',
                        arrows: false,
                        infinite: true,
                        pauseOnHover: false,
                        variableWidth: true
                    });
                }
            }

            // Toggle del dropdown de modalidades y sincronización - usando delegación de eventos para múltiples dropdowns
            const modalidadesContainer = document.querySelector('.modalidades-single');
            
            // Función para sincronizar anchos de columnas de un dropdown específico
            function syncColumnWidths(dropdown) {
                const headerRow = dropdown.querySelector('.modalidad-header-row');
                const contentRows = dropdown.querySelectorAll('.modalidad-content-row');
                
                if (headerRow && contentRows.length > 0) {
                    // Obtener todas las celdas del header
                    const headerCells = Array.from(headerRow.children);
                    const columnWidths = headerCells.map(cell => {
                        const rect = cell.getBoundingClientRect();
                        return rect.width + 'px';
                    });
                    
                    // Construir grid-template-columns con los anchos calculados
                    const gridColumns = columnWidths.join(' ');
                    
                    // Aplicar a todas las filas de contenido del mismo dropdown
                    contentRows.forEach(row => {
                        row.style.gridTemplateColumns = gridColumns;
                    });
                }
            }
            
            // Delegación de eventos para los chevrons
            if (modalidadesContainer) {
                modalidadesContainer.addEventListener('click', function(e) {
                    const chevron = e.target.closest('.modalidad-chevron');
                    if (chevron) {
                        const modalidadDropdown = chevron.closest('.modalidades-dropdown');
                        if (modalidadDropdown) {
                    modalidadDropdown.classList.toggle('open');
                            
                            // Sincronizar cuando se abre
                            if (modalidadDropdown.classList.contains('open')) {
                                setTimeout(() => syncColumnWidths(modalidadDropdown), 150);
                            }
                        }
                    }
                });
                
                // Observer para cada dropdown
                const dropdowns = document.querySelectorAll('.modalidades-dropdown');
                dropdowns.forEach(dropdown => {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                if (dropdown.classList.contains('open')) {
                                    setTimeout(() => syncColumnWidths(dropdown), 150);
                                }
                            }
                        });
                    });
                    observer.observe(dropdown, { attributes: true });
                });
            }
            
            // Sincronizar al redimensionar
            window.addEventListener('resize', function() {
                const openDropdowns = document.querySelectorAll('.modalidades-dropdown.open');
                openDropdowns.forEach(dropdown => {
                    setTimeout(() => syncColumnWidths(dropdown), 100);
                });
            });
            
            // Toggle del dropdown de cursada
            const cursadaContainer = document.querySelector('.cursada-container');
            if (cursadaContainer) {
                cursadaContainer.addEventListener('click', function(e) {
                    const chevron = e.target.closest('.cursada-chevron');
                    if (chevron) {
                        const cursadaDropdown = chevron.closest('.cursada-dropdown');
                        if (cursadaDropdown) {
                            cursadaDropdown.classList.toggle('open');
                        }
                    }
                });
            }
            
        });
    </script>
</html>


