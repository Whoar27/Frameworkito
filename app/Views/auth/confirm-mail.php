<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Columna Izquierda: Slider de Información -->
        <div class="auth-info">
            <div class="info-slider">
                <div class="info-track">
                    <!-- Slide 1: Email Enviado -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h3 class="info-title">Email Enviado</h3>
                            <p class="info-text">
                                Hemos enviado las instrucciones de recuperación a tu
                                dirección de correo electrónico.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 2: Revisa tu Bandeja -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h3 class="info-title">Revisa tu Bandeja</h3>
                            <p class="info-text">
                                El email debería llegar en los próximos minutos.
                                No olvides revisar la carpeta de spam.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3: Enlace Temporal -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="info-title">Enlace Temporal</h3>
                            <p class="info-text">
                                El enlace de recuperación expirará en 1 hora
                                por razones de seguridad.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 4: Instrucciones -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-mouse-pointer"></i>
                            </div>
                            <h3 class="info-title">Sigue las Instrucciones</h3>
                            <p class="info-text">
                                Haz clic en el enlace del email para crear
                                tu nueva contraseña de forma segura.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 5: Soporte -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="info-title">¿Necesitas Ayuda?</h3>
                            <p class="info-text">
                                Si no recibes el email o tienes problemas,
                                nuestro equipo está listo para ayudarte.
                            </p>
                        </div>
                    </div>

                    <!-- Slide 6: Seguridad -->
                    <div class="info-slide">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h3 class="info-title">Proceso Seguro</h3>
                            <p class="info-text">
                                Este método garantiza que solo tú puedas
                                restablecer la contraseña de tu cuenta.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Los controles se generan automáticamente por JavaScript -->
            </div>
        </div>

        <!-- Columna Derecha: Contenido de Confirmación -->
        <div class="auth-card">
            <div class="auth-content">
                <!-- Header -->
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-envelope-circle-check"></i>
                    </div>
                    <h1 class="auth-title">Email Enviado</h1>
                    <p class="auth-subtitle">Revisa tu correo para continuar</p>
                </div>

                <!-- Mensajes Flash -->
                <?php
                $flashTypes = ['success', 'error', 'warning', 'info'];
                foreach ($flashTypes as $type) {
                    if ($msg = \App\Helpers\Session::getFlash($type)) {
                ?>
                        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-triangle' : 'info-circle') ?> me-2"></i>
                            <?= e($msg) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                <?php
                    }
                }

                if (!empty($_SESSION['_flash']['email_error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['_flash']['email_error']) . '</div>';
                    // Borra el mensaje si quieres comportamiento flash real:
                    unset($_SESSION['_flash']['email_error']);
                }
                ?>

                <!-- Información del proceso -->
                <div class="mt-4">
                    <h6 class="mb-3">
                        <i class="fas fa-list-check me-2"></i>
                        Próximos pasos:
                    </h6>

                    <div class="step-list">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6>Revisa tu email</h6>
                                <p class="small text-muted mb-0">
                                    Busca un email de <strong><?= e($app_name ?? 'Frameworkito') ?></strong>
                                    en tu bandeja de entrada.
                                </p>
                            </div>
                        </div>

                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6>Haz clic en el enlace</h6>
                                <p class="small text-muted mb-0">
                                    Sigue el enlace "Restablecer Contraseña" en el email.
                                </p>
                            </div>
                        </div>

                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6>Crea tu nueva contraseña</h6>
                                <p class="small text-muted mb-0">
                                    Elige una contraseña segura y confirma el cambio.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opciones adicionales -->
                <div class="mt-4">
                    <h6 class="mb-3">
                        <i class="fas fa-question-circle me-2"></i>
                        ¿No recibes el email?
                    </h6>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <!-- Reenviar email -->
                                <form method="POST" action="/resend-password-reset" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="email" value="<?= e($email ?? '') ?>">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-redo me-2"></i>
                                        Reenviar Email
                                    </button>
                                </form>

                                <!-- Volver a intentar -->
                                <a href="/forgot-password" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Intentar con Otro Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de verificación -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="mb-2">
                        <i class="fas fa-search me-2"></i>
                        ¿Dónde buscar el email?
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li><i class="fas fa-check me-2 text-success"></i>Bandeja de entrada principal</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Carpeta de spam o correo no deseado</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Carpeta de promociones (Gmail)</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Carpeta de actualizaciones (Gmail)</li>
                        <li><i class="fas fa-check me-2 text-success"></i>Verifica que escribiste bien tu email</li>
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
                        <a href="/register" class="auth-link-small">
                            <i class="fas fa-user-plus me-1"></i>Crear cuenta nueva
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos específicos para los pasos */
    .step-list {
        position: relative;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .step-item:last-child {
        margin-bottom: 0;
    }

    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        bottom: -24px;
        width: 2px;
        background: #e5e7eb;
    }

    .step-number {
        width: 38px;
        height: 38px;
        background: var(--auth-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 1rem;
        flex-shrink: 0;
        z-index: 1;
        position: relative;
        background: #4f46e5;
    }

    .step-content h6 {
        margin-bottom: 0.25rem;
        color: var(--auth-text);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .step-content p {
        line-height: 1.4;
    }
</style>