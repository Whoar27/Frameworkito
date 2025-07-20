/* ========================================
   GUEST LAYOUT - JAVASCRIPT CON WHOAR
   Theme Management & Layout Interactions
   ======================================== */

class ThemeManager {
    constructor() {
        this.theme = this.getStoredTheme() || 'system';
        this.init();
    }

    init() {
        this.applyTheme(this.theme);
        this.bindEvents();
    }

    getStoredTheme() {
        try {
            return localStorage.getItem('theme');
        } catch (e) {
            return null;
        }
    }

    setStoredTheme(theme) {
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {
            console.warn('Cannot store theme preference');
        }
    }

    getSystemTheme() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    applyTheme(theme) {
        const root = document.documentElement;

        // Remove existing theme attributes
        root.removeAttribute('data-theme');

        if (theme === 'system') {
            // Let CSS handle system preference
            this.theme = 'system';
        } else {
            root.setAttribute('data-theme', theme);
            this.theme = theme;
        }

        this.setStoredTheme(theme);
        this.updateThemeToggle();
        this.updateThemeIcon(); // Nueva función para actualizar el icono

        // Dispatch theme change event
        window.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme, actualTheme: this.getActualTheme() }
        }));
    }

    getActualTheme() {
        if (this.theme === 'system') {
            return this.getSystemTheme();
        }
        return this.theme;
    }

    updateThemeIcon() {
        // Seleccionar todos los botones de tema (móvil y desktop)
        const themeButtons = document.querySelectorAll('.btn-ghost[data-bs-toggle="dropdown"] i');
        if (!themeButtons.length) return;

        // Iconos para cada tema
        const iconos = {
            'light': 'fas fa-sun',
            'dark': 'fas fa-moon',
            'system': 'fas fa-adjust'
        };

        // Actualizar todos los iconos de los botones
        const nuevoIcono = iconos[this.theme] || iconos.system;
        themeButtons.forEach(themeButton => {
            themeButton.className = nuevoIcono;
        });

        // Actualizar el título de todos los botones
        const tituloTexto = {
            'light': 'Tema claro activo - Cambiar tema',
            'dark': 'Tema oscuro activo - Cambiar tema',
            'system': 'Tema del sistema activo - Cambiar tema'
        };

        document.querySelectorAll('.btn-ghost[data-bs-toggle="dropdown"]').forEach(button => {
            button.title = tituloTexto[this.theme];
        });
    }

    updateThemeToggle() {
        // Marca activo el botón correspondiente
        document.querySelectorAll('.theme-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.choice === this.theme);
        });
    }

    bindEvents() {
        // Theme option clicks in dropdown
        document.addEventListener('click', (e) => {
            const option = e.target.closest('.theme-option');
            if (option) {
                const choice = option.dataset.choice;
                this.applyTheme(choice);
            }
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.theme === 'system') {
                this.updateThemeIcon(); // Actualizar icono cuando cambie el sistema
                this.applyTheme('system');
            }
        });

        // Actualizar icono al cargar la página
        setTimeout(() => {
            this.updateThemeIcon();
        }, 100);
    }
}

class LayoutManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupMobileToggle();
        this.setupBackToTop();
        this.setupHeaderScroll();
        this.setupCookieBanner();
        this.setupSmoothScrolling();
        this.setupFormEnhancements();
        this.setupAnimations();
        this.bindEvents();
    }

    setupMobileToggle() {
        // Bootstrap maneja automáticamente el toggle del menú móvil
        // No necesitamos código personalizado adicional
    }

    setupBackToTop() {
        const backToTop = document.getElementById('backToTop');
        if (!backToTop) return;

        const toggleBackToTop = () => {
            const scrolled = window.pageYOffset > 300;
            backToTop.classList.toggle('visible', scrolled);
        };

        window.addEventListener('scroll', toggleBackToTop);

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            Whoar.informacion('Regresando al inicio', {
                duracion: 1500,
                posicion: 'inferior-centro'
            });
        });
    }

    setupHeaderScroll() {
        let lastScrollTop = 0;
        const header = document.querySelector('.main-header');

        if (header) {
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // Add shadow on scroll
                header.classList.toggle('scrolled', scrollTop > 10);

                // Opcional: Hide/show header on scroll
                // if (scrollTop > lastScrollTop && scrollTop > 100) {
                //     header.style.transform = 'translateY(-100%)';
                // } else {
                //     header.style.transform = 'translateY(0)';
                // }

                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            });
        }
    }

    setupCookieBanner() {
        const cookieBanner = document.getElementById('cookieBanner');
        const cookieAccept = document.getElementById('cookieAccept');
        const cookieDecline = document.getElementById('cookieDecline');

        if (!cookieBanner) return;

        const showCookieBanner = () => {
            const consent = localStorage.getItem('cookieConsent');
            if (!consent) {
                setTimeout(() => {
                    cookieBanner.classList.add('visible');
                }, 2000);
            }
        };

        const hideCookieBanner = () => {
            cookieBanner.classList.remove('visible');
        };

        if (cookieAccept) {
            cookieAccept.addEventListener('click', () => {
                localStorage.setItem('cookieConsent', 'accepted');
                hideCookieBanner();
                Whoar.exito('Preferencias de cookies guardadas', {
                    duracion: 3000
                });
            });
        }

        if (cookieDecline) {
            cookieDecline.addEventListener('click', () => {
                localStorage.setItem('cookieConsent', 'declined');
                hideCookieBanner();
                Whoar.advertencia('Cookies rechazadas', {
                    duracion: 3000
                });
            });
        }

        showCookieBanner();
    }

    setupSmoothScrolling() {
        // Smooth scroll for anchor links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="#"]');
            if (!link) return;

            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                e.preventDefault();
                const headerHeight = document.querySelector('.main-header')?.offsetHeight || 70;
                const targetPosition = targetElement.offsetTop - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    }

    setupFormEnhancements() {
        // Add floating labels effect
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            const handleFocus = () => {
                input.parentElement?.classList.add('focused');
            };

            const handleBlur = () => {
                if (!input.value) {
                    input.parentElement?.classList.remove('focused');
                }
            };

            input.addEventListener('focus', handleFocus);
            input.addEventListener('blur', handleBlur);

            // Check initial state
            if (input.value) {
                input.parentElement?.classList.add('focused');
            }
        });

        // Form validation feedback
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    Whoar.error('Por favor, revisa los campos del formulario', {
                        duracion: 4000
                    });
                }
                form.classList.add('was-validated');
            });
        });
    }

    setupAnimations() {
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        const animatedElements = document.querySelectorAll('.fade-in, .slide-up, .hover-lift');
        animatedElements.forEach(el => observer.observe(el));

        // Counter animation
        const counters = document.querySelectorAll('.stat-number[data-count]');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    this.animateCounter(entry.target);
                    entry.target.classList.add('counted');
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => counterObserver.observe(counter));
    }

    animateCounter(element) {
        const target = parseInt(element.dataset.count);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString();
            }
        };

        updateCounter();
    }

    bindEvents() {
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('.search-form input');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            // Escape to close modals/dropdowns
            if (e.key === 'Escape') {
                this.closeAllDropdowns();
                this.closeMobileMenu();
            }

            // Theme toggle with Ctrl/Cmd + Shift + T
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                const themeOptions = document.querySelectorAll('.theme-option');
                const currentActive = document.querySelector('.theme-option.active');
                if (currentActive && themeOptions.length > 0) {
                    const currentIndex = Array.from(themeOptions).indexOf(currentActive);
                    const nextIndex = (currentIndex + 1) % themeOptions.length;
                    const nextTheme = themeOptions[nextIndex].dataset.theme;
                    window.themeManager?.applyTheme(nextTheme);
                }
            }
        });

        // Dropdown auto-close
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                this.closeAllDropdowns();
            }
        });

        // Page visibility API for performance
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.onPageVisible();
            } else {
                this.onPageHidden();
            }
        });

        // Search functionality
        const searchForm = document.querySelector('.search-form');
        const searchInput = document.querySelector('.search-form input');

        if (searchForm && searchInput) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const query = searchInput.value.trim();
                if (query) {
                    this.performSearch(query);
                } else {
                    Whoar.advertencia('Por favor ingresa un término de búsqueda', {
                        duracion: 3000
                    });
                }
            });
        }
    }

    performSearch(query) {
        Whoar.informacion('Buscando...', {
            duracion: 2000
        });

        // Simulate search API call
        setTimeout(() => {
            Whoar.exito(`Resultados para: "${query}"`, {
                duracion: 4000
            });
            // Redirect to search results or show results
            // window.location.href = `/search?q=${encodeURIComponent(query)}`;
        }, 1000);
    }

    closeAllDropdowns() {
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }

    closeMobileMenu() {
        const navbarCollapse = document.querySelector('.navbar-collapse');
        if (navbarCollapse) {
            navbarCollapse.classList.remove('show');
        }
    }

    onPageVisible() {
        // Resume animations, refresh data, etc.
        console.log('Page is visible');
    }

    onPageHidden() {
        // Pause animations, stop timers, etc.
        console.log('Page is hidden');
    }
}

