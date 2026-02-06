<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="ITCA - Noticias">
    <title>ITCA - Noticias</title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">

    <!-- Styles -->
        @vite(['resources/css/public.css', 'resources/css/noticias.css', 'resources/js/app.js'])
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
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
                <a href="{{ route('home') }}" class="logo">ITCA</a>
                
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
            <!-- Breadcrumb y Title Section (Noticias) -->
            <section class="noticias-header-section">
                <div class="noticias-top-container">
                    <div class="noticias-breadcrumb">
                        <a href="{{ route('home') }}" class="noticias-breadcrumb-link">Inicio</a>
                        <span class="noticias-breadcrumb-separator">></span>
                        <span class="noticias-breadcrumb-current">Noticias</span>
                    </div>
                </div>

                <div class="noticias-main-content">
                    <h1 class="noticias-header-title">
                        <span class="noticias-title-line-1">Nuestro instituto</span>
                        <span class="noticias-title-line-2">
                             <span class="noticias-highlight">en acción</span>
                        </span>
                    </h1>

                    <div class="noticias-latest-badge">
                        <span class="noticias-badge-regular">Última</span>
                        <span class="noticias-badge-semibold">noticia</span>
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hamburger Menu
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');
            
            if (hamburger) {
                hamburger.addEventListener('click', function() {
                    this.classList.toggle('active');
                    navLinks.classList.toggle('active');
                    document.body.classList.toggle('no-scroll');
                });
            }

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
                    document.querySelectorAll('.contacto-sede-plus').forEach(plus => {
                        if (plus !== this.querySelector('.contacto-sede-plus')) plus.textContent = '+';
                    });
                    
                    // Toggle current
                    const isOpen = content.classList.toggle('active');
                    this.querySelector('.contacto-sede-plus').textContent = isOpen ? '-' : '+';
                });
            });

            // Partners Carousel
            $('.partners-slider').slick({
                autoplay: true,
                autoplaySpeed: 0,
                speed: 1500,
                cssEase: 'linear',
                infinite: true,
                arrows: false,
                dots: false,
                pauseOnHover: false,
                variableWidth: true
            });
        });
    </script>
</body>
</html>
