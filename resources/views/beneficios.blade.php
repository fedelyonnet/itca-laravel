<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="ITCA - Instituto Tecnológico de Capacitación Automotriz">
    <title>ITCA - Beneficios</title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">

    <!-- Styles -->
        @vite(['resources/css/public.css', 'resources/css/beneficios.css', 'resources/js/app.js'])
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    </head>
<body id="top">
    <!-- Sticky Bar -->
    @if($stickyBar && $stickyBar->visible == true)
    <div class="sticky-bar" style="background-color: {{ $stickyBar->color }} !important;">
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
                <a href="{{ url('/') }}" class="logo">ITCA</a>
                
                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('somos-itca') }}" class="nav-link">
                            Somos ITCA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('carreras') }}" class="nav-link">
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
        <!-- Breadcrumb Section -->
        <section class="beneficios-header-section">
            <div class="container somos-itca-container beneficios-top-container">
                 <div class="lista-carreras-breadcrumb">
                    <a href="/" class="lista-carreras-breadcrumb-link">Inicio</a>
                    <span class="lista-carreras-breadcrumb-separator">></span>
                    <span class="lista-carreras-breadcrumb-current">Beneficios</span>
                </div>
            </div>

            <div class="container somos-itca-container">
                <div class="beneficios-main-content">
                    <h1 class="beneficios-header-title">
                        <span class="beneficios-title-line-1">Los beneficios que marcan</span>
                        <span class="beneficios-title-line-2">
                            <span class="beneficios-highlight">la diferencia</span> al estudiar en ITCA
                        </span>
                    </h1>

                    <div class="beneficios-image-container">
                        @if(isset($content->hero_image) && $content->hero_image)
                            <img src="{{ asset('storage/' . $content->hero_image) }}" alt="Beneficios ITCA" class="beneficios-hero-image">
                        @else
                            <img src="/images/desktop/somos-itca/hero-somos.png" alt="Beneficios ITCA" class="beneficios-hero-image">
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Dropdowns Section -->
        <section class="beneficios-dropdowns-section">
            <div class="container somos-itca-container">
                <div class="dropdowns-main-content">
                    @php
                        $somosItca = \App\Models\SomosItcaContent::first();
                    @endphp

                    <!-- Dropdown 2: ¿Qué es el club ITCA? -->
                    <div class="info-dropdown">
                        <div class="info-dropdown-header">
                            <span class="info-dropdown-title">¿Qué es el club ITCA?</span>
                            <img src="/images/desktop/chevron.png" alt="Abrir" class="info-dropdown-chevron">
                        </div>
                        <div class="info-dropdown-content">
                            <div class="que-es-itca-grid">
                                <div class="video-col relative group cursor-pointer video-container-click">
                                    @if(isset($content) && $content->club_itca_video)
                                        <video class="itca-video" playsinline preload="auto">
                                            <source src="{{ asset('storage/' . $content->club_itca_video) }}" type="video/mp4">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none play-overlay">
                                            <img src="/images/desktop/play.png" alt="Play" class="w-16 h-16 opacity-80 group-hover:opacity-100 transition-opacity">
                                        </div>
                                    @else
                                        <div class="video-placeholder-empty">Video no disponible</div>
                                    @endif
                                </div>
                                <div class="text-col">
                                    @if(isset($content) && $content->club_itca_texto)
                                        @php
                                            $text = e($content->club_itca_texto);
                                            $text = preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', $text);
                                            $text = nl2br($text);
                                        @endphp
                                        <p class="itca-text">{!! $text !!}</p>
                                    @else
                                        <p class="itca-text">
                                            */Club ITCA/* es mucho más que un programa de beneficios:<br>
                                            es ser parte de una comunidad que acompaña tu formación y tu crecimiento profesional, antes, durante y después de estudiar.<br><br>
                                            Está pensado para alumnos y egresados que buscan seguir conectados con el mundo automotriz.<br><br>
                                            Como miembro, accedés a descuentos en herramientas, productos y servicios, promociones con marcas líderes del sector, encuentros de egresados, exposiciones y eventos.
                                        </p>
                                    @endif
                                    
                                    <div class="club-itca-btn-wrapper">
                                        <a href="{{ $content->club_itca_button_url ?? '#' }}" class="club-itca-btn">
                                            <strong>Ingresar</strong>&nbsp;a Club ITCA
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Decision Section -->
        <section class="cta-decide-section">
            <div class="container somos-itca-container">
                <div class="cta-stack">
                    <p class="cta-decide-text">
                        <strong>¿Todavía no te decidiste?</strong><br>
                        Estudiá y capacitate con nosotros
                    </p>

                    <div class="cta-bottom-row">
                        <div class="cta-decor cta-decor-left">
                            <img src="/images/desktop/somos-itca/arrow-somos.png" alt="Flecha" class="cta-arrow-img">
                        </div>

                        <div class="cta-btn-wrapper">
                            @php
                                $whatsappItem = $contactosInfo->first(function($item) {
                                    return Str::contains(Str::lower($item->descripcion), 'whatsapp');
                                });
                                $whatsappUrl = '#';
                                if ($whatsappItem) {
                                    $numeroLimpio = preg_replace('/[^0-9]/', '', $whatsappItem->contenido);
                                    $whatsappUrl = "https://wa.me/" . $numeroLimpio;
                                }
                            @endphp

                            <a href="{{ $whatsappUrl }}" target="_blank" class="cta-chat-btn">
                                <strong>Chatear</strong> <span class="cta-text-desktop">con un asesor de inscripción</span><span class="cta-text-mobile">con un asesor</span>
                            </a>
                        </div>

                        <div class="cta-decor cta-decor-right">
                            <img src="/images/desktop/somos-itca/stripes-somos.png" alt="Decoración" class="cta-stripes-img">
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
        <section class="contacto-section" id="contacto">
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contacto Sedes Logic
            const sedeRows = document.querySelectorAll('.contacto-sede-row[data-sede]');
            sedeRows.forEach(row => {
                row.addEventListener('click', function() {
                    const sedeId = this.getAttribute('data-sede');
                    const content = document.getElementById(sedeId + '-content');
                    // Collapse others
                    document.querySelectorAll('.contacto-sede-content').forEach(otherContent => {
                        if (otherContent !== content) otherContent.classList.remove('active');
                    });
                    if (content) content.classList.toggle('active');
                });
            });

            // Info Dropdown Toggle
            const dropdownHeaders = document.querySelectorAll('.info-dropdown-header');
            dropdownHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const dropdown = this.parentElement;
                    const isOpen = dropdown.classList.toggle('active');
                });
            });

            // Video Logic
            const videoContainers = document.querySelectorAll('.video-container-click');
            videoContainers.forEach(container => {
                const video = container.querySelector('video');
                const overlay = container.querySelector('.play-overlay');
                
                if (!video || !overlay) return;

                container.addEventListener('click', function(e) {
                    if (!video.hasAttribute('controls')) {
                         video.play();
                    }
                });

                video.addEventListener('play', function() {
                    video.setAttribute('controls', 'true');
                    overlay.style.opacity = '0';
                });

                video.addEventListener('pause', function() {
                    overlay.style.opacity = '1'; 
                });
            });

            // Initialize first dropdown as open
            const firstDropdown = document.querySelector('.info-dropdown');
            if (firstDropdown) {
                firstDropdown.classList.add('active');
                const video = firstDropdown.querySelector('video');
                if (video) {
                    // video.muted = true;
                    // video.play().catch(error => console.log("Autoplay prevented:", error));
                }
            }

             // Slick Carousel for Partners
             if ($('.partners-slider').length) {
                $('.partners-slider').slick({
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 0,
                    speed: 3000,
                    cssEase: 'linear',
                    arrows: false,
                    variableWidth: true,
                    pauseOnHover: false,
                    infinite: true,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 5,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 4,
                            }
                        },
                        {
                            breakpoint: 520,
                            settings: {
                                slidesToShow: 3,
                            }
                        }
                    ]
                });
            }
        });
    </script>
    </body>
</html>
