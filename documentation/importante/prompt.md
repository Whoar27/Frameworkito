<!-- layout -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'Inicio' ?> | Frameworkito</title>
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
                        <span class="brand-text">Frameworkito</span>
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
                        <span class="h5 mb-0">Frameworkito</span>
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
                            © <?= date('Y') ?> Frameworkito. Todos los derechos reservados.
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

</html>

<!---------------------------------------------------- home -->
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-6 fade-in">
                <div class="hero-content">
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4">
                        <i class="fas fa-rocket me-2"></i>
                        Sistema de Autenticación Profesional
                    </div>

                    <h1 class="display-4 fw-bold mb-4">
                        Seguridad y Control de
                        <span class="text-primary">Usuarios</span>
                        Simplificados
                    </h1>

                    <p class="lead text-muted mb-5">
                        Frameworkito te proporciona todo lo que necesitas para implementar
                        un sistema de autenticación robusto y escalable en tus proyectos PHP.
                        Desde login seguro hasta gestión avanzada de roles y permisos.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mb-5">
                        <a href="<?= $_ENV['APP_URL'] ?? '' ?>/register" class="btn btn-primary btn-lg hover-lift">
                            <i class="fas fa-user-plus me-2"></i>
                            Comenzar Gratis
                        </a>
                        <a href="<?= $_ENV['APP_URL'] ?? '' ?>/demo" class="btn btn-outline-primary btn-lg hover-lift">
                            <i class="fas fa-play me-2"></i>
                            Ver Demo
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="row text-center">
                            <?php foreach ($stats as $stat): ?>
                                <div class="col-6 col-lg-3 mb-3">
                                    <div class="stat-item">
                                        <i class="<?= htmlspecialchars($stat['icon']) ?> text-primary mb-2 fa-2x"></i>
                                        <div class="stat-number h4 mb-1" data-count="<?= preg_replace('/[^0-9]/', '', $stat['number']) ?>"><?= htmlspecialchars($stat['number']) ?></div>
                                        <div class="stat-label text-muted small"><?= htmlspecialchars($stat['label']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 slide-up">
                <div class="hero-image text-center">
                    <div class="position-relative">
                        <!-- Dashboard Preview Mockup -->
                        <div class="dashboard-mockup p-4 bg-white rounded-3 shadow-lg">
                            <div class="mockup-header d-flex align-items-center mb-3">
                                <div class="d-flex gap-2">
                                    <div class="mockup-dot bg-danger rounded-circle" style="width: 12px; height: 12px;"></div>
                                    <div class="mockup-dot bg-warning rounded-circle" style="width: 12px; height: 12px;"></div>
                                    <div class="mockup-dot bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                                </div>
                                <div class="mockup-url bg-light rounded-pill px-3 py-1 ms-3 flex-grow-1 text-muted small">
                                    authmanager.local/dashboard
                                </div>
                            </div>

                            <!-- Mockup Content -->
                            <div class="mockup-content">
                                <div class="row mb-3">
                                    <div class="col-8">
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle me-2" style="width: 32px; height: 32px;"></div>
                                                <div>
                                                    <div class="bg-dark bg-opacity-25 rounded" style="width: 80px; height: 8px;"></div>
                                                    <div class="bg-dark bg-opacity-15 rounded mt-1" style="width: 60px; height: 6px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-success bg-opacity-10 rounded p-2 text-center">
                                            <small class="text-success fw-semibold">Online</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <div class="bg-dark bg-opacity-25 rounded mb-1" style="height: 6px;"></div>
                                            <div class="bg-dark bg-opacity-15 rounded" style="height: 4px; width: 70%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <div class="bg-dark bg-opacity-25 rounded mb-1" style="height: 6px;"></div>
                                            <div class="bg-dark bg-opacity-15 rounded" style="height: 4px; width: 50%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Elements -->
                            <div class="position-absolute top-0 start-0 translate-middle">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow animate-bounce" style="width: 60px; height: 60px;">
                                    <i class="fas fa-shield-alt fa-lg"></i>
                                </div>
                            </div>

                            <div class="position-absolute top-0 end-0 translate-middle-y">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow animate-pulse" style="width: 48px; height: 48px;">
                                    <i class="fas fa-check fa-sm"></i>
                                </div>
                            </div>

                            <div class="position-absolute bottom-0 start-0 translate-middle-x">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 40px; height: 40px;">
                                    <i class="fas fa-users fa-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5 fade-in">
                <h2 class="display-5 fw-bold mb-4">Todo lo que Necesitas para la Autenticación</h2>
                <p class="lead text-muted">
                    Funcionalidades completas y probadas que cubren todos los aspectos de la gestión de usuarios y seguridad.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($features as $index => $feature): ?>
                <div class="col-lg-4 col-md-6 fade-in" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <div class="feature-card h-100 p-4 bg-white rounded-3 shadow-sm border-0 hover-lift">
                        <div class="feature-icon mb-3">
                            <div class="icon-wrapper d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width: 60px; height: 60px;">
                                <i class="<?= htmlspecialchars($feature['icon']) ?> fa-lg"></i>
                            </div>
                        </div>

                        <h5 class="fw-semibold mb-3"><?= htmlspecialchars($feature['title']) ?></h5>
                        <p class="text-muted mb-0"><?= htmlspecialchars($feature['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5 fade-in">
                <h2 class="display-5 fw-bold mb-4">Implementación en 3 Pasos</h2>
                <p class="lead text-muted">
                    Configuración rápida y sencilla para tener tu sistema funcionando en minutos.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 text-center slide-up">
                <div class="step-item">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4 hover-lift" style="width: 80px; height: 80px;">
                        <span class="h3 mb-0">1</span>
                    </div>
                    <h5 class="fw-semibold mb-3">Descargar e Instalar</h5>
                    <p class="text-muted">
                        Clona el repositorio y ejecuta <code class="code-inline">composer install</code> para instalar las dependencias.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 text-center slide-up" style="animation-delay: 0.2s;">
                <div class="step-item">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4 hover-lift" style="width: 80px; height: 80px;">
                        <span class="h3 mb-0">2</span>
                    </div>
                    <h5 class="fw-semibold mb-3">Configurar Base de Datos</h5>
                    <p class="text-muted">
                        Configura tu archivo <code class="code-inline">.env</code> con los datos de tu base de datos y ejecuta las migraciones.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 text-center slide-up" style="animation-delay: 0.4s;">
                <div class="step-item">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4 hover-lift" style="width: 80px; height: 80px;">
                        <span class="h3 mb-0">3</span>
                    </div>
                    <h5 class="fw-semibold mb-3">¡Listo para Usar!</h5>
                    <p class="text-muted">
                        Tu sistema de autenticación está funcionando. Personaliza las vistas según tu marca.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center fade-in">
            <div class="col-lg-8">
                <h2 class="h1 fw-bold mb-3">¿Listo para Comenzar?</h2>
                <p class="lead mb-4 opacity-90">
                    Únete a cientos de desarrolladores que ya confían en Frameworkito para sus proyectos.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= $_ENV['APP_URL'] ?? '' ?>/contact" class="btn btn-light btn-lg me-3 hover-lift">
                    <i class="fas fa-envelope me-2"></i>
                    Contactar
                </a>
                <a href="<?= $_ENV['APP_URL'] ?? '' ?>/register" class="btn btn-outline-light btn-lg hover-lift">
                    <i class="fas fa-rocket me-2"></i>
                    Comenzar
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5 fade-in">
                <h2 class="display-5 fw-bold mb-4">Lo que Dicen los Desarrolladores</h2>
                <p class="lead text-muted">
                    Testimonios reales de desarrolladores que han usado Frameworkito en sus proyectos.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 fade-in">
                <div class="testimonial-card bg-white p-4 rounded-3 shadow-sm h-100 hover-lift">
                    <div class="stars text-warning mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="mb-4">
                        "Increíble sistema base. Me ahorró semanas de desarrollo y la arquitectura es muy limpia y escalable."
                    </p>
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <span class="fw-semibold">CR</span>
                        </div>
                        <div>
                            <div class="fw-semibold">Carlos Rodríguez</div>
                            <small class="text-muted">Full Stack Developer</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 fade-in" style="animation-delay: 0.2s;">
                <div class="testimonial-card bg-white p-4 rounded-3 shadow-sm h-100 hover-lift">
                    <div class="stars text-warning mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="mb-4">
                        "La documentación es excelente y la implementación de seguridad está muy bien pensada. Lo recomiendo 100%."
                    </p>
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <span class="fw-semibold">AM</span>
                        </div>
                        <div>
                            <div class="fw-semibold">Ana Martínez</div>
                            <small class="text-muted">Backend Developer</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 fade-in" style="animation-delay: 0.4s;">
                <div class="testimonial-card bg-white p-4 rounded-3 shadow-sm h-100 hover-lift">
                    <div class="stars text-warning mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="mb-4">
                        "Perfecto para startups que necesitan lanzar rápido pero sin sacrificar la seguridad. Excelente trabajo."
                    </p>
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <span class="fw-semibold">JL</span>
                        </div>
                        <div>
                            <div class="fw-semibold">Jorge López</div>
                            <small class="text-muted">Tech Lead</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center fade-in">
                <div class="newsletter-box bg-primary bg-opacity-10 rounded-3 p-5">
                    <h3 class="h2 fw-bold mb-3">Mantente Actualizado</h3>
                    <p class="lead text-muted mb-4">
                        Recibe las últimas actualizaciones, tips de seguridad y nuevas funcionalidades directamente en tu inbox.
                    </p>
                    <form class="newsletter-form d-flex flex-column flex-sm-row gap-3 justify-content-center" data-validate>
                        <div class="form-group flex-grow-1" style="max-width: 400px;">
                            <input type="email" class="form-control form-control-lg" placeholder="tu@email.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg hover-lift">
                            <i class="fas fa-paper-plane me-2"></i>
                            Suscribirse
                        </button>
                    </form>
                    <small class="text-muted mt-3 d-block">
                        <i class="fas fa-lock me-1"></i>
                        No spam. Puedes cancelar tu suscripción en cualquier momento.
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Newsletter form submission
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input[type="email"]').value;

                if (email) {
                    Whoar.exito('¡Gracias por suscribirte!', {
                        duracion: 4000,
                        posicion: 'superior-centro'
                    });
                    this.reset();
                }
            });
        }

        // Copy code functionality for code blocks
        document.querySelectorAll('.code-inline').forEach(code => {
            code.style.cursor = 'pointer';
            code.title = 'Click para copiar';

            code.addEventListener('click', function() {
                Utils.copyToClipboard(this.textContent);
            });
        });

        // Smooth reveal animations on scroll
        const revealElements = document.querySelectorAll('.fade-in, .slide-up');
        const revealOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const revealOnScroll = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, revealOptions);

        revealElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            revealOnScroll.observe(el);
        });
    });
