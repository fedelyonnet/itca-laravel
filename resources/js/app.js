/* ========================================
   ITCA - JavaScript Público
   Funcionalidad para el sitio público
   ======================================== */

// ========================================
// FUNCIONES GLOBALES PARA BENEFICIOS
// ========================================

// Función para actualizar la barra de progreso
function updateProgressBar(swiper) {
    if (!swiper || !swiper.el) return;
    // Priorizar contenedor desktop para evitar colisiones con mobile
    let container = swiper.el.closest('.beneficios-desktop');
    if (!container) {
        container = swiper.el.closest('.beneficios-carousel-section');
    }
    if (!container) return;
    // En home, usar clases específicas para desktop si existen
    const progressIndicator = container.querySelector('.beneficios-desktop-progress-indicator') || container.querySelector('.beneficios-progress-indicator');
    const progressBar = container.querySelector('.beneficios-desktop-progress-bar') || container.querySelector('.beneficios-progress-bar');
    if (!progressIndicator || !progressBar) return;

    let progress = 0;
    if (Array.isArray(swiper.snapGrid) && typeof swiper.snapIndex === 'number') {
        const maxSnap = Math.max(1, swiper.snapGrid.length - 1);
        progress = Math.max(0, Math.min(1, swiper.snapIndex / maxSnap));
    } else if (typeof swiper.progress === 'number') {
        progress = Math.max(0, Math.min(1, swiper.progress));
    } else if (typeof swiper.activeIndex === 'number' && swiper.slides) {
        const maxNavigableSlide = Math.max(1, swiper.slides.length - 1);
        progress = Math.max(0, Math.min(1, swiper.activeIndex / maxNavigableSlide));
    }

    const trackWidth = progressBar.offsetWidth;
    const indicatorWidth = progressIndicator.offsetWidth;
    const maxPosition = Math.max(0, trackWidth - indicatorWidth);
    const position = progress * maxPosition;
    // Usar posicionamiento por left para máxima compatibilidad en build
    progressIndicator.style.transform = 'none';
    progressIndicator.style.left = `${position}px`;
}

