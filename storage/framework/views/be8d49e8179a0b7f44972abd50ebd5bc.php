<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="ITCA - Instituto Tecnológico de Capacitación Automotriz">
    <title>ITCA - Instituto Tecnológico de Capacitación y Automotriz</title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">

    <!-- Styles -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/public.css', 'resources/js/app.js']); ?>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    </head>
<body>
        <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <!-- Logo -->
                <a href="#top" class="logo">ITCA</a>
                
                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li><a href="#" class="nav-link">Somos ITCA</a></li>
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
        <!-- Hero Section -->
            <section class="hero">
                <div class="hero-container">
                    <div class="hero-quadrant hero-quadrant-1">
                        <div class="q1-content">
                            <div class="q1-text">
                                <h1>Instituto Tecnológico de</h1>
                                <h1>Capacitación Automotriz</h1>
                            </div>
                            <div class="q1-logo">
                                <img src="/images/logo.png" alt="Logo ITCA" />
                            </div>
                        </div>
                    </div>
                    <div class="hero-quadrant hero-quadrant-2">
                        <?php if($desktopImg1 && $desktopImg1->url): ?>
                            <img src="<?php echo e(asset('storage/' . $desktopImg1->url)); ?>" alt="Imagen ITCA" />
                        <?php else: ?>
                            <img src="/images/desktop/img1.jpg" alt="Imagen ITCA" />
                        <?php endif; ?>
                    </div>
                    <div class="hero-quadrant hero-quadrant-3">
                        <div class="q3-top">
                            <div class="q3-text">
                                <div class="q3-line1">
                                    <img src="/images/desktop/arrow.png" alt="Arrow" class="q3-arrow" />
                                    <span class="q3-capacitate">Capacitate</span>
                                </div>
                                <div class="q3-line2">
                                    <span class="q3-en">en</span>
                                    <span class="q3-mecanica">Mecánica</span>
                                    <span class="q3-y">y</span>
                                </div>
                            </div>
                        </div>
                        <div class="q3-middle">
                            <div class="q3-middle-text">
                                <div class="q3-middle-line1">
                                    <span class="q3-convertite">convertite en</span>
                                </div>
                                <div class="q3-middle-line2">
                                    <img src="/images/desktop/barras.png" alt="Barras" class="q3-barras" />
                                    <span class="q3-profesional">Profesional</span>
                                    <img src="/images/desktop/estrellas.png" alt="Estrellas" class="q3-estrellas" />
                                </div>
                            </div>
                        </div>
                        <div class="q3-bottom">
                            <div class="q3-bottom-text">
                                <div class="q3-bottom-line1">
                                    <span class="q3-transforma">Transforma tu pasión</span>
                                </div>
                                <div class="q3-bottom-line2">
                                    <span class="q3-profesion">en tu profesion</span>
                                </div>
                            </div>
                            <button class="q3-button">¡Inscribirme ahora!</button>
                        </div>
                    </div>
                    
                    <!-- Carrusel Mobile -->
                    <div class="mobile-carousel-section">
                        <div class="mobile-carousel">
                            <div class="carousel-track">
                                <div class="carousel-slide">
                                    <?php if($mobileImg1 && $mobileImg1->url): ?>
                                        <img src="<?php echo e(asset('storage/' . $mobileImg1->url)); ?>" alt="Imagen 1" loading="lazy" />
                                    <?php else: ?>
                                        <img src="/images/mobile/foto_1.webp" alt="Imagen 1" loading="lazy" />
                                    <?php endif; ?>
                                </div>
                                <div class="carousel-slide">
                                    <?php if($mobileImg2 && $mobileImg2->url): ?>
                                        <img src="<?php echo e(asset('storage/' . $mobileImg2->url)); ?>" alt="Imagen 2" loading="lazy" />
                                    <?php else: ?>
                                        <img src="/images/mobile/foto_2.webp" alt="Imagen 2" loading="lazy" />
                                    <?php endif; ?>
                                    <button class="carousel-promo-button">¡Ver más promos!</button>
                                </div>
                                <div class="carousel-slide">
                                    <?php if($mobileVideo && $mobileVideo->url): ?>
                                        <video class="carousel-video" muted loop disablePictureInPicture controlsList="nodownload nofullscreen noremoteplayback">
                                            <source src="<?php echo e(asset('storage/' . $mobileVideo->url)); ?>" type="video/mp4">
                                            Tu navegador no soporta el elemento video.
                                        </video>
                                    <?php else: ?>
                                        <video class="carousel-video" muted loop disablePictureInPicture controlsList="nodownload nofullscreen noremoteplayback">
                                            <source src="/images/mobile/video.mp4" type="video/mp4">
                                            Tu navegador no soporta el elemento video.
                                        </video>
                                    <?php endif; ?>
                                    <button class="carousel-play-button">
                                        <img src="/images/desktop/play.png" alt="Play" class="carousel-play-icon" loading="lazy" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-quadrant hero-quadrant-4">
                        <div class="q4-left">
                            <img src="/images/desktop/llavero.png" alt="Llavero ITCA" class="q4-image" loading="lazy" />
                            <button class="q4-promo-button">¡Ver más promos!</button>
                        </div>
                        <div class="q4-right">
                            <?php if($desktopVideo && $desktopVideo->url): ?>
                                <video class="q4-video" muted loop disablePictureInPicture controlsList="nodownload nofullscreen noremoteplayback">
                                    <source src="<?php echo e(asset('storage/' . $desktopVideo->url)); ?>" type="video/mp4">
                                    Tu navegador no soporta el elemento video.
                                </video>
                            <?php else: ?>
                                <video class="q4-video" muted loop disablePictureInPicture controlsList="nodownload nofullscreen noremoteplayback">
                                    <source src="/images/desktop/video.mp4" type="video/mp4">
                                    Tu navegador no soporta el elemento video.
                                </video>
                            <?php endif; ?>
                            <button class="q4-play-button">
                                <img src="/images/desktop/play.png" alt="Play" class="q4-play-icon" loading="lazy" />
                            </button>
                        </div>
                    </div>
            </div>
        </section>

        <!-- Carreras Section -->
            <section class="carreras-section">
                <div class="carreras-container">
                    <h2 class="carreras-title">¡Elegí <span class="carreras-title-highlight">la carrera</span> para vos!</h2>
                    <div class="carreras-images">
                        <?php $__currentLoopData = $cursosFeatured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Determinar modalidad
                                $modalidad = '';
                                if ($curso->modalidad_presencial && $curso->modalidad_online) {
                                    $modalidad = 'Presencial / Online';
                                } elseif ($curso->modalidad_presencial) {
                                    $modalidad = 'Presencial';
                                } elseif ($curso->modalidad_online) {
                                    $modalidad = 'Online';
                                }
                                
                                // Obtener año de fecha_inicio
                                $año = $curso->fecha_inicio ? $curso->fecha_inicio->format('Y') : '2026';
                                
                                // Dividir nombre en dos líneas si es muy largo
                                $nombrePartes = explode(' ', $curso->nombre);
                                $linea1 = '';
                                $linea2 = '';
                                
                                if (count($nombrePartes) <= 3) {
                                    $linea1 = $curso->nombre;
                                } else {
                                    $mitad = ceil(count($nombrePartes) / 2);
                                    $linea1 = implode(' ', array_slice($nombrePartes, 0, $mitad));
                                    $linea2 = implode(' ', array_slice($nombrePartes, $mitad));
                                }
                            ?>
                            
                            <div class="carreras-image-container">
                                <!-- Imagen Desktop -->
                                <?php if($curso->ilustracion_desktop): ?>
                                    <img src="<?php echo e(asset('storage/' . $curso->ilustracion_desktop)); ?>" alt="<?php echo e($curso->nombre); ?>" class="carreras-image carreras-image-desktop" />
                                <?php else: ?>
                                    <img src="/images/desktop/Moto_1.webp" alt="<?php echo e($curso->nombre); ?>" class="carreras-image carreras-image-desktop" />
                                <?php endif; ?>
                                
                                <!-- Imagen Mobile -->
                                <?php if($curso->ilustracion_mobile): ?>
                                    <img src="<?php echo e(asset('storage/' . $curso->ilustracion_mobile)); ?>" alt="<?php echo e($curso->nombre); ?>" class="carreras-image carreras-image-mobile" />
                                <?php else: ?>
                                    <img src="/images/mobile/moto_mobile.webp" alt="<?php echo e($curso->nombre); ?>" class="carreras-image carreras-image-mobile" />
                                <?php endif; ?>
                                
                                <!-- Badge Modalidad Desktop -->
                                <div class="carreras-modalidad-badge carreras-modalidad-badge-desktop">
                                    <span class="carreras-modalidad-text">Modalidad: <strong><?php echo e($modalidad); ?></strong></span>
                    </div>
                                
                                <!-- Badge Inicio Mobile -->
                                <div class="carreras-inicio-badge carreras-inicio-badge-mobile">
                                    <span class="carreras-inicio-text">Inicia en</span>
                                    <span class="carreras-inicio-year"><?php echo e($año); ?></span>
                    </div>
                                
                                <!-- Barra Desktop -->
                                <a href="#" class="carreras-image-bar carreras-image-bar-button">
                                    <div class="carreras-bar-left">
                                        <div class="carreras-bar-start">Inicia en</div>
                                        <div class="carreras-bar-year"><?php echo e($año); ?></div>
                    </div>
                                    <div class="carreras-bar-center">
                                        <div class="carreras-bar-line1"><?php echo e($linea1); ?></div>
                                        <?php if($linea2): ?>
                                            <div class="carreras-bar-line2"><?php echo e($linea2); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <img src="/images/desktop/btnflecha.png" alt="Flecha" class="carreras-bar-arrow" />
                                </a>
                                
                                <!-- Contenido Mobile -->
                                <div class="carreras-mobile-content-wrapper">
                                    <div class="carreras-modalidad-badge carreras-modalidad-badge-mobile">
                                        <span class="carreras-modalidad-text">Modalidad: <strong><?php echo e($modalidad); ?></strong></span>
                                    </div>
                                    <a href="#" class="carreras-title-mobile carreras-title-mobile-desktop">
                                        <div class="carreras-title-text">
                                            <div class="carreras-title-line1"><?php echo e($linea1); ?></div>
                                            <?php if($linea2): ?>
                                                <div class="carreras-title-line2"><?php echo e($linea2); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <img src="/images/desktop/btnflecha.png" alt="Flecha" class="carreras-title-arrow" />
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    
                    <!-- Carrusel de Beneficios con Swiper -->
                    <div class="beneficios-carousel-section">
                        <div class="swiper beneficios-swiper">
                            <div class="swiper-wrapper">
                                <?php $__currentLoopData = $beneficios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="swiper-slide beneficios-carousel-slide">
                                    <img src="<?php echo e(asset('storage/' . $beneficio->imagen_desktop)); ?>" alt="<?php echo e($beneficio->titulo_linea1); ?>" class="beneficios-slide-img-desktop" loading="lazy" />
                                    <img src="<?php echo e(asset('storage/' . $beneficio->imagen_mobile)); ?>" alt="<?php echo e($beneficio->titulo_linea1); ?>" class="beneficios-slide-img-mobile" loading="lazy" />
                                    <div class="beneficios-slide-content">
                                        <div class="beneficios-slide-main">
                                            <div class="beneficios-slide-title">
                                                <div class="beneficios-slide-line1"><?php echo e($beneficio->titulo_linea1); ?></div>
                                                <div class="beneficios-slide-line2 <?php echo e($beneficio->tipo_titulo === 'small' ? 'beneficios-slide-line2-small' : ''); ?>"><?php echo e($beneficio->titulo_linea2); ?></div>
                    </div>
                                            <div class="beneficios-slide-description">
                                                <?php echo preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($beneficio->descripcion)); ?>

                    </div>
                    </div>
                                        <?php if($beneficio->mostrar_bottom): ?>
                                        <div class="beneficios-slide-bottom beneficios-slide-bottom-<?php echo e($beneficio->getAlineacionBottomAttribute()); ?>">
                                            <?php if($beneficio->getAlineacionBottomAttribute() === 'left'): ?>
                                                <div class="beneficios-slide-link beneficios-slide-link-visible">
                                                    <a href="<?php echo e($beneficio->url); ?>" target="_blank"><?php echo e($beneficio->texto_boton ?: 'Ver más'); ?></a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-hidden">
                                                    <a href="#" class="beneficios-slide-icon-btn">
                                                        <img src="/images/desktop/btnflecha.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php elseif($beneficio->getAlineacionBottomAttribute() === 'right'): ?>
                                                <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                    <a href="#">Ver más</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                    <a href="<?php echo e($beneficio->url ?: '#'); ?>" target="_blank" class="beneficios-slide-icon-btn">
                                                        <img src="/images/desktop/btnflecha.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                    <a href="#">Ver más</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                    <a href="<?php echo e($beneficio->url ?: '#'); ?>" target="_blank" class="beneficios-slide-icon-btn">
                                                        <img src="/images/desktop/btnflecha.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        
                        <!-- Botones de navegación -->
                        <div class="beneficios-carousel-controls">
                            <!-- Barra de progreso -->
                            <div class="beneficios-progress-bar">
                                <div class="beneficios-progress-track"></div>
                                <div class="beneficios-progress-indicator"></div>
                            </div>
                            <div class="beneficios-controls-wrapper">
                                <a href="#" class="beneficios-ver-todos-btn">Ver todos los beneficios</a>
                                <button class="beneficios-carousel-btn beneficios-carousel-btn-prev">
                                    <img src="/images/desktop/beneficios/arrow-b.png" alt="Anterior" class="beneficios-arrow-left" loading="lazy" />
                                </button>
                                <button class="beneficios-carousel-btn beneficios-carousel-btn-next">
                                    <img src="/images/desktop/beneficios/arrow-b.png" alt="Siguiente" loading="lazy" />
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carrusel Nativo de Beneficios (solo mobile) -->
                    <div class="beneficios-mobile-carousel-section">
                        <div class="beneficios-mobile-carousel">
                            <div class="beneficios-carousel-track">
                                <?php $__currentLoopData = $beneficios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="beneficios-carousel-slide">
                                    <img src="<?php echo e(asset('storage/' . $beneficio->imagen_mobile)); ?>" alt="<?php echo e($beneficio->titulo_linea1); ?>" loading="lazy" />
                                    <div class="beneficios-slide-content">
                                        <div class="beneficios-slide-main">
                                            <div class="beneficios-slide-title">
                                                <div class="beneficios-slide-line1"><?php echo e($beneficio->titulo_linea1); ?></div>
                                                <div class="beneficios-slide-line2 <?php echo e($beneficio->tipo_titulo === 'small' ? 'beneficios-slide-line2-small' : ''); ?>"><?php echo e($beneficio->titulo_linea2); ?></div>
                                            </div>
                                            <div class="beneficios-slide-description">
                                                <?php echo preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($beneficio->descripcion)); ?>

                                            </div>
                                        </div>
                                        <?php if($beneficio->mostrar_bottom): ?>
                                        <div class="beneficios-slide-bottom beneficios-slide-bottom-<?php echo e($beneficio->getAlineacionBottomAttribute()); ?>">
                                            <?php if($beneficio->getAlineacionBottomAttribute() === 'left'): ?>
                                                <div class="beneficios-slide-link beneficios-slide-link-visible">
                                                    <a href="<?php echo e($beneficio->url); ?>" target="_blank"><?php echo e($beneficio->texto_boton ?: 'Ver más'); ?></a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-hidden">
                                                    <a href="#" class="beneficios-slide-icon-btn">
                                                        <img src="/images/mobile/flechaup.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php elseif($beneficio->getAlineacionBottomAttribute() === 'right'): ?>
                                                <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                    <a href="#">Ver más</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-visible">
                                                    <a href="<?php echo e($beneficio->url ?: '#'); ?>" target="_blank" class="beneficios-slide-icon-btn">
                                                        <img src="/images/mobile/flechaup.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="beneficios-slide-link beneficios-slide-link-hidden">
                                                    <a href="#">Ver más</a>
                                                </div>
                                                <div class="beneficios-slide-icon beneficios-slide-icon-hidden">
                                                    <a href="#" class="beneficios-slide-icon-btn">
                                                        <img src="/images/mobile/flechaup.png" alt="Flecha" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <img src="/images/mobile/arrowicon.png" alt="Anterior" />
                                </button>
                                
                                <button class="beneficios-carousel-btn beneficios-arrow-right" onclick="scrollBeneficiosCarousel('right')">
                                    <img src="/images/mobile/arrowicon.png" alt="Siguiente" />
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </section>

            <!-- Sedes Section -->
            <section class="sedes-section">
                <div class="sedes-container">
                    <h2 class="sedes-title">
                        <span class="sedes-text-regular">Conocé todas </span>
                        <span class="sedes-text-highlight">nuestras sedes</span>
                    </h2>
                    
                    <div class="sedes-grid">
                        <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($sede->disponible): ?>
                                <!-- Sede disponible - con flipping -->
                                <div class="sedes-grid-item" 
                                     id="sede-<?php echo e($sede->id); ?>-card"
                                     data-direccion="<?php echo e($sede->direccion); ?>"
                                     data-contacto="<?php echo e($sede->telefono); ?>">
                                    <img src="<?php echo e(asset('storage/' . $sede->imagen_desktop)); ?>" 
                                         alt="<?php echo e($sede->nombre); ?>" 
                                         class="sedes-grid-image" loading="lazy">
                                    <div class="sedes-grid-title <?php echo e($sede->tipo_titulo === 'dos_lineas' ? 'dos-lineas' : ''); ?>">
                                        <?php if($sede->tipo_titulo === 'dos_lineas'): ?>
                                            <?php
                                                $partes = explode(' ', $sede->nombre, 2);
                                                $primeraLinea = $partes[0];
                                                $segundaLinea = isset($partes[1]) ? $partes[1] : '';
                                            ?>
                                            <div class="sedes-title-line"><?php echo e($primeraLinea); ?></div>
                                            <div class="sedes-title-line"><?php echo e($segundaLinea); ?></div>
                                        <?php else: ?>
                                            <div class="sedes-title-line"><?php echo e($sede->nombre); ?></div>
                                        <?php endif; ?>
                        </div>
                        </div>
                            <?php else: ?>
                                <!-- Sede no disponible - card "Próximamente" -->
                                <div class="sedes-grid-item proximamente">
                                    <img src="<?php echo e(asset('storage/' . $sede->imagen_desktop)); ?>" 
                                         alt="<?php echo e($sede->nombre); ?>" 
                                         class="sedes-grid-image" loading="lazy">
                                    <div class="sedes-grid-title proximamente">
                                        <div class="sedes-title-line">PROXIMAMENTE</div>
                        </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Versión Mobile - Acordeón -->
                    <div class="sedes-accordion-mobile">
                        <?php $__currentLoopData = $sedes->where('disponible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="sedes-accordion-item" data-sede="<?php echo e($sede->id); ?>">
                                <div class="sedes-accordion-header">
                                    <img src="<?php echo e(asset('storage/' . $sede->imagen_mobile)); ?>" 
                                         alt="<?php echo e($sede->nombre); ?>" 
                                         class="sedes-accordion-image" loading="lazy">
                                    <div class="sedes-accordion-title <?php echo e($sede->tipo_titulo === 'dos_lineas' ? 'dos-lineas' : ''); ?>">
                                        <?php if($sede->tipo_titulo === 'dos_lineas'): ?>
                                            <?php
                                                $partes = explode(' ', $sede->nombre, 2);
                                                $primeraLinea = $partes[0];
                                                $segundaLinea = isset($partes[1]) ? $partes[1] : '';
                                            ?>
                                            <div class="sedes-accordion-line"><?php echo e($primeraLinea); ?></div>
                                            <div class="sedes-accordion-line"><?php echo e($segundaLinea); ?></div>
                                        <?php else: ?>
                                            <div class="sedes-accordion-line"><?php echo e($sede->nombre); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="sedes-accordion-content">
                                    <div class="sedes-accordion-direccion"><?php echo e($sede->direccion); ?></div>
                                    <div class="sedes-accordion-contacto">Contacto: <?php echo e($sede->telefono); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

            <!-- Comunidad Section -->
            <section class="comunidad-section">
                <div class="comunidad-container">
                    <h2 class="comunidad-title">
                        <span class="comunidad-text-regular">Unite a nuestra </span>
                        <span class="comunidad-text-highlight">comunidad ITCA</span>
                    </h2>
                    
                    <div class="comunidad-grid">
                        <?php $__currentLoopData = $testimonios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="comunidad-grid-item">
                                <div class="comunidad-grid-image">
                                    <!-- Header -->
                                    <div class="comunidad-card-header">
                                        <img src="<?php echo e(asset('storage/' . $testimonio->avatar)); ?>" 
                                             alt="Avatar" class="comunidad-card-avatar" loading="lazy">
                                        <div class="comunidad-card-info">
                                            <p class="comunidad-card-sede"><?php echo e($testimonio->sede); ?></p>
                                            <p class="comunidad-card-nombre"><?php echo e($testimonio->nombre); ?></p>
                                            <p class="comunidad-card-tiempo">hace <?php echo e($testimonio->tiempo_testimonio); ?> meses</p>
                                            <p class="comunidad-card-carrera"><?php echo e($testimonio->carrera); ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Main -->
                                    <div class="comunidad-card-main">
                                        <p class="comunidad-card-texto"><?php echo e($testimonio->texto); ?></p>
                                    </div>
                                    
                                    <!-- Bottom -->
                                    <div class="comunidad-card-bottom">
                                        <img src="/images/desktop/comunidad/stars.png" alt="Stars" class="comunidad-card-stars" loading="lazy">
                                        <img src="<?php echo e(asset('storage/' . $testimonio->icono)); ?>" 
                                             alt="Icono" class="comunidad-card-google" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Botón Ver más opiniones - Desktop -->
                    <div class="comunidad-desktop-ver-todos">
                        <a href="#" class="comunidad-desktop-ver-todos-btn">Ver más opiniones</a>
                    </div>
                    
                    <!-- Carrusel de Comunidad con Swiper -->
                    <div class="comunidad-carousel-section">
                        <div class="swiper comunidad-swiper">
                            <div class="swiper-wrapper">
                                <?php $__currentLoopData = $testimonios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="swiper-slide comunidad-carousel-slide">
                                    <div class="comunidad-grid-image">
                                        <!-- Header -->
                                        <div class="comunidad-card-header">
                                            <img src="<?php echo e(asset('storage/' . $testimonio->avatar)); ?>" alt="Avatar" class="comunidad-card-avatar" loading="lazy">
                                            <div class="comunidad-card-info">
                                                <p class="comunidad-card-sede"><?php echo e($testimonio->sede); ?></p>
                                                <p class="comunidad-card-nombre"><?php echo e($testimonio->nombre); ?></p>
                                                <p class="comunidad-card-tiempo">hace <?php echo e($testimonio->tiempo_testimonio); ?> meses</p>
                                                <p class="comunidad-card-carrera"><?php echo e($testimonio->carrera); ?></p>
                                            </div>
                                        </div>
                                        
                                        <!-- Main -->
                                        <div class="comunidad-card-main">
                                            <p class="comunidad-card-texto"><?php echo e($testimonio->texto); ?></p>
                                        </div>
                                        
                                        <!-- Bottom -->
                                        <div class="comunidad-card-bottom">
                                            <img src="/images/desktop/comunidad/stars.png" alt="Stars" class="comunidad-card-stars" loading="lazy">
                                            <img src="<?php echo e(asset('storage/' . $testimonio->icono)); ?>" alt="Icono" class="comunidad-card-google" loading="lazy">
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        
                        <!-- Botones de navegación -->
                        <div class="comunidad-tablet-carousel-controls">
                            <!-- Barra de progreso -->
                            <div class="comunidad-tablet-progress-bar">
                                <div class="comunidad-tablet-progress-track"></div>
                                <div class="comunidad-tablet-progress-indicator"></div>
                            </div>
                            <div class="comunidad-tablet-controls-wrapper">
                                <a href="#" class="comunidad-tablet-ver-todos-btn">Ver más opiniones</a>
                                <button class="comunidad-tablet-carousel-btn comunidad-tablet-carousel-btn-prev">
                                    <img src="/images/desktop/beneficios/arrow-b.png" alt="Anterior" class="comunidad-tablet-arrow-left" loading="lazy" />
                                </button>
                                <button class="comunidad-tablet-carousel-btn comunidad-tablet-carousel-btn-next">
                                    <img src="/images/desktop/beneficios/arrow-b.png" alt="Siguiente" loading="lazy" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Comunidad Mobile Carousel Section -->
            <section class="comunidad-mobile-carousel-section">
                <div class="comunidad-container">
                    <h2 class="comunidad-title">
                        <span class="comunidad-text-regular">Unite a nuestra </span>
                        <span class="comunidad-text-highlight">comunidad ITCA</span>
                    </h2>
                </div>
                <div class="comunidad-mobile-carousel">
                    <div class="comunidad-carousel-track">
                        <?php $__currentLoopData = $testimonios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="comunidad-carousel-slide">
                            <div class="comunidad-mobile-card">
                                <div class="comunidad-card-header">
                                    <img src="<?php echo e(asset('storage/' . $testimonio->avatar)); ?>" alt="Avatar" class="comunidad-card-avatar" loading="lazy">
                                    <div class="comunidad-card-info">
                                        <p class="comunidad-card-sede"><?php echo e($testimonio->sede); ?></p>
                                        <p class="comunidad-card-nombre"><?php echo e($testimonio->nombre); ?></p>
                                        <p class="comunidad-card-tiempo">hace <?php echo e($testimonio->tiempo_testimonio); ?> meses</p>
                                        <p class="comunidad-card-carrera"><?php echo e($testimonio->carrera); ?></p>
                                    </div>
                                </div>
                                <div class="comunidad-card-main">
                                    <p class="comunidad-card-texto"><?php echo e($testimonio->texto); ?></p>
                                </div>
                                <div class="comunidad-card-bottom">
                                    <img src="/images/desktop/comunidad/stars.png" alt="Stars" class="comunidad-card-stars" loading="lazy">
                                    <img src="<?php echo e(asset('storage/' . $testimonio->icono)); ?>" alt="Icono" class="comunidad-card-google" loading="lazy">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                
                <!-- Controles del carrusel -->
                <div class="comunidad-carousel-controls">
                    <div class="comunidad-progress-bar">
                        <div class="comunidad-progress-track"></div>
                        <div class="comunidad-progress-indicator"></div>
                    </div>
                    <div class="comunidad-controls-row">
                        <a href="#" class="comunidad-ver-todos-btn">Ver más opiniones</a>
                        <button class="comunidad-carousel-btn comunidad-btn-prev" onclick="scrollComunidadCarousel(-1)">
                            <img src="/images/mobile/arrowicon.png" alt="Previous" class="comunidad-arrow-left">
                        </button>
                        <button class="comunidad-carousel-btn comunidad-btn-next" onclick="scrollComunidadCarousel(1)">
                            <img src="/images/mobile/arrowicon.png" alt="Next">
                        </button>
                    </div>
                </div>
            </section>

            <!-- En Acción Section -->
            <section class="en-accion-section">
                <div class="en-accion-container">
                    <h2 class="en-accion-title">
                        <span class="en-accion-text-regular">Nuestro Instituto </span>
                        <span class="en-accion-text-highlight">en acción</span>
                    </h2>
                    
                    <!-- Tablet Structure (600px-1099px) -->
                    <div class="en-accion-tablet-section">
                        <div class="en-accion-tablet-carousel-section">
                            <div class="en-accion-tablet-carousel">
                                <div class="en-accion-tablet-carousel-track">
                                    <?php $__currentLoopData = $videosTablet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="en-accion-tablet-carousel-slide en-accion-tablet-slide-<?php echo e($index + 1); ?>">
                                        <video class="en-accion-tablet-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                            <source src="<?php echo e(asset('storage/' . $video->video)); ?>" type="video/mp4">
                                        </video>
                                        <button class="en-accion-tablet-play-button">
                                            <img src="/images/desktop/play.png" alt="Play" class="en-accion-tablet-play-icon" />
                                        </button>
                                        <a href="<?php echo e($video->url); ?>" target="_blank" class="en-accion-tablet-<?php echo e($video->getPlatformClass()); ?>-btn">
                                            Ir a <?php echo e($video->getPlatformName()); ?>

                                        </a>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tablet News Section -->
                        <div class="en-accion-tablet-news">
                            <div class="en-accion-tablet-news-header">
                                <span class="en-accion-tablet-news-text-1">Últimas</span>
                                <span class="en-accion-tablet-news-text-2">noticias</span>
                            </div>
                            <div class="en-accion-tablet-news-content">
                                <h3 class="en-accion-tablet-news-title">Nueva alianza: <span class="en-accion-tablet-news-title-light">ITCA y Royal Enfield</span></h3>
                                <p class="en-accion-tablet-news-text">La reconocida marca y fabricante de motocicletas se apoyará en nuestro Instituto para la capacitación de posventa. El motivo de la asistencia de Motoblog fue presenciar la firma de un convenio de mutuo beneficio entre el ITCA y Royal Enfield Argentina...<a href="#" class="en-accion-tablet-news-link">Ver más</a></p>
                                <div class="en-accion-tablet-news-image">
                                    <img src="/images/desktop/en-accion/news.png" alt="News" class="en-accion-tablet-news-img">
                                </div>
                                <a href="#" class="en-accion-tablet-news-btn">Ver más noticias</a>
                            </div>
                        </div>
                        
                        <!-- Tablet Corp Section -->
                        <div class="en-accion-tablet-corp">
                            <div class="en-accion-tablet-corp-header">
                                <div class="en-accion-tablet-corp-title">
                                    <span class="en-accion-tablet-corp-title-light">Programa: </span>
                                    <span class="en-accion-tablet-corp-title-bold">ITCA CORPORATIVO</span>
                                </div>
                                <div class="en-accion-tablet-corp-image">
                                    <img src="/images/desktop/en-accion/corp.png" alt="ITCA CORPORATIVO" class="en-accion-tablet-corp-img">
                                </div>
                            </div>
                            <div class="en-accion-tablet-corp-subtitle">
                                <span class="en-accion-tablet-corp-subtitle-bold">Capacitamos a técnicos</span>
                                <span class="en-accion-tablet-corp-subtitle-light">de empresas líderes</span>
                            </div>
                            <div class="en-accion-tablet-corp-text">
                                A través de nuestra División Corporativa, trabajamos en conjunto con las empresas líderes de la industria aportando conocimiento y habilidades a los equipos técnicos. Nuestro compromiso es proveer los conocimientos y habilidades para que sus equipos técnicos cumplan las metas delineadas para llegar a los objetivos...
                                <a href="#" class="en-accion-tablet-corp-link">Ver más</a>
                            </div>
                            <div class="en-accion-tablet-corp-button">
                                <a href="#" class="en-accion-tablet-corp-btn">
                                    <img src="/images/desktop/btnflecha.png" alt="Flecha" />
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- Mobile Carousel Section -->
                    <div class="en-accion-mobile-carousel-section">
                        <div class="en-accion-mobile-carousel">
                            <div class="en-accion-carousel-track">
                                <?php $__currentLoopData = $videosMobile; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="en-accion-carousel-slide">
                                    <video class="en-accion-mobile-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                        <source src="<?php echo e(asset('storage/' . $video->video)); ?>" type="video/mp4">
                                    </video>
                                    <button class="en-accion-mobile-play-button">
                                        <img src="/images/desktop/play.png" alt="Play" class="en-accion-mobile-play-icon" />
                                    </button>
                                    <a href="<?php echo e($video->url); ?>" target="_blank" class="en-accion-mobile-<?php echo e($video->getPlatformClass()); ?>-btn">
                                        Ir a <?php echo e($video->getPlatformName()); ?>

                                    </a>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        
                        <!-- Controles del carrusel -->
                        <div class="en-accion-mobile-carousel-controls">
                            <button class="en-accion-mobile-prev-btn">
                                <img src="/images/mobile/arrowicon.png" alt="Anterior" />
                            </button>
                            <div class="en-accion-mobile-progress-bar">
                                <div class="en-accion-mobile-progress-track"></div>
                                <div class="en-accion-mobile-progress-indicator"></div>
                            </div>
                            <button class="en-accion-mobile-next-btn">
                                <img src="/images/mobile/arrowicon.png" alt="Siguiente" />
                            </button>
                        </div>
                    </div>
                    
                    <!-- Mobile News Section -->
                    <div class="en-accion-mobile-news">
                        <div class="en-accion-mobile-news-header">
                            <span class="en-accion-mobile-news-text-1">Últimas</span>
                            <span class="en-accion-mobile-news-text-2">noticias</span>
                        </div>
                        <div class="en-accion-mobile-news-content">
                            <h3 class="en-accion-mobile-news-title">
                                <span class="en-accion-mobile-news-title-bold">Nueva alianza:</span><br>
                                <span class="en-accion-mobile-news-title-light">ITCA y Royal Enfield</span>
                            </h3>
                            <p class="en-accion-mobile-news-text">La reconocida marca y fabricante de motocicletas se apoyará en nuestro Instituto para la capacitación de posventa. El motivo de la asistencia de Motoblog fue presenciar la firma de un convenio de mutuo beneficio entre el ITCA y Royal Enfield Argentina...<a href="#" class="en-accion-mobile-news-link">Ver más</a></p>
                            <div class="en-accion-mobile-news-image">
                                <img src="/images/desktop/en-accion/news.png" alt="News" class="en-accion-mobile-news-img">
                            </div>
                            <a href="#" class="en-accion-mobile-news-btn">Ver más noticias</a>
                        </div>
                    </div>
                    
                    <!-- Mobile Corp Section -->
                    <div class="en-accion-mobile-corp">
                        <div class="en-accion-mobile-corp-title">
                            <span class="en-accion-mobile-corp-title-light">Programa: </span>
                            <span class="en-accion-mobile-corp-title-bold">ITCA CORPORATIVO</span>
                        </div>
                        <div class="en-accion-mobile-corp-image">
                            <img src="/images/desktop/en-accion/corp.png" alt="ITCA CORPORATIVO" class="en-accion-mobile-corp-img">
                        </div>
                        <div class="en-accion-mobile-corp-subtitle">
                            <span class="en-accion-mobile-corp-subtitle-bold">Capacitamos a técnicos</span><br>
                            <span class="en-accion-mobile-corp-subtitle-light">de empresas líderes</span>
                        </div>
                        <div class="en-accion-mobile-corp-text">
                            A través de nuestra División Corporativa, trabajamos en conjunto con las empresas líderes de la industria aportando conocimiento y habilidades a los equipos técnicos. Nuestro compromiso es proveer los conocimientos y habilidades para que sus equipos técnicos cumplan las metas delineadas para llegar a los objetivos...
                            <a href="#" class="en-accion-mobile-corp-link">Ver más</a>
                        </div>
                        <div class="en-accion-mobile-corp-button">
                            <a href="#" class="en-accion-mobile-corp-btn">
                                <img src="/images/desktop/btnflecha.png" alt="Flecha" />
                            </a>
                        </div>
                    </div>
                    
                    <!-- Desktop Grid -->
                        <div class="en-accion-grid">
                        <!-- Fila superior -->
                        <div class="en-accion-row-1">
                            <div class="en-accion-item en-accion-item-1">
                                <?php if($video1): ?>
                                    <video class="en-accion-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                        <source src="<?php echo e(asset('storage/' . $video1->video)); ?>" type="video/mp4">
                                    </video>
                                    <button class="en-accion-play-button">
                                        <img src="/images/desktop/play.png" alt="Play" class="en-accion-play-icon" />
                                    </button>
                                    <a href="<?php echo e($video1->url); ?>" target="_blank" class="en-accion-<?php echo e($video1->getPlatformClass()); ?>-btn">
                                        Ir a <?php echo e($video1->getPlatformName()); ?>

                                    </a>
                                <?php else: ?>
                                    <!-- Fallback si no hay video -->
                                    <div class="en-accion-placeholder">
                                        <p>No hay video disponible</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="en-accion-item en-accion-item-2">
                                <div class="en-accion-item-2-div-1">
                                    <div class="en-accion-item-2-div-1-button">
                                        <span class="en-accion-item-2-div-1-text-1">Últimas</span>
                                        <span class="en-accion-item-2-div-1-text-2">noticias</span>
                                    </div>
                                </div>
                                <div class="en-accion-item-2-content">
                                    <div class="en-accion-item-2-div-2">
                                        <h3 class="en-accion-item-2-div-2-title">Nueva alianza: <span class="en-accion-item-2-div-2-title-light">ITCA y Royal Enfield</span></h3>
                                    </div>
                                    <div class="en-accion-item-2-div-2-text">
                                        <p>La reconocida marca y fabricante de motocicletas se apoyará en nuestro Instituto para la capacitación de posventa. El motivo de la asistencia de Motoblog fue presenciar la firma de un convenio de mutuo beneficio entre el ITCA y Royal Enfield Argentina...<a href="#" class="en-accion-item-2-div-2-link">Ver más</a></p>
                                    </div>
                                    <div class="en-accion-item-2-div-3">
                                        <img src="/images/desktop/en-accion/news.png" alt="News" class="en-accion-item-2-div-3-image">
                                    </div>
                                    <div class="en-accion-item-2-div-4">
                                        <a href="#" class="en-accion-item-2-div-4-btn">Ver más noticias</a>
                                    </div>
                                </div>
                            </div>
                            <div class="en-accion-item en-accion-item-3">
                                <?php if($video3): ?>
                                    <video class="en-accion-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                        <source src="<?php echo e(asset('storage/' . $video3->video)); ?>" type="video/mp4">
                                    </video>
                                    <button class="en-accion-play-button">
                                        <img src="/images/desktop/play.png" alt="Play" class="en-accion-play-icon" />
                                    </button>
                                    <a href="<?php echo e($video3->url); ?>" target="_blank" class="en-accion-<?php echo e($video3->getPlatformClass()); ?>-btn">
                                        Ir a <?php echo e($video3->getPlatformName()); ?>

                                    </a>
                                <?php else: ?>
                                    <!-- Fallback si no hay video -->
                                    <div class="en-accion-placeholder">
                                        <p>No hay video disponible</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                            
                            <!-- Fila inferior -->
                            <div class="en-accion-row-2">
                                <div class="en-accion-item en-accion-item-4">
                                    <div class="en-accion-item-4-content">
                                        <div class="en-accion-item-4-group">
                                            <div class="en-accion-item-4-title-wrapper">
                                                <div class="en-accion-item-4-title">
                                                    <span class="en-accion-item-4-title-light">Programa: </span>
                                                    <span class="en-accion-item-4-title-bold">ITCA CORPORATIVO</span>
                                                </div>
                                            </div>
                                            <div class="en-accion-item-4-image">
                                                <img src="/images/desktop/en-accion/corp.png" alt="ITCA CORPORATIVO" class="en-accion-item-4-img">
                                            </div>
                                        </div>
                                        <div class="en-accion-item-4-subtitle">
                                            <span class="en-accion-item-4-subtitle-bold">Capacitamos a técnicos</span>
                                            <span class="en-accion-item-4-subtitle-light">de empresas líderes</span>
                                        </div>
                                        <div class="en-accion-item-4-text">
                                            A través de nuestra División Corporativa, trabajamos en conjunto con las empresas líderes de la industria aportando conocimiento y habilidades a los equipos técnicos. Nuestro compromiso es proveer los conocimientos y habilidades para que sus equipos técnicos cumplan las metas delineadas para llegar a los objetivos...
                                            <a href="#" class="en-accion-item-4-link">Ver más</a>
                                        </div>
                                        <div class="en-accion-item-4-button">
                                            <a href="#" class="en-accion-item-4-btn">
                                                <img src="/images/desktop/btnflecha.png" alt="Flecha" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="en-accion-item en-accion-item-5">
                                    <?php if($video5): ?>
                                        <video class="en-accion-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                            <source src="<?php echo e(asset('storage/' . $video5->video)); ?>" type="video/mp4">
                                        </video>
                                        <button class="en-accion-play-button">
                                            <img src="/images/desktop/play.png" alt="Play" class="en-accion-play-icon" />
                                        </button>
                                        <a href="<?php echo e($video5->url); ?>" target="_blank" class="en-accion-<?php echo e($video5->getPlatformClass()); ?>-btn">
                                            Ir a <?php echo e($video5->getPlatformName()); ?>

                                        </a>
                                    <?php else: ?>
                                        <!-- Fallback si no hay video -->
                                        <div class="en-accion-placeholder">
                                            <p>No hay video disponible</p>
                                        </div>
                                    <?php endif; ?>
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
                            <?php if($partners->count() > 0): ?>
                                <!-- Primera copia de logos -->
                                <?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <a href="<?php echo e($partner->url); ?>" target="_blank" class="partners-logo">
                                            <img src="<?php echo e(asset('storage/' . $partner->logo)); ?>" alt="<?php echo e($partner->url); ?>" loading="lazy">
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <!-- Segunda copia de logos para loop infinito -->
                                <?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                                        <a href="<?php echo e($partner->url); ?>" target="_blank" class="partners-logo">
                                            <img src="<?php echo e(asset('storage/' . $partner->logo)); ?>" alt="<?php echo e($partner->url); ?>" loading="lazy">
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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
                            <?php $__currentLoopData = $sedes->where('disponible', true)->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="contacto-sede-row" data-sede="<?php echo e(Str::slug($sede->nombre)); ?>">
                                    <div class="contacto-sede-text"><?php echo e($sede->nombre); ?></div>
                                    <div class="contacto-sede-plus">+</div>
                                </div>
                                <div class="contacto-sede-content" id="<?php echo e(Str::slug($sede->nombre)); ?>-content">
                                    <div class="contacto-sede-direccion"><?php echo e($sede->direccion); ?></div>
                                    <div class="contacto-sede-contacto">Contacto: <?php echo e($sede->telefono); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    
    <!-- JavaScript para desplegable de sedes -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funcionalidad de sedes - Acordeón
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
</html><?php /**PATH /home/fede/Desktop/itca-laravel/resources/views/welcome.blade.php ENDPATH**/ ?>