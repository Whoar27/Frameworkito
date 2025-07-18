<?php
// Variables para la vista home
$title = 'Inicio';
$description = 'Sistema de autenticación profesional con PHP - Seguro, rápido y fácil de implementar';
$currentPage = 'home';

// Datos para las estadísticas del hero
$stats = [
    [
        'icon' => 'fas fa-users',
        'number' => '1,000+',
        'label' => 'Desarrolladores'
    ],
    [
        'icon' => 'fas fa-shield-alt',
        'number' => '99.9%',
        'label' => 'Uptime'
    ],
    [
        'icon' => 'fas fa-rocket',
        'number' => '500+',
        'label' => 'Proyectos'
    ],
    [
        'icon' => 'fas fa-star',
        'number' => '5.0',
        'label' => 'Rating'
    ]
];

// Datos para las características
$features = [
    [
        'icon' => 'fas fa-shield-alt',
        'title' => 'Seguridad Avanzada',
        'description' => 'Encriptación robusta, protección CSRF, validación de entrada y prevención de ataques comunes.'
    ],
    [
        'icon' => 'fas fa-users-cog',
        'title' => 'Gestión de Roles',
        'description' => 'Sistema completo de roles y permisos con control granular de acceso a funcionalidades.'
    ],
    [
        'icon' => 'fas fa-mobile-alt',
        'title' => 'Autenticación 2FA',
        'description' => 'Doble factor de autenticación opcional para mayor seguridad en cuentas críticas.'
    ],
    [
        'icon' => 'fas fa-database',
        'title' => 'Logs de Auditoría',
        'description' => 'Registro detallado de todas las actividades de usuarios para auditoría y seguridad.'
    ],
    [
        'icon' => 'fas fa-key',
        'title' => 'Recuperación Segura',
        'description' => 'Sistema de recuperación de contraseñas con tokens seguros y expiración automática.'
    ],
    [
        'icon' => 'fas fa-tachometer-alt',
        'title' => 'Alto Rendimiento',
        'description' => 'Optimizado para velocidad con caché inteligente y consultas de base de datos eficientes.'
    ],
    [
        'icon' => 'fas fa-plug',
        'title' => 'Fácil Integración',
        'description' => 'API simple y documentada que se integra perfectamente con cualquier proyecto PHP.'
    ],
    [
        'icon' => 'fas fa-cogs',
        'title' => 'Totalmente Configurable',
        'description' => 'Personaliza cada aspecto del sistema según las necesidades de tu proyecto.'
    ],
    [
        'icon' => 'fas fa-life-ring',
        'title' => 'Soporte Completo',
        'description' => 'Documentación detallada, ejemplos de código y soporte técnico para implementación.'
    ]
];
?>

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
                        AuthManager Base te proporciona todo lo que necesitas para implementar
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
                    Únete a cientos de desarrolladores que ya confían en AuthManager Base para sus proyectos.
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
                    Testimonios reales de desarrolladores que han usado AuthManager Base en sus proyectos.
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