</script>

<!---------------------------------------------------- about -->
<!-- Hero Section -->
<section class="hero-section about-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center fade-in">
                <div class="hero-content">
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Acerca de Nosotros
                    </div>

                    <h1 class="display-4 fw-bold mb-4">
                        <?= htmlspecialchars($page_title ?? 'Acerca de Frameworkito') ?>
                    </h1>

                    <p class="lead text-muted mb-4">
                        <?= htmlspecialchars($meta_description ?? 'Conoce más sobre nuestro sistema de autenticación y las tecnologías que utilizamos.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="content-card h-100">
                    <div class="card-icon mb-3">
                        <i class="fas fa-bullseye text-primary fa-2x"></i>
                    </div>
                    <h3 class="h4 fw-semibold mb-3">Nuestra Misión</h3>
                    <p class="text-muted">
                        Proporcionar soluciones de autenticación seguras, escalables y fáciles de implementar
                        que permitan a los desarrolladores enfocarse en lo que realmente importa: crear
                        aplicaciones excepcionales.
                    </p>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="content-card h-100">
                    <div class="card-icon mb-3">
                        <i class="fas fa-eye text-primary fa-2x"></i>
                    </div>
                    <h3 class="h4 fw-semibold mb-3">Nuestra Visión</h3>
                    <p class="text-muted">
                        Ser la base de referencia para sistemas de autenticación en PHP, estableciendo
                        estándares de seguridad y usabilidad que impulsen el desarrollo web moderno
                        y profesional.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Nuestros Valores</h2>
                <p class="lead text-muted">
                    Los principios que guían cada línea de código y cada decisión de diseño.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="value-card text-center">
                    <div class="value-icon mb-3">
                        <i class="fas fa-shield-alt text-primary fa-3x"></i>
                    </div>
                    <h4 class="h5 fw-semibold mb-3">Seguridad</h4>
                    <p class="text-muted">
                        La seguridad no es una característica adicional, es la base fundamental
                        de todo lo que construimos.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="value-card text-center">
                    <div class="value-icon mb-3">
                        <i class="fas fa-lightbulb text-primary fa-3x"></i>
                    </div>
                    <h4 class="h5 fw-semibold mb-3">Simplicidad</h4>
                    <p class="text-muted">
                        Crear soluciones complejas es fácil. Crear soluciones simples
                        que resuelvan problemas complejos es nuestro arte.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="value-card text-center">
                    <div class="value-icon mb-3">
                        <i class="fas fa-rocket text-primary fa-3x"></i>
                    </div>
                    <h4 class="h5 fw-semibold mb-3">Innovación</h4>
                    <p class="text-muted">
                        Adoptamos las mejores prácticas actuales mientras anticipamos
                        las necesidades del desarrollo futuro.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technology Section -->
<section class="technology-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Tecnología que Confiamos</h2>
                <p class="lead text-muted">
                    Construido con tecnologías probadas y modernas para garantizar rendimiento y confiabilidad.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="tech-card text-center p-4">
                    <div class="tech-icon mb-3">
                        <i class="fab fa-php text-primary fa-3x"></i>
                    </div>
                    <h5 class="fw-semibold">PHP 8.0+</h5>
                    <p class="text-muted small">Backend robusto y moderno</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="tech-card text-center p-4">
                    <div class="tech-icon mb-3">
                        <i class="fab fa-bootstrap text-primary fa-3x"></i>
                    </div>
                    <h5 class="fw-semibold">Bootstrap 5</h5>
                    <p class="text-muted small">UI responsive y moderna</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="tech-card text-center p-4">
                    <div class="tech-icon mb-3">
                        <i class="fas fa-database text-primary fa-3x"></i>
                    </div>
                    <h5 class="fw-semibold">MySQL</h5>
                    <p class="text-muted small">Base de datos confiable</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="tech-card text-center p-4">
                    <div class="tech-icon mb-3">
                        <i class="fab fa-js-square text-primary fa-3x"></i>
                    </div>
                    <h5 class="fw-semibold">JavaScript ES6</h5>
                    <p class="text-muted small">Interactividad moderna</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 fw-bold mb-3">¿Listo para Comenzar?</h2>
                <p class="lead mb-4 opacity-90">
                    Implementa un sistema de autenticación profesional en tu próximo proyecto.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/contact" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-envelope me-2"></i>
                    Contactar
                </a>
                <a href="/" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-home me-2"></i>
                    Inicio
                </a>
            </div>
        </div>
    </div>
</section>

<!---------------------------------------------------- contact -->
<!-- Hero Section -->
<section class="hero-section contact-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center fade-in">
                <div class="hero-content">
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4">
                        <i class="fas fa-envelope me-2"></i>
                        Contáctanos
                    </div>

                    <h1 class="display-4 fw-bold mb-4">
                        <?= htmlspecialchars($page_title ?? 'Contacto - Frameworkito') ?>
                    </h1>

                    <p class="lead text-muted mb-4">
                        <?= htmlspecialchars($meta_description ?? 'Ponte en contacto con nosotros para soporte técnico o consultas comerciales.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <div class="card-header">
                        <h3 class="h4 fw-semibold mb-2">Envíanos un Mensaje</h3>
                        <p class="text-muted mb-0">Te responderemos lo antes posible</p>
                    </div>

                    <form id="contactForm" action="/contact" method="POST" class="contact-form">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?? '' ?>">

                        <div class="row g-3">
                            <!-- Name Field -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Nombre Completo
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    required
                                    placeholder="Tu nombre completo">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    Email
                                </label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    required
                                    placeholder="tu@email.com">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Subject Field -->
                            <div class="col-12">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    Asunto
                                </label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Selecciona un asunto</option>
                                    <option value="Consulta General">Consulta General</option>
                                    <option value="Soporte Técnico">Soporte Técnico</option>
                                    <option value="Consulta Comercial">Consulta Comercial</option>
                                    <option value="Reporte de Bug">Reporte de Bug</option>
                                    <option value="Solicitud de Feature">Solicitud de Feature</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Message Field -->
                            <div class="col-12">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment text-primary me-2"></i>
                                    Mensaje
                                </label>
                                <textarea
                                    class="form-control"
                                    id="message"
                                    name="message"
                                    rows="6"
                                    required
                                    placeholder="Escribe tu mensaje aquí..."></textarea>
                                <div class="invalid-feedback"></div>
                                <div class="form-text">Mínimo 10 caracteres, máximo 1000</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="contact-info">
                    <!-- Contact Methods -->
                    <div class="info-card mb-4">
                        <h4 class="h5 fw-semibold mb-3">
                            <i class="fas fa-address-book text-primary me-2"></i>
                            Información de Contacto
                        </h4>

                        <div class="contact-item mb-3">
                            <div class="contact-icon">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="contact-details">
                                <strong>Email</strong>
                                <p class="mb-0">soporte@authmanager.com</p>
                            </div>
                        </div>

                        <div class="contact-item mb-3">
                            <div class="contact-icon">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div class="contact-details">
                                <strong>Teléfono</strong>
                                <p class="mb-0">+1 (555) 123-4567</p>
                            </div>
                        </div>

                        <div class="contact-item mb-3">
                            <div class="contact-icon">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div class="contact-details">
                                <strong>Horario de Atención</strong>
                                <p class="mb-0">Lun - Vie: 9:00 AM - 6:00 PM</p>
                                <small class="text-muted">Zona horaria: GMT-5</small>
                            </div>
                        </div>
                    </div>

                    <!-- Response Time -->
                    <div class="info-card mb-4">
                        <h4 class="h5 fw-semibold mb-3">
                            <i class="fas fa-stopwatch text-primary me-2"></i>
                            Tiempo de Respuesta
                        </h4>

                        <div class="response-times">
                            <div class="response-item">
                                <span class="badge bg-success mb-2">Consultas Generales</span>
                                <p class="small mb-2">24-48 horas</p>
                            </div>

                            <div class="response-item">
                                <span class="badge bg-warning mb-2">Soporte Técnico</span>
                                <p class="small mb-2">4-12 horas</p>
                            </div>

                            <div class="response-item">
                                <span class="badge bg-danger mb-2">Emergencias</span>
                                <p class="small mb-0">2-4 horas</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Link -->
                    <div class="info-card">
                        <h4 class="h5 fw-semibold mb-3">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            ¿Necesitas Ayuda Rápida?
                        </h4>
                        <p class="text-muted mb-3">
                            Consulta nuestras preguntas frecuentes antes de enviar un mensaje.
                        </p>
                        <a href="/faq" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Ver FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Info Section -->
<section class="additional-info py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="info-feature text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt text-primary fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Información Segura</h5>
                    <p class="text-muted small">
                        Todos los datos enviados están protegidos con encriptación SSL y se manejan conforme a políticas de privacidad.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-feature text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users text-primary fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Equipo Experto</h5>
                    <p class="text-muted small">
                        Nuestro equipo técnico tiene amplia experiencia en sistemas de autenticación y desarrollo web.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-feature text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset text-primary fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Soporte Continuo</h5>
                    <p class="text-muted small">
                        Ofrecemos soporte técnico continuo para asegurar el éxito de tu implementación.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous validation
                clearValidation();

                // Validate form
                if (validateForm()) {
                    submitForm();
                }
            });
        }

        function validateForm() {
            let isValid = true;

            // Name validation
            const name = document.getElementById('name');
            if (name.value.trim().length < 2) {
                showError(name, 'El nombre debe tener al menos 2 caracteres');
                isValid = false;
            }

            // Email validation
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError(email, 'Introduce un email válido');
                isValid = false;
            }

            // Subject validation
            const subject = document.getElementById('subject');
            if (!subject.value) {
                showError(subject, 'Selecciona un asunto');
                isValid = false;
            }

            // Message validation
            const message = document.getElementById('message');
            if (message.value.trim().length < 10) {
                showError(message, 'El mensaje debe tener al menos 10 caracteres');
                isValid = false;
            } else if (message.value.trim().length > 1000) {
                showError(message, 'El mensaje no puede exceder 1000 caracteres');
                isValid = false;
            }

            return isValid;
        }

        function showError(field, message) {
            field.classList.add('is-invalid');
            const feedback = field.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = message;
            }
        }

        function clearValidation() {
            const fields = form.querySelectorAll('.form-control, .form-select');
            fields.forEach(field => {
                field.classList.remove('is-invalid', 'is-valid');
            });
        }

        function submitForm() {
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner me-2"></i>Enviando...';

            // Create FormData
            const formData = new FormData(form);

            // Submit via fetch
            fetch('/contact', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('¡Mensaje enviado correctamente! Te responderemos pronto.');
                        form.reset();
                    } else {
                        showAlert('Hubo un error al enviar el mensaje. Inténtalo de nuevo.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error de conexión. Verifica tu conexión a internet.', 'error');
                })
                .finally(() => {
                    // Reset button
                    submitBtn.classList.remove('loading');
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Enviar Mensaje';
                });
        }

        function showSuccess(message) {
            showAlert(message, 'success');
        }

        function showAlert(message, type) {
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
            alert.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            // Insert before form
            form.parentNode.insertBefore(alert, form);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        // Character counter for message
        const messageField = document.getElementById('message');
        if (messageField) {
            const maxLength = 1000;
            const helpText = messageField.parentNode.querySelector('.form-text');

            messageField.addEventListener('input', function() {
                const currentLength = this.value.length;
                const remaining = maxLength - currentLength;

                if (helpText) {
                    helpText.textContent = `Caracteres: ${currentLength}/${maxLength}`;

                    if (remaining < 50) {
                        helpText.classList.add('text-warning');
                    } else {
                        helpText.classList.remove('text-warning');
                    }

                    if (remaining < 0) {
                        helpText.classList.add('text-danger');
                        helpText.classList.remove('text-warning');
                    } else {
                        helpText.classList.remove('text-danger');
                    }
                }
            });
        }
    });