// Función para actualizar el estado de los botones de navegación
function updateNavigationButtons(swiper) {
    const nextBtn = swiper.el.closest('.beneficios-carousel-section').querySelector('.beneficios-carousel-btn-next');
    const prevBtn = swiper.el.closest('.beneficios-carousel-section').querySelector('.beneficios-carousel-btn-prev');
    
    if (!nextBtn || !prevBtn) return;
    
    // Deshabilitar botón anterior si estamos en el primer slide
    if (swiper.isBeginning) {
        prevBtn.classList.add('swiper-button-disabled');
    } else {
        prevBtn.classList.remove('swiper-button-disabled');
    }
    
    // Deshabilitar botón siguiente si hemos llegado al final
    if (swiper.isEnd) {
        nextBtn.classList.add('swiper-button-disabled');
    } else {
        nextBtn.classList.remove('swiper-button-disabled');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // MENÚ HAMBURGUESA
    // ========================================
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
        
        // Cerrar menú al hacer click en un enlace
        const navLinkItems = document.querySelectorAll('.nav-link');
        navLinkItems.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
        
        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            }
        });
    }
    
    // ========================================
    // VIDEO PLAY/PAUSE Q4
    // ========================================
    const q4Video = document.querySelector('.q4-video');
    const q4PlayButton = document.querySelector('.q4-play-button');
    
    if (q4Video && q4PlayButton) {
        let videoStarted = false;
        let userPausedManually = false; // Flag para saber si el usuario pausó manualmente
        
        // Función para intentar reproducir el video (solo al inicio)
        function tryPlayVideo() {
            if (q4Video.paused && !videoStarted && !userPausedManually) {
                // Intentar activar el audio automáticamente
                q4Video.muted = false;
                q4Video.play().then(() => {
                    q4Video.classList.add('playing');
                    videoStarted = true;
                }).catch(() => {
                    // Si falla, intentar con muted para permitir autoplay
                    q4Video.muted = true;
                    q4Video.play().then(() => {
                        q4Video.classList.add('playing');
                        videoStarted = true;
                    }).catch(() => {
                        // Autoplay bloqueado completamente
                    });
                });
            }
        }
        
        // Intentar reproducir cuando el video esté listo
        q4Video.addEventListener('loadedmetadata', tryPlayVideo);
        q4Video.addEventListener('canplay', tryPlayVideo);
        q4Video.addEventListener('canplaythrough', tryPlayVideo);
        
        // Intentar inmediatamente si ya está listo
        if (q4Video.readyState >= 2) {
            tryPlayVideo();
        }
        
        // Intentar reproducir cuando el usuario interactúa (solo una vez al inicio)
        let userInteractionHandled = false;
        function handleUserInteraction() {
            if (!userInteractionHandled && q4Video.paused && !userPausedManually) {
                userInteractionHandled = true;
                // Activar audio cuando el usuario interactúa
                q4Video.muted = false;
                q4Video.play().then(() => {
                    q4Video.classList.add('playing');
                    videoStarted = true;
                }).catch(() => {
                    // Error
                });
            }
        }
        
        // Escuchar eventos de interacción (solo una vez al inicio)
        window.addEventListener('scroll', handleUserInteraction, { once: true, passive: true });
        document.addEventListener('click', handleUserInteraction, { once: true });
        document.addEventListener('touchstart', handleUserInteraction, { once: true, passive: true });
        document.addEventListener('mousemove', handleUserInteraction, { once: true, passive: true });
        
        // Función para toggle play/pause
        function toggleVideo() {
            if (q4Video.paused) {
                // Asegurar que el video no esté silenciado cuando el usuario lo reproduce
                q4Video.muted = false;
                q4Video.play().then(() => {
                    q4Video.classList.add('playing');
                    videoStarted = true;
                    userPausedManually = false; // Resetear el flag cuando el usuario reproduce manualmente
                }).catch(() => {
                    // Silenciar errores de reproducción
                });
            } else {
                q4Video.pause();
                q4Video.classList.remove('playing');
                userPausedManually = true; // Marcar que el usuario pausó manualmente
            }
        }
        
        // Click en el botón de play
        q4PlayButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleVideo();
        });
        
        // Click en cualquier parte del video para pausar
        q4Video.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            if (!q4Video.paused) {
                q4Video.pause();
                q4Video.classList.remove('playing');
                userPausedManually = true; // Marcar que el usuario pausó manualmente
            }
        });
        
        // Actualizar estado cuando el video se pausa
        q4Video.addEventListener('pause', function() {
            q4Video.classList.remove('playing');
            // No resetear videoStarted aquí para evitar que se reproduzca automáticamente
        });
        
        // Actualizar estado cuando el video se reproduce (incluyendo autoplay)
        q4Video.addEventListener('play', function() {
            q4Video.classList.add('playing');
            videoStarted = true;
        });
        
        // Verificar si el video ya está reproduciéndose al cargar (autoplay)
        if (!q4Video.paused) {
            q4Video.classList.add('playing');
            videoStarted = true;
        }
        
        // También verificar después de un pequeño delay por si el autoplay tarda
        setTimeout(function() {
            if (!q4Video.paused) {
                q4Video.classList.add('playing');
                videoStarted = true;
            }
        }, 500);
    }
    
    // ========================================
    // VIDEO PLAY/PAUSE EN-ACCION
    // ========================================
    const enAccionVideos = document.querySelectorAll('.en-accion-video');
    const enAccionPlayButtons = document.querySelectorAll('.en-accion-play-button');
    
    // Control de videos en-accion
    enAccionVideos.forEach((video, index) => {
        // Función para toggle play/pause
        function toggleEnAccionVideo() {
            if (video.paused) {
                video.play().then(() => {
                    video.classList.add('playing');
                }).catch(() => {
                    // silencio
                });
            } else {
                video.pause();
                video.classList.remove('playing');
            }
        }
        
        // Click en el botón de play
        if (enAccionPlayButtons[index]) {
            enAccionPlayButtons[index].addEventListener('click', function(e) {
                e.stopPropagation();
                toggleEnAccionVideo();
            });
        }
        
        // Click en cualquier parte del video para pausar
        video.addEventListener('click', function(e) {
            if (!video.paused) {
                video.pause();
                video.classList.remove('playing');
            }
        });
        
        // Mostrar botón cuando el video se pausa
        video.addEventListener('pause', function() {
            video.classList.remove('playing');
        });
        
        // Ocultar botón cuando el video se reproduce
        video.addEventListener('play', function() {
            video.classList.add('playing');
            // Pausar otros videos cuando uno se reproduce
            enAccionVideos.forEach((otherVideo, otherIndex) => {
                if (otherIndex !== index && !otherVideo.paused) {
                    otherVideo.pause();
                    otherVideo.classList.remove('playing');
                }
            });
        });
    });
    
    
    // ========================================
    // VIDEOS MOBILE EN-ACCION
    // ========================================
    const enAccionMobileVideos = document.querySelectorAll('.en-accion-mobile-video');
    const enAccionMobilePlayButtons = document.querySelectorAll('.en-accion-mobile-play-button');
    
    // Control de videos en-accion mobile
    enAccionMobileVideos.forEach((video, index) => {
        // Función para toggle play/pause
        function toggleEnAccionMobileVideo() {
            if (video.paused) {
                video.play().then(() => {
                    video.classList.add('playing');
                }).catch(() => {
                    // silencio
                });
            } else {
                video.pause();
                video.classList.remove('playing');
            }
        }
        
        // Click en el botón de play
        if (enAccionMobilePlayButtons[index]) {
            enAccionMobilePlayButtons[index].addEventListener('click', function(e) {
                e.stopPropagation();
                toggleEnAccionMobileVideo();
            });
        }
        
        // Click en cualquier parte del video para pausar
        video.addEventListener('click', function(e) {
            if (!video.paused) {
                video.pause();
                video.classList.remove('playing');
            }
        });
        
        // Mostrar botón cuando el video se pausa
        video.addEventListener('pause', function() {
            video.classList.remove('playing');
        });
        
        // Ocultar botón cuando el video se reproduce
        video.addEventListener('play', function() {
            video.classList.add('playing');
            // Pausar otros videos cuando uno se reproduce
            enAccionMobileVideos.forEach((otherVideo, otherIndex) => {
                if (otherIndex !== index && !otherVideo.paused) {
                    otherVideo.pause();
                    otherVideo.classList.remove('playing');
                }
            });
        });
    });
    
    // ========================================
    // CARRUSEL MOBILE EN-ACCION CONTROLS
    // ========================================
    const enAccionMobileCarousel = document.querySelector('.en-accion-mobile-carousel');
    const enAccionMobilePrevBtn = document.querySelector('.en-accion-mobile-prev-btn');
    const enAccionMobileNextBtn = document.querySelector('.en-accion-mobile-next-btn');
    const enAccionMobileVideo = document.querySelector('.en-accion-mobile-video');
    const enAccionMobilePlayButton = document.querySelector('.en-accion-mobile-play-button');
    const enAccionMobileProgressIndicator = document.querySelector('.en-accion-mobile-progress-indicator');
    
    
    if (enAccionMobileCarousel && enAccionMobilePrevBtn && enAccionMobileNextBtn) {
        let currentSlide = 0;
        const totalSlides = 3;
        
        // Variable para evitar actualizaciones múltiples
        let isUpdatingProgress = false;
        
        // Función para actualizar la barra de progreso (EN ACCION MOBILE)
        function updateEnAccionMobileProgressBar() {
            if (isUpdatingProgress) return;
            isUpdatingProgress = true;
            
            if (enAccionMobileProgressIndicator) {
                const progressBar = document.querySelector('.en-accion-mobile-progress-bar');
                if (!progressBar) {
                    isUpdatingProgress = false;
                    return;
                }
                
                // Calcular progreso (0 a 1)
                const progress = currentSlide / (totalSlides - 1);
                
                // Obtener ancho de la barra y del indicador
                const trackWidth = progressBar.offsetWidth;
                const indicatorWidth = enAccionMobileProgressIndicator.offsetWidth;
                const maxPosition = Math.max(0, trackWidth - indicatorWidth);
                const position = progress * maxPosition;
                
                // Usar left como beneficios (más compatible)
                enAccionMobileProgressIndicator.style.transform = 'none';
                enAccionMobileProgressIndicator.style.left = `${position}px`;
            }
            
            // Reset flag después de un pequeño delay
            setTimeout(() => {
                isUpdatingProgress = false;
            }, 50);
        }
        
        // Función para ir al slide anterior
        function goToPreviousSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                const firstSlide = enAccionMobileCarousel.querySelector('.en-accion-carousel-slide');
                const slideWidth = firstSlide.offsetWidth + 15; // Ancho dinámico + 15px padding
                enAccionMobileCarousel.scrollLeft -= slideWidth;
                updateEnAccionMobileProgressBar();
            }
        }
        
        // Función para ir al slide siguiente
        function goToNextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                const firstSlide = enAccionMobileCarousel.querySelector('.en-accion-carousel-slide');
                const slideWidth = firstSlide.offsetWidth + 15; // Ancho dinámico + 15px padding
                enAccionMobileCarousel.scrollLeft += slideWidth;
                updateEnAccionMobileProgressBar();
            }
        }
        
        // Event listeners para los botones
        enAccionMobilePrevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            goToPreviousSlide();
        });
        
        enAccionMobileNextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            goToNextSlide();
        });
        
        // Inicializar la barra de progreso
        updateEnAccionMobileProgressBar();
        
        // Sincronizar la barra con el scroll del carousel
        enAccionMobileCarousel.addEventListener('scroll', function() {
            const firstSlide = enAccionMobileCarousel.querySelector('.en-accion-carousel-slide');
            const slideWidth = firstSlide.offsetWidth + 15;
            const newSlide = Math.round(enAccionMobileCarousel.scrollLeft / slideWidth);
            
            if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
                currentSlide = newSlide;
                updateEnAccionMobileProgressBar();
            }
        });
    }
    
    // Control de videos de En Acción
    document.addEventListener('DOMContentLoaded', function() {
        const enAccionMobileVideos = document.querySelectorAll('.en-accion-mobile-video');
        const enAccionMobilePlayButtons = document.querySelectorAll('.en-accion-mobile-play-button');
        
        // Función para toggle play/pause de un video específico
        function toggleVideo(video) {
            if (video.paused) {
                video.play().then(() => {
                    video.classList.add('playing');
                }).catch(error => {
                    // Error silencioso
                });
            } else {
                video.pause();
                video.classList.remove('playing');
            }
        }
        
        // Función para pausar todos los otros videos
        function pauseOtherVideos(currentVideo) {
            enAccionMobileVideos.forEach(video => {
                if (video !== currentVideo && !video.paused) {
                    video.pause();
                    video.classList.remove('playing');
                }
            });
        }
        
        // Configurar cada video
        enAccionMobileVideos.forEach((video, index) => {
            const playButton = enAccionMobilePlayButtons[index];
            
            if (playButton) {
                // Click en el botón de play
                playButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    pauseOtherVideos(video);
                    toggleVideo(video);
                });
                
                // Click en cualquier parte del video para pausar
                video.addEventListener('click', function(e) {
                    if (!video.paused) {
                        video.pause();
                        video.classList.remove('playing');
                    }
                });
                
                // Mostrar botón cuando el video se pausa
                video.addEventListener('pause', function() {
                    video.classList.remove('playing');
                });
                
                // Ocultar botón cuando el video se reproduce
                video.addEventListener('play', function() {
                    video.classList.add('playing');
                    pauseOtherVideos(video);
                });
            }
        });
    });
    
    
    // ========================================
    // VIDEOS TABLET EN-ACCION
    // ========================================
    const enAccionTabletVideos = document.querySelectorAll('.en-accion-tablet-video');
    const enAccionTabletPlayButtons = document.querySelectorAll('.en-accion-tablet-play-button');
    
    // Control de videos en-accion tablet
    enAccionTabletVideos.forEach((video, index) => {
        // Función para toggle play/pause
        function toggleEnAccionTabletVideo() {
            if (video.paused) {
                video.play().then(() => {
                    video.classList.add('playing');
                }).catch(() => {
                    // silencio
                });
            } else {
                video.pause();
                video.classList.remove('playing');
            }
        }
        
        // Click en el botón de play
        if (enAccionTabletPlayButtons[index]) {
            enAccionTabletPlayButtons[index].addEventListener('click', function(e) {
                e.stopPropagation();
                toggleEnAccionTabletVideo();
            });
        }
        
        // Click en cualquier parte del video para pausar
        video.addEventListener('click', function(e) {
            if (!video.paused) {
                video.pause();
                video.classList.remove('playing');
            }
        });
        
        // Mostrar botón cuando el video se pausa
        video.addEventListener('pause', function() {
            video.classList.remove('playing');
        });
        
        // Ocultar botón cuando el video se reproduce
        video.addEventListener('play', function() {
            video.classList.add('playing');
            // Pausar otros videos cuando uno se reproduce
            enAccionTabletVideos.forEach((otherVideo, otherIndex) => {
                if (otherIndex !== index && !otherVideo.paused) {
                    otherVideo.pause();
                    otherVideo.classList.remove('playing');
                }
            });
        });
    });
    
    
    // ========================================
    // CARRUSEL MOBILE - CSS PURO
    // ========================================
    const carouselVideos = document.querySelectorAll('.carousel-video');
    const carouselPlayButtons = document.querySelectorAll('.carousel-play-button');
    
    // Control de videos en el carrusel
    carouselVideos.forEach((video, index) => {
        let videoStarted = false;
        let userPausedManually = false;
        
        // Función para intentar reproducir el video (solo al inicio)
        function tryPlayVideo() {
            if (video.paused && !videoStarted && !userPausedManually) {
                // Intentar activar el audio automáticamente
                video.muted = false;
                video.play().then(() => {
                    video.classList.add('playing');
                    videoStarted = true;
                }).catch(() => {
                    // Si falla, intentar con muted para permitir autoplay
                    video.muted = true;
                    video.play().then(() => {
                        video.classList.add('playing');
                        videoStarted = true;
                    }).catch(() => {
                        // Autoplay bloqueado completamente
                    });
                });
            }
        }
        
        // Intentar reproducir cuando el video esté listo
        video.addEventListener('loadedmetadata', tryPlayVideo);
        video.addEventListener('canplay', tryPlayVideo);
        video.addEventListener('canplaythrough', tryPlayVideo);
        
        // Intentar inmediatamente si ya está listo
        if (video.readyState >= 2) {
            tryPlayVideo();
        }
        
        // Intentar reproducir cuando el usuario interactúa (solo una vez al inicio)
        let userInteractionHandled = false;
        function handleUserInteraction() {
            if (!userInteractionHandled && video.paused && !userPausedManually) {
                userInteractionHandled = true;
                // Activar audio cuando el usuario interactúa
                video.muted = false;
                video.play().then(() => {
                    video.classList.add('playing');
                    videoStarted = true;
                }).catch(() => {
                    // Error
                });
            }
        }
        
        // Escuchar eventos de interacción (solo una vez al inicio)
        window.addEventListener('scroll', handleUserInteraction, { once: true, passive: true });
        document.addEventListener('click', handleUserInteraction, { once: true });
        document.addEventListener('touchstart', handleUserInteraction, { once: true, passive: true });
        
        // Verificar si el video ya está reproduciéndose al cargar (autoplay)
        if (!video.paused) {
            video.classList.add('playing');
            videoStarted = true;
        }
        
        // También verificar después de un pequeño delay por si el autoplay tarda
        setTimeout(function() {
            if (!video.paused) {
                video.classList.add('playing');
                videoStarted = true;
            }
        }, 500);
        
        // Función para toggle play/pause
        function toggleVideo() {
            if (video.paused) {
                // Asegurar que el video no esté silenciado cuando el usuario lo reproduce
                video.muted = false;
                video.play().then(() => {
                    video.classList.add('playing');
                    videoStarted = true;
                    userPausedManually = false;
                }).catch(() => {
                    // Silenciar errores de reproducción
                });
            } else {
                video.pause();
                video.classList.remove('playing');
                userPausedManually = true;
            }
        }
        
        // Click en el botón de play
        if (carouselPlayButtons[index]) {
            carouselPlayButtons[index].addEventListener('click', function(e) {
                e.stopPropagation();
                toggleVideo();
            });
        }
        
        // Click en cualquier parte del video para pausar
        video.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            if (!video.paused) {
                video.pause();
                video.classList.remove('playing');
                userPausedManually = true;
            }
        });
        
        // Actualizar estado cuando el video se pausa
        video.addEventListener('pause', function() {
            video.classList.remove('playing');
        });
        
        // Actualizar estado cuando el video se reproduce
        video.addEventListener('play', function() {
            video.classList.add('playing');
            videoStarted = true;
            // Pausar otros videos cuando uno se reproduce
            carouselVideos.forEach((otherVideo, otherIndex) => {
                if (otherIndex !== index && !otherVideo.paused) {
                    otherVideo.pause();
                    otherVideo.classList.remove('playing');
                }
            });
        });
    });
    

    // ========================================
    // CARRUSEL DE BENEFICIOS (NATIVO MOBILE)
    // ========================================
    
    // Variable global para trackear el slide actual
    let currentBeneficiosSlide = 0;
    
    // Variable para almacenar la última posición calculada
    let lastProgressPosition = -1;
    
    // Variable para detectar si el usuario hizo scroll manual
    let userScrolledManually = false;
    
    // Variable para detectar si estamos al principio o al final
    let isAtBeginningBeneficios = true;
    let isAtEndBeneficios = false;
    
    
    // Función para actualizar la barra de progreso basada en scroll real
    function updateBeneficiosProgressBar() {
        const carousel = document.querySelector('.beneficios-mobile-carousel');
        if (!carousel) return;
        
        // Buscar elementos específicamente dentro de la sección mobile
        const mobileSection = carousel.closest('.beneficios-mobile-carousel-section');
        if (!mobileSection) return;
        
        const progressIndicator = mobileSection.querySelector('.beneficios-progress-indicator');
        const progressBar = mobileSection.querySelector('.beneficios-progress-bar');
        
        if (!progressIndicator || !progressBar) return;
        
        // Calcular progreso basado en la posición real de scroll
        const scrollLeft = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
        
        // Actualizar variables de posición
        isAtBeginningBeneficios = scrollLeft <= 10; // 10px de tolerancia
        isAtEndBeneficios = scrollLeft >= maxScroll - 10; // 10px de tolerancia
        
        // Evitar división por cero
        const progress = maxScroll > 0 ? Math.min(scrollLeft / maxScroll, 1) : 0;
        
        // Obtener dimensiones reales del DOM
        const trackWidth = progressBar.offsetWidth;
        const indicatorWidth = progressIndicator.offsetWidth;
        const maxPosition = trackWidth - indicatorWidth;
        
        // Calcular posición del indicador
        const indicatorPosition = progress * maxPosition;
        
        // Solo actualizar si la posición cambió significativamente (evitar micro-movimientos)
        if (Math.abs(indicatorPosition - lastProgressPosition) > 0.5) {
            progressIndicator.style.transform = `translateX(${indicatorPosition}px)`;
            lastProgressPosition = indicatorPosition;
        }
    }
    
    // Función para actualizar estado de botones (simplificada)
    function updateBeneficiosButtons() {
        // Función vacía - botones siempre habilitados
    }
    
    // Función para mover el carrusel usando scrollIntoView
    function scrollBeneficiosCarousel(direction) {
        const mobileSection = document.querySelector('.beneficios-mobile-carousel-section');
        if (!mobileSection) return;
        
        const carousel = mobileSection.querySelector('.beneficios-mobile-carousel');
        if (!carousel) return;
        
        const slides = carousel.querySelectorAll('.beneficios-carousel-slide');
        const totalSlides = slides.length;
        
        // Actualizar variables de posición antes de usar la lógica
        const scrollLeft = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
        isAtBeginningBeneficios = scrollLeft <= 10; // 10px de tolerancia
        isAtEndBeneficios = scrollLeft >= maxScroll - 10; // 10px de tolerancia
        
        // Actualizar currentBeneficiosSlide basado en la posición real del scroll
        const slideWidth = carousel.querySelector('.beneficios-carousel-slide').offsetWidth + 15; // Incluir padding
        const realCurrentSlide = Math.round(scrollLeft / slideWidth);
        currentBeneficiosSlide = Math.max(0, Math.min(realCurrentSlide, totalSlides - 1));
        
        // Lógica según si vengo de dedo o botones
        if (userScrolledManually) {
            // Vengo de navegar con dedo
            if (isAtBeginningBeneficios && direction === 'right') {
                // Estoy al principio y presiono derecha → siguiente
                currentBeneficiosSlide = Math.min(totalSlides - 1, currentBeneficiosSlide + 1);
            } else if (isAtEndBeneficios && direction === 'left') {
                // Estoy al final y presiono izquierda → anterior
                currentBeneficiosSlide = Math.max(0, currentBeneficiosSlide - 1);
            } else {
                // Estoy en el medio → ir a extremos
                if (direction === 'left') {
                    currentBeneficiosSlide = 0; // Ir al principio
                } else {
                    currentBeneficiosSlide = totalSlides - 1; // Ir al final
                }
            }
        } else {
            // Vengo de navegar con botones → siempre de a uno
            if (direction === 'left') {
                currentBeneficiosSlide = Math.max(0, currentBeneficiosSlide - 1);
            } else if (direction === 'right') {
                currentBeneficiosSlide = Math.min(totalSlides - 1, currentBeneficiosSlide + 1);
            }
        }
        
        // Resetear flag después de usar botones
        userScrolledManually = false;
        
        
        // Usar scrollIntoView en la slide target
        const targetSlide = slides[currentBeneficiosSlide];
        if (targetSlide) {
            targetSlide.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'start'
            });
        }
        
        // Actualizar barra de progreso
        updateBeneficiosProgressBar();
    }
    
    // Inicializar el carrusel de beneficios
    function initializeBeneficiosCarousel() {
        const mobileSection = document.querySelector('.beneficios-mobile-carousel-section');
        if (!mobileSection) {
            setTimeout(initializeBeneficiosCarousel, 100);
            return;
        }
        
        const beneficiosCarousel = mobileSection.querySelector('.beneficios-mobile-carousel');
        const leftBtn = mobileSection.querySelector('.beneficios-arrow-left');
        const rightBtn = mobileSection.querySelector('.beneficios-arrow-right');
        
        if (beneficiosCarousel && leftBtn && rightBtn) {
            // Asegurar que la variable esté en 0 al inicializar
            currentBeneficiosSlide = 0;
            
            // Actualizar barra de progreso al cargar la página
            updateBeneficiosProgressBar();
            
            // Detectar scroll manual con touch
            beneficiosCarousel.addEventListener('touchstart', function() {
                // Marcar que el usuario está haciendo scroll manual
                userScrolledManually = true;
            });
            
            // Listener para actualizar barra de progreso
            beneficiosCarousel.addEventListener('scroll', function() {
                // Solo actualizar la barra de progreso
                updateBeneficiosProgressBar();
            });
        } else {
            // Retry después de 100ms si los elementos no están listos
            setTimeout(initializeBeneficiosCarousel, 100);
        }
    }
    
    // Ejecutar inicialización
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeBeneficiosCarousel);
    } else {
        // DOM ya está listo
        setTimeout(initializeBeneficiosCarousel, 50);
    }
    
    // Ejecutar también cuando la ventana esté completamente cargada
    window.addEventListener('load', () => {
        setTimeout(initializeBeneficiosCarousel, 100);
    });
    
    // Hacer la función global para que funcione desde el HTML
    window.scrollBeneficiosCarousel = scrollBeneficiosCarousel;

    // ========================================
    // CARRUSEL DE BENEFICIOS CON SWIPER
    // ========================================
    
    // Inicializador con guardas para esperar a window.Swiper (CDN) en producción
    function initBeneficiosSwiper(retryCount = 0) {
        const beneficiosSwiperElement = document.querySelector('.beneficios-desktop .beneficios-carousel-section .beneficios-swiper');
        if (!beneficiosSwiperElement) {
            if (retryCount < 20) {
                return setTimeout(() => initBeneficiosSwiper(retryCount + 1), 100);
            }
            return;
        }
        if (typeof window === 'undefined' || typeof window.Swiper === 'undefined') {
            if (retryCount < 20) {
                return setTimeout(() => initBeneficiosSwiper(retryCount + 1), 100);
            }
            return;
        }
        
        const beneficiosSwiper = new window.Swiper('.beneficios-desktop .beneficios-carousel-section .beneficios-swiper', {
        // Configuración básica
        loop: false,
        slidesPerView: 'auto', // Usar 'auto' para que respete el CSS
        slidesPerGroup: 1, // Por defecto avanza de a 1
        spaceBetween: 5,
        speed: 600, // Velocidad fija para todos los movimientos
        // Configuración responsive
        breakpoints: {
            600: {
                slidesPerView: 'auto',
                slidesPerGroup: 1, // En tablet avanza de a 1 slide
                spaceBetween: 15,
            },
            1100: {
                slidesPerView: 'auto',
                slidesPerGroup: 2, // En desktop avanza de a 2 slides
                spaceBetween: 15,
            },
            1366: {
                slidesPerView: 'auto',
                slidesPerGroup: 2,
                spaceBetween: 18,
            },
            1920: {
                slidesPerView: 'auto',
                slidesPerGroup: 2,
                spaceBetween: 20,
            }
        },
        
        // Navegación con botones personalizados
        navigation: {
            nextEl: '.beneficios-desktop .beneficios-carousel-btn-next',
            prevEl: '.beneficios-desktop .beneficios-carousel-btn-prev',
            disabledClass: 'swiper-button-disabled',
        },
        
        // Soporte para touch/drag
        touchRatio: 1,
        touchAngle: 45,
        grabCursor: true,
        
        // Transiciones y observadores
        effect: 'slide',
        watchSlidesProgress: true,
        observer: true,
        observeParents: true,
        
        // Eventos
        on: {
            init: function () {
                // Recalcular con pequeño atraso por imágenes/layout
                updateProgressBar(this);
                updateNavigationButtons(this);
                setTimeout(() => updateProgressBar(this), 50);
                // Vincular clics de navegación para forzar actualización inmediata
                const container = this.el.closest('.beneficios-desktop') || this.el.closest('.beneficios-carousel-section');
                if (container) {
                    const nextBtn = container.querySelector('.beneficios-carousel-btn-next');
                    const prevBtn = container.querySelector('.beneficios-carousel-btn-prev');
                    if (nextBtn) nextBtn.addEventListener('click', () => setTimeout(() => updateProgressBar(this), 0));
                    if (prevBtn) prevBtn.addEventListener('click', () => setTimeout(() => updateProgressBar(this), 0));
                }
            },
            slideChange: function () {
                updateProgressBar(this);
                updateNavigationButtons(this);
            },
            progress: function () {
                // Actualizar continuamente según progreso interno
                updateProgressBar(this);
            },
            setTranslate: function () {
                // Asegurar update cuando cambia translate
                updateProgressBar(this);
            },
            transitionEnd: function () {
                // Al finalizar transición CSS
                updateProgressBar(this);
            },
            slideChangeTransitionEnd: function () {
                // Asegurar medición pos-transición
                updateProgressBar(this);
            },
            resize: function () {
                updateProgressBar(this);
                updateNavigationButtons(this);
                setTimeout(() => updateProgressBar(this), 50);
            }
        }
        });
        
        // Recalcular cuando la ventana terminó de cargar (imágenes listas)
        window.addEventListener('load', () => {
            setTimeout(() => updateProgressBar(beneficiosSwiper), 100);
        });
    }
    
    // Ejecutar inicialización protegida
    initBeneficiosSwiper();
    
});

