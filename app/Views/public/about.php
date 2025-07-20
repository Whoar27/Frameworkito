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