</script>

<!---------------------------------------------------- faq -->
<!-- Hero Section -->
<section class="hero-section faq-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center fade-in">
                <div class="hero-content">
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4">
                        <i class="fas fa-question-circle me-2"></i>
                        Preguntas Frecuentes
                    </div>

                    <h1 class="display-4 fw-bold mb-4">
                        <?= htmlspecialchars($page_title ?? 'FAQ - Preguntas Frecuentes') ?>
                    </h1>

                    <p class="lead text-muted mb-4">
                        <?= htmlspecialchars($meta_description ?? 'Encuentra respuestas rápidas a las preguntas más comunes sobre Frameworkito.') ?>
                    </p>

                    <!-- Search Box -->
                    <div class="faq-search mx-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control border-start-0"
                                placeholder="Buscar en preguntas frecuentes..."
                                id="faqSearch">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Categories -->
<section class="categories-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="categories-nav">
                    <div class="nav nav-pills justify-content-center flex-wrap" id="faq-categories">
                        <button class="nav-link active" data-category="all">
                            <i class="fas fa-th-large me-2"></i>Todas
                        </button>
                        <button class="nav-link" data-category="general">
                            <i class="fas fa-info-circle me-2"></i>General
                        </button>
                        <button class="nav-link" data-category="instalacion">
                            <i class="fas fa-download me-2"></i>Instalación
                        </button>
                        <button class="nav-link" data-category="configuracion">
                            <i class="fas fa-cogs me-2"></i>Configuración
                        </button>
                        <button class="nav-link" data-category="seguridad">
                            <i class="fas fa-shield-alt me-2"></i>Seguridad
                        </button>
                        <button class="nav-link" data-category="soporte">
                            <i class="fas fa-headset me-2"></i>Soporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="faq-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- General -->
                <div class="faq-category" data-category="general">
                    <h3 class="category-title">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Preguntas Generales
                    </h3>

                    <div class="accordion mb-4" id="generalAccordion">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                    ¿Qué es Frameworkito?
                                </button>
                            </h2>
                            <div id="general1" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    Frameworkito es un sistema de autenticación reutilizable desarrollado en PHP vanilla que implementa el patrón MVC. Está diseñado para ser una base sólida que puedes usar en múltiples proyectos, ya sean sitios web corporativos o sistemas de gestión internos.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                    ¿Qué tecnologías utiliza?
                                </button>
                            </h2>
                            <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0">
                                        <li><strong>Backend:</strong> PHP 8.0+ vanilla</li>
                                        <li><strong>Base de datos:</strong> MySQL/MariaDB</li>
                                        <li><strong>Frontend:</strong> Bootstrap 5 + JavaScript ES6</li>
                                        <li><strong>Autenticación:</strong> Delight-im/Auth</li>
                                        <li><strong>Servidor:</strong> Apache (compatible con XAMPP)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                    ¿Es gratis y de código abierto?
                                </button>
                            </h2>
                            <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    Sí, Frameworkito está disponible bajo la licencia MIT, lo que significa que puedes usarlo, modificarlo y distribuirlo libremente, incluso en proyectos comerciales.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instalación -->
                <div class="faq-category" data-category="instalacion">
                    <h3 class="category-title">
                        <i class="fas fa-download text-primary me-2"></i>
                        Instalación y Setup
                    </h3>

                    <div class="accordion mb-4" id="instalacionAccordion">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#install1">
                                    ¿Cómo instalo Frameworkito?
                                </button>
                            </h2>
                            <div id="install1" class="accordion-collapse collapse" data-bs-parent="#instalacionAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Descarga y extrae el proyecto</li>
                                        <li>Ejecuta <code>composer install</code> (opcional)</li>
                                        <li>Copia <code>.env.example</code> a <code>.env</code></li>
                                        <li>Ejecuta <code>php generate-key.php</code></li>
                                        <li>Configura tu base de datos en <code>.env</code></li>
                                        <li>Ejecuta las migraciones SQL</li>
                                        <li>Apunta tu servidor web a la carpeta <code>/public</code></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#install2">
                                    ¿Necesito Composer obligatoriamente?
                                </button>
                            </h2>
                            <div id="install2" class="accordion-collapse collapse" data-bs-parent="#instalacionAccordion">
                                <div class="accordion-body">
                                    No, el sistema incluye un autoloader manual que funciona sin Composer. Sin embargo, Composer es recomendado para tener acceso a todas las librerías y funcionalidades avanzadas como Delight-im/Auth y PHPMailer.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#install3">
                                    ¿Funciona con XAMPP?
                                </button>
                            </h2>
                            <div id="install3" class="accordion-collapse collapse" data-bs-parent="#instalacionAccordion">
                                <div class="accordion-body">
                                    Sí, está completamente optimizado para XAMPP. Solo necesitas colocar el proyecto en <code>htdocs</code> y configurar el virtual host para que apunte a la carpeta <code>/public</code>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuración -->
                <div class="faq-category" data-category="configuracion">
                    <h3 class="category-title">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Configuración
                    </h3>

                    <div class="accordion mb-4" id="configAccordion">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#config1">
                                    ¿Cómo configuro el tipo de aplicación?
                                </button>
                            </h2>
                            <div id="config1" class="accordion-collapse collapse" data-bs-parent="#configAccordion">
                                <div class="accordion-body">
                                    En tu archivo <code>.env</code>, configura:
                                    <ul class="mt-2">
                                        <li><code>APP_TYPE=website</code> - Para sitios web con páginas públicas</li>
                                        <li><code>APP_TYPE=system</code> - Para sistemas de gestión internos</li>
                                    </ul>
                                    Esto cambiará el comportamiento de la página principal.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#config2">
                                    ¿Cómo activo el modo mantenimiento?
                                </button>
                            </h2>
                            <div id="config2" class="accordion-collapse collapse" data-bs-parent="#configAccordion">
                                <div class="accordion-body">
                                    Cambia <code>APP_MAINTENANCE=true</code> en tu <code>.env</code>. Puedes configurar IPs permitidas en <code>app/Config/app.php</code> para que ciertos usuarios (como administradores) puedan acceder durante el mantenimiento.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#config3">
                                    ¿Cómo configuro el envío de emails?
                                </button>
                            </h2>
                            <div id="config3" class="accordion-collapse collapse" data-bs-parent="#configAccordion">
                                <div class="accordion-body">
                                    Configura las variables SMTP en tu <code>.env</code>:
                                    <pre class="bg-light p-2 rounded mt-2"><code>MAIL_HOST=smtp.gmail.com
 MAIL_PORT=587
 MAIL_USERNAME=tu-email@gmail.com
 MAIL_PASSWORD=tu-app-password
 MAIL_ENCRYPTION=tls</code></pre>
                                    Para Gmail, necesitas generar una contraseña de aplicación.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seguridad -->
                <div class="faq-category" data-category="seguridad">
                    <h3 class="category-title">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        Seguridad
                    </h3>

                    <div class="accordion mb-4" id="securityAccordion">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#security1">
                                    ¿Qué medidas de seguridad incluye?
                                </button>
                            </h2>
                            <div id="security1" class="accordion-collapse collapse" data-bs-parent="#securityAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0">
                                        <li>Protección CSRF en todos los formularios</li>
                                        <li>Rate limiting contra ataques de fuerza bruta</li>
                                        <li>Validación robusta de entrada</li>
                                        <li>Headers de seguridad configurables</li>
                                        <li>Encriptación segura de contraseñas</li>
                                        <li>Tokens seguros para recuperación</li>
                                        <li>Logs de auditoría completos</li>
                                        <li>Autenticación de dos factores (2FA) opcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#security2">
                                    ¿Cómo genero una clave de aplicación segura?
                                </button>
                            </h2>
                            <div id="security2" class="accordion-collapse collapse" data-bs-parent="#securityAccordion">
                                <div class="accordion-body">
                                    Ejecuta el script incluido: <code>php generate-key.php</code>. Esto generará automáticamente una clave segura de 32 caracteres y la agregará a tu archivo <code>.env</code>.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#security3">
                                    ¿Cómo habilito HTTPS en producción?
                                </button>
                            </h2>
                            <div id="security3" class="accordion-collapse collapse" data-bs-parent="#securityAccordion">
                                <div class="accordion-body">
                                    Configura <code>FORCE_HTTPS=true</code> en tu <code>.env</code> de producción. Esto redirigirá automáticamente todo el tráfico HTTP a HTTPS y activará cookies seguras.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Soporte -->
                <div class="faq-category" data-category="soporte">
                    <h3 class="category-title">
                        <i class="fas fa-headset text-primary me-2"></i>
                        Soporte y Ayuda
                    </h3>

                    <div class="accordion mb-4" id="supportAccordion">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#support1">
                                    ¿Dónde puedo reportar bugs o solicitar features?
                                </button>
                            </h2>
                            <div id="support1" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                                <div class="accordion-body">
                                    Puedes usar nuestro <a href="/contact">formulario de contacto</a> seleccionando "Reporte de Bug" o "Solicitud de Feature" como asunto. También puedes crear issues en el repositorio de GitHub si está disponible.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#support2">
                                    ¿Hay documentación técnica disponible?
                                </button>
                            </h2>
                            <div id="support2" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                                <div class="accordion-body">
                                    Sí, la documentación completa está disponible en la carpeta <code>/documentation</code> del proyecto, incluyendo guías de instalación, configuración, uso y referencia de API.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#support3">
                                    ¿Cuál es el tiempo de respuesta para soporte?
                                </button>
                            </h2>
                            <div id="support3" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0">
                                        <li><strong>Consultas generales:</strong> 24-48 horas</li>
                                        <li><strong>Soporte técnico:</strong> 4-12 horas</li>
                                        <li><strong>Emergencias:</strong> 2-4 horas</li>
                                    </ul>
                                    Los tiempos pueden variar según la complejidad del problema.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Still Have Questions -->