// ========================================
// SEDES CARDS FLIP - Dinámico
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Obtener todas las cards de sedes dinámicamente (excluyendo proximamente)
    const sedeCards = document.querySelectorAll('.sedes-grid-item:not(.proximamente)');
    
    sedeCards.forEach(card => {
        const cardId = card.id;
        if (cardId) {
            // Obtener datos de la card desde atributos data
            const direccion = card.dataset.direccion || 'Dirección no disponible';
            const contacto = card.dataset.contacto || 'Contacto no disponible';
            const linkGoogleMaps = card.dataset.linkGoogleMaps || '';
            const linkWhatsapp = card.dataset.linkWhatsapp || '';
            
            // Crear el back con contenido dinámico
            const back = document.createElement('div');
            back.className = 'sedes-card-back';
            
            // Construir el HTML dinámicamente
            let backContent = `
                <div class="sedes-back-content">
                    <div class="sedes-item">${direccion}</div>
                    <div class="sedes-item">Contacto: ${contacto}</div>
            `;
            
            // Agregar enlaces como items individuales
            if (linkGoogleMaps) {
                backContent += `
                    <div class="sedes-item">
                        <a href="${linkGoogleMaps}" target="_blank" class="sedes-link sedes-link-maps">
                            📍 Ver en Maps
                        </a>
                    </div>
                `;
            }
            
            if (linkWhatsapp) {
                backContent += `
                    <div class="sedes-item">
                        <a href="${linkWhatsapp}" target="_blank" class="sedes-link sedes-link-whatsapp">
                            💬 WhatsApp
                        </a>
                    </div>
                `;
            }
            
            backContent += `</div>`;
            
            back.innerHTML = backContent;
            card.appendChild(back);
            
            // Event listener para toggle
            card.addEventListener('click', function() {
                card.classList.toggle('flipped');
            });
        }
    });
    
    // ========================================
    // CARRUSEL DE COMUNIDAD CON SWIPER
    // ========================================
    
        // Función para actualizar la barra de progreso de comunidad
        function updateComunidadProgressBar(swiper) {
            const progressIndicator = document.querySelector('.comunidad-tablet-progress-indicator');
            const progressBar = document.querySelector('.comunidad-tablet-progress-bar');
        if (!progressIndicator || !progressBar || !swiper) return;
        
        // Calcular progreso real basado en la posición actual
        const totalSlides = swiper.slides.length;
        const currentSlide = swiper.activeIndex;
        
        // Ajustar el cálculo para considerar las posiciones reales de navegación
        let progress;
        if (swiper.isEnd) {
            // Si estamos al final, la barra debe estar completamente a la derecha
            progress = 1;
        } else if (swiper.isBeginning) {
            // Si estamos al inicio, la barra debe estar completamente a la izquierda
            progress = 0;
        } else {
            // Para posiciones intermedias, usar cálculo proporcional
            // Usar totalSlides - 1 para todos los dispositivos
            const maxNavigableSlide = totalSlides - 1;
            progress = currentSlide / maxNavigableSlide;
        }
        
        // Obtener dimensiones reales del DOM
        const trackWidth = progressBar.offsetWidth;
        const indicatorWidth = progressIndicator.offsetWidth;
        const maxPosition = trackWidth - indicatorWidth;
        
        // Calcular posición final
        const position = progress * maxPosition;
        
        // Aplicar transformación
        progressIndicator.style.transform = `translateX(${position}px)`;
    }
    
    const swiperElement = document.querySelector('.comunidad-swiper');
    if (!swiperElement) {
        return;
    }
    
    function initComunidadSwiper(retryCount = 0) {
        const swiperElement = document.querySelector('.comunidad-swiper');
        if (!swiperElement) {
            if (retryCount < 20) {
                return setTimeout(() => initComunidadSwiper(retryCount + 1), 100);
            }
            return;
        }
        if (typeof window === 'undefined' || typeof window.Swiper === 'undefined') {
            if (retryCount < 20) {
                return setTimeout(() => initComunidadSwiper(retryCount + 1), 100);
            }
            return;
        }
        
        const comunidadSwiper = new window.Swiper('.comunidad-swiper', {
        // Configuración básica
        loop: false,
        slidesPerView: 'auto', // Usar 'auto' para que respete el CSS
        slidesPerGroup: 1, // Por defecto avanza de a 1
        spaceBetween: 5,
        speed: 600, // Velocidad fija para todos los movimientos
        
        // Navegación
        navigation: {
            nextEl: '.comunidad-tablet-carousel-btn-next',
            prevEl: '.comunidad-tablet-carousel-btn-prev',
        },
        
        // Eventos
        on: {
            init: function() {
                updateComunidadProgressBar(this);
            },
            slideChange: function() {
                updateComunidadProgressBar(this);
            },
            resize: function() {
                updateComunidadProgressBar(this);
            }
        }
        });
    }
    
    // Ejecutar inicialización protegida
    initComunidadSwiper();
});

