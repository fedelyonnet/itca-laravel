/* ========================================
   ITCA - JavaScript Público
   Funcionalidad para el sitio público
   ======================================== */

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
        // Función para toggle play/pause
        function toggleVideo() {
            if (q4Video.paused) {
                q4Video.play();
                q4Video.classList.add('playing');
            } else {
                q4Video.pause();
                q4Video.classList.remove('playing');
            }
        }
        
        // Click en el botón de play
        q4PlayButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleVideo();
        });
        
        // Click en cualquier parte del video para pausar
        q4Video.addEventListener('click', function() {
            if (!q4Video.paused) {
                q4Video.pause();
                q4Video.classList.remove('playing');
            }
        });
        
        // Mostrar botón cuando el video se pausa
        q4Video.addEventListener('pause', function() {
            q4Video.classList.remove('playing');
        });
        
        // Ocultar botón cuando el video se reproduce
        q4Video.addEventListener('play', function() {
            q4Video.classList.add('playing');
        });
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
                }).catch(error => {
                    console.log('Error playing video:', error);
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
                }).catch(error => {
                    console.log('Error playing video:', error);
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
        
        // Función para actualizar la barra de progreso
        function updateProgressBar() {
            if (enAccionMobileProgressIndicator) {
                const progressWidth = (currentSlide / (totalSlides - 1)) * 66.666;
                enAccionMobileProgressIndicator.style.left = `${progressWidth}%`;
            }
        }
        
        // Función para ir al slide anterior
        function goToPreviousSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                const firstSlide = enAccionMobileCarousel.querySelector('.en-accion-carousel-slide');
                const slideWidth = firstSlide.offsetWidth + 15; // Ancho dinámico + 15px padding
                enAccionMobileCarousel.scrollLeft -= slideWidth;
                updateProgressBar();
            }
        }
        
        // Función para ir al slide siguiente
        function goToNextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                const firstSlide = enAccionMobileCarousel.querySelector('.en-accion-carousel-slide');
                const slideWidth = firstSlide.offsetWidth + 15; // Ancho dinámico + 15px padding
                enAccionMobileCarousel.scrollLeft += slideWidth;
                updateProgressBar();
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
        updateProgressBar();
        
        // Sincronizar la barra con el scroll del carousel
        enAccionMobileCarousel.addEventListener('scroll', function() {
            const firstSlide = enAccionMobileCarousel.querySelector('.en-accion-carousel-slide');
            const slideWidth = firstSlide.offsetWidth + 15;
            const newSlide = Math.round(enAccionMobileCarousel.scrollLeft / slideWidth);
            
            if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
                currentSlide = newSlide;
                updateProgressBar();
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
                }).catch(error => {
                    console.log('Error playing video:', error);
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
        // Función para toggle play/pause
        function toggleVideo() {
            if (video.paused) {
                video.play();
                video.classList.add('playing');
            } else {
                video.pause();
                video.classList.remove('playing');
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
        video.addEventListener('click', function() {
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
    
    // Función para actualizar la barra de progreso
    function updateProgressBar(swiper) {
        const progressIndicator = document.querySelector('.beneficios-progress-indicator');
        const progressBar = document.querySelector('.beneficios-progress-bar');
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
    
    const beneficiosSwiper = new Swiper('.beneficios-swiper', {
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
            nextEl: '.beneficios-carousel-btn-next',
            prevEl: '.beneficios-carousel-btn-prev',
            disabledClass: 'swiper-button-disabled',
        },
        
        // Soporte para touch/drag
        touchRatio: 1,
        touchAngle: 45,
        grabCursor: true,
        
        // Transiciones suaves
        effect: 'slide',
        
        // Eventos
        on: {
            init: function () {
                updateProgressBar(this);
                updateNavigationButtons(this);
            },
            slideChange: function () {
                updateProgressBar(this);
                updateNavigationButtons(this);
            }
        }
    });

    // Función para actualizar el estado de los botones de navegación
    function updateNavigationButtons(swiper) {
        const nextBtn = document.querySelector('.beneficios-carousel-btn-next');
        const prevBtn = document.querySelector('.beneficios-carousel-btn-prev');
        
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
            
            // Crear el back con contenido dinámico
            const back = document.createElement('div');
            back.className = 'sedes-card-back';
            back.innerHTML = `
                <div class="sedes-back-content">
                    <div class="sedes-direccion">${direccion}</div>
                    <div class="sedes-contacto">Contacto: ${contacto}</div>
                </div>
            `;
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
    
    const comunidadSwiper = new Swiper('.comunidad-swiper', {
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
            speed: 3000,
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
    setTimeout(initializePartnersCarousel, 1000);
});