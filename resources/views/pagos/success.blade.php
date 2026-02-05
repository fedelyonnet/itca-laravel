<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="ITCA - Pago Exitoso">
    <title>Pago Exitoso - ITCA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/public.css', 'resources/css/ret-mp.css', 'resources/js/app.js'])
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body id="top">
    <!-- Sticky Bar -->
    @if(isset($stickyBar) && $stickyBar && $stickyBar->visible == true)
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
    <header class="header {{ isset($stickyBar) && $stickyBar && $stickyBar->visible == true ? 'header-with-sticky' : '' }}">
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
                        <a href="{{ route('carreras') }}" class="nav-link" data-target="carreras">
                            Carreras
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('beneficios') }}" class="nav-link">
                            Beneficios
                        </a>
                    </li>
                    <li>
                        <a href="#contacto" class="nav-link" data-target="contacto">
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

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-container">
            <div class="breadcrumb-list lista-carreras-breadcrumb carrera-show-breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Inicio</a>
                <span class="breadcrumb-separator lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                
                <a href="{{ route('carreras') }}" class="breadcrumb-link lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Carreras</a>
                <span class="breadcrumb-separator lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>

                @if(isset($nombre_curso) && $nombre_curso)
                <span class="breadcrumb-link lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">{{ $nombre_curso }}</span>
                <span class="breadcrumb-separator lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                @else
                <span class="breadcrumb-link lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Name carrera dummy text</span>
                <span class="breadcrumb-separator lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                @endif
                
                <span class="breadcrumb-item active lista-carreras-breadcrumb-current carrera-show-breadcrumb-current">
                    Pre-Inscripción <span class="breadcrumb-separator lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span> Pago Confirmado
                </span>
            </div>
        </div>
    </div>

    <main>
        <!-- Success Message Section -->
        <section class="payment-status-section">
            <div class="payment-status-container">
                <h1 class="payment-status-title">
                    <span class="payment-status-title-1">Hoy no sólo te inscribiste:</span>
                    <span class="payment-status-title-2">le diste <span class="payment-status-highlight">arranque a tu formación</span></span>
                </h1>

                <!-- Payment Details Box -->
                <div class="payment-details-box">
                    <div class="payment-details-header">
                        <div class="payment-details-header-content">
                            <img src="{{ asset('images/logoblue.png') }}" alt="ITCA Logo" class="payment-header-logo">
                            <h2 class="payment-header-title">
                                <span class="title-medium">¡Compra </span>
                                <span class="title-bold">confirmada!</span>
                            </h2>
                        </div>
                    </div>
                    <div class="payment-details-body">
                        <div class="payment-details-col-left">
                            <div class="course-info-card">
                                <p class="receipt-summary-title">Resúmen de compra</p>
                                <h3 class="receipt-payment-for">Pago de <span class="semibold">matrícula</span> para:</h3>
                                <div class="course-info-list">
                                    <div class="receipt-item">
                                        <div class="receipt-icon">
                                            <img src="{{ asset('images/desktop/success/wrench.png') }}" alt="">
                                        </div>
                                        <div class="receipt-info">
                                            <p class="receipt-item-title">Carrera</p>
                                            <p class="receipt-item-subtitle">{{ $cursada?->carrera ?? $nombre_curso ?? 'Carrera no especificada' }}</p>
                                        </div>
                                    </div>

                                    <div class="receipt-item">
                                        <div class="receipt-icon">
                                            <img src="{{ asset('images/desktop/success/spot.png') }}" alt="">
                                        </div>
                                        <div class="receipt-info">
                                            <p class="receipt-item-title">Sede</p>
                                            <p class="receipt-item-subtitle">{{ $cursada?->sede ?? 'Sede no especificada' }}</p>
                                        </div>
                                    </div>

                                    <div class="receipt-item">
                                        <div class="receipt-icon">
                                            <img src="{{ asset('images/desktop/success/gear.png') }}" alt="">
                                        </div>
                                        <div class="receipt-info">
                                            <p class="receipt-item-title">Modalidad</p>
                                            <p class="receipt-item-subtitle">{{ $cursada?->xModalidad ?? 'Modalidad no especificada' }}</p>
                                        </div>
                                    </div>

                                    <div class="receipt-item">
                                        <div class="receipt-icon">
                                            <img src="{{ asset('images/desktop/success/calendar.png') }}" alt="">
                                        </div>
                                        <div class="receipt-info">
                                            <p class="receipt-item-title">Fecha de inicio</p>
                                            <p class="receipt-item-subtitle">
                                                @if(isset($cursada) && isset($cursada->Fecha_Inicio))
                                                    {{ \Carbon\Carbon::parse($cursada->Fecha_Inicio)->format('d/m/Y') }}
                                                @else
                                                    Fecha no disponible
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="receipt-item">
                                        <div class="receipt-icon">
                                            <img src="{{ asset('images/desktop/success/clock.png') }}" alt="">
                                        </div>
                                        <div class="receipt-info">
                                            <p class="receipt-item-title">Turno</p>
                                            <p class="receipt-item-subtitle">{{ $cursada?->xTurno ?? 'Turno no especificado' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-details-col-right">
                            <div class="payment-info-content">
                                <h3>¡Tu pago fue procesado <span class="semibold">con éxito!</span></h3>
                                <ul class="payment-meta-list">
                                    <li>Código de compra: #{{ $inscripcion?->collection_id ?? 'n/d' }}</li>
                                    <li>Fecha: {{ $inscripcion ? \Carbon\Carbon::parse($inscripcion->created_at)->translatedFormat('j M Y – H:i') . ' hs' : now()->translatedFormat('j M Y – H:i') . ' hs' }}</li>
                                    <li>Estado del pago: <span class="status-approved">Aprobado</span></li>
                                </ul>
                                
                                <div class="payment-price-breakdown">
                                    @php
                                        $finalPaid = (float) ($inscripcion?->monto_matricula ?? 0);
                                        $discountAmount = (float) ($inscripcion?->monto_descuento ?? 0);
                                        $subtotal = $finalPaid + $discountAmount;
                                        $couponCode = $inscripcion?->codigo_descuento ?? null;
                                    @endphp

                                    <div class="price-row">
                                        <span>Subtotal:</span>
                                        <span class="price-value">${{ number_format($subtotal, 2, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="price-row {{ $discountAmount > 0 ? 'savings' : '' }}">
                                        <span>Descuento{{ $couponCode ? ' ('.$couponCode.')' : '' }}:</span>
                                        <span class="price-value">
                                            @if($discountAmount > 0)
                                                -${{ number_format($discountAmount, 2, ',', '.') }}
                                            @else
                                                $0,00
                                            @endif
                                        </span>
                                    </div>

                                    <div class="price-row total">
                                        <span>Total:</span>
                                        <span class="price-value">${{ number_format($finalPaid, 2, ',', '.') }}</span>
                                    </div>

                                    <p class="tax-note">
                                        @php
                                            // Mostramos estrictamente el valor de Sin_iva_Mat de la tabla cursadas
                                            $valSinIva = $cursada?->Sin_iva_Mat ?? 0;
                                        @endphp
                                        Precio total sin impuestos nacionales: ARS $ {{ number_format($valSinIva, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="receipt-col-divider"></div>

                            <div class="payment-actions-area">
                                <a href="{{ isset($inscripcion) ? route('mp.comprobante', $inscripcion->id) : '#' }}" class="btn-download-receipt" target="_blank">
                                    Descargar comprobante
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="payment-details-footer">
                        <p class="footer-text-1">¿Tenés dudas sobre la inscripción?</p>
                        <p class="footer-text-2">No te preocupes te comunicamos con un asesor:</p>
                        
                        <div class="footer-contact-actions">
                            @php
                                $whatsapp = $contactosInfo->filter(function($c) {
                                    return Str::contains(Str::lower($c->descripcion), 'whatsapp');
                                })->first();
                                $waNumber = $whatsapp ? Str::of($whatsapp->contenido)->replace(['-', ' ', '+'], '') : '1122674822';
                                $waLink = "https://wa.me/549" . $waNumber;
                            @endphp
                            <a href="{{ $waLink }}" target="_blank" class="btn-whatsapp-footer">
                                <strong>Chatear</strong> con un asesor de inscripción
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Beneficios Section -->
        <section class="beneficios-section" id="beneficios">
            <div class="beneficios-container">
                <h2 class="beneficios-title">
                    <span class="beneficios-text-regular">Ser </span>
                    <span class="beneficios-text-bold">estudiante ITCA</span>
                    <span class="beneficios-text-regular"> tiene </span>
                    <span class="beneficios-text-highlight">sus beneficios</span>
                </h2>
                
                @if(isset($beneficios) && count($beneficios) > 0)
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
                    <!-- Barra de progreso -->
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
                                            <div class="beneficios-slide-icon beneficios-slide-icon-hidden">
                                                <a href="#" class="beneficios-slide-icon-btn">
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
                @endif
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
                        @if(isset($partners) && $partners->count() > 0)
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
                        @if(isset($sedes))
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
                        @endif
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
    <!-- Slick Carousel JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <!-- JavaScript para desplegable de sedes -->
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
        });
    </script>
</body>
</html>