// ========================================
// SEDES ACORDEÓN MOBILE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const accordionItems = document.querySelectorAll('.sedes-accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.sedes-accordion-header');
        
        header.addEventListener('click', function() {
            // Cerrar todos los otros items
            accordionItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle del item actual
            item.classList.toggle('active');
        });
    });
});

// ========================================
// CARRUSEL DE COMUNIDAD (NATIVO MOBILE)
// ========================================

// Variable global para trackear el slide actual
let currentComunidadSlide = 0;

// Variable para detectar si el usuario hizo scroll manual
let userScrolledManuallyComunidad = false;

// Variable para almacenar la última posición calculada
let lastProgressPositionComunidad = -1;

// Variable para detectar si estamos al principio o al final
let isAtBeginningComunidad = true;
let isAtEndComunidad = false;

// Función para actualizar la barra de progreso basada en scroll real
function updateComunidadProgressBar() {
    const carousel = document.querySelector('.comunidad-mobile-carousel');
    if (!carousel) return;
    
    // Buscar elementos específicamente dentro de la sección mobile
    const mobileSection = carousel.closest('.comunidad-mobile-carousel-section');
    if (!mobileSection) return;
    
    const progressIndicator = mobileSection.querySelector('.comunidad-progress-indicator');
    const progressBar = mobileSection.querySelector('.comunidad-progress-bar');
    
    if (!progressIndicator || !progressBar) return;
    
    // Calcular progreso basado en la posición real de scroll (igual que beneficios)
    const scrollLeft = carousel.scrollLeft;
    const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
    
    // Actualizar variables de posición
    isAtBeginningComunidad = scrollLeft <= 10; // 10px de tolerancia
    isAtEndComunidad = scrollLeft >= maxScroll - 10; // 10px de tolerancia
    
    // Evitar división por cero
    const progress = maxScroll > 0 ? Math.min(scrollLeft / maxScroll, 1) : 0;
    
    // Obtener dimensiones reales del DOM
    const trackWidth = progressBar.offsetWidth;
    const indicatorWidth = progressIndicator.offsetWidth;
    const maxPosition = trackWidth - indicatorWidth;
    
    // Calcular posición del indicador
    const indicatorPosition = progress * maxPosition;
    
    // Solo actualizar si la posición cambió significativamente (evitar micro-movimientos)
    if (Math.abs(indicatorPosition - lastProgressPositionComunidad) > 0.5) {
        progressIndicator.style.transform = `translateX(${indicatorPosition}px)`;
        lastProgressPositionComunidad = indicatorPosition;
    }
}