<section class="help-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 fw-bold mb-3">¿Aún tienes preguntas?</h2>
                <p class="lead mb-4 opacity-90">
                    No encontraste la respuesta que buscabas? Nuestro equipo de soporte está aquí para ayudarte.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/contact" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-envelope me-2"></i>
                    Contactar Soporte
                </a>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('faqSearch');
        const categoryButtons = document.querySelectorAll('[data-category]');
        const faqCategories = document.querySelectorAll('.faq-category');
        const faqItems = document.querySelectorAll('.faq-item');

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                if (searchTerm === '') {
                    // Show all items and categories
                    showAllCategories();
                    clearHighlights();
                } else {
                    searchFAQ(searchTerm);
                }
            });
        }

        // Category filtering
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.dataset.category;

                // Update active button
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Clear search
                if (searchInput) searchInput.value = '';
                clearHighlights();

                // Filter categories
                if (category === 'all') {
                    showAllCategories();
                } else {
                    filterByCategory(category);
                }
            });
        });

        function searchFAQ(searchTerm) {
            let hasResults = false;

            faqItems.forEach(item => {
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    highlightText(item, searchTerm);
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide categories based on results
            faqCategories.forEach(category => {
                const visibleItems = category.querySelectorAll('.faq-item[style*="block"], .faq-item:not([style*="none"])');
                const hasVisibleItems = Array.from(visibleItems).some(item =>
                    item.style.display !== 'none'
                );

                category.style.display = hasVisibleItems ? 'block' : 'none';
            });

            // Show no results message
            showNoResultsMessage(!hasResults, searchTerm);
        }

        function filterByCategory(category) {
            faqCategories.forEach(cat => {
                if (cat.dataset.category === category) {
                    cat.style.display = 'block';
                    // Show all items in this category
                    cat.querySelectorAll('.faq-item').forEach(item => {
                        item.style.display = 'block';
                    });
                } else {
                    cat.style.display = 'none';
                }
            });
        }

        function showAllCategories() {
            faqCategories.forEach(category => {
                category.style.display = 'block';
                category.querySelectorAll('.faq-item').forEach(item => {
                    item.style.display = 'block';
                });
            });
            hideNoResultsMessage();
        }

        function highlightText(item, searchTerm) {
            // Remove previous highlights
            clearHighlights(item);

            const question = item.querySelector('.accordion-button');
            const answer = item.querySelector('.accordion-body');

            [question, answer].forEach(element => {
                if (element) {
                    const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
                    const originalHTML = element.innerHTML;
                    const highlightedHTML = originalHTML.replace(regex, '<span class="highlight">$1</span>');
                    element.innerHTML = highlightedHTML;
                }
            });
        }

        function clearHighlights(container = document) {
            const highlights = container.querySelectorAll('.highlight');
            highlights.forEach(highlight => {
                const parent = highlight.parentNode;
                parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
                parent.normalize();
            });
        }

        function escapeRegex(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function showNoResultsMessage(show, searchTerm) {
            let noResultsDiv = document.getElementById('noResults');

            if (show) {
                if (!noResultsDiv) {
                    noResultsDiv = document.createElement('div');
                    noResultsDiv.id = 'noResults';
                    noResultsDiv.className = 'text-center py-5';
                    noResultsDiv.innerHTML = `
                    <div class="no-results-content">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No se encontraron resultados</h4>
                        <p class="text-muted">No encontramos preguntas que coincidan con "<strong>${searchTerm}</strong>"</p>
                        <p class="text-muted">Intenta con otros términos o <a href="/contact">contáctanos</a> directamente.</p>
                    </div>
                `;

                    // Insert after the last category
                    const lastCategory = document.querySelector('.faq-category:last-of-type');
                    if (lastCategory) {
                        lastCategory.parentNode.insertBefore(noResultsDiv, lastCategory.nextSibling);
                    }
                }
                noResultsDiv.style.display = 'block';
            } else {
                hideNoResultsMessage();
            }
        }

        function hideNoResultsMessage() {
            const noResultsDiv = document.getElementById('noResults');
            if (noResultsDiv) {
                noResultsDiv.style.display = 'none';
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }

            // Escape to clear search
            if (e.key === 'Escape' && searchInput) {
                searchInput.value = '';
                showAllCategories();
                clearHighlights();
                searchInput.blur();
            }
        });

        // URL hash navigation for direct links to FAQ items
        function handleHashNavigation() {
            const hash = window.location.hash;
            if (hash) {
                const targetElement = document.querySelector(hash);
                if (targetElement && targetElement.classList.contains('accordion-collapse')) {
                    // Open the accordion item
                    const button = document.querySelector(`[data-bs-target="${hash}"]`);
                    if (button) {
                        // Show the category first
                        const category = targetElement.closest('.faq-category');
                        if (category) {
                            const categoryName = category.dataset.category;
                            if (categoryName) {
                                const categoryButton = document.querySelector(`[data-category="${categoryName}"]`);
                                if (categoryButton) {
                                    categoryButton.click();
                                }
                            }
                        }

                        // Then expand the accordion
                        setTimeout(() => {
                            if (!targetElement.classList.contains('show')) {
                                button.click();
                            }
                            // Scroll to the element
                            setTimeout(() => {
                                targetElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }, 300);
                        }, 100);
                    }
                }
            }
        }

        // Handle hash navigation on page load and hash change
        handleHashNavigation();
        window.addEventListener('hashchange', handleHashNavigation);

        // Add copy link functionality to FAQ items
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('contextmenu', function(e) {
                e.preventDefault();

                const target = this.getAttribute('data-bs-target');
                if (target) {
                    const url = window.location.origin + window.location.pathname + target;

                    // Copy to clipboard
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(url).then(() => {
                            showCopyNotification(this);
                        }).catch(() => {
                            // Fallback for older browsers
                            fallbackCopyToClipboard(url, this);
                        });
                    } else {
                        fallbackCopyToClipboard(url, this);
                    }
                }
            });
        });

        function showCopyNotification(element) {
            const notification = document.createElement('div');
            notification.className = 'copy-notification';
            notification.innerHTML = '<i class="fas fa-check me-1"></i>Enlace copiado';
            notification.style.cssText = `
            position: absolute;
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            z-index: 1000;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        `;

            element.style.position = 'relative';
            element.appendChild(notification);

            // Animate in
            setTimeout(() => notification.style.opacity = '1', 10);

            // Remove after 2 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 2000);
        }

        function fallbackCopyToClipboard(text, element) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                showCopyNotification(element);
            } catch (err) {
                console.error('Error copying to clipboard:', err);
            }

            document.body.removeChild(textArea);
        }

        // Analytics tracking for FAQ interactions
        function trackFAQInteraction(action, question) {
            // You can integrate with Google Analytics or other analytics services here
            if (typeof gtag !== 'undefined') {
                gtag('event', 'faq_interaction', {
                    'event_category': 'FAQ',
                    'event_label': question,
                    'action': action
                });
            }

            // Console log for development
            console.log('FAQ Interaction:', action, question);
        }

        // Track accordion opens
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                const question = this.textContent.trim();
                trackFAQInteraction('question_opened', question);
            });
        });

        // Track search usage
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.trim().length > 2) {
                        trackFAQInteraction('search_performed', this.value.trim());
                    }
                }, 1000);
            });
        }

        // Track category filtering
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.dataset.category;
                trackFAQInteraction('category_filtered', category);
            });
        });
    });
