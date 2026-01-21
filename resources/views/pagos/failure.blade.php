<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ITCA - Pago Fallido">
    <title>Problema con el Pago - ITCA</title>

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
                        <a href="#beneficios" class="nav-link" data-target="beneficios">
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
                    Pre-Inscripción <span class="breadcrumb-separator lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span> Pago Rechazado
                </span>
            </div>
        </div>
    </div>

    <main>
        <!-- Failure Message Section -->
        <section class="payment-status-section">
            <div class="payment-status-container">
                <h1 class="payment-status-title failure-main-title">
                    <span class="failure-line-1 payment-status-highlight payment-status-highlight-failure">Tu pago fue rechazado</span> 
                    <span class="failure-line-2">pero no dejes que una pequeña</span>
                    <span class="failure-line-3">falla frene tu camino</span>
                </h1>

                <!-- Tarjeta de detalles del pago fallido -->
                <div class="payment-details-box">
                    <div class="payment-details-header">
                        <div class="payment-details-header-content">
                            <img src="{{ asset('images/logoblue.png') }}" alt="ITCA Logo" class="payment-header-logo">
                            <h2 class="payment-header-title">
                                <span class="title-medium">¡Pago</span> <span class="title-bold">rechazado!</span>
                            </h2>
                        </div>
                    </div>

                    <div class="payment-details-body">
                        <!-- Columna Izquierda: Detalles del Curso -->
                        <div class="payment-details-col-left">
                            <div class="receipt-info-content">
                                <div class="receipt-summary-title">Resumen de compra</div>
                                <h3 class="receipt-payment-for">Pago de <span class="semibold">matrícula</span> para:</h3>
                                <div class="failure-course-info-list">
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
                            
                            <div class="payment-actions-area">
                                <div class="failure-modification-container" style="margin-top: 0;"> <!-- Remove margin-top as area has padding -->
                                    <a href="{{ route('carreras') }}" class="btn-modify-choice">
                                        <img src="{{ asset('images/arrow-left.png') }}" class="btn-modify-icon" alt="Volver">
                                        Modificar mi elección
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Formulario (Restaurada) -->
                        <div class="payment-details-col-right">
                            <div class="payment-info-content failure-right-content">
                                <h3 class="failure-right-title">Completá tus datos nuevamente para continuar:</h3>
                                
                                <form class="cursada-formulario failure-form" id="formulario-retry">
                                    <div class="cursada-formulario-grid">
                                        <div class="cursada-formulario-columna-izq">
                                            <div class="cursada-formulario-campo">
                                                <input type="text" name="nombre" id="nombre-retry" placeholder="Nombre *" 
                                                    value="{{ $inscripcion?->lead?->nombre }}"
                                                    class="cursada-formulario-input" tabindex="1">
                                            </div>
                                            <div class="cursada-formulario-campo">
                                                <input type="text" name="dni" id="dni-retry" placeholder="DNI *" 
                                                    value="{{ $inscripcion?->lead?->dni }}"
                                                    class="cursada-formulario-input" maxlength="8" pattern="[0-9]{7,8}" inputmode="numeric" tabindex="3">
                                            </div>
                                            <div class="cursada-formulario-campo cursada-formulario-campo-telefono">
                                                <div class="cursada-telefono-wrapper">
                                                    <div class="cursada-telefono-prefijo-container">
                                                        @php
                                                            $prefijo = $inscripcion?->lead?->telefono_prefijo ?? '+54';
                                                        @endphp
                                                        <select name="telefono_prefijo" id="telefono-prefijo-retry" class="cursada-telefono-prefijo" tabindex="-1">
                                                            <option value="+54" {{ $prefijo == '+54' ? 'selected' : '' }}>+54</option>
                                                            <option value="+1" {{ $prefijo == '+1' ? 'selected' : '' }}>+1</option>
                                                            <option value="+52" {{ $prefijo == '+52' ? 'selected' : '' }}>+52</option>
                                                            <option value="+55" {{ $prefijo == '+55' ? 'selected' : '' }}>+55</option>
                                                            <option value="+34" {{ $prefijo == '+34' ? 'selected' : '' }}>+34</option>
                                                            <option value="+33" {{ $prefijo == '+33' ? 'selected' : '' }}>+33</option>
                                                            <option value="+39" {{ $prefijo == '+39' ? 'selected' : '' }}>+39</option>
                                                            <option value="+49" {{ $prefijo == '+49' ? 'selected' : '' }}>+49</option>
                                                        </select>
                                                        <span class="cursada-telefono-chevron">▼</span>
                                                    </div>
                                                    <input type="tel" name="telefono" id="telefono-retry" placeholder="Teléfono *" 
                                                        value="{{ $inscripcion?->lead?->telefono }}"
                                                        class="cursada-formulario-input cursada-telefono-input" maxlength="14" pattern="[0-9]{8,14}" inputmode="numeric" tabindex="5">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cursada-formulario-columna-der">
                                            <div class="cursada-formulario-campo">
                                                <input type="text" name="apellido" id="apellido-retry" placeholder="Apellido *" 
                                                    value="{{ $inscripcion?->lead?->apellido }}"
                                                    class="cursada-formulario-input" tabindex="2">
                                            </div>
                                            <div class="cursada-formulario-campo">
                                                <input type="email" name="correo" id="correo-retry" placeholder="Correo electrónico *" 
                                                    value="{{ $inscripcion?->lead?->correo }}"
                                                    class="cursada-formulario-input" tabindex="4">
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <p class="failure-retry-text">
                                    <strong>Intentá nuevamente</strong> procesar el pago:
                                </p>
                                
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

                                    <p class="tax-note-failure">
                                        @php
                                            // Mostramos estrictamente el valor de Sin_iva_Mat de la tabla cursadas
                                            $valSinIva = $cursada?->Sin_iva_Mat ?? 0;
                                        @endphp
                                        Precio total sin impuestos nacionales: ARS $ {{ number_format($valSinIva, 2, ',', '.') }}
                                    </p>
                                </div>

                                <p class="failure-matricula-note">
                                    <strong>Ahora solo debés pagar la matrícula</strong> para poder reservar tu vacante
                                </p>
                            </div>

                            <div class="receipt-col-divider"></div>

                            <div class="payment-actions-area">
                                <a href="#" class="btn-pay-matricula" id="btn-retry-payment"> <!-- ID para identificarlo si se necesita JS luego -->
                                    Ir a pagar matrícula
                                </a>
                            </div>
                        </div>

                        <!-- Columna Derecha: Estado y Acciones -->

                    </div>
                    
                    <!-- Footer del Recuadro -->
                    <div class="payment-details-footer">
                        <p class="footer-text-1">¿Necesitás ayuda con tu pago?</p>
                        <p class="footer-text-2">Nuestro equipo de asesores está disponible para ayudarte a resolverlo.</p>
                        <a href="https://wa.me/5491122674822" target="_blank" class="btn-whatsapp-footer">
                            <strong>Chatear con un asesor</strong>
                        </a>
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Acordeón de sedes
            const sedeRows = document.querySelectorAll('.contacto-sede-row[data-sede]');
            sedeRows.forEach(row => {
                row.addEventListener('click', function() {
                    const sedeId = this.getAttribute('data-sede');
                    const content = document.getElementById(sedeId + '-content');
                    document.querySelectorAll('.contacto-sede-content').forEach(otherContent => {
                        if (otherContent !== content) otherContent.classList.remove('active');
                    });
                    if (content) content.classList.toggle('active');
                });
            });

            // 2. Lógica de Reintento de Pago
            const btnRetry = document.getElementById('btn-retry-payment');
            const formRetry = document.getElementById('formulario-retry');

            if (btnRetry && formRetry) {
                btnRetry.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    if (btnRetry.disabled) return;

                    // Limpiar errores previos
                    formRetry.querySelectorAll('.cursada-formulario-input').forEach(i => i.style.borderColor = '');

                    // Validación básica
                    const nombre = document.getElementById('nombre-retry').value.trim();
                    const apellido = document.getElementById('apellido-retry').value.trim();
                    const dni = document.getElementById('dni-retry').value.trim();
                    const correo = document.getElementById('correo-retry').value.trim();
                    const telefono = document.getElementById('telefono-retry').value.trim();

                    let hasError = false;
                    if (!nombre) { document.getElementById('nombre-retry').style.borderColor = 'red'; hasError = true; }
                    if (!apellido) { document.getElementById('apellido-retry').style.borderColor = 'red'; hasError = true; }
                    if (!dni || dni.length < 7) { document.getElementById('dni-retry').style.borderColor = 'red'; hasError = true; }
                    if (!correo || !correo.includes('@')) { document.getElementById('correo-retry').style.borderColor = 'red'; hasError = true; }
                    if (!telefono) { document.getElementById('telefono-retry').style.borderColor = 'red'; hasError = true; }

                    if (hasError) {
                        alert('Por favor, complete todos los campos obligatorios correctamente.');
                        return;
                    }

                    const originalText = btnRetry.textContent;
                    btnRetry.textContent = 'Procesando...';
                    btnRetry.disabled = true;
                    btnRetry.style.opacity = '0.7';

                    try {
                        // A. Ejecutar reCAPTCHA
                        let recaptchaToken = null;
                        if (typeof grecaptcha !== 'undefined') {
                            recaptchaToken = await new Promise((resolve) => {
                                grecaptcha.ready(() => {
                                    grecaptcha.execute('{{ env("RECAPTCHA_SITE_KEY") }}', {action: 'submit'}).then(resolve);
                                });
                            });
                        }

                        // B. Guardar/Actualizar Lead
                        const leadResponse = await fetch('{{ route("leads.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                nombre,
                                apellido,
                                dni,
                                correo,
                                telefono: document.getElementById('telefono-prefijo-retry').value + ' ' + telefono,
                                cursada_id: '{{ $cursada?->ID_Curso }}',
                                tipo: 'Retry',
                                'g-recaptcha-response': recaptchaToken
                            })
                        });

                        const leadData = await leadResponse.json();
                        if (!leadData.success) {
                            throw new Error(leadData.message || 'Error al guardar los datos del alumno.');
                        }

                        // C. Crear Preferencia de Mercado Pago
                        const mpResponse = await fetch('/mp/create_preference', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                lead_id: leadData.lead_id,
                                cursada_id: '{{ $cursada?->ID_Curso }}',
                                codigo_descuento: '{{ $inscripcion?->codigo_descuento }}'
                            })
                        });

                        const mpData = await mpResponse.json();
                        if (mpData.init_point) {
                            window.location.href = mpData.init_point;
                        } else {
                            throw new Error(mpData.error || 'No se pudo generar el link de pago.');
                        }

                    } catch (error) {
                        console.error('Retry Error:', error);
                        alert('Hubo un problema: ' + error.message);
                        btnRetry.textContent = originalText;
                        btnRetry.disabled = false;
                        btnRetry.style.opacity = '1';
                    }
                });
            }
        });
    </script>
</body>
</html>
