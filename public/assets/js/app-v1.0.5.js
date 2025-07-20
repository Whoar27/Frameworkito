/**
 * PANEL DE ADMINISTRACIÓN - Frameworkito
 * JavaScript principal con funcionalidades completas
 * Manejo de sidebar, temas, navegación y componentes interactivos
 */

// ============================
// CONFIGURACIÓN GLOBAL
// ============================

const AdminPanel = {
    // Configuración por defecto
    config: {
        sidebarBreakpoint: 768,
        theme: localStorage.getItem('admin-theme') || 'light',
        sidebarState: localStorage.getItem('admin-sidebar-state') || 'expanded',
        animationDuration: 300,
        debounceDelay: 250
    },

    // Estado actual
    state: {
        isMobile: false,
        sidebarOpen: false,
        sidebarCollapsed: false,
        currentTheme: 'light',
        searchFocused: false
    },

    // Referencias a elementos DOM
    elements: {
        html: null,
        body: null,
        sidebar: null,
        mainContent: null,
        sidebarOverlay: null,
        sidebarToggle: null,
        sidebarToggleMobile: null,
        themeToggle: null,
        searchInput: null,
        navLinks: null,
        submenuToggles: null
    },

    // Utilidades
    utils: {
        debounce: null,
        throttle: null
    }
};

// ============================
// UTILIDADES GENERALES
// ============================

/**
 * Función debounce para optimizar eventos
 */
