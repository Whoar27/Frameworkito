/**
 * Frameworkito - JavaScript de Autenticación Unificado
 * Todas las funcionalidades de auth en un solo archivo
 * Version: 2.0.0
 */

// ============================================================================
// UTILIDADES GLOBALES
// ============================================================================

// Namespace global para utilidades de autenticación
window.Frameworkito = {
    // CSRF token management
    getCsrfToken: function () {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    },

    // Form utilities
    showFormLoading: function (formId, buttonId) {
        const form = document.getElementById(formId);
        const button = document.getElementById(buttonId);
        if (form && button) {
            button.disabled = true;
            const text = button.querySelector('.btn-text');
            const loading = button.querySelector('.btn-loading');
            if (text) text.classList.add('d-none');
            if (loading) loading.classList.remove('d-none');
        }
    },

    hideFormLoading: function (formId, buttonId) {
        const form = document.getElementById(formId);
        const button = document.getElementById(buttonId);
        if (form && button) {
            button.disabled = false;
            const text = button.querySelector('.btn-text');
            const loading = button.querySelector('.btn-loading');
            if (text) text.classList.remove('d-none');
            if (loading) loading.classList.add('d-none');
        }
    },

    // Page transitions
    showPageLoader: function () {
        document.getElementById('pageLoader')?.classList.remove('d-none');
    },

    hidePageLoader: function () {
        document.getElementById('pageLoader')?.classList.add('d-none');
    },

    // Redirect with loader
    redirectTo: function (url, delay = 500) {
        this.showPageLoader();
        setTimeout(() => {
            window.location.href = url;
        }, delay);
    }
};

// Namespace para utilidades de autenticación específicas
window.AuthUtils = {
    /**
     * Toggle visibility de campos de contraseña
     * @param {string} inputId - ID del input de contraseña
     */
    togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');

        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    },

    /**
     * Validar formato de email
     * @param {string} email - Email a validar
     * @returns {boolean}
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    /**
     * Verificar fortaleza de contraseña
     * @param {string} password - Contraseña a verificar
     * @returns {boolean} - True si cumple todos los requisitos
     */
    checkPasswordStrength(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        // Update requirement indicators if they exist
        Object.keys(requirements).forEach(req => {
            const element = document.getElementById(`req-${req}`);
            if (!element) return;

            const icon = element.querySelector('i');
            if (!icon) return;

            if (requirements[req]) {
                icon.className = 'fas fa-check text-success';
                element.classList.add('text-success');
                element.classList.remove('text-danger');
            } else {
                icon.className = 'fas fa-times text-danger';
                element.classList.add('text-danger');
                element.classList.remove('text-success');
            }
        });

        return Object.values(requirements).every(req => req);
    },

    /**
     * Limpiar errores de validación en formulario
     * @param {string} formId - ID del formulario (opcional)
     */
    clearFormErrors(formId = null) {
        const container = formId ? document.getElementById(formId) : document;
        if (!container) return;

        container.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        container.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
        });
    },

    /**
     * Mostrar error en campo específico
     * @param {string} fieldId - ID del campo
     * @param {string} message - Mensaje de error
     */
    showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(fieldId + '-error');

        if (field) field.classList.add('is-invalid');
        if (errorElement) errorElement.textContent = message;
    },

    /**
     * Auto-ocultar alertas después de un tiempo
     * @param {number} delay - Tiempo en milisegundos (default 5000)
     */
    autoHideAlerts(delay = 5000) {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.classList.remove('show');
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.remove();
                        }
                    }, 150);
                }
            }, delay);
        });
    },

    /**
     * Llenar credenciales de demo (solo desarrollo)
     * @param {string} type - Tipo de usuario (admin, user, moderator)
     */
    fillDemoCredentials(type) {
        const credentials = {
            admin: {
                email: 'admin@authmanager.com',
                password: 'Admin123!'
            },
            user: {
                email: 'user@authmanager.com',
                password: 'User123!'
            },
            moderator: {
                email: 'moderator@authmanager.com',
                password: 'Mod123!'
            }
        };

        if (!credentials[type]) return;

        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');
        const rememberField = document.getElementById('remember');

        if (emailField) emailField.value = credentials[type].email;
        if (passwordField) passwordField.value = credentials[type].password;
        if (rememberField) rememberField.checked = true;

        // Mostrar feedback visual
        if (emailField) emailField.focus();
    },

    /**
     * Realizar petición AJAX con manejo de errores
     * @param {string} url - URL del endpoint
     * @param {object} options - Opciones de fetch
     * @returns {Promise}
     */
    async fetchWithErrorHandling(url, options = {}) {
        try {
            // Agregar CSRF token si es POST/PUT/DELETE
            if (['POST', 'PUT', 'DELETE'].includes(options.method?.toUpperCase())) {
                const csrfToken = Frameworkito.getCsrfToken();

                if (options.body instanceof FormData) {
                    options.body.append('_token', csrfToken);
                } else if (options.headers && options.headers['Content-Type'] === 'application/x-www-form-urlencoded') {
                    options.body += `&_token=${encodeURIComponent(csrfToken)}`;
                }
            }

            const response = await fetch(url, {
                credentials: 'same-origin',
                ...options
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            }

            return await response.text();
        } catch (error) {
            console.error('Fetch error:', error);
            throw error;
        }
    }
};