class Utils {
    static debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    static throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    static formatDate(date, locale = 'es-ES') {
        return new Intl.DateTimeFormat(locale, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    }

    static formatNumber(number, locale = 'es-ES') {
        return new Intl.NumberFormat(locale).format(number);
    }

    static copyToClipboard(text) {
        if (navigator.clipboard) {
            return navigator.clipboard.writeText(text).then(() => {
                Whoar.exito('Texto copiado al portapapeles', {
                    duracion: 2000
                });
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                document.body.removeChild(textArea);
                Whoar.exito('Texto copiado al portapapeles', {
                    duracion: 2000
                });
                return Promise.resolve();
            } catch (err) {
                document.body.removeChild(textArea);
                Whoar.error('No se pudo copiar el texto', {
                    duracion: 3000
                });
                return Promise.reject(err);
            }
        }
    }

    static apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const config = { ...defaultOptions, ...options };

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            config.headers['X-CSRF-TOKEN'] = csrfToken;
        }

        // Show loading notification
        Whoar.informacion('Procesando solicitud...', {
            duracion: 1000
        });

        return fetch(url, config)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                Whoar.exito('Solicitud completada exitosamente', {
                    duracion: 2000
                });
                return data;
            })
            .catch(error => {
                console.error('API request failed:', error);
                Whoar.error('Error en la conexión. Intenta nuevamente.', {
                    duracion: 4000
                });
                throw error;
            });
    }

    static getSystemInfo() {
        return {
            userAgent: navigator.userAgent,
            language: navigator.language,
            platform: navigator.platform,
            cookieEnabled: navigator.cookieEnabled,
            onLine: navigator.onLine,
            viewport: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            screen: {
                width: screen.width,
                height: screen.height,
                colorDepth: screen.colorDepth
            }
        };
    }

    static detectDevice() {
        const ua = navigator.userAgent;
        return {
            isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua),
            isTablet: /iPad|Android(?!.*Mobile)/i.test(ua),
            isDesktop: !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua),
            isTouchDevice: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
            browser: this.getBrowser(ua),
            os: this.getOS(ua)
        };
    }

    static getBrowser(ua) {
        if (ua.includes('Chrome')) return 'Chrome';
        if (ua.includes('Firefox')) return 'Firefox';
        if (ua.includes('Safari')) return 'Safari';
        if (ua.includes('Edge')) return 'Edge';
        if (ua.includes('Opera')) return 'Opera';
        return 'Unknown';
    }

    static getOS(ua) {
        if (ua.includes('Windows')) return 'Windows';
        if (ua.includes('Mac')) return 'macOS';
        if (ua.includes('Linux')) return 'Linux';
        if (ua.includes('Android')) return 'Android';
        if (ua.includes('iOS')) return 'iOS';
        return 'Unknown';
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize managers
    window.themeManager = new ThemeManager();
    window.layoutManager = new LayoutManager();

    // Make Utils globally available
    window.Utils = Utils;

    // Device detection
    const device = Utils.detectDevice();
    document.body.classList.add(
        device.isMobile ? 'is-mobile' : 'is-desktop',
        device.isTouchDevice ? 'is-touch' : 'is-mouse'
    );

    // Performance monitoring
    if ('performance' in window) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                const perfData = performance.getEntriesByType('navigation')[0];
            }, 500);
        });
    }

    // Global error handling
    window.addEventListener('error', (e) => {
        console.error('Global error:', e.error);
        Whoar.error('Ha ocurrido un error inesperado', {
            duracion: 5000
        });
    });

    window.addEventListener('unhandledrejection', (e) => {
        console.error('Unhandled promise rejection:', e.reason);
        Whoar.advertencia('Se ha detectado un problema menor', {
            duracion: 4000
        });
    });
});

// Service Worker registration (if available)
if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
                Whoar.informacion('Modo offline disponible', {
                    duracion: 3000
                });
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}

// Export for modules (if using module system)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ThemeManager,
        LayoutManager,
        Utils
    };
}