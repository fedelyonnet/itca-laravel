<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll Spy logic for Home Page
        const navLinks = document.querySelectorAll('.nav-link[data-target]');
        const sections = {
            'home': document.body, 
            'beneficios': document.getElementById('beneficios'),
            'contacto': document.getElementById('contacto')
        };

        function activateLink(targetId) {
            navLinks.forEach(link => {
                if (link.dataset.target === targetId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Click handler
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const target = this.dataset.target;
                if (target === 'carreras') return; // Let default navigation happen
                
                // For on-page links
                activateLink(target);
            });
        });

        // Scroll listener
        let isScrolling = false;
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    const scrollY = window.scrollY;
                    const windowHeight = window.innerHeight;
                    const docHeight = document.documentElement.scrollHeight;
                    
                    // Default to home
                    let activeTarget = 'home';
                    
                    // Check Beneficios
                    if (sections.beneficios) {
                        const rect = sections.beneficios.getBoundingClientRect();
                        if (rect.top <= windowHeight / 2 && rect.bottom >= 100) {
                            activeTarget = 'beneficios';
                        }
                    }
                    
                    // Check Contacto
                    if (sections.contacto) {
                        const rect = sections.contacto.getBoundingClientRect();
                        if (rect.top <= windowHeight / 2 && rect.bottom >= 100) {
                            activeTarget = 'contacto';
                        }
                    }
                    
                    // If at bottom, contact might be active
                    if (scrollY + windowHeight >= docHeight - 50) {
                        activeTarget = 'contacto';
                    }
                    
                    activateLink(activeTarget);
                    isScrolling = false;
                });
                isScrolling = true;
            }
        });

        // Initial check
        if (window.location.hash) {
            const target = window.location.hash.substring(1);
            if (sections[target]) {
                activateLink(target);
            }
        } else {
            activateLink('home');
        }
    });
</script>