// ============================================================================
// INICIALIZADORES DE FORMULARIOS
// ============================================================================

// Namespace para inicializadores de formularios específicos
window.AuthForms = {
    /**
     * Inicializar formulario de login
     * @param {object} options - Opciones de configuración
     */
    initLogin(options = {}) {
        const form = document.getElementById('loginForm');
        if (!form) return;

        if (options.debug) {
            console.log('🔐 Login form initialized');
        }

        // Detectar si viene de verificación exitosa
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('verified') === '1') {
            this.showVerificationSuccessMessage();
        }

        // Detectar si viene de registro exitoso
        if (urlParams.get('registered') === '1') {
            this.showRegistrationSuccessMessage();
        }

        // Event listener para envío del formulario
        form.addEventListener('submit', (e) => {
            this.handleLoginSubmit(e, options);
        });

        // Auto-focus en primer campo
        const firstInput = form.querySelector('input:not([type="hidden"]):not([disabled])');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    },

    /**
     * Manejar envío del formulario de login
     * @param {Event} e - Evento de submit
     * @param {object} options - Opciones
     */
    handleLoginSubmit(e, options) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        let isValid = true;
        AuthUtils.clearFormErrors('loginForm');

        // Validate email
        if (!email) {
            AuthUtils.showFieldError('email', 'El email es requerido');
            isValid = false;
        } else if (!AuthUtils.isValidEmail(email)) {
            AuthUtils.showFieldError('email', 'Formato de email inválido');
            isValid = false;
        }

        // Validate password
        if (!password) {
            AuthUtils.showFieldError('password', 'La contraseña es requerida');
            isValid = false;
        } else if (password.length < 6) {
            AuthUtils.showFieldError('password', 'La contraseña debe tener al menos 6 caracteres');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        Frameworkito.showFormLoading('loginForm', 'loginBtn');

        // El formulario continuará con el envío normal
        return true;
    },

    /**
     * Inicializar formulario de registro
     * @param {object} options - Opciones de configuración
     */
    initRegister(options = {}) {
        const form = document.getElementById('registerForm');
        if (!form) return;

        // Log de debug si está habilitado
        if (options.debug) {
            console.log('📝 Register form initialized');
        }

        // Real-time password validation
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');

        if (passwordField) {
            passwordField.addEventListener('input', (e) => {
                const password = e.target.value;
                AuthUtils.checkPasswordStrength(password);

                // Check password confirmation match
                if (confirmField && confirmField.value && password !== confirmField.value) {
                    AuthUtils.showFieldError('password_confirmation', 'Las contraseñas no coinciden');
                } else if (confirmField && confirmField.value) {
                    document.getElementById('password_confirmation').classList.remove('is-invalid');
                    const errorEl = document.getElementById('password_confirmation-error');
                    if (errorEl) errorEl.textContent = '';
                }
            });
        }

        if (confirmField) {
            confirmField.addEventListener('input', (e) => {
                const confirmPassword = e.target.value;
                const password = passwordField ? passwordField.value : '';

                if (confirmPassword && password !== confirmPassword) {
                    AuthUtils.showFieldError('password_confirmation', 'Las contraseñas no coinciden');
                } else {
                    e.target.classList.remove('is-invalid');
                    const errorEl = document.getElementById('password_confirmation-error');
                    if (errorEl) errorEl.textContent = '';
                }
            });
        }

        // Form submission
        form.addEventListener('submit', (e) => {
            this.handleRegisterSubmit(e, options);
        });
    },

    /**
     * Manejar envío del formulario de registro
     * @param {Event} e - Evento de submit
     * @param {object} options - Opciones
     */
    handleRegisterSubmit(e, options) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms')?.checked;

        let isValid = true;
        AuthUtils.clearFormErrors('registerForm');

        // Validate name
        if (!name || name.length < 2) {
            AuthUtils.showFieldError('name', 'El nombre debe tener al menos 2 caracteres');
            isValid = false;
        }

        // Validate email
        if (!email) {
            AuthUtils.showFieldError('email', 'El email es requerido');
            isValid = false;
        } else if (!AuthUtils.isValidEmail(email)) {
            AuthUtils.showFieldError('email', 'Formato de email inválido');
            isValid = false;
        }

        // Validate password
        if (!password) {
            AuthUtils.showFieldError('password', 'La contraseña es requerida');
            isValid = false;
        } else if (!AuthUtils.checkPasswordStrength(password)) {
            AuthUtils.showFieldError('password', 'La contraseña no cumple con los requisitos');
            isValid = false;
        }

        // Validate password confirmation
        if (!passwordConfirmation) {
            AuthUtils.showFieldError('password_confirmation', 'Confirma tu contraseña');
            isValid = false;
        } else if (password !== passwordConfirmation) {
            AuthUtils.showFieldError('password_confirmation', 'Las contraseñas no coinciden');
            isValid = false;
        }

        // Validate terms if field exists
        if (terms !== undefined && !terms) {
            AuthUtils.showFieldError('terms', 'Debes aceptar los términos y condiciones');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        Frameworkito.showFormLoading('registerForm', 'registerBtn');
        return true;
    },

    /**
     * Inicializar formulario de recuperación de contraseña
     * @param {object} options - Opciones de configuración
     */
    initForgotPassword(options = {}) {
        const form = document.getElementById('forgotForm');
        if (!form) return;

        if (options.debug) {
            console.log('🔑 Forgot password form initialized');
        }

        form.addEventListener('submit', (e) => {
            this.handleForgotPasswordSubmit(e, options);
        });
    },

    /**
     * Manejar envío del formulario de forgot password
     * @param {Event} e - Evento de submit
     * @param {object} options - Opciones
     */
    handleForgotPasswordSubmit(e, options) {
        const email = document.getElementById('email').value.trim();

        let isValid = true;
        AuthUtils.clearFormErrors('forgotForm');

        // Validate email
        if (!email) {
            AuthUtils.showFieldError('email', 'El email es requerido');
            isValid = false;
        } else if (!AuthUtils.isValidEmail(email)) {
            AuthUtils.showFieldError('email', 'Formato de email inválido');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        Frameworkito.showFormLoading('forgotForm', 'forgotBtn');

        // Re-enable button after timeout in case of server error
        setTimeout(() => {
            Frameworkito.hideFormLoading('forgotForm', 'forgotBtn');
        }, 10000);

        return true;
    },

    /**
     * Inicializar formulario de reset de contraseña
     * @param {object} options - Opciones de configuración
     */
    initResetPassword(options = {}) {
        // Protección contra doble inicialización con valores vacíos
        if (!window.__resetFormInit) window.__resetFormInit = false;

        // Si ya fue inicializado correctamente, ignora llamadas posteriores con valores vacíos
        if (window.__resetFormInit && (!options.selector || !options.token)) {
            return;
        }

        const form = document.getElementById('resetForm');
        if (!form) return;

        if (options.debug) {
            
            
        }

        

        // Validar que tenemos los tokens necesarios
        if (!options.selector || !options.token) {
            this.showInvalidResetLink();
            return;
        }
        // Marcar como inicializado solo si los valores son válidos
        window.__resetFormInit = true;

        // Real-time password validation
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');

        if (passwordField) {
            passwordField.addEventListener('input', (e) => {
                const password = e.target.value;
                AuthUtils.checkPasswordStrength(password);

                // Check password confirmation match
                if (confirmField && confirmField.value && password !== confirmField.value) {
                    AuthUtils.showFieldError('password_confirmation', 'Las contraseñas no coinciden');
                } else if (confirmField && confirmField.value) {
                    document.getElementById('password_confirmation').classList.remove('is-invalid');
                    const errorEl = document.getElementById('password_confirmation-error');
                    if (errorEl) errorEl.textContent = '';
                }
            });
        }

        if (confirmField) {
            confirmField.addEventListener('input', (e) => {
                const confirmPassword = e.target.value;
                const password = passwordField ? passwordField.value : '';

                if (confirmPassword && password !== confirmPassword) {
                    AuthUtils.showFieldError('password_confirmation', 'Las contraseñas no coinciden');
                } else {
                    e.target.classList.remove('is-invalid');
                    const errorEl = document.getElementById('password_confirmation-error');
                    if (errorEl) errorEl.textContent = '';
                }
            });
        }

        // Form submission
        form.addEventListener('submit', (e) => {
            this.handleResetPasswordSubmit(e, options);
        });
    },

    /**
     * Manejar envío del formulario de reset password
     * @param {Event} e - Evento de submit
     * @param {object} options - Opciones
     */
    handleResetPasswordSubmit(e, options) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        let isValid = true;
        AuthUtils.clearFormErrors('resetForm');

        // Validate password
        if (!password) {
            AuthUtils.showFieldError('password', 'La nueva contraseña es requerida');
            isValid = false;
        } else if (!AuthUtils.checkPasswordStrength(password)) {
            AuthUtils.showFieldError('password', 'La contraseña no cumple con los requisitos');
            isValid = false;
        }

        // Validate password confirmation
        if (!passwordConfirmation) {
            AuthUtils.showFieldError('password_confirmation', 'Confirma tu nueva contraseña');
            isValid = false;
        } else if (password !== passwordConfirmation) {
            AuthUtils.showFieldError('password_confirmation', 'Las contraseñas no coinciden');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        Frameworkito.showFormLoading('resetForm', 'resetBtn');
        return true;
    },

    /**
     * Inicializar verificación de email
     * @param {object} options - Datos de verificación {selector, token, csrfToken}
     */
    initVerifyEmail(options = {}) {
        const verificationContent = document.getElementById('verificationContent');
        if (!verificationContent) return;

        if (options.debug) {
            console.log('✉️ Email verification initialized');
            
        }

        // Check if we have valid tokens for auto-verification
        if (options.selector && options.token) {
            // Simulate verification process
            setTimeout(() => {
                this.performVerification(options.selector, options.token, options.csrfToken);
            }, 2000);
        } else {
            // No valid tokens, show error immediately
            this.showVerificationError('Enlace de verificación inválido o incompleto');
        }

        // Setup manual form if exists
        const manualForm = document.getElementById('manualForm');
        if (manualForm) {
            manualForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(manualForm);

                AuthUtils.fetchWithErrorHandling('/verify-email', {
                    method: 'POST',
                    body: formData
                })
                    .then(data => {
                        if (data.success) {
                            this.showVerificationSuccess();
                        } else {
                            alert(data.message || 'Error en la verificación manual');
                        }
                    })
                    .catch(error => {
                        console.error('Manual verification error:', error);
                        alert('Error procesando la verificación manual');
                    });
            });
        }
    },

    /**
     * Realizar verificación de email
     * @param {string} selector
     * @param {string} token
     * @param {string} csrfToken
     */
    async performVerification(selector, token, csrfToken) {
        try {
            const data = await AuthUtils.fetchWithErrorHandling('/verify-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    's': selector,
                    't': token,
                    '_token': csrfToken || ''
                })
            });

            if (data.success) {
                this.showVerificationSuccess();
            } else {
                this.showVerificationError(data.message || 'Error desconocido durante la verificación');
            }
        } catch (error) {
            console.error('Verification error:', error);

            // Fallback: simulate success for demo
            setTimeout(() => {
                this.showVerificationSuccess();
            }, 1000);
        }
    },

    /**
     * Mostrar estado de éxito en verificación
     */
    showVerificationSuccess() {
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const successState = document.getElementById('successState');

        if (loadingState) loadingState.classList.add('d-none');
        if (errorState) errorState.classList.add('d-none');
        if (successState) successState.classList.remove('d-none');

        // Auto-redirect to login after 3 seconds
        setTimeout(() => {
            window.location.href = '/login?verified=1';
        }, 3000);
    },

    /**
     * Mostrar estado de error en verificación
     * @param {string} message - Mensaje de error
     */
    showVerificationError(message = 'Error de verificación') {
        const loadingState = document.getElementById('loadingState');
        const successState = document.getElementById('successState');
        const errorState = document.getElementById('errorState');
        const manualForm = document.getElementById('manualForm');

        if (loadingState) loadingState.classList.add('d-none');
        if (successState) successState.classList.add('d-none');
        if (errorState) errorState.classList.remove('d-none');
        if (manualForm) manualForm.classList.remove('d-none');

        // Update error message if provided
        const errorText = document.querySelector('#errorState p');
        if (errorText && message) {
            errorText.textContent = message;
        }
    },

    /**
     * Mostrar enlace de reset inválido
     */
    showInvalidResetLink() {
        const errorMessage = document.createElement('div');
        errorMessage.className = 'alert alert-danger alert-dismissible fade show';
        errorMessage.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Enlace inválido</strong> Este enlace de recuperación no es válido o ha expirado.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const authCard = document.querySelector('.auth-card');
        if (authCard) {
            authCard.insertBefore(errorMessage, authCard.firstChild);
            const form = document.getElementById('resetForm');
            if (form) {
                form.style.opacity = '0.5';
                form.style.pointerEvents = 'none';
            }
        }

        // setTimeout(() => {
        //     window.location.href = '/forgot-password?expired=1';
        // }, 5000);
    },

    /**
     * Mostrar mensaje de verificación exitosa
     */
    showVerificationSuccessMessage() {
        const successMessage = document.createElement('div');
        successMessage.className = 'alert alert-success alert-dismissible fade show';
        successMessage.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ¡Email verificado exitosamente! Ya puedes iniciar sesión.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const authCard = document.querySelector('.auth-card');
        if (authCard) {
            authCard.insertBefore(successMessage, authCard.firstChild);
        }
    },

    /**
     * Mostrar mensaje de registro exitoso
     */
    showRegistrationSuccessMessage() {
        const successMessage = document.createElement('div');
        successMessage.className = 'alert alert-info alert-dismissible fade show';
        successMessage.innerHTML = `
            <i class="fas fa-envelope me-2"></i>
            ¡Registro exitoso! Revisa tu email para verificar tu cuenta.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const authCard = document.querySelector('.auth-card');
        if (authCard) {
            authCard.insertBefore(successMessage, authCard.firstChild);
        }
    }
};

// ============================================================================
// AUTO-INICIALIZACIÓN GLOBAL
// ============================================================================

document.addEventListener('DOMContentLoaded', function () {
    // Auto-hide alerts
    AuthUtils.autoHideAlerts();

    // Auto-hide page loader
    Frameworkito.hidePageLoader();

    // Global toggle password visibility function
    window.togglePasswordVisibility = AuthUtils.togglePasswordVisibility;

    // Global demo credentials function
    window.fillDemoCredentials = AuthUtils.fillDemoCredentials;

    // Handle navigation with loading
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href^="/"]');
        if (link && !link.hasAttribute('target') && !e.ctrlKey && !e.metaKey) {
            e.preventDefault();
            Frameworkito.redirectTo(link.href);
        }
    });
});

// ============================================================================
// GLOBAL ERROR HANDLER
// ============================================================================

window.addEventListener('error', function (e) {
    console.error('Auth page error:', e.error);
    Frameworkito.hidePageLoader();

    // Hide any loading states
    const loadingButtons = document.querySelectorAll('.btn-loading:not(.d-none)');
    loadingButtons.forEach(btn => {
        const parentBtn = btn.closest('button');
        if (parentBtn && parentBtn.id) {
            const formId = parentBtn.closest('form')?.id;
            if (formId) {
                Frameworkito.hideFormLoading(formId, parentBtn.id);
            }
        }
    });
});

// ============================================================================
// SLIDER DE INFORMACIÓN
// ============================================================================

window.InfoSlider = {
    currentSlide: 0,
    totalSlides: 0,
    track: null,
    autoPlayInterval: null,
    autoPlayDelay: 5000, // 5 segundos
    
    /**
     * Inicializar el slider
     */
    init() {
        this.track = document.querySelector('.info-track');
        if (!this.track) return;

        this.totalSlides = document.querySelectorAll('.info-slide').length;
        if (this.totalSlides <= 1) return;

        this.createControls();
        this.bindEvents();
        this.startAutoPlay();
    },

    /**
     * Crear controles del slider
     */
    createControls() {
        const sliderContainer = document.querySelector('.info-slider');
        if (!sliderContainer) return;

        // Crear contenedor de controles
        const controls = document.createElement('div');
        controls.className = 'slider-controls';

        // Botón anterior
        const prevBtn = document.createElement('button');
        prevBtn.className = 'slider-nav prev';
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.setAttribute('aria-label', 'Slide anterior');

        // Indicadores
        const indicators = document.createElement('div');
        indicators.className = 'slider-indicators';

        for (let i = 0; i < this.totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `slider-dot ${i === 0 ? 'active' : ''}`;
            dot.setAttribute('data-slide', i);
            dot.setAttribute('aria-label', `Ir al slide ${i + 1}`);
            indicators.appendChild(dot);
        }

        // Botón siguiente
        const nextBtn = document.createElement('button');
        nextBtn.className = 'slider-nav next';
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.setAttribute('aria-label', 'Slide siguiente');

        // Agregar elementos al contenedor
        controls.appendChild(prevBtn);
        controls.appendChild(indicators);
        controls.appendChild(nextBtn);

        sliderContainer.appendChild(controls);
    },

    /**
     * Vincular eventos
     */
    bindEvents() {
        // Navegación con flechas
        document.querySelector('.slider-nav.prev')?.addEventListener('click', () => {
            this.prevSlide();
        });

        document.querySelector('.slider-nav.next')?.addEventListener('click', () => {
            this.nextSlide();
        });

        // Indicadores
        document.querySelectorAll('.slider-dot').forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
            });
        });

        // Pausar autoplay al hacer hover
        const sliderContainer = document.querySelector('.info-slider');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => {
                this.stopAutoPlay();
            });

            sliderContainer.addEventListener('mouseleave', () => {
                this.startAutoPlay();
            });
        }

        // Navegación con teclado
        document.addEventListener('keydown', (e) => {
            if (!document.querySelector('.auth-info:hover')) return;

            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.prevSlide();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.nextSlide();
            }
        });

        // Navegación táctil (swipe)
        this.addTouchEvents();
    },

    /**
     * Agregar eventos táctiles para swipe
     */
    addTouchEvents() {
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;

        const slider = document.querySelector('.info-slider');
        if (!slider) return;

        slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });

        slider.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
            this.handleSwipe();
        });

        const handleSwipe = () => {
            const diffX = startX - endX;
            const diffY = startY - endY;

            // Solo procesar swipes horizontales
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    this.nextSlide(); // Swipe left
                } else {
                    this.prevSlide(); // Swipe right
                }
            }
        };

        this.handleSwipe = handleSwipe;
    },

    /**
     * Ir al slide anterior
     */
    prevSlide() {
        this.currentSlide = this.currentSlide > 0 ? this.currentSlide - 1 : this.totalSlides - 1;
        this.updateSlide();
        this.restartAutoPlay();
    },

    /**
     * Ir al slide siguiente
     */
    nextSlide() {
        this.currentSlide = this.currentSlide < this.totalSlides - 1 ? this.currentSlide + 1 : 0;
        this.updateSlide();
        this.restartAutoPlay();
    },

    /**
     * Ir a un slide específico
     * @param {number} index - Índice del slide
     */
    goToSlide(index) {
        if (index >= 0 && index < this.totalSlides) {
            this.currentSlide = index;
            this.updateSlide();
            this.restartAutoPlay();
        }
    },

    /**
     * Actualizar la posición del slider
     */
    updateSlide() {
        if (!this.track) return;

        // Mover el track
        const translateX = -this.currentSlide * 100;
        this.track.style.transform = `translateX(${translateX}%)`;

        // Actualizar indicadores
        document.querySelectorAll('.slider-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });

        // Actualizar botones de navegación
        const prevBtn = document.querySelector('.slider-nav.prev');
        const nextBtn = document.querySelector('.slider-nav.next');

        if (prevBtn) prevBtn.disabled = false;
        if (nextBtn) nextBtn.disabled = false;

        // Agregar clase para animaciones
        this.track.parentElement?.classList.add('sliding');
        setTimeout(() => {
            this.track.parentElement?.classList.remove('sliding');
        }, 500);
    },

    /**
     * Iniciar autoplay
     */
    startAutoPlay() {
        if (this.totalSlides <= 1) return;
        
        this.stopAutoPlay();
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    },

    /**
     * Detener autoplay
     */
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    },

    /**
     * Reiniciar autoplay
     */
    restartAutoPlay() {
        this.stopAutoPlay();
        setTimeout(() => {
            this.startAutoPlay();
        }, 1000); // Esperar 1 segundo antes de reiniciar
    },

    /**
     * Agregar nuevo slide programáticamente
     * @param {Object} slideData - Datos del slide {icon, title, text}
     */
    addSlide(slideData) {
        if (!this.track) return;

        const slide = document.createElement('div');
        slide.className = 'info-slide';
        slide.innerHTML = `
            <div class="info-card">
                <div class="info-icon">
                    <i class="${slideData.icon}"></i>
                </div>
                <h3 class="info-title">${slideData.title}</h3>
                <p class="info-text">${slideData.text}</p>
            </div>
        `;

        this.track.appendChild(slide);
        this.totalSlides++;

        // Recrear controles
        const oldControls = document.querySelector('.slider-controls');
        if (oldControls) {
            oldControls.remove();
            this.createControls();
            this.bindEvents();
        }
    },

    /**
     * Destruir el slider
     */
    destroy() {
        this.stopAutoPlay();
        const controls = document.querySelector('.slider-controls');
        if (controls) {
            controls.remove();
        }
    }
};

// ============================================================================
// ACTUALIZACIÓN DEL NAMESPACE AUTHMANAGER
// ============================================================================

// Agregar slider a Frameworkito
window.Frameworkito.slider = InfoSlider;

// ============================================================================
// INICIALIZACIÓN ACTUALIZADA
// ============================================================================

// Actualizar inicialización global
document.addEventListener('DOMContentLoaded', function() {
    // Funciones existentes
    AuthUtils.autoHideAlerts();
    Frameworkito.hidePageLoader();

    // NUEVO: Inicializar slider de información
    InfoSlider.init();

    // Funciones globales
    window.togglePasswordVisibility = AuthUtils.togglePasswordVisibility;
    window.fillDemoCredentials = AuthUtils.fillDemoCredentials;

    // Handle navigation with loading
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href^="/"]');
        if (link && !link.hasAttribute('target') && !e.ctrlKey && !e.metaKey) {
            e.preventDefault();
            Frameworkito.redirectTo(link.href);
        }
    });
});

// ============================================================================
// DEBUG & EXPORT
// ============================================================================

// Export for debugging (development only)
if (typeof window !== 'undefined' && window.console) {
    window.AuthDebug = {
        manager: Frameworkito,
        utils: AuthUtils,
        forms: AuthForms,
        slider: InfoSlider,
        version: '2.0.0'
    };
}