function debounce(func, wait) {
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

/**
 * Función throttle para optimizar eventos
 */
function throttle(func, limit) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Detecta si es dispositivo móvil
 */
function isMobileDevice() {
    return window.innerWidth <= AdminPanel.config.sidebarBreakpoint;
}

/**
 * Obtiene elementos del DOM
 */
function getElements() {
    AdminPanel.elements = {
        html: document.documentElement,
        body: document.body,
        sidebar: document.getElementById('sidebar'),
        mainContent: document.getElementById('mainContent'),
        sidebarOverlay: document.getElementById('sidebarOverlay'),
        sidebarToggle: document.getElementById('sidebarToggle'),
        sidebarToggleMobile: document.getElementById('sidebarToggleMobile'),
        themeToggle: document.getElementById('themeToggle'),
        searchInput: document.querySelector('.search-input'),
        navLinks: document.querySelectorAll('.nav-link'),
        submenuToggles: document.querySelectorAll('.submenu-toggle')
    };
}

/**
 * Muestra notificación usando el sistema Whoar
 */
function showNotification(type, message, options = {}) {
    if (typeof window.Whoar !== 'undefined') {
        return window.Whoar.notificacion(type, message, options);
    } else {
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

// ============================
// MANEJO DEL SIDEBAR
// ============================

const SidebarManager = {
    /**
     * Inicializa el manejo del sidebar
     */
    init() {
        this.setupEventListeners();
        this.restoreState();
        this.handleResize();
    },

    /**
     * Configura los event listeners del sidebar
     */
    setupEventListeners() {
        // Toggle desktop
        if (AdminPanel.elements.sidebarToggle) {
            AdminPanel.elements.sidebarToggle.addEventListener('click', () => {
                this.toggleCollapse();
            });
        }

        // Toggle mobile
        if (AdminPanel.elements.sidebarToggleMobile) {
            AdminPanel.elements.sidebarToggleMobile.addEventListener('click', () => {
                this.toggleMobile();
            });
        }

        // Overlay mobile
        if (AdminPanel.elements.sidebarOverlay) {
            AdminPanel.elements.sidebarOverlay.addEventListener('click', () => {
                this.closeMobile();
            });
        }

        // Resize window
        window.addEventListener('resize', debounce(() => {
            this.handleResize();
        }, AdminPanel.config.debounceDelay));

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && AdminPanel.state.isMobile && AdminPanel.state.sidebarOpen) {
                this.closeMobile();
            }
        });
    },

    /**
     * Alterna el colapso del sidebar en desktop
     */
    toggleCollapse() {
        if (AdminPanel.state.isMobile) return;

        AdminPanel.state.sidebarCollapsed = !AdminPanel.state.sidebarCollapsed;
        this.applyCollapsedState();
        this.saveState();
    },

    /**
     * Alterna la visibilidad del sidebar en mobile
     */
    toggleMobile() {
        if (!AdminPanel.state.isMobile) return;

        AdminPanel.state.sidebarOpen = !AdminPanel.state.sidebarOpen;
        this.applyMobileState();
    },

    /**
     * Cierra el sidebar en mobile
     */
    closeMobile() {
        if (!AdminPanel.state.isMobile) return;

        AdminPanel.state.sidebarOpen = false;
        this.applyMobileState();
    },

    /**
     * Aplica el estado colapsado
     */
    applyCollapsedState() {
        const { sidebar, body } = AdminPanel.elements;

        if (AdminPanel.state.sidebarCollapsed) {
            sidebar.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            body.classList.remove('sidebar-collapsed');
        }

        // Cerrar todos los submenús cuando está colapsado
        if (AdminPanel.state.sidebarCollapsed) {
            this.closeAllSubmenus();
        }
    },

    /**
     * Aplica el estado móvil
     */
    applyMobileState() {
        const { sidebar, sidebarOverlay } = AdminPanel.elements;

        if (AdminPanel.state.sidebarOpen) {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    },

    /**
     * Maneja el cambio de tamaño de ventana
     */
    handleResize() {
        const wasMobile = AdminPanel.state.isMobile;
        AdminPanel.state.isMobile = isMobileDevice();

        // Si cambió de móvil a desktop o viceversa
        if (wasMobile !== AdminPanel.state.isMobile) {
            this.resetStates();

            if (AdminPanel.state.isMobile) {
                // Cambió a móvil
                this.setupMobile();
            } else {
                // Cambió a desktop
                this.setupDesktop();
            }
        }
    },

    /**
     * Configura para móvil
     */
    setupMobile() {
        const { sidebar, body } = AdminPanel.elements;

        // Limpiar clases de desktop
        sidebar.classList.remove('collapsed');
        body.classList.remove('sidebar-collapsed');

        // Cerrar sidebar móvil
        AdminPanel.state.sidebarOpen = false;
        this.applyMobileState();
    },

    /**
     * Configura para desktop
     */
    setupDesktop() {
        const { sidebar, sidebarOverlay } = AdminPanel.elements;

        // Limpiar estados móviles
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
        AdminPanel.state.sidebarOpen = false;

        // Aplicar estado colapsado guardado
        this.applyCollapsedState();
    },

    /**
     * Resetea todos los estados
     */
    resetStates() {
        document.body.style.overflow = '';
    },

    /**
     * Cierra todos los submenús
     */
    closeAllSubmenus() {
        const openItems = document.querySelectorAll('.nav-item.open, .submenu-item.open');
        openItems.forEach(item => {
            item.classList.remove('open');
        });
    },

    /**
     * Restaura el estado guardado
     */
    restoreState() {
        AdminPanel.state.isMobile = isMobileDevice();

        if (!AdminPanel.state.isMobile) {
            AdminPanel.state.sidebarCollapsed = AdminPanel.config.sidebarState === 'collapsed';
            this.applyCollapsedState();
        } else {
            this.setupMobile();
        }
    },

    /**
     * Guarda el estado actual
     */
    saveState() {
        const state = AdminPanel.state.sidebarCollapsed ? 'collapsed' : 'expanded';
        localStorage.setItem('admin-sidebar-state', state);
        AdminPanel.config.sidebarState = state;
    }
};

// ============================
// MANEJO DE NAVEGACIÓN
// ============================

const NavigationManager = {
    /**
     * Inicializa el manejo de navegación
     */
    init() {
        this.setupSubmenuToggles();
        this.setupActiveStates();
        this.setupLinkClicks();
    },

    /**
     * Configura los toggles de submenús
     */
    setupSubmenuToggles() {
        AdminPanel.elements.submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSubmenu(toggle);
            });
        });
    },

    /**
     * Alterna un submenú
     */
    toggleSubmenu(toggle) {
        const parentItem = toggle.closest('.nav-item, .submenu-item');
        const isOpen = parentItem.classList.contains('open');
        console.log('[TOGGLE] Clic en:', toggle);
        console.log('[TOGGLE] parentItem:', parentItem);

        // Si el sidebar está colapsado en desktop, no hacer nada
        if (!AdminPanel.state.isMobile && AdminPanel.state.sidebarCollapsed) {
            console.log('[TOGGLE] Sidebar colapsado, no se hace nada.');
            return;
        }

        // Cerrar todos los submenús hijos (nietos)
        this.closeChildSubmenus(parentItem);
        console.log('[TOGGLE] Se cierran submenús hijos de', parentItem);

        // Cerrar otros submenús del mismo nivel
        const siblingItems = this.getSiblingItems(parentItem);
        console.log('[TOGGLE] Siblings encontrados:', siblingItems);
        siblingItems.forEach(item => {
            if (item !== parentItem) {
                item.classList.remove('open');
                this.closeChildSubmenus(item);
                console.log('[TOGGLE] Se cierra sibling:', item);
            }
        });

        // Alternar el submenú actual
        if (isOpen) {
            parentItem.classList.remove('open');
            console.log('[TOGGLE] Se cierra parentItem:', parentItem);
        } else {
            parentItem.classList.add('open');
            console.log('[TOGGLE] Se abre parentItem:', parentItem);
        }
    },

    /**
     * Obtiene elementos hermanos del mismo nivel
     */
    getSiblingItems(item) {
        const parent = item.parentElement;
        const selector = item.classList.contains('nav-item') ? '.nav-item' : '.submenu-item';
        return Array.from(parent.querySelectorAll(`:scope > ${selector}`));
    },

    /**
     * Cierra submenús hijos
     */
    closeChildSubmenus(item) {
        const childItems = item.querySelectorAll('.nav-item.open, .submenu-item.open');
        childItems.forEach(child => {
            child.classList.remove('open');
        });
    },

    /**
     * Configura los estados activos de navegación
     */
    setupActiveStates() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link:not(.submenu-toggle), .submenu-link:not(.submenu-toggle)');

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (href === currentPath || currentPath.startsWith(href + '/'))) {
                this.setActiveLink(link);
            }
        });
    },

    /**
     * Establece un enlace como activo
     */
    setActiveLink(link) {
        // Remover estados activos previos
        document.querySelectorAll('.nav-link.active, .submenu-link.active').forEach(activeLink => {
            activeLink.classList.remove('active');
        });

        // Agregar estado activo
        link.classList.add('active');

        // Expandir menús padre si es necesario
        this.expandParentMenus(link);
    },

    /**
     * Expande los menús padre de un enlace activo
     */
    expandParentMenus(link) {
        let parent = link.closest('.nav-item, .submenu-item');

        while (parent) {
            parent.classList.add('open');
            parent = parent.parentElement.closest('.nav-item, .submenu-item');
        }
    },

    /**
     * Configura los clics en enlaces
     */
    setupLinkClicks() {
        const navLinks = document.querySelectorAll('.nav-link:not(.submenu-toggle), .submenu-link:not(.submenu-toggle)');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Si es móvil, cerrar sidebar al hacer clic en un enlace
                if (AdminPanel.state.isMobile && AdminPanel.state.sidebarOpen) {
                    setTimeout(() => {
                        SidebarManager.closeMobile();
                    }, 100);
                }
            });
        });
    }
};