// Función para mover el carrusel usando scrollIntoView
function scrollComunidadCarousel(direction) {
    const mobileSection = document.querySelector('.comunidad-mobile-carousel-section');
    if (!mobileSection) return;
    
    const carousel = mobileSection.querySelector('.comunidad-mobile-carousel');
    if (!carousel) return;
    
    const slides = carousel.querySelectorAll('.comunidad-carousel-slide');
    const totalSlides = slides.length;
    
    // Actualizar variables de posición antes de usar la lógica
    const scrollLeft = carousel.scrollLeft;
    const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
    isAtBeginningComunidad = scrollLeft <= 10; // 10px de tolerancia
    isAtEndComunidad = scrollLeft >= maxScroll - 10; // 10px de tolerancia
    
    // Actualizar currentComunidadSlide basado en la posición real del scroll
    const slideWidth = carousel.querySelector('.comunidad-carousel-slide').offsetWidth + 15; // Incluir padding
    const realCurrentSlide = Math.round(scrollLeft / slideWidth);
    currentComunidadSlide = Math.max(0, Math.min(realCurrentSlide, totalSlides - 1));
    
    // Lógica según si vengo de dedo o botones
    if (userScrolledManuallyComunidad) {
        // Vengo de navegar con dedo
        if (isAtBeginningComunidad && direction === 1) {
            // Estoy al principio y presiono derecha → siguiente
            currentComunidadSlide = Math.min(totalSlides - 1, currentComunidadSlide + 1);
        } else if (isAtEndComunidad && direction === -1) {
            // Estoy al final y presiono izquierda → anterior
            currentComunidadSlide = Math.max(0, currentComunidadSlide - 1);
        } else {
            // Estoy en el medio → ir a extremos
            if (direction === -1) {
                currentComunidadSlide = 0; // Ir al principio
            } else {
                currentComunidadSlide = totalSlides - 1; // Ir al final
            }
        }
    } else {
        // Vengo de navegar con botones → siempre de a uno
        if (direction === -1) {
            currentComunidadSlide = Math.max(0, currentComunidadSlide - 1);
        } else if (direction === 1) {
            currentComunidadSlide = Math.min(totalSlides - 1, currentComunidadSlide + 1);
        }
    }
    
    // Resetear flag después de usar botones
    userScrolledManuallyComunidad = false;
    
    // Usar scrollIntoView en la slide target
    const targetSlide = slides[currentComunidadSlide];
    if (targetSlide) {
        targetSlide.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'start'
        });
    }
    
    // Actualizar barra de progreso
    updateComunidadProgressBar();
}

// Inicializar el carrusel de comunidad
function initializeComunidadCarousel() {
    const mobileSection = document.querySelector('.comunidad-mobile-carousel-section');
    if (!mobileSection) {
        setTimeout(initializeComunidadCarousel, 100);
        return;
    }
    
    const comunidadCarousel = mobileSection.querySelector('.comunidad-mobile-carousel');
    const leftBtn = mobileSection.querySelector('.comunidad-btn-prev');
    const rightBtn = mobileSection.querySelector('.comunidad-btn-next');
    
    if (comunidadCarousel && leftBtn && rightBtn) {
        // Asegurar que la variable esté en 0 al inicializar
        currentComunidadSlide = 0;
        
        // Actualizar barra de progreso al cargar la página
        updateComunidadProgressBar();
        
        // Detectar scroll manual con touch
        comunidadCarousel.addEventListener('touchstart', function() {
            // Marcar que el usuario está haciendo scroll manual
            userScrolledManuallyComunidad = true;
        });
        
        // Listener para actualizar barra de progreso
        comunidadCarousel.addEventListener('scroll', function() {
            // Solo actualizar la barra de progreso
            updateComunidadProgressBar();
        });
    } else {
        // Retry después de 100ms si los elementos no están listos
        setTimeout(initializeComunidadCarousel, 100);
    }
}

// Ejecutar inicialización
// Esperar a que el DOM esté completamente cargado
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeComunidadCarousel);
} else {
    // DOM ya está listo
    setTimeout(initializeComunidadCarousel, 50);
}

// Ejecutar también cuando la ventana esté completamente cargada
window.addEventListener('load', () => {
    setTimeout(initializeComunidadCarousel, 100);
});

// Hacer la función global para que funcione desde el HTML
window.scrollComunidadCarousel = scrollComunidadCarousel;

// ========================================
// TESTIMONIOS CARRERA MOBILE CAROUSEL
// ========================================

// Variables globales para testimonios-carrera
let currentTestimoniosCarreraSlide = 0;
let userScrolledManuallyTestimoniosCarrera = false;
let lastProgressPositionTestimoniosCarrera = -1;
let isAtBeginningTestimoniosCarrera = true;
let isAtEndTestimoniosCarrera = false;

// Función para actualizar la barra de progreso basada en scroll real
function updateTestimoniosCarreraProgressBar() {
    const carousel = document.querySelector('.testimonios-carrera-mobile-carousel');
    if (!carousel) return;
    
    // Buscar elementos específicamente dentro de la sección mobile
    const mobileSection = carousel.closest('.testimonios-carrera-mobile-carousel-section');
    if (!mobileSection) return;
    
    const progressIndicator = mobileSection.querySelector('.testimonios-carrera-progress-indicator');
    const progressBar = mobileSection.querySelector('.testimonios-carrera-progress-bar');
    
    if (!progressIndicator || !progressBar) return;
    
    // Calcular progreso basado en la posición real de scroll
    const scrollLeft = carousel.scrollLeft;
    const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
    
    // Actualizar variables de posición
    isAtBeginningTestimoniosCarrera = scrollLeft <= 10; // 10px de tolerancia
    isAtEndTestimoniosCarrera = scrollLeft >= maxScroll - 10; // 10px de tolerancia
    
    // Evitar división por cero
    const progress = maxScroll > 0 ? Math.min(scrollLeft / maxScroll, 1) : 0;
    
    // Obtener dimensiones reales del DOM
    const trackWidth = progressBar.offsetWidth;
    const indicatorWidth = progressIndicator.offsetWidth;
    const maxPosition = trackWidth - indicatorWidth;
    
    // Calcular posición del indicador
    const indicatorPosition = progress * maxPosition;
    
    // Solo actualizar si la posición cambió significativamente (evitar micro-movimientos)
    if (Math.abs(indicatorPosition - lastProgressPositionTestimoniosCarrera) > 0.5) {
        progressIndicator.style.transform = `translateX(${indicatorPosition}px)`;
        lastProgressPositionTestimoniosCarrera = indicatorPosition;
    }
}

