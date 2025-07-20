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