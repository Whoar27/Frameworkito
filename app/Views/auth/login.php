<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Columna Izquierda: Slider de Información -->
        <div class="auth-info">
            <div class="info-slider">
                <div class="info-track">
                    <!-- Slide 1: Seguridad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <h3 class="info-title">Acceso Seguro</h3>
                            <p class="info-text">
                                Tu información está protegida con encriptación de grado militar
                                y autenticación de dos factores opcional.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 2: Disponibilidad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="info-title">Disponible 24/7</h3>
                            <p class="info-text">
                                Accede a tu cuenta en cualquier momento desde cualquier dispositivo
                                con conexión a internet.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3: Responsivo -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3 class="info-title">Completamente Responsivo</h3>
                            <p class="info-text">
                                Diseñado para funcionar perfectamente en escritorio,
                                tablets y dispositivos móviles.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 4: Soporte -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="info-title">Soporte Profesional</h3>
                            <p class="info-text">
                                Nuestro equipo de expertos está disponible para ayudarte
                                con cualquier pregunta o problema que puedas tener.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 5: Rendimiento -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 class="info-title">Alto Rendimiento</h3>
                            <p class="info-text">
                                Optimizado para cargar rápidamente y brindar la mejor
                                experiencia de usuario posible.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Los controles se generan automáticamente por JavaScript -->
            </div>
        </div>

        <!-- Columna Derecha: Formulario -->
        <div class="auth-card">
            <div class="auth-content">
                <!-- Header -->
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h1 class="auth-title">Iniciar Sesión</h1>
                    <p class="auth-subtitle">Accede a tu cuenta en <?= e($app_name ?? 'AuthManager Base') ?></p>
                </div>

                <!-- Mensajes Flash -->
                <?php
                $flashTypes = ['success', 'error', 'warning', 'info'];
                foreach ($flashTypes as $type) {
                    if ($msg = \App\Helpers\Session::getFlash($type)) {
                        $type == 'error' ? $type = 'danger' : $type;
                ?>
                        <div class="alert alert-<?= $type ?>">
                            <?= e($msg) ?>
                        </div>
                <?php
                    }
                }
                ?>

                <!-- Formulario de Login -->
                <form method="POST" action="/login" class="auth-form" id="loginForm">
                    <?= csrf_field() ?>

                    <!-- Redirect Hidden Field -->
                    <input type="hidden" name="redirect" value="<?= e($redirect_to ?? '/home') ?>">

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="login"
                            placeholder="tu-email@ejemplo.com"
                            value="<?= e($_POST['login'] ?? '') ?>"
                            required
                            autocomplete="email"
                            autofocus>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Contraseña
                        </label>
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Tu contraseña"
                                required
                                autocomplete="current-password"
                                minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>

                    <!-- Recordarme y Olvidé contraseña -->
                    <div class="form-options">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>
                        <a href="/forgot-password" class="forgot-password-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Botón de Login -->
                    <button type="submit" class="btn btn-primary btn-auth" id="loginBtn">
                        <span class="btn-text">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Iniciar Sesión
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Iniciando...
                        </span>
                    </button>

                    <!-- Demo Credentials (solo en desarrollo) -->
                    <?php if (($app_debug ?? false)): ?>
                        <div class="demo-credentials">
                            <p class="demo-title">
                                <i class="fas fa-key me-2"></i>Credenciales de Prueba:
                            </p>
                            <div class="demo-buttons">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillDemoCredentials('admin')">
                                    <i class="fas fa-crown me-1"></i>Admin
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="fillDemoCredentials('user')">
                                    <i class="fas fa-user me-1"></i>Usuario
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="fillDemoCredentials('moderator')">
                                    <i class="fas fa-shield me-1"></i>Moderador
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>

                <!-- Footer del formulario -->
                <div class="auth-footer">
                    <p class="auth-footer-text">
                        ¿No tienes una cuenta?
                        <a href="/register" class="auth-link">Créate una aquí</a>
                    </p>

                    <!-- Enlaces adicionales -->
                    <div class="auth-links">
                        <a href="/" class="auth-link-small">
                            <i class="fas fa-home me-1"></i>Volver al inicio
                        </a>
                        <a href="/contact" class="auth-link-small">
                            <i class="fas fa-question-circle me-1"></i>¿Necesitas ayuda?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AuthForms.initLogin({
            redirectTo: '<?= e($redirect_to ?? '/home') ?>',
            debug: <?= config('app.debug', false) ? 'true' : 'false' ?>
        });
    });
</script>