const ThemeManager = {
    _systemListener: null,

    /**
     * Actualiza el icono y tooltip del dropdown según el tema
     */
    updateDropdownIconAndTooltip(theme) {
        const icon = document.getElementById('themeDropdownIcon');
        const button = document.getElementById('themeDropdownButton');
        // Corregido: usa la preferencia guardada, no el tema aplicado
        let userPref = localStorage.getItem('admin-theme') || 'light';
        let iconClass = 'fa-sun';
        let tooltip = 'Tema claro';
        if (userPref === 'system') {
            iconClass = 'fa-desktop';
            tooltip = 'Tema automático (según sistema)';
        } else if (userPref === 'dark') {
            iconClass = 'fa-moon';
            tooltip = 'Tema oscuro';
        }
        if (icon) {
            icon.classList.remove('fa-sun', 'fa-moon', 'fa-desktop');
            icon.classList.add(iconClass);
        }
        if (button) {
            button.setAttribute('title', tooltip);
            button.setAttribute('aria-label', tooltip);
        }
    },

    /**
     * Inicializa el manejo de temas y el dropdown
     */
    init() {
        this.setupDropdown();
        // Aplicar tema guardado o sistema
        let theme = localStorage.getItem('admin-theme') || 'light';
        if (theme === 'system') {
            theme = this.detectSystemTheme();
            this.listenToSystemTheme();
        }
        this.applyTheme(theme);
        this.updateDropdownIconAndTooltip(theme);
    },

    /**
     * Configura el dropdown de tema
     */
    setupDropdown() {
        const dropdown = document.getElementById('themeDropdown');
        if (!dropdown) return;
        const options = dropdown.querySelectorAll('.theme-option');
        options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                const selected = option.getAttribute('data-theme-value');
                this.saveTheme(selected);
                let themeToApply = selected;
                if (selected === 'system') {
                    themeToApply = this.detectSystemTheme();
                    this.listenToSystemTheme();
                } else {
                    this.removeSystemThemeListener();
                }
                this.applyTheme(themeToApply);
                this.updateDropdownIconAndTooltip(selected);
                this.markActiveOption(selected);
            });
        });
        // Marcar opción activa al iniciar
        const saved = localStorage.getItem('admin-theme') || 'light';
        this.markActiveOption(saved);
    },

    /**
     * Marca la opción activa en el dropdown
     */
    markActiveOption(selected) {
        const options = document.querySelectorAll('#themeDropdown .theme-option');
        options.forEach(opt => {
            if (opt.getAttribute('data-theme-value') === selected) {
                opt.classList.add('active');
            } else {
                opt.classList.remove('active');
            }
        });
    },

    /**
     * Escucha cambios de preferencia del sistema
     */
    listenToSystemTheme() {
        if (this._systemListener) return;
        this._systemListener = (e) => {
            const sysTheme = e.matches ? 'dark' : 'light';
            this.applyTheme(sysTheme);
            this.updateDropdownIconAndTooltip('system');
            this.markActiveOption('system');
        };
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', this._systemListener);
    },

    /**
     * Deja de escuchar cambios del sistema
     */
    removeSystemThemeListener() {
        if (this._systemListener) {
            window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', this._systemListener);
            this._systemListener = null;
        }
    },

    /**
     * Aplica el tema visualmente
     */
    applyTheme(theme) {
        AdminPanel.elements.html.setAttribute('data-theme', theme);
        AdminPanel.state.currentTheme = theme;
        AdminPanel.config.theme = theme;
        this.updateDropdownIconAndTooltip(theme);
    },

    /**
     * Guarda la preferencia
     */
    saveTheme(theme) {
        localStorage.setItem('admin-theme', theme);
    },

    /**
     * Detecta el tema del sistema
     */
    detectSystemTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }
};

