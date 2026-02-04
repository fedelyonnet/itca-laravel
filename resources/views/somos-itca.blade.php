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
            <!-- Main / Intro Section -->
            <section class="main-section" id="main">
                <div class="container somos-itca-container">
                    <div class="main-content">
                <h1 class="main-title">
                    <span class="main-title-line-1">Tu pasión por la mecánica,</span>
                    <span class="main-title-line-2">
                        <span class="highlight">Nuestro compromiso</span>
                    </span>
                    <span class="main-title-line-3">con tu futuro</span>
                </h1>
                
                <div class="main-image-container">
                    <img src="/images/desktop/somos-itca/d1.png" alt="" class="deco-1">
                    <img src="/images/desktop/somos-itca/d2.png" alt="" class="deco-2">
                    @if(isset($content->hero_image) && $content->hero_image)
                        <img src="{{ asset('storage/' . $content->hero_image) }}" alt="Somos ITCA" class="main-hero-image">
                    @else
                        <img src="/images/desktop/somos-itca/hero-somos.png" alt="Somos ITCA" class="main-hero-image">
                    @endif
                </div>
                    </div>
                </div>
            </section>

            <!-- Main Content Section -->
            <section class="main-content-section">
                <div class="container somos-itca-container">
                    <div class="main-content">
                        <div class="info-dropdown">
                            <div class="info-dropdown-header">
                                <span class="info-dropdown-title">¿Qué es ITCA?</span>
                                <img src="/images/desktop/chevron.png" alt="Abrir" class="info-dropdown-chevron">
                            </div>
                            <div class="info-dropdown-content">
                                <div class="que-es-itca-grid">
                                    <div class="video-col relative group cursor-pointer video-container-click">
                                        @if(isset($content) && $content->video_url)
                                            <video src="{{ asset('storage/' . $content->video_url) }}" class="itca-video" playsinline></video>
                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none play-overlay">
                                                <img src="/images/desktop/play.png" alt="Play" class="w-16 h-16 opacity-80 group-hover:opacity-100 transition-opacity">
                                            </div>
                                        @else
                                            <div class="video-placeholder-empty">Video no disponible</div>
                                        @endif
                                    </div>
                                    <div class="text-col">
                                        @if(isset($content) && $content->que_es_itca)
                                            @php
                                                // Convertir */texto/* a <strong>texto</strong> y saltos de línea a <br>
                                                $formattedText = nl2br(preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', e($content->que_es_itca)));
                                                
                                                // Para asegurar que los strongs parseados no se escapen, usamos e() antes y luego revertimos SOLO para strong
                                                // Una forma más segura:
                                                $text = e($content->que_es_itca); // Escapar todo primero
                                                $text = preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', $text); // Aplicar negrita
                                                $text = nl2br($text); // Aplicar br
                                            @endphp
                                            <p class="itca-text">{!! $text !!}</p>
                                        @else
                                            <p class="itca-text">
                                                <strong>ITCA (Instituto Tecnológico de Capacitación Automotriz)</strong> es el lugar donde tu pasión por los fierros se convierte en una profesión real.
                                            </p>
                                            <p class="itca-text">
                                                Es una <strong>institución educativa especializada en mecánica, electrónica y tecnologías del automóvil y la moto</strong>, pensada para jóvenes que quieren aprender haciendo, con clases prácticas, equipamiento real y una visión enfocada en salida laboral inmediata.
                                            </p>
                                            <p class="itca-text itca-text-extra">
                                                Vas a aprender mecánica con autos y motos reales, con docentes expertos y talleres equipados, preparándote para trabajar en talleres, abrir tu propio emprendimiento o integrarte fácilmente al mercado automotriz.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-dropdown">
                            <div class="info-dropdown-header">
                                <span class="info-dropdown-title">¿Por qué elegirnos?</span>
                                <img src="/images/desktop/chevron.png" alt="Abrir" class="info-dropdown-chevron">
                            </div>
                            <div class="info-dropdown-content">
                                <div class="por-que-grid">
                                    <!-- Image Column -->
                                    <div class="por-que-img-col">
                                        @if(isset($content) && $content->img_por_que)
                                            <img src="{{ asset('storage/' . $content->img_por_que) }}" alt="Por qué elegirnos" class="por-que-main-img">
                                        @else
                                            <!-- Placeholder or hidden if empty -->
                                            <div class="por-que-img-placeholder">Sin imagen</div>
                                        @endif
                                    </div>

                                    <!-- Text Column -->
                                    <div class="por-que-text-col">
                                        <ul class="por-que-list">
                                            @if(isset($content) && $content->porQueItems && $content->porQueItems->count() > 0)
                                                @foreach($content->porQueItems as $item)
                                                    <li class="por-que-item">
                                                        <div class="por-que-icon-wrapper">
                                                            @if($item->image_path)
                                                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="Icono" class="por-que-icon">
                                                            @else
                                                                {{-- Fallback a ciclo de iconos predeterminados --}}
                                                                @php $iconIndex = ($loop->index % 5) + 1; @endphp
                                                                <img src="/images/desktop/somos-itca/ico{{ $iconIndex }}.png" alt="Icono" class="por-que-icon">
                                                            @endif
                                                        </div>
                                                        <span class="por-que-text">{{ $item->descripcion }}</span>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="por-que-item">
                                                    <div class="por-que-icon-wrapper">
                                                        <img src="/images/desktop/somos-itca/ico1.png" alt="Icono" class="por-que-icon">
                                                    </div>
                                                    <span class="por-que-text">Práctica real desde el primer día.</span>
                                                </li>
                                                <li class="por-que-item">
                                                    <div class="por-que-icon-wrapper">
                                                        <img src="/images/desktop/somos-itca/ico2.png" alt="Icono" class="por-que-icon">
                                                    </div>
                                                    <span class="por-que-text">Pasión que se convierte en profesión.</span>
                                                </li>
                                                <li class="por-que-item">
                                                    <div class="por-que-icon-wrapper">
                                                        <img src="/images/desktop/somos-itca/ico3.png" alt="Icono" class="por-que-icon">
                                                    </div>
                                                    <span class="por-que-text">Salida laboral concreta.</span>
                                                </li>
                                                <li class="por-que-item">
                                                    <div class="por-que-icon-wrapper">
                                                        <img src="/images/desktop/somos-itca/ico4.png" alt="Icono" class="por-que-icon">
                                                    </div>
                                                    <span class="por-que-text">Reconocimiento de la industria.</span>
                                                </li>
                                                <li class="por-que-item">
                                                    <div class="por-que-icon-wrapper">
                                                        <img src="/images/desktop/somos-itca/ico5.png" alt="Icono" class="por-que-icon">
                                                    </div>
                                                    <span class="por-que-text">Flexibilidad para estudiar: presencial y semipresencial.</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-dropdown instalaciones-dropdown">
                            <div class="info-dropdown-header">
                                <span class="info-dropdown-title">Instalaciones</span>
                                <img src="/images/desktop/chevron.png" alt="Abrir" class="info-dropdown-chevron">
                            </div>
                            <div class="info-dropdown-content instalaciones-dropdown-content">
                                <p class="instalaciones-text">
                                    En ITCA, cada aula y cada taller están pensados para que te sumerjas en la mecánica desde el primer día.<span class="mobile-hidden-text"><br>
                                    No hay teoría sin práctica: vas a tocar, desarmar y construir sobre lo que realmente usan los talleres de hoy.</span>
                                </p>

                                @if(isset($instalaciones) && $instalaciones->count() > 0)
                                    <!-- Carrusel Instalaciones (Clon de Formadores) -->
                                    <div class="formadores-section">
                                        <div class="fotos-carousel-section">
                                            <div class="swiper fotos-swiper formadores-swiper">
                                                <div class="swiper-wrapper">
                                                    @foreach($instalaciones as $inst)
                                                        <div class="swiper-slide fotos-carousel-slide formador-slide">
                                                            <div class="formador-img-wrapper">
                                                                <img src="{{ asset('storage/' . $inst->image_path) }}" 
                                                                     alt="Instalación ITCA" 
                                                                     class="fotos-slide-img formador-img" 
                                                                     loading="lazy" />
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!-- Paginación para Mobile -->
                                                <!-- Paginación para Mobile personalizada (Barra de progreso arriba, flechas abajo) -->
                                                <div class="fotos-carousel-controls mobile-only-controls" style="margin-top: 20px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
                                                    
                                                    <!-- Barra de Progreso -->
                                                    <div class="fotos-progress-bar mobile-progress-bar" style="width: 100%; max-width: 200px; height: 4px; background-color: rgba(255,255,255,0.2); border-radius: 2px; position: relative; overflow: hidden;">
                                                        <div class="fotos-progress-indicator mobile-progress-indicator" style="position: absolute; top: 0; left: 0; height: 100%; background-color: #65E09C; width: 0%; transition: width 0.3s ease;"></div>
                                                    </div>

                                                    <!-- Flechas de Navegación -->
                                                    <div class="mobile-arrows-wrapper" style="display: flex; gap: 20px;">
                                                        <button class="fotos-carousel-btn fotos-carousel-btn-prev mobile-prev-btn" style="background: none; border: none; cursor: pointer; padding: 0; opacity: 0.5; transition: opacity 0.3s;">
                                                            <img src="/images/desktop/arrow-b.svg" alt="Anterior" class="fotos-arrow-left mobile-control-img" />
                                                        </button>
                                                        <button class="fotos-carousel-btn fotos-carousel-btn-next mobile-next-btn" style="background: none; border: none; cursor: pointer; padding: 0; opacity: 1; transition: opacity 0.3s;">
                                                            <img src="/images/desktop/arrow-b.svg" alt="Siguiente" class="mobile-control-img" />
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <!-- Controles Desktop (fuera del swiper) -->
                                            <div class="fotos-controls-wrapper formadores-controls">
                                                <button class="fotos-carousel-btn fotos-carousel-btn-prev">
                                                    <img src="/images/desktop/arrow-b.svg" alt="Anterior" class="fotos-arrow-left" />
                                                </button>
                                                <button class="fotos-carousel-btn fotos-carousel-btn-next">
                                                    <img src="/images/desktop/arrow-b.svg" alt="Siguiente" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif



                                <div class="instalaciones-features-container">
                                    <ul class="instalaciones-list">
                                        @if(isset($instalacionItems) && $instalacionItems->count() > 0)
                                            @foreach($instalacionItems as $item)
                                                <li class="instalaciones-item">
                                                    @php $folder = $item->image_path ? asset('storage/'.$item->image_path) : '/images/desktop/somos-itca/star'.(($loop->index % 3) + 1).'.png'; @endphp
                                                    <img src="/images/desktop/somos-itca/star{{ ($loop->index % 3) + 1 }}.png" alt="Star" class="instalaciones-star">
                                                    <span>{!! preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', e($item->descripcion)) !!}</span>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="instalaciones-item">
                                                <img src="/images/desktop/somos-itca/star1.png" alt="Star" class="instalaciones-star">
                                                <span>Trabajo directo sobre <strong>motores y vehículos.</strong></span>
                                            </li>
                                            <li class="instalaciones-item">
                                                <img src="/images/desktop/somos-itca/star2.png" alt="Star" class="instalaciones-star">
                                                <span><strong>Equipamiento completo</strong> para una formación profesional.</span>
                                            </li>
                                            <li class="instalaciones-item">
                                                <img src="/images/desktop/somos-itca/star3.png" alt="Star" class="instalaciones-star">
                                                <span><strong>Aulas Taller</strong> que integran teoría y práctica.</span>
                                            </li>
                                            <li class="instalaciones-item">
                                                <img src="/images/desktop/somos-itca/star1.png" alt="Star" class="instalaciones-star">
                                                <span><strong>Confort:</strong> aire acondicionado, proyector y sillas cómodas.</span>
                                            </li>
                                            <li class="instalaciones-item">
                                                <img src="/images/desktop/somos-itca/star2.png" alt="Star" class="instalaciones-star">
                                                <span><strong>Seguridad:</strong> detectores de humo, cámaras de seguridad y normas que garantizan un entorno cuidado.</span>
                                            </li>
                                            <li class="instalaciones-item">
                                                <img src="/images/desktop/somos-itca/star3.png" alt="Star" class="instalaciones-star">
                                                <span><strong>Ubicación estratégica:</strong> Sede Central y Sedes en GBA para estar cerca tuyo.</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="info-dropdown formadores-dropdown">
                            <div class="info-dropdown-header">
                                <span class="info-dropdown-title">Formadores</span>
                                <img src="/images/desktop/chevron.png" alt="Abrir" class="info-dropdown-chevron">
                            </div>
                            <div class="info-dropdown-content">
                                <p class="formadores-text-intro">
                                    @if(isset($content->formadores_texto) && $content->formadores_texto)
                                        @php
                                            $formText = e($content->formadores_texto);
                                            $formText = preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', $formText);
                                            $formText = nl2br($formText);
                                        @endphp
                                        {!! $formText !!}
                                    @else
                                        Quienes enseñan en nuestra institución son profesionales que viven la mecánica día a día.<br>
                                        Contamos con un equipo de <strong>más de 50 docentes</strong>, con años de experiencia en talleres y en la industria<br>
                                        automotriz, que transmiten sus conocimientos con compromiso, cercanía y vocación por enseñar.
                                    @endif
                                </p>


                                @if(isset($formadores) && $formadores->count() > 0)
                                    <!-- Carrusel Formadores (Responsive para Desktop y Mobile) -->
                                    <div class="formadores-section">
                                        <div class="fotos-carousel-section">
                                            <div class="swiper fotos-swiper formadores-swiper">
                                                <div class="swiper-wrapper">
@foreach($formadores as $formador)<div class="swiper-slide fotos-carousel-slide formador-slide"><div class="formador-img-wrapper"><img src="{{ asset('storage/' . $formador->image_path) }}" alt="{{ $formador->nombre }}" class="fotos-slide-img formador-img" loading="lazy" /></div><h3 class="formador-name">{{ $formador->nombre }}</h3></div>@endforeach
                                                </div>
                                                <!-- Paginación para Mobile personalizada (Barra de progreso arriba, flechas abajo) -->
                                                <div class="fotos-carousel-controls mobile-only-controls" style="margin-top: 20px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
                                                    
                                                    <!-- Barra de Progreso -->
                                                    <div class="fotos-progress-bar mobile-progress-bar" style="width: 100%; max-width: 200px; height: 4px; background-color: rgba(255,255,255,0.2); border-radius: 2px; position: relative; overflow: hidden;">
                                                        <div class="fotos-progress-indicator mobile-progress-indicator" style="position: absolute; top: 0; left: 0; height: 100%; background-color: #65E09C; width: 0%; transition: width 0.3s ease;"></div>
                                                    </div>

                                                    <!-- Flechas de Navegación -->
                                                    <div class="mobile-arrows-wrapper" style="display: flex; gap: 20px;">
                                                        <button class="fotos-carousel-btn fotos-carousel-btn-prev mobile-prev-btn" style="background: none; border: none; cursor: pointer; padding: 0; opacity: 0.5; transition: opacity 0.3s;">
                                                            <img src="/images/desktop/arrow-b.svg" alt="Anterior" class="fotos-arrow-left mobile-control-img" />
                                                        </button>
                                                        <button class="fotos-carousel-btn fotos-carousel-btn-next mobile-next-btn" style="background: none; border: none; cursor: pointer; padding: 0; opacity: 1; transition: opacity 0.3s;">
                                                            <img src="/images/desktop/arrow-b.svg" alt="Siguiente" class="mobile-control-img" />
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <!-- Controles Desktop (fuera del swiper) -->
                                            <div class="fotos-controls-wrapper formadores-controls">
                                                <button class="fotos-carousel-btn fotos-carousel-btn-prev">
                                                    <img src="/images/desktop/arrow-b.svg" alt="Anterior" class="fotos-arrow-left" />
                                                </button>
                                                <button class="fotos-carousel-btn fotos-carousel-btn-next">
                                                    <img src="/images/desktop/arrow-b.svg" alt="Siguiente" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p style="color: #999; font-style: italic; text-align: center; padding: 20px;">
                                        Próximamente conocerás a nuestro equipo.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Info Metrics Section -->
            <section class="info-metrics-section">
                <div class="container metrics-container somos-itca-container">
                    <div class="metrics-grid">
                        @for($i = 1; $i <= 4; $i++)
                            @php
                                $num = $content->{"m{$i}_number"} ?? '';
                                $title = $content->{"m{$i}_title"} ?? '';
                                $text = $content->{"m{$i}_text"} ?? '';
                                
                                if($text) {
                                    $text = nl2br(preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', e($text)));
                                }
                            @endphp
                            
                            @if($num || $title || $text)
                            <!-- Metric {{ $i }} -->
                            <div class="metric-item">
                                <h2 class="metric-number">{{ $num }}</h2>
                                <h3 class="metric-title">{{ $title }}</h3>
                                <div class="metric-text">
                                    {!! $text !!}
                                </div>
                            </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </section>

            <!-- Categorías Section -->
            <section class="categorias-section">
                <div class="categorias-grid">
                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $img = $content->{"cat{$i}_img"} ?? '';
                            $title = $content->{"cat{$i}_title"} ?? '';
                            $text = $content->{"cat{$i}_text"} ?? '';
                            
                            if($text) {
                                $text = nl2br(preg_replace('/\*\/(.*?)\/\*/', '<strong>$1</strong>', e($text)));
                            }
                        @endphp

                        @if($img || $title || $text)
                        <!-- Card {{ $i }}: {{ $title }} -->
                        <div class="categoria-card">
                            <div class="categoria-card-inner">
                                <div class="categoria-card-front">
                                    <div class="categoria-img-wrapper">
                                        @if($img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="{{ $title }}" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="categoria-label categoria-label-front">{{ $title }}</div>
                                </div>
                                <div class="categoria-card-back">
                                    <div class="categoria-desc">
                                        {!! $text !!}
                                    </div>
                                    <div class="categoria-label categoria-label-back">{{ $title }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endfor
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Info Dropdown Toggle
            const dropdownHeaders = document.querySelectorAll('.info-dropdown-header');
            
            dropdownHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    // Toggle active class on the parent container
                    const dropdown = this.parentElement;
                    const isOpen = dropdown.classList.toggle('active');

                    // If it's the Instalaciones dropdown
                    if (dropdown.classList.contains('instalaciones-dropdown')) {
                        const swiperEl = dropdown.querySelector('.fotos-swiper');
                        
                        if (isOpen) {
                            // Opened: Wait for transition then set fully-open for overflow visible
                            setTimeout(() => {
                                dropdown.classList.add('fully-open');
                                if (swiperEl && swiperEl.swiper) {
                                    swiperEl.swiper.update();
                                }
                            }, 600); // 600ms matches CSS transition duration
                        } else {
                            // Closed: Remove fully-open immediately to clip content during collapse
                            dropdown.classList.remove('fully-open');
                        }
                    }
                });
            });

            // Video Logic
            const videoContainers = document.querySelectorAll('.video-container-click');
            
            videoContainers.forEach(container => {
                const video = container.querySelector('video');
                const overlay = container.querySelector('.play-overlay');
                
                if (!video || !overlay) return;

                // 1. Click en el contenedor (para el primer Play o cuando no hay controles)
                container.addEventListener('click', function(e) {
                    // Si ya tiene controles, dejamos que el navegador maneje los clicks (play/pause/seek)
                    // Solo intervenimos si NO tiene controles (estado inicial)
                    if (!video.hasAttribute('controls')) {
                         video.play();
                    }
                });

                // 2. Al dar Play
                video.addEventListener('play', function() {
                    video.setAttribute('controls', 'true'); // Habilitar controles nativos
                    overlay.style.opacity = '0'; // Ocultar botón grande
                });

                // 3. Al Pausar
                video.addEventListener('pause', function() {
                    // Mantenemos los controles visibles para que el usuario pueda usar la barra
                    // Pero mostramos el botón grande semi-transparente como indicador
                    overlay.style.opacity = '1'; 
                });
            });

            // Contacto Sedes Logic (Existing)
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

            // Flip Cards Logic - Click to toggle
            const cards = document.querySelectorAll('.categoria-card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    this.classList.toggle('is-flipped');
                });
            });

            // Initialize first dropdown as open and play video muted
            const firstDropdown = document.querySelector('.info-dropdown');
            if (firstDropdown) {
                firstDropdown.classList.add('active');
                
                const videoContainer = firstDropdown.querySelector('.video-container-click');
                if (videoContainer) {
                    const video = videoContainer.querySelector('video');
                    if (video) {
                        // video.muted = true;
                        // video.play().catch(error => console.log("Autoplay prevented:", error));
                    }
                }
            }
        });
    </script>
    </body>
</html>
