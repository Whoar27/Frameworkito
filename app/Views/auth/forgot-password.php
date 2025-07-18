<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Columna Izquierda: Slider de Información -->
        <div class="auth-info">
            <div class="info-slider">
                <div class="info-track">
                    <!-- Slide 1: Proceso Simple -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <h3 class="info-title">Proceso Simple</h3>
                            <p class="info-text">
                                Ingresa tu email y te enviaremos un enlace seguro para
                                restablecer tu contraseña en minutos.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 2: Seguridad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 class="info-title">Totalmente Seguro</h3>
                            <p class="info-text">
                                El enlace de recuperación es único, temporal y expira
                                automáticamente por tu seguridad.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3: Rapidez -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h3 class="info-title">Recuperación Rápida</h3>
                            <p class="info-text">
                                Recibirás el email de recuperación en segundos.
                                Revisa también tu carpeta de spam.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 4: Soporte -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-life-ring"></i>
                            </div>
                            <h3 class="info-title">¿Necesitas Ayuda?</h3>
                            <p class="info-text">
                                Si no recibes el email o tienes problemas, nuestro
                                equipo de soporte está aquí para ayudarte.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 5: Prevención -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3 class="info-title">Consejos de Seguridad</h3>
                            <p class="info-text">
                                Usa una contraseña única y fuerte. Considera habilitar
                                la autenticación de dos factores.
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
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <h1 class="auth-title">Recuperar Contraseña</h1>
                    <p class="auth-subtitle">Te ayudamos a recuperar el acceso a tu cuenta</p>
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

                <!-- Formulario de Recuperación -->
                <form method="POST" action="/forgot-password" class="auth-form" id="forgotForm">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email de tu cuenta
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="tu-email@ejemplo.com"
                            value="<?= e($_POST['email'] ?? $_GET['email'] ?? '') ?>"
                            required
                            autocomplete="email"
                            autofocus>
                        <div class="invalid-feedback" id="email-error"></div>
                        <div class="form-text">
                            <i class="fas fa-shield-alt me-1"></i>
                            Solo enviaremos el enlace a emails registrados en nuestro sistema.
                        </div>
                    </div>

                    <!-- Botón de Envío -->
                    <button type="submit" class="btn btn-primary btn-auth" id="forgotBtn">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane me-2"></i>
                            Enviar Enlace de Recuperación
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Enviando...
                        </span>
                    </button>
                </form>

                <!-- Instrucciones adicionales -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="mb-2">
                        <i class="fas fa-question-circle me-2"></i>
                        ¿No recibes el email?
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li><i class="fas fa-check me-2 text-success"></i>Revisa tu carpeta de spam o correo no deseado</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Verifica que escribiste correctamente tu email</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Espera unos minutos, a veces hay retraso</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Contacta a soporte si persiste el problema</li>
                    </ul>
                </div>

                <!-- Footer del formulario -->
                <div class="auth-footer">
                    <p class="auth-footer-text">
                        ¿Recordaste tu contraseña?
                        <a href="/login" class="auth-link">Volver a iniciar sesión</a>
                    </p>

                    <!-- Enlaces adicionales -->
                    <div class="auth-links">
                        <a href="/" class="auth-link-small">
                            <i class="fas fa-home me-1"></i>Volver al inicio
                        </a>
                        <a href="/contact" class="auth-link-small">
                            <i class="fas fa-question-circle me-1"></i>¿Necesitas ayuda?
                        </a>
                        <?php if (!isset($_GET['email'])): ?>
                            <a href="/register" class="auth-link-small">
                                <i class="fas fa-user-plus me-1"></i>Crear cuenta nueva
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AuthForms.initForgotPassword({
            csrfToken: '<?= csrf_token() ?>'
        });
    });
</script>