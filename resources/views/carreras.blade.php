<!DOCTYPE html>
<html lang="es">
<head>
    @use('Illuminate\Support\Str')
    {{-- 
        @var \Illuminate\Database\Eloquent\Collection|\App\Models\Curso[] $carreras
        @var \Illuminate\Database\Eloquent\Collection|\App\Models\Beneficio[] $beneficios
        @var \Illuminate\Database\Eloquent\Collection|\App\Models\Partner[] $partners
        @var \Illuminate\Database\Eloquent\Collection|\App\Models\Sede[] $sedes
        @var \App\Models\StickyBar|null $stickyBar
    --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="ITCA - Instituto Tecnológico de Capacitación Automotriz - Carreras">
    <title>Carreras - ITCA - Instituto Tecnológico de Capacitación y Automotriz</title>
    
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
<body>
    <!-- Sticky Bar -->
    @if($stickyBar && $stickyBar->visible == true)
    <div class="sticky-bar" @style(['background-color: ' . $stickyBar->color . ' !important'])>
        <div class="container">
            <div class="sticky-bar-content">
                <div class="sticky-bar-text-container">
                    <span class="sticky-bar-text" @style(['color: ' . ($stickyBar->text_color ?? '#ffffff') . ' !important'])>{!! $stickyBar->getFormattedTextAttribute() !!}</span>
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
                <a href="{{ route('home') }}" class="logo">ITCA</a>
                
                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('somos-itca') }}" class="nav-link">
                            Somos ITCA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('carreras') }}" class="nav-link active">
                            Carreras
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('beneficios') }}" class="nav-link">
                            Beneficios
                        </a>
                    </li>
                    <li>
                        <a href="#contacto" class="nav-link">
                            Contacto
                        </a>
                    </li>
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
        <!-- Lista Carreras Section -->
        <section class="lista-carreras">
            <div class="lista-carreras-container">
                <div class="lista-carreras-header">
                    <div class="lista-carreras-breadcrumb">
                        <a href="/" class="lista-carreras-breadcrumb-link">Inicio</a>
                        <span class="lista-carreras-breadcrumb-separator">></span>
                        <span class="lista-carreras-breadcrumb-current">Carreras</span>
                    </div>
                    <h2 class="lista-carreras-title">
                        <span class="lista-carreras-title-text-1">¡Elegí</span>
                        <span class="lista-carreras-title-text-2">la carrera</span>
                        <span class="lista-carreras-title-text-3">para vos!</span>
                    </h2>
                </div>
                <div class="lista-carreras-content">
                    @foreach($carreras as $index => $carrera)
                    <div class="carrera-wrapper carrera-wrapper-{{ Str::slug($carrera->nombre) }}">
                        <div class="carrera-container carrera-{{ Str::slug($carrera->nombre) }}">
                            <div class="carrera-left">
                                <div class="carrera-title">
                                    <h3>
                                        <div class="carrera-title-line1">Carrera de</div>
                                        <div class="carrera-title-line2">
                                            @php
                                                $palabras = explode(' ', $carrera->nombre);
                                                $ultimaPalabra = array_pop($palabras);
                                                $resto = implode(' ', $palabras);
                                            @endphp
                                            {{ $resto }} <span class="carrera-title-highlight">{{ $ultimaPalabra }}</span>
                                        </div>
                                    </h3>
                                </div>
                                <div class="carrera-text">
                                    <p>{{ $carrera->descripcion }}</p>
                                </div>
                                <div class="carrera-buttons">
                                    <img src="/images/desktop/arrow.png" alt="Flecha" class="carrera-btn-arrow">
                                    <a href="{{ route('carreras.show', $carrera->id) }}" class="carrera-btn-info">
                                        <span>Quiero más info sobre la carrera</span>
                                    </a>
                                    <a href="{{ route('inscripcion', $carrera->id) }}" class="carrera-btn-inscribir">¡Inscribirme ahora!</a>
                                </div>
                            </div>
                        <div class="carrera-right">
                            <a href="{{ route('carreras.show', $carrera->id) }}" class="carrera-image">
                                <!-- Imagen Desktop (solo visible en desktop y tablet) -->
                                <img src="{{ asset('storage/' . $carrera->ilustracion_desktop) }}" alt="{{ $carrera->nombre }}" class="carreras-image-desktop" loading="lazy" />
                                <!-- Imagen Mobile (solo visible en mobile) -->
                                <img src="{{ asset('storage/' . $carrera->ilustracion_mobile) }}" alt="{{ $carrera->nombre }}" class="carreras-image-mobile" loading="lazy" />
                                <div class="carreras-modalidad-badge carreras-modalidad-badge-desktop">
                                    <span class="carreras-modalidad-text">Modalidad: <strong>
                                        @if($carrera->modalidad_online && $carrera->modalidad_presencial)
                                            Presencial / Online
                                        @elseif($carrera->modalidad_presencial)
                                            Presencial
                                        @else
                                            Online
                                        @endif
                                    </strong></span>
                                </div>
                            </a>
                        </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Div UTN Mobile - Igual que welcome.blade.php (solo mobile/tablet) -->
                    <div class="carreras-UTN-base carreras-UTN-home">
                        <div class="carreras-UTN-left">
                            <div class="carreras-UTN-text-base">
                                <div class="carreras-UTN-line1">Nuestras <span class="carreras-UTN-bold">especializaciones</span></div>
                                <div class="carreras-UTN-line2">están certificadas por:</div>
                            </div>
                        </div>
                        <div class="carreras-UTN-right">
                            <img src="/images/desktop/logo-utn.png" alt="Logo UTN" class="carreras-UTN-logo-base carreras-UTN-logo-desktop">
                            <img src="/images/mobile/utn-mobile.webp" alt="Logo UTN" class="carreras-UTN-logo-base carreras-UTN-logo-mobile">
                        </div>
                    </div>
                    
                    <!-- Div UTN - Ancho completo (solo desktop) -->
                    <div class="carreras-UTN-carreras">
                        <div class="carreras-UTN-carreras-left">
                            <div class="carreras-UTN-text-base">
                                <div class="carreras-UTN-line1">Nuestras <span class="carreras-UTN-bold">especializaciones</span></div>
                                <div class="carreras-UTN-line2">están certificadas por:</div>
                            </div>
                        </div>
                        <div class="carreras-UTN-carreras-right">
                            <img src="/images/desktop/logo-utn.png" alt="Logo UTN" class="carreras-UTN-carreras-logo">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Beneficios Section -->
        <section class="beneficios-section">
            <div class="beneficios-container">
                <h2 class="beneficios-title">
                    <span class="beneficios-text-regular">Ser </span>
                    <span class="beneficios-text-bold">estudiante ITCA</span>
                    <span class="beneficios-text-regular"> tiene </span>
                    <span class="beneficios-text-highlight">sus beneficios</span>
                </h2>
                
                <!-- Carrusel de Beneficios con Swiper (Desktop/Tablet) -->
                <div class="beneficios-desktop">
                <div class="beneficios-carousel-section">
                    <div class="swiper beneficios-swiper">
                        <div class="swiper-wrapper">
                            @foreach($beneficios as $beneficio)
                            <div class="swiper-slide beneficios-carousel-slide">
                                <img src="{{ asset('storage/' . $beneficio->imagen_desktop) }}" alt="{{ $beneficio->titulo_linea1 }}" class="beneficios-slide-img-desktop" />
                                <img src="{{ asset('storage/' . $beneficio->imagen_mobile) }}" alt="{{ $beneficio->titulo_linea1 }}" class="beneficios-slide-img-mobile" />
                                <div class="beneficios-slide-content">
                                    <div class="beneficios-slide-main">
                                        <div class="beneficios-slide-title">
                                            <div class="beneficios-slide-line1">{{ $beneficio->titulo_linea1 }}</div>
                                            <div class="beneficios-slide-line2 {{ $beneficio->tipo_titulo === 'small' ? 'beneficios-slide-line2-small' : '' }}">{{ $beneficio->titulo_linea2 }}</div>
                                        </div>
                                        <div class="beneficios-slide-description">
                                            {!! $beneficio->descripcion !!}
                                        </div>
                                    </div>
                                    @if($beneficio->mostrar_bottom)
                                    <div class="beneficios-slide-bottom beneficios-slide-bottom-{{ $beneficio->getAlineacionBottomAttribute() }}">
                                        @if($beneficio->getAlineacionBottomAttribute() === 'left')
                                            <div class="beneficios-slide-link beneficios-slide-link-visible">
                                                <a href="{{ $beneficio->url }}" target="_blank">{{ $beneficio->texto_boton ?: 'Ver más' }}</a>
                                            </div>
                                            <div class="beneficios-slide-icon beneficios-slide-icon-hidden">
                                                <a href="#" class="beneficios-slide-icon-btn">
                                                    <img src="/images/desktop/btnflecha.png" alt="Flecha" loading="lazy" />
                                                </a>
                                            </div>
                                        @elseif($beneficio->getAlineacionBottomAttribute() === 'right')
                                            <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                <a href="#">Ver más</a>
                                            </div>
                                            <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                <a href="{{ $beneficio->url ?: '#' }}" target="_blank" class="beneficios-slide-icon-btn">
                                                    <img src="/images/desktop/btnflecha.png" alt="Flecha" loading="lazy" />
                                                </a>
                                            </div>
                                        @else
                                            <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                <a href="#">Ver más</a>
                                            </div>
                                            <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                <a href="{{ $beneficio->url ?: '#' }}" target="_blank" class="beneficios-slide-icon-btn">
                                                    <img src="/images/desktop/btnflecha.png" alt="Flecha" loading="lazy" />
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Botones de navegación -->
                    <div class="beneficios-carousel-controls">
                        <!-- Barra de progreso (Desktop) -->
                        <div class="beneficios-progress-bar beneficios-desktop-progress-bar">
                            <div class="beneficios-progress-track"></div>
                            <div class="beneficios-progress-indicator beneficios-desktop-progress-indicator"></div>
                        </div>
                        <div class="beneficios-controls-wrapper">
                            <a href="#" class="beneficios-ver-todos-btn">Ver todos los beneficios</a>
                            <button class="beneficios-carousel-btn beneficios-carousel-btn-prev">
                                <img src="/images/desktop/arrow-b.svg" alt="Anterior" class="beneficios-arrow-left" />
                            </button>
                            <button class="beneficios-carousel-btn beneficios-carousel-btn-next">
                                <img src="/images/desktop/arrow-b.svg" alt="Siguiente" />
                            </button>
                        </div>
                    </div>
                    </div>
                    
                    <!-- Carrusel Nativo de Beneficios (solo mobile) -->
                    <div class="beneficios-mobile-carousel-section">
                        <div class="beneficios-mobile-carousel">
                            <div class="beneficios-carousel-track">
                                @foreach($beneficios as $beneficio)
                                <div class="beneficios-carousel-slide">
                                    <img src="{{ asset('storage/' . $beneficio->imagen_mobile) }}" alt="{{ $beneficio->titulo_linea1 }}" />
                                    <div class="beneficios-slide-content">
                                        <div class="beneficios-slide-main">
                                            <div class="beneficios-slide-title">
                                                <div class="beneficios-slide-line1">{{ $beneficio->titulo_linea1 }}</div>
                                                <div class="beneficios-slide-line2 {{ $beneficio->tipo_titulo === 'small' ? 'beneficios-slide-line2-small' : '' }}">{{ $beneficio->titulo_linea2 }}</div>
                                            </div>
                                            <div class="beneficios-slide-description">
                                                {!! $beneficio->descripcion !!}
                                            </div>
                                        </div>
                                        @if($beneficio->mostrar_bottom)
                                        <div class="beneficios-slide-bottom beneficios-slide-bottom-{{ $beneficio->getAlineacionBottomAttribute() }}">
                                            @if($beneficio->getAlineacionBottomAttribute() === 'left')
                                                <div class="beneficios-slide-link beneficios-slide-link-visible">
                                                    <a href="{{ $beneficio->url }}" target="_blank">{{ $beneficio->texto_boton ?: 'Ver más' }}</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-hidden">
                                                    <a href="#" class="beneficios-slide-icon-btn">
                                                        <img src="/images/mobile/flechaup.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            @elseif($beneficio->getAlineacionBottomAttribute() === 'right')
                                                <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                    <a href="#">Ver más</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                    <a href="{{ $beneficio->url ?: '#' }}" target="_blank" class="beneficios-slide-icon-btn">
                                                        <img src="/images/mobile/flechaup.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            @else
                                                <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                    <a href="#">Ver más</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                    <a href="{{ $beneficio->url ?: '#' }}" target="_blank" class="beneficios-slide-icon-btn">
                                                        <img src="/images/mobile/flechaup.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Barra de estado del carrusel de beneficios -->
                        <div class="beneficios-carousel-controls">
                            <div class="beneficios-progress-bar">
                                <div class="beneficios-progress-track"></div>
                                <div class="beneficios-progress-indicator"></div>
                            </div>
                            
                            <!-- Botones de navegación y ver todos -->
                            <div class="beneficios-controls-row">
                                <a href="#" class="beneficios-ver-todos-btn">Ver todos los beneficios</a>
                                
                                <button class="beneficios-carousel-btn beneficios-arrow-left" onclick="scrollBeneficiosCarousel('left')">
                                    <img src="/images/desktop/arrow-b.svg" alt="Anterior" />
                                </button>
                                
                                <button class="beneficios-carousel-btn beneficios-arrow-right" onclick="scrollBeneficiosCarousel('right')">
                                    <img src="/images/desktop/arrow-b.svg" alt="Siguiente" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partners Section -->
        <section class="partners-section">
            <div class="partners-content">
                <h2 class="partners-title">
                    <span class="partners-title-text-1">Nos acompañan en la</span>
                    <span class="partners-title-text-2">formación profesional</span>
                </h2>
                
                <!-- Slick Carousel de Logos -->
                <div class="partners-carousel">
                    <div class="partners-slider">
                        @if($partners->count() > 0)
                            <!-- Primera copia de logos -->
                            @foreach($partners as $partner)
                                <div>
                                    <a href="{{ $partner->url }}" target="_blank" class="partners-logo">
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->url }}" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                            
                            <!-- Segunda copia de logos para loop infinito -->
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

        <!-- Contacto Section -->
        <section class="contacto-section">
            <div class="contacto-content">
                <!-- Tercio Izquierdo - Sedes -->
                <div class="contacto-tercio contacto-sedes">
                    <h3 class="contacto-tercio-title">Sedes</h3>
                    <div class="contacto-sedes-content">
                        @foreach($sedes as $sede)
                        <div class="contacto-sede-row" data-sede="{{ Str::slug($sede->nombre) }}">
                            <div class="contacto-sede-text">{{ $sede->nombre }}</div>
                            <div class="contacto-sede-plus">+</div>
                        </div>
                        <div class="contacto-sede-content" id="{{ Str::slug($sede->nombre) }}-content">
                            <div class="contacto-sede-direccion">{{ $sede->direccion }}</div>
                            <div class="contacto-sede-contacto">Contacto: {{ $sede->telefono }}</div>
                            
                            @if(!empty($sede->link_google_maps) && trim($sede->link_google_maps) !== '')
                                <div class="contacto-sede-link">
                                    <a href="{{ $sede->link_google_maps }}" target="_blank" class="contacto-sede-link-maps">
                                        📍 Ver en Maps
                                    </a>
                                </div>
                            @endif
                            
                            @if(!empty($sede->link_whatsapp) && trim($sede->link_whatsapp) !== '')
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
                            @if(isset($contactosInfo) && $contactosInfo->count() > 0)
                                @foreach($contactosInfo as $contacto)
                                    <div class="contacto-info-item">
                                        <span class="contacto-info-label">{{ $contacto->descripcion }} </span>
                                        <span class="contacto-info-value">
                                            @if(Str::contains(Str::lower($contacto->descripcion), ['mail', 'email']))
                                                <a href="mailto:{{ $contacto->contenido }}">{{ $contacto->contenido }}</a>
                                            @elseif(Str::contains(Str::lower($contacto->descripcion), ['whatsapp']))
                                                <a href="https://wa.me/549{{ Str::of($contacto->contenido)->replace(['-', ' '], '') }}" target="_blank">{{ $contacto->contenido }}</a>
                                            @else
                                                {{ $contacto->contenido }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @else
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
                            @endif
                        </div>
                        
                        <!-- Redes Sociales (visible solo en desktop) -->
                        <h4 class="contacto-redes-title contacto-redes-desktop">Redes Sociales</h4>
                        <div class="contacto-redes-icons contacto-redes-desktop">
                            @if(isset($contactosSocial) && $contactosSocial->count() > 0)
                                @foreach($contactosSocial as $social)
                                    <a href="{{ $social->contenido }}" target="_blank" class="contacto-redes-link">
                                        @if($social->icono)
                                            @if(Str::startsWith($social->icono, 'images/'))
                                                <img src="{{ asset($social->icono) }}" alt="{{ $social->descripcion }}" class="contacto-redes-icon">
                                            @else
                                                <img src="{{ asset('storage/' . $social->icono) }}" alt="{{ $social->descripcion }}" class="contacto-redes-icon">
                                            @endif
                                        @else
                                            <span class="contacto-redes-text">{{ $social->descripcion }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            @else
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
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Tercio Redes Sociales (solo visible en mobile) -->
                <div class="contacto-tercio contacto-redes">
                    <h4 class="contacto-redes-title">Redes Sociales</h4>
                    <div class="contacto-redes-icons">
                        @if(isset($contactosSocial) && $contactosSocial->count() > 0)
                            @foreach($contactosSocial as $social)
                                <a href="{{ $social->contenido }}" target="_blank" class="contacto-redes-link">
                                    @if($social->icono)
                                        @if(Str::startsWith($social->icono, 'images/'))
                                            <img src="{{ asset($social->icono) }}" alt="{{ $social->descripcion }}" class="contacto-redes-icon">
                                        @else
                                            <img src="{{ asset('storage/' . $social->icono) }}" alt="{{ $social->descripcion }}" class="contacto-redes-icon">
                                        @endif
                                    @else
                                        <span class="contacto-redes-text">{{ $social->descripcion }}</span>
                                    @endif
                                </a>
                            @endforeach
                        @else
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
                        @endif
                    </div>
                </div>
                
                <!-- Tercio Derecho - Formulario -->
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script>
        // Función para manejar imágenes responsive de carreras
        function handleCarrerasImages() {
            const isMobile = window.innerWidth < 600;
            const desktopImages = document.querySelectorAll('.carreras-image-desktop');
            const mobileImages = document.querySelectorAll('.carreras-image-mobile');
            
            desktopImages.forEach(img => {
                img.style.display = isMobile ? 'none' : 'block';
            });
            
            mobileImages.forEach(img => {
                img.style.display = isMobile ? 'block' : 'none';
            });
        }
        
        // Función para marcar imágenes como cargadas
        function markImagesAsLoaded() {
            const images = document.querySelectorAll('.carrera-image img');
            images.forEach(img => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                }
            });
        }
        
        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            handleCarrerasImages();
            markImagesAsLoaded();
        });
        
        // Ejecutar al redimensionar la ventana
        window.addEventListener('resize', function() {
            handleCarrerasImages();
        });

        // Beneficios Carousel - La inicialización se maneja en app.js
        // No es necesario duplicar la configuración aquí


        // Contacto Sedes Expand/Collapse
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
        });
    </script>
    
</body>
</html>
