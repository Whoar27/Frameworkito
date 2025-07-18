<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Columna Izquierda: Slider de Información -->
        <div class="auth-info">
            <div class="info-slider">
                <div class="info-track">
                    <!-- Slide 1: Contraseña Segura -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="info-title">Contraseña Segura</h3>
                            <p class="info-text">
                                Elige una contraseña única que no uses en otras cuentas
                                para mantener tu información protegida.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 2: Una Sola Vez -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <h3 class="info-title">Una Sola Vez</h3>
                            <p class="info-text">
                                Este enlace solo puede usarse una vez. Si tienes problemas,
                                solicita un nuevo enlace de recuperación.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3: Acceso Inmediato -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <h3 class="info-title">Acceso Inmediato</h3>
                            <p class="info-text">
                                Después de cambiar tu contraseña podrás iniciar sesión
                                inmediatamente con tus nuevas credenciales.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 4: Consejos de Seguridad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3 class="info-title">Consejos de Seguridad</h3>
                            <p class="info-text">
                                Usa una combinación de letras, números y símbolos.
                                Evita información personal como fechas o nombres.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 5: Protección Adicional -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 class="info-title">Protección Adicional</h3>
                            <p class="info-text">
                                Considera habilitar la autenticación de dos factores
                                para mayor seguridad en tu cuenta.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 6: Cambio Exitoso -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="info-title">Cambio Exitoso</h3>
                            <p class="info-text">
                                Una vez cambiada, recibirás una confirmación por email
                                y podrás acceder con tu nueva contraseña.
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
                        <i class="fas fa-lock-open"></i>
                    </div>
                    <h1 class="auth-title">Nueva Contraseña</h1>
                    <p class="auth-subtitle">Crea una nueva contraseña segura para tu cuenta</p>
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
                <!-- Formulario de Reset -->
                <form method="POST" action="/reset-password" class="auth-form" id="resetForm">
                    <?= csrf_field() ?>

                    <!-- Hidden fields para el token -->
                    <input type="hidden" name="selector" value="<?= e($selector ?? '') ?>">
                    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

                    <!-- Nueva Contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Nueva contraseña
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
                                autofocus
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
                            <i class="fas fa-lock me-2"></i>Confirmar nueva contraseña
                        </label>
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repite tu nueva contraseña"
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
                        <p class="requirements-title">La nueva contraseña debe contener:</p>
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

                    <!-- Botón de Reset -->
                    <button type="submit" class="btn btn-primary btn-auth" id="resetBtn">
                        <span class="btn-text">
                            <i class="fas fa-check me-2"></i>
                            Cambiar Contraseña
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Cambiando...
                        </span>
                    </button>
                </form>

                <!-- Footer del formulario -->
                <div class="auth-footer">
                    <p class="auth-footer-text">
                        ¿Recordaste tu contraseña?
                        <a href="/login" class="auth-link">Volver al login</a>
                    </p>

                    <!-- Enlaces adicionales -->
                    <div class="auth-links">
                        <a href="/" class="auth-link-small">
                            <i class="fas fa-home me-1"></i>Volver al inicio
                        </a>
                        <a href="/contact" class="auth-link-small">
                            <i class="fas fa-question-circle me-1"></i>¿Necesitas ayuda?
                        </a>
                        <a href="/forgot-password" class="auth-link-small">
                            <i class="fas fa-redo me-1"></i>Solicitar nuevo enlace
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AuthForms.initResetPassword({
            selector: '<?= e($selector ?? '') ?>',
            token: '<?= e($token ?? '') ?>',
            csrfToken: '<?= csrf_token() ?>'
        });
    });
</script>