</script>

<!---------------------------------------------------- css -->
/* ========================================
DISEÑO MODERNO - LAYOUT PARA INVITADOS
Soporte para Temas: Claro/Oscuro/Auto
======================================== */

/* Variables base - definidas primero */
:root {
/* Colores de marca */
--primary-500: #0066ff;
--primary-400: #4d94ff;
--primary-600: #0052cc;
--primary-700: #003d99;

--secondary-500: #00d084;
--secondary-400: #4ddf9f;
--secondary-600: #00a86b;

--accent-500: #ff6b35;
--accent-400: #ff8f6b;
--accent-600: #e55a2b;

/* Estados: Éxito/Advertencia/Error */
--success-500: #10b981;
--warning-500: #f59e0b;
--error-500: #ef4444;

/* Tipografía */
--font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
--font-mono: 'JetBrains Mono', 'Fira Code', monospace;

/* Espaciado y Layout */
--sidebar-width: 280px;
--topbar-height: 70px;
--header-height: 80px;
--border-radius: 12px;
--border-radius-sm: 8px;
--border-radius-lg: 16px;

/* Transiciones */
--transition-fast: 0.15s ease;
--transition-normal: 0.3s ease;
--transition-slow: 0.5s ease;

/* Sombras */
--shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
--shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
--shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
--shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
--shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

/* Valores por defecto (tema claro) */
--bg-primary: #ffffff;
--bg-secondary: #f8fafc;
--bg-tertiary: #f1f5f9;
--bg-elevated: #ffffff;

--text-primary: #0f172a;
--text-secondary: #475569;
--text-tertiary: #64748b;
--text-muted: #94a3b8;
--text-inverse: #ffffff;

--border-primary: #e2e8f0;
--border-secondary: #cbd5e1;
--border-focus: var(--primary-500);

--surface-primary: #ffffff;
--surface-secondary: #f8fafc;
--surface-hover: #f1f5f9;
--surface-active: #e2e8f0;

/* Elementos de formulario */
--input-bg: #ffffff;
--input-border: #d1d5db;
--input-focus-border: var(--primary-500);
--input-placeholder: #9ca3af;
}

/* Tema Oscuro - Sobrescribe las variables */
[data-theme="dark"] {
--bg-primary: #0f172a;
--bg-secondary: #1e293b;
--bg-tertiary: #334155;
--bg-elevated: #1e293b;

--text-primary: #f8fafc;
--text-secondary: #cbd5e1;
--text-tertiary: #94a3b8;
--text-muted: #64748b;
--text-inverse: #0f172a;

--border-primary: #334155;
--border-secondary: #475569;
--border-focus: var(--primary-400);

--surface-primary: #1e293b;
--surface-secondary: #334155;
--surface-hover: #475569;
--surface-active: #64748b;

/* Elementos de formulario */
--input-bg: #334155;
--input-border: #475569;
--input-focus-border: var(--primary-400);
--input-placeholder: #64748b;
}

/* Tema Automático - Solo aplica si no hay tema manual */
@media (prefers-color-scheme: dark) {
:root:not([data-theme]):not([data-theme="light"]) {
--bg-primary: #0f172a;
--bg-secondary: #1e293b;
--bg-tertiary: #334155;
--bg-elevated: #1e293b;

--text-primary: #f8fafc;
--text-secondary: #cbd5e1;
--text-tertiary: #94a3b8;
--text-muted: #64748b;
--text-inverse: #0f172a;

--border-primary: #334155;
--border-secondary: #475569;
--border-focus: var(--primary-400);

--surface-primary: #1e293b;
--surface-secondary: #334155;
--surface-hover: #475569;
--surface-active: #64748b;

--input-bg: #334155;
--input-border: #475569;
--input-focus-border: var(--primary-400);
--input-placeholder: #64748b;
}
}

