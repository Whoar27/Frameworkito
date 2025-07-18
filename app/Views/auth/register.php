<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Columna Izquierda: Slider de Información -->
        <div class="auth-info">
            <div class="info-slider">
                <div class="info-track">
                    <!-- Slide 1: Registro Rápido -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 class="info-title">Empieza en Segundos</h3>
                            <p class="info-text">
                                Crea tu cuenta gratis y comienza a usar todas las funcionalidades
                                inmediatamente sin complicaciones.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 2: Seguridad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="info-title">Datos Protegidos</h3>
                            <p class="info-text">
                                Tu información personal está cifrada y protegida con los más altos
                                estándares de seguridad internacional.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3: Soporte -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="info-title">Soporte 24/7</h3>
                            <p class="info-text">
                                Nuestro equipo de soporte está disponible para ayudarte
                                en cualquier momento que lo necesites.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 4: Sin Compromisos -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            <h3 class="info-title">Gratis para Siempre</h3>
                            <p class="info-text">
                                Accede a todas las funciones básicas sin costo alguno.
                                Sin trucos, sin letra pequeña.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 5: Comunidad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="info-title">Únete a la Comunidad</h3>
                            <p class="info-text">
                                Forma parte de miles de usuarios que ya confían en
                                nuestra plataforma para sus proyectos.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 6: Actualizaciones -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <h3 class="info-title">Siempre Actualizado</h3>
                            <p class="info-text">
                                Recibe automáticamente las últimas características y
                                mejoras de seguridad sin esfuerzo adicional.
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
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="auth-title">Crear Cuenta</h1>
                    <p class="auth-subtitle">Únete a <?= e($app_name ?? 'AuthManager Base') ?></p>
                </div>

                <!-- Mensajes Flash -->
                <?php
                $flashTypes = ['success', 'error', 'warning', 'info'];
                foreach ($flashTypes as $type) {
                    if ($msg = \App\Helpers\Session::getFlash($type)) {
                ?>
                        <div class="alert alert-<?= $type ?>">
                            <?= e($msg) ?>
                        </div>
                <?php
                    }
                }
                ?>

                <!-- Formulario de Registro -->
                <form method="POST" action="/register" class="auth-form" id="registerForm">
                    <?= csrf_field() ?>

                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Nombre completo
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            placeholder="Tu nombre completo"
                            value="<?= e($_POST['name'] ?? '') ?>"
                            required
                            autocomplete="name"
                            autofocus
                            minlength="2"
                            maxlength="100">
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="tu-email@ejemplo.com"
                            value="<?= e($_POST['email'] ?? '') ?>"
                            required
                            autocomplete="email">
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
                                placeholder="Crea una contraseña segura"
                                required
                                autocomplete="new-password"
                                minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirmar contraseña
                        </label>
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repite tu contraseña"
                                required
                                autocomplete="new-password"
                                minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">
                                <i class="fas fa-eye" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="password_confirmation-error"></div>
                    </div>

                    <!-- Requisitos de Contraseña -->
                    <div class="password-requirements" id="passwordRequirements">
                        <p class="requirements-title">La contraseña debe contener:</p>
                        <ul class="requirements-list">
                            <li id="req-length">
                                <i class="fas fa-times text-danger"></i>
                                Al menos 8 caracteres
                            </li>
                            <li id="req-uppercase">
                                <i class="fas fa-times text-danger"></i>
                                Una letra mayúscula
                            </li>
                            <li id="req-lowercase">
                                <i class="fas fa-times text-danger"></i>
                                Una letra minúscula
                            </li>
                            <li id="req-number">
                                <i class="fas fa-times text-danger"></i>
                                Un número
                            </li>
                            <li id="req-symbol">
                                <i class="fas fa-times text-danger"></i>
                                Un símbolo especial
                            </li>
                        </ul>
                    </div>

                    <!-- Términos y Condiciones -->
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" required>
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="/terms" target="_blank" class="auth-link">Términos y Condiciones</a>
                                y la <a href="/privacy" target="_blank" class="auth-link">Política de Privacidad</a>
                            </label>
                            <div class="invalid-feedback" id="terms-error"></div>
                        </div>
                    </div>

                    <!-- Botón de Registro -->
                    <button type="submit" class="btn btn-primary btn-auth" id="registerBtn">
                        <span class="btn-text">
                            <i class="fas fa-user-plus me-2"></i>
                            Crear Cuenta
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Creando cuenta...
                        </span>
                    </button>
                </form>

                <!-- Footer del formulario -->
                <div class="auth-footer">
                    <p class="auth-footer-text">
                        ¿Ya tienes una cuenta?
                        <a href="/login" class="auth-link">Inicia sesión aquí</a>
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
        AuthForms.initRegister({
            csrfToken: '<?= csrf_token() ?>',
            debug: <?= config('app.debug', false) ? 'true' : 'false' ?>
        });
    });
</script>