// ============================
// MANEJO DE BÚSQUEDA
// ============================

const SearchManager = {
    /**
     * Inicializa el manejo de búsqueda
     */
    init() {
        this.setupSearchInput();
        this.setupSearchHandlers();
    },

    /**
     * Configura el input de búsqueda
     */
    setupSearchInput() {
        if (!AdminPanel.elements.searchInput) return;

        AdminPanel.elements.searchInput.addEventListener('focus', () => {
            AdminPanel.state.searchFocused = true;
            this.onSearchFocus();
        });

        AdminPanel.elements.searchInput.addEventListener('blur', () => {
            AdminPanel.state.searchFocused = false;
            this.onSearchBlur();
        });

        AdminPanel.elements.searchInput.addEventListener('input', debounce((e) => {
            this.onSearchInput(e.target.value);
        }, 300));
    },

    /**
     * Configura los manejadores de búsqueda
     */
    setupSearchHandlers() {
        // Escuchar teclas globales
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K para enfocar búsqueda
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                this.focusSearch();
            }

            // Escape para cerrar búsqueda
            if (e.key === 'Escape' && AdminPanel.state.searchFocused) {
                this.blurSearch();
            }
        });
    },

    /**
     * Enfoca el input de búsqueda
     */
    focusSearch() {
        if (AdminPanel.elements.searchInput) {
            AdminPanel.elements.searchInput.focus();
        }
    },

    /**
     * Quita el foco del input de búsqueda
     */
    blurSearch() {
        if (AdminPanel.elements.searchInput) {
            AdminPanel.elements.searchInput.blur();
        }
    },

    /**
     * Maneja el evento de foco en búsqueda
     */
    onSearchFocus() {
        // Aquí se puede implementar lógica adicional como mostrar sugerencias
        console.log('Search focused');
    },

    /**
     * Maneja el evento de pérdida de foco en búsqueda
     */
    onSearchBlur() {
        // Aquí se puede implementar lógica para ocultar sugerencias
        console.log('Search blurred');
    },

    /**
     * Maneja la entrada de texto en búsqueda
     */
    onSearchInput(value) {
        if (value.length >= 2) {
            this.performSearch(value);
        } else {
            this.clearSearch();
        }
    },

    /**
     * Realiza la búsqueda
     */
    performSearch(query) {
        console.log('Searching for:', query);
        // Aquí se implementaría la lógica de búsqueda real
        // Por ejemplo, hacer una petición AJAX al servidor
    },

    /**
     * Limpia los resultados de búsqueda
     */
    clearSearch() {
        console.log('Clearing search results');
        // Aquí se limpiarían los resultados mostrados
    }
};