/* ========================================
ESTILOS BASE
======================================== */

/* Reset básico */
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

/* Configuración HTML base */
html {
font-size: 16px;
scroll-behavior: smooth;
}

/* Estilos del cuerpo del documento */
body {
font-family: var(--font-primary);
font-size: 0.875rem;
line-height: 1.6;
color: var(--text-primary);
background-color: var(--bg-primary);
transition: background-color var(--transition-normal), color var(--transition-normal);
overflow-x: hidden;
}

/* ========================================
ESTRUCTURA DEL LAYOUT
======================================== */

/* Layout principal para páginas públicas */
.public-layout {
min-height: 100vh;
background-color: var(--bg-primary);
padding-top: var(--header-height);
}

/* ========================================
CABECERA Y NAVEGACIÓN
======================================== */

/* Estilos de la cabecera principal */
.main-header {
position: fixed;
top: 0;
left: 0;
right: 0;
z-index: 1050;
background: var(--bg-primary);
border-bottom: 1px solid var(--border-primary);
box-shadow: var(--shadow-sm);
transition: all var(--transition-normal);
}

/* Barra de navegación */
.navbar {
padding: 1rem 0;
min-height: var(--header-height);
}

/* Estilos de la marca/logo */
.navbar-brand {
font-weight: 700;
color: var(--text-primary);
text-decoration: none;
transition: all var(--transition-fast);
}

.navbar-brand:hover {
color: var(--primary-500);
transform: translateY(-2px);
}

/* Contenedor de la marca */
.brand-container {
display: flex;
align-items: center;
gap: 0.5rem;
}

/* Icono de la marca */
.brand-icon {
font-size: 1.5rem;
color: var(--primary-500);
background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
}

/* Texto de la marca */
.brand-text {
font-size: 1.5rem;
font-weight: 800;
color: var(--text-primary);
}

/* Subtítulo de la marca */
.brand-subtitle {
font-size: 0.875rem;
font-weight: 500;
background: var(--primary-500);
color: white;
padding: 0.125rem 0.5rem;
border-radius: 12px;
margin-left: 0.25rem;
}

/* Enlaces de navegación */
.navbar-nav .nav-link {
font-weight: 500;
color: var(--text-secondary);
padding: 0.75rem 1rem;
margin: 0 0.25rem;
border-radius: var(--border-radius);
transition: all var(--transition-fast);
position: relative;
}

.navbar-nav .nav-link:hover {
color: var(--primary-500);
background-color: var(--surface-hover);
transform: translateY(-1px);
}

.navbar-nav .nav-link.active {
color: var(--primary-500);
background-color: var(--surface-active);
font-weight: 600;
}

/* Indicador de enlace activo */
.navbar-nav .nav-link.active::after {
content: '';
position: absolute;
bottom: -8px;
left: 50%;
transform: translateX(-50%);
width: 6px;
height: 6px;
background: var(--primary-500);
border-radius: 50%;
}

/* Acciones de la barra de navegación */
.navbar-actions {
gap: 0.75rem;
}

/* Botón para móviles */
.navbar-toggler {
border: none;
padding: 0.5rem;
border-radius: var(--border-radius);
background: var(--surface-hover);
transition: all var(--transition-fast);
}

.navbar-toggler:hover {
background: var(--surface-active);
transform: scale(1.05);
}

.navbar-toggler:focus {
box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
}

/* ========================================
BOTONES
======================================== */

/* Botón fantasma (sin fondo) */
.btn-ghost {
background: none;
border: none;
color: var(--text-secondary);
padding: 0.625rem;
border-radius: var(--border-radius);
cursor: pointer;
transition: all var(--transition-fast);
position: relative;
display: flex;
align-items: center;
gap: 0.5rem;
}

.btn-ghost:hover {
background-color: var(--surface-hover);
color: var(--text-primary);
transform: translateY(-1px);
}

.btn-ghost:active {
transform: translateY(0);
}

/* Avatar de usuario */
.user-avatar {
width: 32px;
height: 32px;
border-radius: 50%;
object-fit: cover;
border: 2px solid var(--primary-500);
transition: all var(--transition-fast);
}

.user-menu-btn:hover .user-avatar {
transform: scale(1.1);
border-color: var(--secondary-500);
}

/* Menús desplegables */
.dropdown-menu {
border: none;
border-radius: var(--border-radius);
box-shadow: var(--shadow-xl);
background-color: var(--surface-primary);
border: 1px solid var(--border-primary);
margin-top: 0.5rem;
min-width: 160px;
}

.dropdown-item {
color: var(--text-secondary);
padding: 0.75rem 1rem;
transition: all var(--transition-fast);
background: var(--surface-primary);
border: none;
width: 100%;
text-align: left;
display: flex;
align-items: center;
}

.dropdown-item:hover {
background-color: var(--surface-hover);
color: var(--text-primary);
}

.dropdown-header {
padding: 0.75rem 1rem;
border-bottom: 1px solid var(--border-primary);
background: var(--surface-secondary);
}

.dropdown-divider {
border-color: var(--border-primary);
margin: 0.5rem 0;
}

/* Opción de tema activa */
.theme-option.active {
background: var(--primary-500);
color: white;
}

.theme-option.active i {
color: white !important;
}

/* ========================================
CONTENIDO PRINCIPAL
======================================== */

.main-content {
flex: 1;
min-height: calc(100vh - var(--header-height));
margin-left: 0 !important;
}

/* Sección Hero específica */
.hero-section {
padding: 100px 0;
background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
position: relative;
overflow: hidden;
}

/* Efecto de fondo para hero */
.hero-section::before {
content: '';
position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
background:
radial-gradient(circle at 20% 50%, rgba(0, 102, 255, 0.1) 0%, transparent 50%),
radial-gradient(circle at 80% 20%, rgba(0, 208, 132, 0.1) 0%, transparent 50%),
radial-gradient(circle at 40% 80%, rgba(255, 107, 53, 0.1) 0%, transparent 50%);
pointer-events: none;
}

/* Animaciones */
.fade-in {
animation: fadeInUp 0.8s ease-out;
}

.slide-up {
animation: slideUp 0.8s ease-out 0.2s both;
}

@keyframes fadeInUp {
from {
opacity: 0;
transform: translateY(30px);
}
to {
opacity: 1;
transform: translateY(0);
}
}

@keyframes slideUp {
from {
opacity: 0;
transform: translateY(50px);
}
to {
opacity: 1;
transform: translateY(0);
}
}

/* Mockup de dashboard */
.dashboard-mockup {
position: relative;
max-width: 400px;
margin: 0 auto;
animation: float 6s ease-in-out infinite;
}

@keyframes float {
0%, 100% {
transform: translateY(0px);
}
50% {
transform: translateY(-20px);
}
}

/* Estadísticas en hero */
.hero-stats .stat-item {
text-align: center;
padding: 1rem;
transition: all var(--transition-fast);
}

.hero-stats .stat-item:hover {
transform: translateY(-5px);
}

.stat-number {
font-weight: 700;
color: var(--primary-500);
}

/* Tarjetas de características */
.feature-card {
transition: all var(--transition-normal);
border: 1px solid var(--border-primary);
height: 100%;
}

.feature-card:hover {
transform: translateY(-8px);
box-shadow: var(--shadow-lg);
border-color: var(--primary-500);
}

.feature-card .icon-wrapper {
transition: all var(--transition-fast);
}

.feature-card:hover .icon-wrapper {
background-color: var(--primary-500);
color: white;
transform: scale(1.1);
}

/* Elementos de pasos */
.step-item {
position: relative;
padding: 2rem 1rem;
}

.step-number {
font-weight: 700;
position: relative;
z-index: 2;
transition: all var(--transition-normal);
}

.step-item:hover .step-number {
transform: scale(1.1);
box-shadow: var(--shadow-lg);
}

/* Tarjetas de testimonio */
.testimonial-card {
transition: all var(--transition-normal);
border: 1px solid var(--border-primary);
}

.testimonial-card:hover {
transform: translateY(-4px);
box-shadow: var(--shadow-md);
}

.hover-lift {
transition: transform var(--transition-fast);
}

.hover-lift:hover {
transform: translateY(-4px);
}

/* ========================================
PIE DE PÁGINA
======================================== */

.main-footer {
background-color: var(--bg-tertiary);
border-top: 1px solid var(--border-primary);
padding: 4rem 0 2rem;
margin-top: auto;
}

.footer-brand {
display: flex;
align-items: center;
margin-bottom: 1rem;
}

.footer-title {
font-weight: 600;
color: var(--text-primary);
margin-bottom: 1rem;
font-size: 0.875rem;
text-transform: uppercase;
letter-spacing: 0.05em;
}

