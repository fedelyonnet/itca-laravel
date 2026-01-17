<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="ITCA - Instituto Tecnológico de Capacitación Automotriz">
    <title>ITCA - Somos ITCA</title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">

    <!-- Styles -->
        @vite(['resources/css/public.css', 'resources/css/somos-itca.css', 'resources/js/app.js'])
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
                        <a href="{{ url('/#beneficios') }}" class="nav-link">
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    </body>
</html>
