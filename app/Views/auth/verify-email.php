<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Columna Izquierda: Slider de Información -->
        <div class="auth-info">
            <div class="info-slider">
                <div class="info-track">
                    <!-- Slide 1: Cuenta Activada -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h3 class="info-title">Cuenta Activada</h3>
                            <p class="info-text">
                                Una vez verificado tu email, tu cuenta estará completamente
                                activada y podrás usar todas las funcionalidades.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 2: Enlace Válido -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="info-title">Enlace Válido</h3>
                            <p class="info-text">
                                Los enlaces de verificación son válidos por 24 horas.
                                Si expira, puedes solicitar uno nuevo.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3: Perfil Completo -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h3 class="info-title">Perfil Completo</h3>
                            <p class="info-text">
                                Después de la verificación podrás completar tu perfil
                                y acceder a todas las características del sistema.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 4: Seguridad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="info-title">Seguridad Mejorada</h3>
                            <p class="info-text">
                                La verificación de email es un paso importante para
                                mantener tu cuenta segura y protegida.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 5: Acceso Completo -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <h3 class="info-title">Acceso Completo</h3>
                            <p class="info-text">
                                Con tu email verificado tendrás acceso a todas las
                                funciones premium y características avanzadas.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 6: Soporte -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="info-title">Ayuda Disponible</h3>
                            <p class="info-text">
                                Si tienes problemas con la verificación, nuestro
                                equipo de soporte está listo para ayudarte.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Los controles se generan automáticamente por JavaScript -->
            </div>
        </div>

        <!-- Columna Derecha: Contenido de Verificación -->
        <div class="auth-card">
            <div class="auth-content">
                <!-- Header -->
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h1 class="auth-title">Verificar Email</h1>
                    <p class="auth-subtitle">Confirma tu dirección de correo electrónico</p>
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

                <!-- Verificación Automática -->
                <div class="verification-content" id="verificationContent">
                    <!-- Estado: Cargando -->
                    <div class="verification-loading text-center" id="loadingState">
                        <div class="loading-spinner mb-3">
                            <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                        </div>
                        <h3 class="mb-3">Verificando tu email...</h3>
                        <p class="text-muted">Por favor espera mientras confirmamos tu dirección de correo.</p>

                        <!-- Barra de progreso animada -->
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <!-- Estado: Éxito -->
                    <div class="verification-success d-none text-center" id="successState">
                        <div class="success-icon mb-3">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h3 class="text-success mb-3">¡Email Verificado!</h3>
                        <p class="text-muted mb-4">Tu dirección de correo ha sido confirmada exitosamente.</p>

                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-party-horn me-2"></i>
                            <strong>¡Bienvenido!</strong> Tu cuenta está ahora completamente activada.
                        </div>

                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
                            </a>
                            <a href="/home" class="btn btn-outline-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Ir al Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Estado: Error -->
                    <div class="verification-error d-none text-center" id="errorState">
                        <div class="error-icon mb-3">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                        </div>
                        <h3 class="text-danger mb-3">Error de Verificación</h3>
                        <p class="text-muted mb-4">
                            No pudimos verificar tu email. El enlace puede haber expirado o ser inválido.
                        </p>

                        <!-- Posibles soluciones -->
                        <div class="alert alert-warning text-start" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>Posibles soluciones:
                            </h6>
                            <ul class="mb-0 small">
                                <li>Verifica que el enlace esté completo</li>
                                <li>Asegúrate de usar el email más reciente</li>
                                <li>El enlace puede haber expirado (24 horas)</li>
                                <li>Intenta solicitar un nuevo enlace</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="/register" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>
                                Registrarse Nuevamente
                            </a>
                            <a href="/contact" class="btn btn-outline-secondary">
                                <i class="fas fa-question-circle me-2"></i>
                                Contactar Soporte
                            </a>
                            <a href="/login" class="btn btn-outline-info">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                ¿Ya tienes cuenta? Inicia sesión
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Formulario Manual (backup) -->
                <form method="POST" action="/verify-email" class="auth-form d-none" id="manualForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="s" value="<?= e($selector ?? '') ?>">
                    <input type="hidden" name="t" value="<?= e($token ?? '') ?>">

                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-hand-point-up me-2"></i>
                        Si la verificación automática falló, intenta verificar manualmente:
                    </div>

                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="fas fa-check me-2"></i>
                        Verificar Email Manualmente
                    </button>
                </form>

                <!-- Instrucciones adicionales -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="mb-2">
                        <i class="fas fa-question-circle me-2"></i>
                        ¿Problemas con la verificación?
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li><i class="fas fa-check me-2 text-success"></i>Haz clic en el enlace desde el email original</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Verifica que el enlace no esté cortado</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Si expiró, regístrate nuevamente</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Contacta a soporte si persiste el problema</li>
                    </ul>
                </div>

                <!-- Footer del formulario -->
                <div class="auth-footer">
                    <p class="auth-footer-text">
                        ¿Ya verificaste tu email?
                        <a href="/login" class="auth-link">Iniciar sesión</a>
                    </p>

                    <!-- Enlaces adicionales -->
                    <div class="auth-links">
                        <a href="/" class="auth-link-small">
                            <i class="fas fa-home me-1"></i>Volver al inicio
                        </a>
                        <a href="/contact" class="auth-link-small">
                            <i class="fas fa-question-circle me-1"></i>¿Necesitas ayuda?
                        </a>
                        <a href="/register" class="auth-link-small">
                            <i class="fas fa-redo me-1"></i>Nuevo registro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AuthForms.initVerifyEmail({
            selector: '<?= e($selector ?? '') ?>',
            token: '<?= e($token ?? '') ?>',
            csrfToken: '<?= csrf_token() ?>'
        });
    });
</script>