.footer-links {
list-style: none;
padding: 0;
margin: 0;
}

.footer-links li {
margin-bottom: 0.5rem;
}

.footer-links a {
color: var(--text-muted);
text-decoration: none;
font-size: 0.875rem;
transition: color var(--transition-fast);
}

.footer-links a:hover {
color: var(--primary-500);
}

.social-links {
display: flex;
gap: 1rem;
}

.social-link {
display: flex;
align-items: center;
justify-content: center;
width: 40px;
height: 40px;
border-radius: 50%;
background-color: var(--surface-secondary);
color: var(--text-muted);
text-decoration: none;
transition: all var(--transition-fast);
}

.social-link:hover {
background-color: var(--primary-500);
color: white;
transform: translateY(-2px);
}

.footer-divider {
border-color: var(--border-primary);
margin: 2rem 0 1.5rem;
}

.footer-bottom {
padding-top: 1.5rem;
}

.copyright {
color: var(--text-muted);
font-size: 0.875rem;
}

.footer-meta {
display: flex;
align-items: center;
gap: 0.5rem;
font-size: 0.75rem;
color: var(--text-muted);
}

.separator {
opacity: 0.5;
}

.version {
background: var(--primary-500);
color: white;
padding: 0.125rem 0.375rem;
border-radius: 4px;
font-weight: 500;
}

/* ========================================
BOTÓN "VOLVER ARRIBA"
======================================== */

.back-to-top {
position: fixed;
bottom: 2rem;
right: 2rem;
width: 50px;
height: 50px;
background: var(--primary-500);
color: white;
border: none;
border-radius: 50%;
cursor: pointer;
opacity: 0;
visibility: hidden;
transform: translateY(20px);
transition: all var(--transition-normal);
z-index: 1000;
box-shadow: var(--shadow-lg);
}

.back-to-top.visible {
opacity: 1;
visibility: visible;
transform: translateY(0);
}

.back-to-top:hover {
background: var(--primary-600);
transform: translateY(-4px);
box-shadow: var(--shadow-xl);
}

/* ========================================
OVERLAY DE CARGA
======================================== */

.loading-overlay {
position: fixed;
top: 0;
left: 0;
right: 0;
bottom: 0;
background: rgba(255, 255, 255, 0.9);
display: flex;
align-items: center;
justify-content: center;
z-index: 9999;
opacity: 0;
visibility: hidden;
transition: all var(--transition-normal);
}

[data-theme="dark"] .loading-overlay {
background: rgba(15, 23, 42, 0.9);
}

.loading-overlay.visible {
opacity: 1;
visibility: visible;
}

.loading-spinner {
text-align: center;
}

/* ========================================
BANNER DE COOKIES
======================================== */

.cookie-banner {
position: fixed;
bottom: 0;
left: 0;
right: 0;
background: var(--bg-tertiary);
border-top: 1px solid var(--border-primary);
padding: 1rem 0;
z-index: 1040;
transform: translateY(100%);
transition: transform var(--transition-normal);
}

.cookie-banner.visible {
transform: translateY(0);
}

.cookie-content {
color: var(--text-secondary);
font-size: 0.875rem;
}

.cookie-link {
color: var(--primary-500);
text-decoration: none;
}

.cookie-link:hover {
text-decoration: underline;
}

/* ========================================
DISEÑO RESPONSIVE
======================================== */

@media (max-width: 991.98px) {
.navbar-actions {
flex-wrap: wrap;
}

.hero-section {
padding: 60px 0;
}

.dashboard-mockup {
max-width: 300px;
}
}

@media (max-width: 767.98px) {
.public-layout {
padding-top: 70px;
}

.navbar {
min-height: 70px;
padding: 0.75rem 0;
}

.main-footer {
padding: 2rem 0 1rem;
}

.hero-section {
padding: 40px 0;
}

.hero-section .display-4 {
font-size: 2rem;
}

.hero-section .lead {
font-size: 1rem;
}

.step-item {
padding: 1rem;
}

.step-number {
width: 60px;
height: 60px;
}

.feature-card {
margin-bottom: 1rem;
}

.social-links {
justify-content: center;
}

.footer-meta {
flex-direction: column;
gap: 0.25rem;
}

.back-to-top {
bottom: 1rem;
right: 1rem;
width: 44px;
height: 44px;
}

.cookie-banner .row {
flex-direction: column;
gap: 1rem;
text-align: center;
}
}

@media (max-width: 575.98px) {
.navbar-actions {
gap: 0.25rem;
}

.navbar-actions .btn {
padding: 0.5rem 0.75rem;
font-size: 0.75rem;
}

.brand-text {
font-size: 1.25rem;
}

.hero-section .btn {
width: 100%;
margin-bottom: 0.5rem;
}

.hero-section .d-flex {
flex-direction: column;
}

.dashboard-mockup {
max-width: 250px;
}
}

/* ========================================
ELEMENTOS DE FORMULARIO
======================================== */

.form-control {
background-color: var(--input-bg);
border: 1px solid var(--input-border);
color: var(--text-primary);
border-radius: var(--border-radius);
padding: 0.75rem 1rem;
transition: all var(--transition-fast);
}

.form-control:focus {
background-color: var(--bg-primary);
border-color: var(--input-focus-border);
box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
outline: none;
}

.form-control::placeholder {
color: var(--input-placeholder);
}

.form-label {
color: var(--text-secondary);
font-weight: 500;
margin-bottom: 0.5rem;
}

/* ========================================
BOTONES (ESTILOS COMPLETOS)
======================================== */

.btn {
font-weight: 500;
border-radius: var(--border-radius);
padding: 0.75rem 1.5rem;
transition: all var(--transition-fast);
border: 1px solid transparent;
text-decoration: none;
display: inline-flex;
align-items: center;
justify-content: center;
gap: 0.5rem;
cursor: pointer;
}

.btn:hover {
transform: translateY(-1px);
box-shadow: var(--shadow-md);
}

.btn:active {
transform: translateY(0);
}

.btn-primary {
background: var(--primary-500);
border-color: var(--primary-500);
color: white;
}

.btn-primary:hover {
background: var(--primary-600);
border-color: var(--primary-600);
color: white;
}

.btn-outline-primary {
background: transparent;
border-color: var(--primary-500);
color: var(--primary-500);
}

.btn-outline-primary:hover {
background: var(--primary-500);
border-color: var(--primary-500);
color: white;
}

.btn-light {
background: var(--surface-primary);
border-color: var(--border-primary);
color: var(--text-primary);
}

.btn-light:hover {
background: var(--surface-hover);
border-color: var(--border-secondary);
color: var(--text-primary);
}

.btn-outline-light {
background: transparent;
border-color: rgba(255, 255, 255, 0.3);
color: rgba(255, 255, 255, 0.9);
}

.btn-outline-light:hover {
background: rgba(255, 255, 255, 0.1);
border-color: rgba(255, 255, 255, 0.5);
color: white;
}

.btn-lg {
padding: 1rem 2rem;
font-size: 1rem;
}

.btn-sm {
padding: 0.5rem 1rem;
font-size: 0.875rem;
}

/* ========================================
BADGES
======================================== */

.badge {
font-size: 0.75rem;
font-weight: 600;
padding: 0.375rem 0.75rem;
border-radius: var(--border-radius-sm);
}

.bg-primary {
background-color: var(--primary-500) !important;
}

.bg-success {
background-color: var(--success-500) !important;
}

.bg-warning {
background-color: var(--warning-500) !important;
}

.bg-danger {
background-color: var(--error-500) !important;
}

.bg-light {
background-color: var(--surface-secondary) !important;
color: var(--text-primary) !important;
}

.bg-opacity-10 {
opacity: 0.1;
}

/* ========================================
TARJETAS
======================================== */

.card {
background: var(--surface-primary);
border: 1px solid var(--border-primary);
border-radius: var(--border-radius);
box-shadow: var(--shadow-sm);
transition: all var(--transition-fast);
}

.card:hover {
box-shadow: var(--shadow-md);
}