// Función para mover el carrusel usando scrollIntoView
function scrollTestimoniosCarreraCarousel(direction) {
    const mobileSection = document.querySelector('.testimonios-carrera-mobile-carousel-section');
    if (!mobileSection) return;
    
    const carousel = mobileSection.querySelector('.testimonios-carrera-mobile-carousel');
    if (!carousel) return;
    
    const slides = carousel.querySelectorAll('.testimonios-carrera-carousel-slide');
    const totalSlides = slides.length;
    
    // Actualizar variables de posición antes de usar la lógica
    const scrollLeft = carousel.scrollLeft;
    const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
    isAtBeginningTestimoniosCarrera = scrollLeft <= 10; // 10px de tolerancia
    isAtEndTestimoniosCarrera = scrollLeft >= maxScroll - 10; // 10px de tolerancia
    
    // Actualizar currentTestimoniosCarreraSlide basado en la posición real del scroll
    const slideWidth = carousel.querySelector('.testimonios-carrera-carousel-slide').offsetWidth + 15; // Incluir padding
    const realCurrentSlide = Math.round(scrollLeft / slideWidth);
    currentTestimoniosCarreraSlide = Math.max(0, Math.min(realCurrentSlide, totalSlides - 1));
    
    // Lógica según si vengo de dedo o botones
    if (userScrolledManuallyTestimoniosCarrera) {
        // Vengo de navegar con dedo
        if (isAtBeginningTestimoniosCarrera && direction === 1) {
            // Estoy al principio y presiono derecha → siguiente
            currentTestimoniosCarreraSlide = Math.min(totalSlides - 1, currentTestimoniosCarreraSlide + 1);
        } else if (isAtEndTestimoniosCarrera && direction === -1) {
            // Estoy al final y presiono izquierda → anterior
            currentTestimoniosCarreraSlide = Math.max(0, currentTestimoniosCarreraSlide - 1);
        } else {
            // Estoy en el medio → ir a extremos
            if (direction === -1) {
                currentTestimoniosCarreraSlide = 0; // Ir al principio
            } else {
                currentTestimoniosCarreraSlide = totalSlides - 1; // Ir al final
            }
        }
    } else {
        // Vengo de navegar con botones → siempre de a uno
        if (direction === -1) {
            currentTestimoniosCarreraSlide = Math.max(0, currentTestimoniosCarreraSlide - 1);
        } else if (direction === 1) {
            currentTestimoniosCarreraSlide = Math.min(totalSlides - 1, currentTestimoniosCarreraSlide + 1);
        }
    }
    
    // Resetear flag después de usar botones
    userScrolledManuallyTestimoniosCarrera = false;
    
    // Usar scrollIntoView en la slide target
    const targetSlide = slides[currentTestimoniosCarreraSlide];
    if (targetSlide) {
        targetSlide.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'start'
        });
    }
    
    // Actualizar barra de progreso
    updateTestimoniosCarreraProgressBar();
}

// Inicializar el carrusel de testimonios-carrera
function initializeTestimoniosCarreraCarousel() {
    const mobileSection = document.querySelector('.testimonios-carrera-mobile-carousel-section');
    if (!mobileSection) {
        setTimeout(initializeTestimoniosCarreraCarousel, 100);
        return;
    }
    
    const testimoniosCarreraCarousel = mobileSection.querySelector('.testimonios-carrera-mobile-carousel');
    const leftBtn = mobileSection.querySelector('.testimonios-carrera-btn-prev');
    const rightBtn = mobileSection.querySelector('.testimonios-carrera-btn-next');
    
    if (testimoniosCarreraCarousel && leftBtn && rightBtn) {
        // Asegurar que la variable esté en 0 al inicializar
        currentTestimoniosCarreraSlide = 0;
        
        // Actualizar barra de progreso al cargar la página
        updateTestimoniosCarreraProgressBar();
        
        // Detectar scroll manual con touch
        testimoniosCarreraCarousel.addEventListener('touchstart', function() {
            // Marcar que el usuario está haciendo scroll manual
            userScrolledManuallyTestimoniosCarrera = true;
        });
        
        // Listener para actualizar barra de progreso
        testimoniosCarreraCarousel.addEventListener('scroll', function() {
            // Solo actualizar la barra de progreso
            updateTestimoniosCarreraProgressBar();
        });
    } else {
        // Retry después de 100ms si los elementos no están listos
        setTimeout(initializeTestimoniosCarreraCarousel, 100);
    }
}

// Ejecutar inicialización
// Esperar a que el DOM esté completamente cargado
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTestimoniosCarreraCarousel);
} else {
    // DOM ya está listo
    setTimeout(initializeTestimoniosCarreraCarousel, 50);
}

// Ejecutar también cuando la ventana esté completamente cargada
window.addEventListener('load', () => {
    setTimeout(initializeTestimoniosCarreraCarousel, 100);
});

// Hacer la función global para que funcione desde el HTML
window.scrollTestimoniosCarreraCarousel = scrollTestimoniosCarreraCarousel;

// ========================================
// VIDEOS MOBILE EN-ACCION CARRERA
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const enAccionCarreraMobileVideos = document.querySelectorAll('.en-accion-carrera-mobile-video');
    const enAccionCarreraMobilePlayButtons = document.querySelectorAll('.en-accion-carrera-mobile-play-button');
    
    // Función para toggle play/pause de un video específico
    function toggleEnAccionCarreraVideo(video) {
        if (video.paused) {
            video.play().then(() => {
                video.classList.add('playing');
            }).catch(error => {
                // Error silencioso
            });
        } else {
            video.pause();
            video.classList.remove('playing');
        }
    }
    
    // Función para pausar todos los otros videos
    function pauseOtherEnAccionCarreraVideos(currentVideo) {
        enAccionCarreraMobileVideos.forEach(video => {
            if (video !== currentVideo && !video.paused) {
                video.pause();
                video.classList.remove('playing');
            }
        });
    }
    
    // Configurar cada video
    enAccionCarreraMobileVideos.forEach((video, index) => {
        const playButton = enAccionCarreraMobilePlayButtons[index];
        
        if (playButton) {
            // Click en el botón de play
            playButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                pauseOtherEnAccionCarreraVideos(video);
                toggleEnAccionCarreraVideo(video);
            });
            
            // Click en cualquier parte del video para pausar
            video.addEventListener('click', function(e) {
                if (!video.paused) {
                    video.pause();
                    video.classList.remove('playing');
                }
            });
            
            // Mostrar botón cuando el video se pausa
            video.addEventListener('pause', function() {
                video.classList.remove('playing');
            });
            
            // Ocultar botón cuando el video se reproduce
            video.addEventListener('play', function() {
                video.classList.add('playing');
                pauseOtherEnAccionCarreraVideos(video);
            });
        }
    });
    
    // ========================================
    // CARRUSEL MOBILE EN-ACCION CARRERA CONTROLS
    // ========================================
    const enAccionCarreraMobileCarousel = document.querySelector('.en-accion-carrera-mobile-carousel');
    const enAccionCarreraMobilePrevBtn = document.querySelector('.en-accion-carrera-mobile-prev-btn');
    const enAccionCarreraMobileNextBtn = document.querySelector('.en-accion-carrera-mobile-next-btn');
    const enAccionCarreraMobileProgressIndicator = document.querySelector('.en-accion-carrera-mobile-progress-indicator');
    
    if (enAccionCarreraMobileCarousel && enAccionCarreraMobilePrevBtn && enAccionCarreraMobileNextBtn) {
        const slides = enAccionCarreraMobileCarousel.querySelectorAll('.en-accion-carrera-carousel-slide');
        const totalSlides = slides.length;
        let currentSlide = 0;
        
        // Variable para evitar actualizaciones múltiples
        let isUpdatingProgress = false;
        
        // Función para actualizar la barra de progreso
        function updateEnAccionCarreraMobileProgressBar() {
            if (isUpdatingProgress) return;
            isUpdatingProgress = true;
            
            if (enAccionCarreraMobileProgressIndicator && totalSlides > 0) {
                const progressBar = document.querySelector('.en-accion-carrera-mobile-progress-bar');
                if (!progressBar) {
                    isUpdatingProgress = false;
                    return;
                }
                
                // Calcular progreso (0 a 1)
                const progress = currentSlide / (totalSlides - 1);
                
                // Obtener ancho de la barra y del indicador
                const trackWidth = progressBar.offsetWidth;
                const indicatorWidth = enAccionCarreraMobileProgressIndicator.offsetWidth;
                const maxPosition = Math.max(0, trackWidth - indicatorWidth);
                const position = progress * maxPosition;
                
                // Usar left como beneficios (más compatible)
                enAccionCarreraMobileProgressIndicator.style.transform = 'none';
                enAccionCarreraMobileProgressIndicator.style.left = `${position}px`;
            }
            
            // Reset flag después de un pequeño delay
            setTimeout(() => {
                isUpdatingProgress = false;
            }, 50);
        }
        
        // Función para ir al slide anterior
        function goToPreviousSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                const firstSlide = enAccionCarreraMobileCarousel.querySelector('.en-accion-carrera-carousel-slide');
                if (firstSlide) {
                    const slideWidth = firstSlide.offsetWidth + 15; // Ancho dinámico + 15px padding
                    enAccionCarreraMobileCarousel.scrollLeft -= slideWidth;
                    updateEnAccionCarreraMobileProgressBar();
                }
            }
        }
        
        // Función para ir al slide siguiente
        function goToNextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                const firstSlide = enAccionCarreraMobileCarousel.querySelector('.en-accion-carrera-carousel-slide');
                if (firstSlide) {
                    const slideWidth = firstSlide.offsetWidth + 15; // Ancho dinámico + 15px padding
                    enAccionCarreraMobileCarousel.scrollLeft += slideWidth;
                    updateEnAccionCarreraMobileProgressBar();
                }
            }
        }
        
        // Event listeners para los botones
        enAccionCarreraMobilePrevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            goToPreviousSlide();
        });
        
        enAccionCarreraMobileNextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            goToNextSlide();
        });
        
        // Inicializar la barra de progreso
        updateEnAccionCarreraMobileProgressBar();
        
        // Sincronizar la barra con el scroll del carousel
        enAccionCarreraMobileCarousel.addEventListener('scroll', function() {
            const firstSlide = enAccionCarreraMobileCarousel.querySelector('.en-accion-carrera-carousel-slide');
            if (firstSlide) {
                const slideWidth = firstSlide.offsetWidth + 15;
                const newSlide = Math.round(enAccionCarreraMobileCarousel.scrollLeft / slideWidth);
                
                if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
                    currentSlide = newSlide;
                    updateEnAccionCarreraMobileProgressBar();
                }
            }
        });
    }
});

