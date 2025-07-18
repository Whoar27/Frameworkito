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
                        <?= htmlspecialchars($meta_description ?? 'Encuentra respuestas rápidas a las preguntas más comunes sobre AuthManager Base.') ?>
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
                                    ¿Qué es AuthManager Base?
                                </button>
                            </h2>
                            <div id="general1" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    AuthManager Base es un sistema de autenticación reutilizable desarrollado en PHP vanilla que implementa el patrón MVC. Está diseñado para ser una base sólida que puedes usar en múltiples proyectos, ya sean sitios web corporativos o sistemas de gestión internos.
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
                                    Sí, AuthManager Base está disponible bajo la licencia MIT, lo que significa que puedes usarlo, modificarlo y distribuirlo libremente, incluso en proyectos comerciales.
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
                                    ¿Cómo instalo AuthManager Base?
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