.card-header {
background: var(--surface-secondary);
border-bottom: 1px solid var(--border-primary);
padding: 1rem 1.5rem;
border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.card-body {
padding: 1.5rem;
}

.card-footer {
background: var(--surface-secondary);
border-top: 1px solid var(--border-primary);
padding: 1rem 1.5rem;
border-radius: 0 0 var(--border-radius) var(--border-radius);
}

/* ========================================
ALERTAS
======================================== */

.alert {
padding: 1rem 1.5rem;
border-radius: var(--border-radius);
border: 1px solid transparent;
margin-bottom: 1rem;
}

.alert-primary {
background: rgba(0, 102, 255, 0.1);
border-color: rgba(0, 102, 255, 0.2);
color: var(--primary-600);
}

.alert-success {
background: rgba(16, 185, 129, 0.1);
border-color: rgba(16, 185, 129, 0.2);
color: var(--success-500);
}

.alert-warning {
background: rgba(245, 158, 11, 0.1);
border-color: rgba(245, 158, 11, 0.2);
color: var(--warning-500);
}

.alert-danger {
background: rgba(239, 68, 68, 0.1);
border-color: rgba(239, 68, 68, 0.2);
color: var(--error-500);
}

/* ========================================
ANIMACIONES
======================================== */

@keyframes pulse {
0%, 100% {
opacity: 1;
}
50% {
opacity: 0.5;
}
}

@keyframes spin {
from {
transform: rotate(0deg);
}
to {
transform: rotate(360deg);
}
}

@keyframes bounce {
0%, 20%, 53%, 80%, 100% {
transform: translate3d(0, 0, 0);
}
40%, 43% {
transform: translate3d(0, -30px, 0);
}
70% {
transform: translate3d(0, -15px, 0);
}
90% {
transform: translate3d(0, -4px, 0);
}
}

.animate-pulse {
animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-spin {
animation: spin 1s linear infinite;
}

.animate-bounce {
animation: bounce 1s infinite;
}

/* ========================================
CLASES UTILITARIAS
======================================== */

.text-gradient {
background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
}

.glass-effect {
background: rgba(255, 255, 255, 0.1);
backdrop-filter: blur(10px);
-webkit-backdrop-filter: blur(10px);
border: 1px solid rgba(255, 255, 255, 0.2);
}

.hover-lift {
transition: transform var(--transition-fast);
}

.hover-lift:hover {
transform: translateY(-2px);
}

.text-primary {
color: var(--primary-500) !important;
}

.text-secondary {
color: var(--text-secondary) !important;
}

.text-muted {
color: var(--text-muted) !important;
}

.text-success {
color: var(--success-500) !important;
}

.text-warning {
color: var(--warning-500) !important;
}

.text-danger {
color: var(--error-500) !important;
}

/* ========================================
ESTADOS DE FOCO PARA ACCESIBILIDAD
======================================== */

.nav-link:focus,
.btn:focus,
.btn-ghost:focus,
.form-control:focus {
outline: 2px solid var(--primary-500);
outline-offset: 2px;
}

/* ========================================
ESTILOS PARA IMPRESIÓN
======================================== */

@media print {
.main-header,
.main-footer,
.back-to-top,
.cookie-banner {
display: none !important;
}

.public-layout {
padding-top: 0 !important;
}

.main-content {
min-height: auto !important;
}

* {
background: white !important;
color: black !important;
box-shadow: none !important;
}
}

/* ========================================
DESPLAZAMIENTO SUAVE
======================================== */

@media (prefers-reduced-motion: no-preference) {
html {
scroll-behavior: smooth;
}
}

/* ========================================
MODO ALTO CONTRASTE
======================================== */

@media (prefers-contrast: high) {
:root {
--border-primary: #000000;
--text-primary: #000000;
--text-secondary: #333333;
}

[data-theme="dark"] {
--border-primary: #ffffff;
--text-primary: #ffffff;
--text-secondary: #cccccc;
}
}

/* ========================================
REDUCCIÓN DE MOVIMIENTO
======================================== */

@media (prefers-reduced-motion: reduce) {
*,
*::before,
*::after {
animation-duration: 0.01ms !important;
animation-iteration-count: 1 !important;
transition-duration: 0.01ms !important;
scroll-behavior: auto !important;
}
}

/* ========================================
COMPONENTES ADICIONALES
======================================== */

/* Notificación Toast */
.notification-toast {
animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
from {
transform: translateX(100%);
opacity: 0;
}
to {
transform: translateX(0);
opacity: 1;
}
}

/* Sugerencias de búsqueda */
.search-suggestions {
position: absolute;
top: 100%;
left: 0;
right: 0;
background: var(--surface-primary);
border: 1px solid var(--border-primary);
border-radius: var(--border-radius);
box-shadow: var(--shadow-lg);
max-height: 300px;
overflow-y: auto;
z-index: 1060;
}

.search-suggestion-item {
padding: 0.75rem 1rem;
cursor: pointer;
transition: background-color var(--transition-fast);
border-bottom: 1px solid var(--border-primary);
}

.search-suggestion-item:hover {
background-color: var(--surface-hover);
}

.search-suggestion-item:last-child {
border-bottom: none;
}

/* Barra de progreso */
.progress-bar-custom {
height: 4px;
background: var(--primary-500);
border-radius: 2px;
transition: width var(--transition-normal);
}

/* Indicadores de estado */
.status-online {
color: var(--success-500);
}

.status-offline {
color: var(--text-muted);
}

.status-busy {
color: var(--warning-500);
}

.status-away {
color: var(--accent-500);
}

/* Esqueleto de carga */
.skeleton {
background: linear-gradient(90deg, var(--surface-secondary) 25%, var(--surface-hover) 50%, var(--surface-secondary) 75%);
background-size: 200% 100%;
animation: skeleton-loading 1.5s infinite;
border-radius: var(--border-radius-sm);
}

@keyframes skeleton-loading {
0% {
background-position: 200% 0;
}
100% {
background-position: -200% 0;
}
}

/* Tooltip personalizado */
.tooltip-custom {
position: absolute;
background: var(--bg-primary);
color: var(--text-primary);
padding: 0.5rem 0.75rem;
border-radius: var(--border-radius-sm);
font-size: 0.75rem;
box-shadow: var(--shadow-lg);
border: 1px solid var(--border-primary);
z-index: 1070;
opacity: 0;
visibility: hidden;
transition: all var(--transition-fast);
pointer-events: none;
}

.tooltip-custom.visible {
opacity: 1;
visibility: visible;
}

/* Bloques de código */
.code-block {
background: var(--surface-secondary);
border: 1px solid var(--border-primary);
border-radius: var(--border-radius);
padding: 1rem;
font-family: var(--font-mono);
font-size: 0.875rem;
overflow-x: auto;
color: var(--text-primary);
}

.code-inline {
background: var(--surface-secondary);
padding: 0.125rem 0.375rem;
border-radius: 4px;
font-family: var(--font-mono);
font-size: 0.85em;
color: var(--primary-600);
border: 1px solid var(--border-primary);
}

/* Indicadores de scroll */
.scroll-indicator {
position: fixed;
top: var(--header-height);
left: 0;
width: 100%;
height: 3px;
background: var(--surface-secondary);
z-index: 1040;
}

.scroll-progress {
height: 100%;
background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
transition: width 0.1s ease;
}

/* Barra de scroll personalizada */
::-webkit-scrollbar {
width: 8px;
height: 8px;
}

::-webkit-scrollbar-track {
background: var(--surface-secondary);
}

::-webkit-scrollbar-thumb {
background: var(--border-secondary);
border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
background: var(--text-muted);
}

/* Barra de scroll para Firefox */
* {
scrollbar-width: thin;
scrollbar-color: var(--border-secondary) var(--surface-secondary);
}

/* Selección de texto */
::selection {
background: rgba(0, 102, 255, 0.2);
color: var(--text-primary);
}

::-moz-selection {
background: rgba(0, 102, 255, 0.2);
color: var(--text-primary);
}

<!---------------------------------------------------- js -->
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
        const themeButton = document.querySelector('.btn-ghost i');
        if (!themeButton) return;

        // Iconos para cada tema
        const iconos = {
            'light': 'fas fa-sun',
            'dark': 'fas fa-moon',
            'system': 'fas fa-adjust'
        };

        // Actualizar el icono del botón
        const nuevoIcono = iconos[this.theme] || iconos.system;
        themeButton.className = nuevoIcono;

        // Actualizar el título del botón
        const themeButtonElement = themeButton.parentElement;
        if (themeButtonElement) {
            const tituloTexto = {
                'light': 'Tema claro activo - Cambiar tema',
                'dark': 'Tema oscuro activo - Cambiar tema',
                'system': 'Tema del sistema activo - Cambiar tema'
            };
            themeButtonElement.title = tituloTexto[this.theme];
        }
    }

    updateThemeToggle() {
        const themeOptions = document.querySelectorAll('.theme-option');
        themeOptions.forEach(option => {
            option.classList.toggle('active', option.dataset.theme === this.theme);
        });
    }

    bindEvents() {
        // Theme option clicks in dropdown
        document.addEventListener('click', (e) => {
            if (e.target.closest('.theme-option')) {
                const theme = e.target.closest('.theme-option').dataset.theme;
                this.applyTheme(theme);
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
        // Navbar toggle for public layout
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');

        if (navbarToggler && navbarCollapse) {
            navbarToggler.addEventListener('click', () => {
                navbarCollapse.classList.toggle('show');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.navbar') && navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
            });
        }
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