// ========================================
// PARTNERS SLICK CAROUSEL
// ========================================
let partnersCarouselInitialized = false;

function initializePartnersCarousel() {
    if (partnersCarouselInitialized) {
        return;
    }
    
    if ($('.partners-slider').length > 0 && typeof $.fn.slick !== 'undefined') {
        if ($('.partners-slider').hasClass('slick-initialized')) {
            return;
        }
        
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
        
        partnersCarouselInitialized = true;
    }
}

$(document).ready(function() {
    // Initialize only once when document is ready
    setTimeout(initializePartnersCarousel, 100);
});

// ========================================
// CERTIFICACION LIST ACCORDION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Desktop accordion
    const certificacionRows = document.querySelectorAll('.certificacion-list-row[data-item]');
    
    certificacionRows.forEach(row => {
        row.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item');
            const content = document.getElementById(itemId + '-content');
            
            // Cerrar todos los otros contenidos desktop
            document.querySelectorAll('.certificacion-list-content-expanded').forEach(otherContent => {
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
    
    // Mobile accordion
    const certificacionMobileRows = document.querySelectorAll('.certificacion-mobile-list-row[data-item]');
    
    certificacionMobileRows.forEach(row => {
        row.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item');
            const content = document.getElementById(itemId + '-content');
            
            // Cerrar todos los otros contenidos mobile
            document.querySelectorAll('.certificacion-mobile-list-content-expanded').forEach(otherContent => {
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

// ========================================
// FAQS LIST ACCORDION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const faqsRows = document.querySelectorAll('.faqs-list-row[data-item]');
    
    faqsRows.forEach(row => {
        row.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item');
            const content = document.getElementById(itemId + '-content');
            
            // Cerrar todos los otros contenidos
            document.querySelectorAll('.faqs-list-content-expanded').forEach(otherContent => {
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

// ========================================
// FUNCIONES PARA FOTOS CAROUSEL
// ========================================

// Función para actualizar la barra de progreso de fotos
function updateFotosProgressBar(swiper) {
    if (!swiper || !swiper.el) return;
    const container = swiper.el.closest('.fotos-desktop');
    if (!container) return;
    const progressIndicator = container.querySelector('.fotos-desktop-progress-indicator') || container.querySelector('.fotos-progress-indicator');
    const progressBar = container.querySelector('.fotos-desktop-progress-bar') || container.querySelector('.fotos-progress-bar');
    if (!progressIndicator || !progressBar) return;

    let progress = 0;
    if (Array.isArray(swiper.snapGrid) && typeof swiper.snapIndex === 'number') {
        const maxSnap = Math.max(1, swiper.snapGrid.length - 1);
        progress = Math.max(0, Math.min(1, swiper.snapIndex / maxSnap));
    } else if (typeof swiper.progress === 'number') {
        progress = Math.max(0, Math.min(1, swiper.progress));
    } else if (typeof swiper.activeIndex === 'number' && swiper.slides) {
        const maxNavigableSlide = Math.max(1, swiper.slides.length - 1);
        progress = Math.max(0, Math.min(1, swiper.activeIndex / maxNavigableSlide));
    }

    const trackWidth = progressBar.offsetWidth;
    const indicatorWidth = progressIndicator.offsetWidth;
    const maxPosition = Math.max(0, trackWidth - indicatorWidth);
    const position = progress * maxPosition;
    progressIndicator.style.transform = 'none';
    progressIndicator.style.left = `${position}px`;
}

// Función para actualizar el estado de los botones de navegación de fotos
function updateFotosNavigationButtons(swiper) {
    if (!swiper || !swiper.el) return;
    
    const carouselSection = swiper.el.closest('.fotos-carousel-section');
    if (!carouselSection) return;
    
    const nextBtn = carouselSection.querySelector('.fotos-carousel-btn-next');
    const prevBtn = carouselSection.querySelector('.fotos-carousel-btn-prev');
    
    if (!nextBtn || !prevBtn) return;
    
    // Deshabilitar botón anterior si está al inicio
    if (swiper.isBeginning) {
        prevBtn.classList.add('swiper-button-disabled');
    } else {
        prevBtn.classList.remove('swiper-button-disabled');
    }
    
    // Deshabilitar botón siguiente si está al final
    if (swiper.isEnd) {
        nextBtn.classList.add('swiper-button-disabled');
    } else {
        nextBtn.classList.remove('swiper-button-disabled');
    }
}

// ========================================
// CARRUSEL DE FOTOS CON SWIPER
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    function initFotosSwiper(retryCount = 0) {
        const fotosSwiperElement = document.querySelector('.fotos-desktop .fotos-carousel-section .fotos-swiper');
        if (!fotosSwiperElement) {
            if (retryCount < 20) {
                return setTimeout(() => initFotosSwiper(retryCount + 1), 100);
            }
            return;
        }
        if (typeof window === 'undefined' || typeof window.Swiper === 'undefined') {
            if (retryCount < 20) {
                return setTimeout(() => initFotosSwiper(retryCount + 1), 100);
            }
            return;
        }
        
        const fotosSwiper = new window.Swiper('.fotos-desktop .fotos-carousel-section .fotos-swiper', {
        loop: false,
        slidesPerView: 'auto',
        slidesPerGroup: 1,
        spaceBetween: 5,
        speed: 600,
        breakpoints: {
            600: {
                slidesPerView: 'auto',
                slidesPerGroup: 1,
                spaceBetween: 15,
            },
            1100: {
                slidesPerView: 'auto',
                slidesPerGroup: 2,
                spaceBetween: 15,
            },
            1366: {
                slidesPerView: 'auto',
                slidesPerGroup: 2,
                spaceBetween: 18,
            },
            1920: {
                slidesPerView: 'auto',
                slidesPerGroup: 2,
                spaceBetween: 20,
            }
        },
        navigation: {
            nextEl: '.fotos-desktop .fotos-carousel-btn-next',
            prevEl: '.fotos-desktop .fotos-carousel-btn-prev',
            disabledClass: 'swiper-button-disabled',
        },
        touchRatio: 1,
        touchAngle: 45,
        grabCursor: true,
        effect: 'slide',
        watchSlidesProgress: true,
        observer: true,
        observeParents: true,
        on: {
            init: function () {
                updateFotosProgressBar(this);
                updateFotosNavigationButtons(this);
                setTimeout(() => updateFotosProgressBar(this), 50);
            },
            slideChange: function () {
                updateFotosProgressBar(this);
                updateFotosNavigationButtons(this);
            },
            slideChangeTransitionEnd: function () {
                updateFotosProgressBar(this);
            },
            resize: function () {
                updateFotosProgressBar(this);
                updateFotosNavigationButtons(this);
                setTimeout(() => updateFotosProgressBar(this), 50);
            }
        }
        });
        
        window.addEventListener('load', () => {
            setTimeout(() => updateFotosProgressBar(fotosSwiper), 100);
        });
    }
    
    initFotosSwiper();
});

// ========================================
// VIDEO PLAY/PAUSE TESTIMONIOS CARRERA
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const testimoniosCarreraVideo = document.querySelector('.testimonios-carrera-video');
    const testimoniosCarreraPlayButton = document.querySelector('.testimonios-carrera-play-button');
    
    if (testimoniosCarreraVideo && testimoniosCarreraPlayButton) {
        // Función para toggle play/pause
        function toggleTestimoniosCarreraVideo() {
            if (testimoniosCarreraVideo.paused) {
                testimoniosCarreraVideo.play().then(() => {
                    testimoniosCarreraVideo.classList.add('playing');
                }).catch(() => {
                    // silencio
                });
            } else {
                testimoniosCarreraVideo.pause();
                testimoniosCarreraVideo.classList.remove('playing');
            }
        }
        
        // Click en el botón de play
        testimoniosCarreraPlayButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleTestimoniosCarreraVideo();
        });
        
        // Click en cualquier parte del video para pausar
        testimoniosCarreraVideo.addEventListener('click', function(e) {
            if (!testimoniosCarreraVideo.paused) {
                testimoniosCarreraVideo.pause();
                testimoniosCarreraVideo.classList.remove('playing');
            }
        });
        
        // Mostrar botón cuando el video se pausa
        testimoniosCarreraVideo.addEventListener('pause', function() {
            testimoniosCarreraVideo.classList.remove('playing');
        });
        
        // Ocultar botón cuando el video se reproduce
        testimoniosCarreraVideo.addEventListener('play', function() {
            testimoniosCarreraVideo.classList.add('playing');
        });
    }

    // ========================================
    // CARRUSEL DE FOTOS MOBILE
    // ========================================
    
    // Variables para el carrusel de fotos (nombres únicos para evitar conflictos)
    let currentFotosSlide = 0;
    let isAtBeginningFotos = true;
    let isAtEndFotos = false;
    let userScrolledManuallyFotos = false;
    let lastProgressPositionFotos = -1;
    
    // Función para actualizar el estado de los botones de navegación móvil de fotos
    function updateFotosMobileNavigationButtons() {
        const mobileSection = document.querySelector('.fotos-mobile-carousel-section');
        if (!mobileSection) return;
        
        const leftBtn = mobileSection.querySelector('.fotos-arrow-left');
        const rightBtn = mobileSection.querySelector('.fotos-arrow-right');
        
        if (!leftBtn || !rightBtn) return;
        
        // Deshabilitar botón izquierdo si está al inicio
        if (isAtBeginningFotos) {
            leftBtn.classList.add('fotos-btn-disabled');
            leftBtn.disabled = true;
        } else {
            leftBtn.classList.remove('fotos-btn-disabled');
            leftBtn.disabled = false;
        }
        
        // Deshabilitar botón derecho si está al final
        if (isAtEndFotos) {
            rightBtn.classList.add('fotos-btn-disabled');
            rightBtn.disabled = true;
        } else {
            rightBtn.classList.remove('fotos-btn-disabled');
            rightBtn.disabled = false;
        }
    }
    
    // Función para actualizar la barra de progreso de fotos
    function updateFotosProgressBar() {
        const carousel = document.querySelector('.fotos-mobile-carousel');
        if (!carousel) return;
        
        // Buscar elementos específicamente dentro de la sección mobile
        const mobileSection = carousel.closest('.fotos-mobile-carousel-section');
        if (!mobileSection) return;
        
        const progressIndicator = mobileSection.querySelector('.fotos-progress-indicator');
        const progressBar = mobileSection.querySelector('.fotos-progress-bar');
        
        if (!progressIndicator || !progressBar) return;
        
        // Calcular progreso basado en la posición real de scroll
        const scrollLeft = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
        
        // Actualizar variables de posición
        isAtBeginningFotos = scrollLeft <= 10; // 10px de tolerancia
        isAtEndFotos = scrollLeft >= maxScroll - 10; // 10px de tolerancia
        
        // Actualizar botones de navegación
        updateFotosMobileNavigationButtons();
        
        // Evitar división por cero
        const progress = maxScroll > 0 ? Math.min(scrollLeft / maxScroll, 1) : 0;
        
        // Obtener dimensiones reales del DOM
        const trackWidth = progressBar.offsetWidth;
        const indicatorWidth = progressIndicator.offsetWidth;
        const maxPosition = trackWidth - indicatorWidth;
        
        // Calcular posición del indicador
        const indicatorPosition = progress * maxPosition;
        
        // Solo actualizar si la posición cambió significativamente (evitar micro-movimientos)
        if (Math.abs(indicatorPosition - lastProgressPositionFotos) > 0.5) {
            progressIndicator.style.transform = `translateX(${indicatorPosition}px)`;
            lastProgressPositionFotos = indicatorPosition;
        }
    }
    
    // Función para mover el carrusel usando scrollIntoView
    function scrollFotosCarousel(direction) {
        const mobileSection = document.querySelector('.fotos-mobile-carousel-section');
        if (!mobileSection) return;
        
        const carousel = mobileSection.querySelector('.fotos-mobile-carousel');
        if (!carousel) return;
        
        const slides = carousel.querySelectorAll('.fotos-carousel-slide');
        const totalSlides = slides.length;
        
        // Actualizar variables de posición antes de usar la lógica
        const scrollLeft = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.offsetWidth;
        isAtBeginningFotos = scrollLeft <= 10; // 10px de tolerancia
        isAtEndFotos = scrollLeft >= maxScroll - 10; // 10px de tolerancia
        
        // Actualizar currentFotosSlide basado en la posición real del scroll
        const slideWidth = carousel.querySelector('.fotos-carousel-slide').offsetWidth + 15; // Incluir padding
        const realCurrentSlide = Math.round(scrollLeft / slideWidth);
        currentFotosSlide = Math.max(0, Math.min(realCurrentSlide, totalSlides - 1));
        
        // Lógica según si vengo de dedo o botones
        if (userScrolledManuallyFotos) {
            // Vengo de navegar con dedo
            if (isAtBeginningFotos && direction === 'right') {
                // Estoy al principio y presiono derecha → siguiente
                currentFotosSlide = Math.min(totalSlides - 1, currentFotosSlide + 1);
            } else if (isAtEndFotos && direction === 'left') {
                // Estoy al final y presiono izquierda → anterior
                currentFotosSlide = Math.max(0, currentFotosSlide - 1);
            } else {
                // Estoy en el medio → ir a extremos
                if (direction === 'left') {
                    currentFotosSlide = 0; // Ir al principio
                } else {
                    currentFotosSlide = totalSlides - 1; // Ir al final
                }
            }
        } else {
            // Vengo de navegar con botones → siempre de a uno
            if (direction === 'left') {
                currentFotosSlide = Math.max(0, currentFotosSlide - 1);
            } else if (direction === 'right') {
                currentFotosSlide = Math.min(totalSlides - 1, currentFotosSlide + 1);
            }
        }
        
        // Resetear flag después de usar botones
        userScrolledManuallyFotos = false;
        
        // Usar scrollIntoView en la slide target
        const targetSlide = slides[currentFotosSlide];
        if (targetSlide) {
            targetSlide.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'start'
            });
        }
        
        // Actualizar barra de progreso
        updateFotosProgressBar();
    }
    
    // Inicializar el carrusel de fotos
    function initializeFotosCarousel() {
        const mobileSection = document.querySelector('.fotos-mobile-carousel-section');
        if (!mobileSection) {
            setTimeout(initializeFotosCarousel, 100);
            return;
        }
        
        const fotosCarousel = mobileSection.querySelector('.fotos-mobile-carousel');
        const leftBtn = mobileSection.querySelector('.fotos-arrow-left');
        const rightBtn = mobileSection.querySelector('.fotos-arrow-right');
        
        if (fotosCarousel && leftBtn && rightBtn) {
            // Asegurar que la variable esté en 0 al inicializar
            currentFotosSlide = 0;
            
            // Actualizar barra de progreso al cargar la página
            updateFotosProgressBar();
            updateFotosMobileNavigationButtons();
            
            // Detectar scroll manual con touch
            fotosCarousel.addEventListener('touchstart', function() {
                // Marcar que el usuario está haciendo scroll manual
                userScrolledManuallyFotos = true;
            });
            
            // Listener para actualizar barra de progreso
            fotosCarousel.addEventListener('scroll', function() {
                // Solo actualizar la barra de progreso
                updateFotosProgressBar();
            });
        } else {
            // Retry después de 100ms si los elementos no están listos
            setTimeout(initializeFotosCarousel, 100);
        }
    }
    
    // Ejecutar inicialización
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFotosCarousel);
    } else {
        // DOM ya está listo
        setTimeout(initializeFotosCarousel, 50);
    }
    
    // Ejecutar también cuando la ventana esté completamente cargada
    window.addEventListener('load', () => {
        setTimeout(initializeFotosCarousel, 100);
    });
    
    // Hacer la función global para que funcione desde el HTML
    window.scrollFotosCarousel = scrollFotosCarousel;
});