// ============================
// MANEJO DE NOTIFICACIONES
// ============================

const NotificationManager = {
    /**
     * Inicializa el manejo de notificaciones
     */
    init() {
        this.setupDropdown();
        this.loadNotifications();
        this.setupPolling();
    },

    /**
     * Configura el dropdown de notificaciones
     */
    setupDropdown() {
        const notificationDropdown = document.querySelector('.notifications-dropdown');
        if (notificationDropdown) {
            const dropdown = new bootstrap.Dropdown(notificationDropdown.querySelector('.dropdown-toggle'));

            notificationDropdown.addEventListener('show.bs.dropdown', () => {
                this.markAsRead();
            });
        }
    },

    /**
     * Carga las notificaciones
     */
    loadNotifications() {
        // Aquí se implementaría la carga de notificaciones desde el servidor
        // console.log('Loading notifications...');
    },

    /**
     * Configura el polling de notificaciones
     */
    setupPolling() {
        // Revisar notificaciones cada 30 segundos
        setInterval(() => {
            this.checkNewNotifications();
        }, 30000);
    },

    /**
     * Verifica nuevas notificaciones
     */
    checkNewNotifications() {
        // Aquí se implementaría la verificación de nuevas notificaciones
        // console.log('Checking for new notifications...');
    },

    /**
     * Marca las notificaciones como leídas
     */
    markAsRead() {
        // Aquí se implementaría el marcado como leído
        // console.log('Marking notifications as read...');
    },

    /**
     * Actualiza el contador de notificaciones
     */
    updateBadge(count) {
        const badge = document.querySelector('.notifications-dropdown .badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }
};

// ============================
// UTILIDADES AJAX
// ============================

const AjaxManager = {
    /**
     * Realiza una petición AJAX con configuración por defecto
     */
    async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        // Agregar token CSRF si está disponible
        if (window.AppConfig && window.AppConfig.csrfToken) {
            defaultOptions.headers['X-CSRF-Token'] = window.AppConfig.csrfToken;
        }

        const finalOptions = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, finalOptions);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        } catch (error) {
            console.error('AJAX request failed:', error);
            showNotification('error', 'Error en la comunicación con el servidor');
            throw error;
        }
    },

    /**
     * Petición GET
     */
    get(url, params = {}) {
        const urlWithParams = new URL(url, window.location.origin);
        Object.keys(params).forEach(key => {
            urlWithParams.searchParams.append(key, params[key]);
        });

        return this.request(urlWithParams.toString());
    },

    /**
     * Petición POST
     */
    post(url, data = {}) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    /**
     * Petición PUT
     */
    put(url, data = {}) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    /**
     * Petición DELETE
     */
    delete(url) {
        return this.request(url, {
            method: 'DELETE'
        });
    }
};

// ============================
// UTILIDADES DE UI
// ============================

const UIUtils = {
    /**
     * Muestra un indicador de carga en un elemento
     */
    showLoading(element) {
        if (element) {
            element.classList.add('loading');
            element.disabled = true;
        }
    },

    /**
     * Oculta el indicador de carga de un elemento
     */
    hideLoading(element) {
        if (element) {
            element.classList.remove('loading');
            element.disabled = false;
        }
    },

    /**
     * Anima la entrada de un elemento
     */
    fadeIn(element, duration = 300) {
        if (!element) return;

        element.style.opacity = '0';
        element.style.display = 'block';

        let start = null;
        function animate(timestamp) {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const opacity = Math.min(progress / duration, 1);

            element.style.opacity = opacity;

            if (progress < duration) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    },

    /**
     * Anima la salida de un elemento
     */
    fadeOut(element, duration = 300) {
        if (!element) return;

        let start = null;
        function animate(timestamp) {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const opacity = Math.max(1 - (progress / duration), 0);

            element.style.opacity = opacity;

            if (progress < duration) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
            }
        }

        requestAnimationFrame(animate);
    },

    /**
     * Confirma una acción usando el sistema Whoar
     */
    async confirm(message, title = '¿Confirmar acción?') {
        if (typeof window.Whoar !== 'undefined') {
            return await window.Whoar.confirmacion({
                titulo: title,
                contenido: message,
                tipo: 'warning'
            });
        } else {
            return confirm(message);
        }
    },

    /**
     * Copia texto al portapapeles
     */
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            showNotification('success', 'Texto copiado al portapapeles');
            return true;
        } catch (error) {
            console.error('Error copying to clipboard:', error);
            showNotification('error', 'Error al copiar al portapapeles');
            return false;
        }
    }
};

