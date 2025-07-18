<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'Inicio' ?> | AuthManager Base</title>
    <meta name="description" content="<?= $description ?? 'Sistema de autenticación profesional con PHP - Seguro, rápido y fácil de implementar' ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('assets/img/favicon.ico') ?>">

    <!-- CSS NotiWhoar -->
    <link href="<?= asset('assets/vendors/notiwhoar/1.0.5/css/styles.css') ?>" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= asset('assets/css/guest-v1.0.5.css') ?>" rel="stylesheet">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="public-layout">

    <!-- Header/Navigation -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Brand Logo -->
                <a class="navbar-brand" href="<?= $_ENV['APP_URL'] ?? '' ?>/">
                    <div class="brand-container">
                        <i class="fas fa-shield-alt brand-icon"></i>
                        <span class="brand-text">AuthManager</span>
                        <span class="brand-subtitle">Base</span>
                    </div>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?? '' ?>/">
                                Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?? '' ?>/about">
                                Acerca de
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?? '' ?>/contact">
                                Contacto
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') === 'faq' ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?? '' ?>/faq">
                                FAQ
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Actions -->
                    <div class="navbar-actions d-flex align-items-center gap-3">
                        <!-- Theme Toggle -->
                        <div class="dropdown">
                            <button class="btn btn-ghost" type="button" data-bs-toggle="dropdown" title="Cambiar tema">
                                <i class="fas fa-adjust"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item theme-option" data-theme="light">
                                        <i class="fas fa-sun me-2"></i>Claro
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item theme-option" data-theme="dark">
                                        <i class="fas fa-moon me-2"></i>Oscuro
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item theme-option" data-theme="system">
                                        <i class="fas fa-adjust me-2"></i>Automático
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Authentication Buttons -->
                        <?php if (isset($user) && $user): ?>
                            <!-- User is logged in -->
                            <div class="dropdown">
                                <button class="btn btn-ghost dropdown-toggle user-menu-btn" type="button" data-bs-toggle="dropdown">
                                    <img src="<?= $_ENV['APP_URL'] ?? '' ?>/assets/img/default-avatar.png" alt="Usuario" class="user-avatar">
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="dropdown-header">
                                        <h6 class="mb-0"><?= htmlspecialchars($user['username']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                    </div>
                                    <!-- <div class="dropdown-divider"></div> -->
                                    <a href="<?= $_ENV['APP_URL'] ?? '' ?>/home" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                    <a href="<?= $_ENV['APP_URL'] ?? '' ?>/profile" class="dropdown-item">
                                        <i class="fas fa-user me-2"></i>Mi Perfil
                                    </a>
                                    <a href="<?= $_ENV['APP_URL'] ?? '' ?>/settings" class="dropdown-item">
                                        <i class="fas fa-cog me-2"></i>Configuración
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="fncLogout()">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- User is not logged in -->
                            <a href="<?= $_ENV['APP_URL'] ?? '' ?>/login" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                <span class="d-none d-sm-inline">Iniciar Sesión</span>
                                <span class="d-sm-none">Login</span>
                            </a>
                            <a href="<?= $_ENV['APP_URL'] ?? '' ?>/register" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i>
                                <span class="d-none d-sm-inline">Registrarse</span>
                                <span class="d-sm-none">Registro</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand mb-3">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        <span class="h5 mb-0">AuthManager Base</span>
                    </div>
                    <p class="text-muted mb-3">
                        Sistema de autenticación profesional para proyectos PHP.
                        Seguro, escalable y fácil de implementar.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <h6 class="footer-title">Producto</h6>
                    <ul class="footer-links">
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/features">Características</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/demo">Demo</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/pricing">Precios</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/changelog">Cambios</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <h6 class="footer-title">Recursos</h6>
                    <ul class="footer-links">
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/docs">Documentación</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/tutorials">Tutoriales</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/api">API Reference</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/support">Soporte</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <h6 class="footer-title">Empresa</h6>
                    <ul class="footer-links">
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/about">Acerca de</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/blog">Blog</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/careers">Carreras</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/contact">Contacto</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <h6 class="footer-title">Legal</h6>
                    <ul class="footer-links">
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/privacy">Privacidad</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/terms">Términos</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/security">Seguridad</a></li>
                        <li><a href="<?= $_ENV['APP_URL'] ?? '' ?>/licenses">Licencias</a></li>
                    </ul>
                </div>
            </div>

            <hr class="footer-divider">

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright mb-0">
                            © <?= date('Y') ?> AuthManager Base. Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-meta">
                            <span class="version">v1.0.0</span>
                            <span class="separator">•</span>
                            <span class="build">Build <?= date('Ymd') ?></span>
                            <span class="separator">•</span>
                            <span class="status">
                                <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i>
                                Todos los sistemas operativos
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" title="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>

    <!-- Cookie Consent Banner -->
    <div class="cookie-banner" id="cookieBanner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="cookie-content">
                        <i class="fas fa-cookie-bite text-warning me-2"></i>
                        <strong>Usamos cookies</strong> para mejorar tu experiencia.
                        Al continuar navegando, aceptas nuestro uso de cookies.
                        <a href="<?= $_ENV['APP_URL'] ?? '' ?>/privacy" class="cookie-link">Más información</a>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button class="btn btn-outline-light btn-sm me-2" id="cookieDecline">
                        Rechazar
                    </button>
                    <button class="btn btn-primary btn-sm" id="cookieAccept">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS NotiWhoar -->
    <script src="<?= asset('assets/vendors/notiwhoar/1.0.5/js/main.js') ?>"></script>

    <!-- Bootstrap 5 JS -->
    <script src="<?= asset('assets/vendors/bootstrap/5.3.7/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Custom JavaScript -->
    <script src="<?= asset('assets/js/guest-v1.0.5.js') ?>"></script>

    <!-- Page-specific scripts -->
    <?= $scripts ?? '' ?>

    <!-- Analytics (if enabled) -->
    <?php if (isset($analytics_enabled) && $analytics_enabled): ?>
        <script>
            // Google Analytics or other analytics code
            console.log('Analytics enabled');
        </script>
    <?php endif; ?>

    <script>
        // Función global para cerrar sesión (usada en el HTML)
        function fncLogout() {
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
                            window.location.href = "<?= $_ENV['APP_URL'] ?>/logout";
                        }
                    });
                }
            });
        }
    </script>

</body>