// ============================
// INICIALIZACIÓN PRINCIPAL
// ============================

/**
 * Inicializa toda la aplicación cuando el DOM esté listo
 */
function initializeApp() {
    // Obtener referencias a elementos
    getElements();

    // Verificar que los elementos principales existan
    if (!AdminPanel.elements.sidebar || !AdminPanel.elements.mainContent) {
        console.error('Elementos principales del admin panel no encontrados');
        return;
    }

    // Inicializar módulos
    SidebarManager.init();
    NavigationManager.init();
    ThemeManager.init();
    SearchManager.init();
    NotificationManager.init();

    // Configurar utilidades globales
    AdminPanel.utils.debounce = debounce;
    AdminPanel.utils.throttle = throttle;

    // Exponer API global
    window.AdminPanel = AdminPanel;
    window.AjaxManager = AjaxManager;
    window.UIUtils = UIUtils;

    // Marcar como inicializado
    document.body.classList.add('admin-initialized');

    // console.log('Admin Panel initialized successfully');
}

/**
 * Maneja errores globales de JavaScript
 */
function handleGlobalErrors() {
    window.addEventListener('error', (event) => {
        console.error('Global error:', event.error);
        // Aquí se podría enviar el error a un servicio de logging
    });

    window.addEventListener('unhandledrejection', (event) => {
        console.error('Unhandled promise rejection:', event.reason);
        // Aquí se podría enviar el error a un servicio de logging
    });
}

// ============================
// INICIALIZACIÓN
// ============================

// Esperar a que el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
} else {
    initializeApp();
}

// Configurar manejo de errores globales
handleGlobalErrors();

// ============================
// FUNCIONES GLOBALES EXPUESTAS
// ============================

/**
 * Función global para cerrar modal de confirmación (usada en el HTML)
 */
window.cerrarModalConfirmacion = function (id, resultado) {
    if (typeof window.Whoar !== 'undefined' && window.Whoar.cerrarModalConfirmacion) {
        window.Whoar.cerrarModalConfirmacion(id, resultado);
    }
};

/**
 * Función global para cerrar modal general (usada en el HTML)
 */
window.cerrarModal = function (id) {
    if (typeof window.Whoar !== 'undefined' && window.Whoar.cerrarModal) {
        window.Whoar.cerrarModal(id);
    }
};

/**
 * Función global para cerrar notificación (usada en el HTML)
 */
window.cerrarNotificacion = function (id) {
    if (typeof window.Whoar !== 'undefined' && window.Whoar.cerrarNotificacion) {
        window.Whoar.cerrarNotificacion(id);
    }
};

/**
 * Función global para cancelar espera (usada en el HTML)
 */
window.cancelarEspera = function (id) {
    if (typeof window.Whoar !== 'undefined' && window.Whoar.cancelarEspera) {
        window.Whoar.cancelarEspera(id);
    }
};

/**
 * Función global para cerrar sesión (usada en el HTML)
 */
window.fncLogout = function () {
    confirmacion({
        titulo: 'Cerrar sesión',
        contenido: '¿Estás seguro de que quieres cerrar sesión?',
        tipo: 'error',
        textoBtnConfirmar: 'Cerrar sesión',
        iconoBtnConfirmar: 'fas fa-sign-out-alt'
    }).then(resultado => {
        if (resultado) {
            notificacionEspera({
                titulo: 'Cerrando sesión...',
                mensaje: 'Por favor espera mientras se cierra la sesión',
                tiempo: 3000,
                mostrarProgreso: true,
                onComplete: () => {
                    let url = window.AppConfig.baseUrl.replace(/\\/g, '');
                    window.location.href = `${url}/logout`;
                }
            });
        }
    });
};