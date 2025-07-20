<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio en Mantenimiento | <?= htmlspecialchars($app_name ?? 'Frameworkito') ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="El sitio está temporalmente en mantenimiento. Volveremos pronto con mejoras.">

    <!-- Bootstrap 5 CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #8b5cf6;
            --secondary: #06b6d4;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --success: #10b981;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text-dark);
            position: relative;
            overflow-x: hidden;
            /* Solo ocultar scroll horizontal */
        }

        .maintenance-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 2;
        }

        .maintenance-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 900px;
            /* Aumentado de 600px a 900px */
            width: 100%;
            position: relative;
            overflow: hidden;
            /* Esto cortará la línea en las esquinas */
            margin: 2rem auto;
            /* Asegurar margen vertical */
        }

        .maintenance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 20px 20px 0 0;
        }

        .maintenance-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 3rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .maintenance-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .maintenance-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .progress-section {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .progress-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .progress-bar-container {
            background: #e2e8f0;
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 10px;
            width: 75%;
            animation: progress 3s ease-in-out infinite;
        }

        @keyframes progress {

            0%,
            100% {
                width: 75%;
            }

            50% {
                width: 85%;
            }
        }

        .progress-text {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .countdown-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .countdown-item {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1rem 0.5rem;
            text-align: center;
        }

        .countdown-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            display: block;
        }

        .countdown-label {
            font-size: 0.8rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .feature-item {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.2rem;
        }

        .feature-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .contact-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .contact-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .contact-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .contact-item i {
            color: white;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .newsletter-section {
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }

        .newsletter-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .newsletter-form {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .newsletter-input {
            padding: 0.75rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .newsletter-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .newsletter-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 80px;
            height: 80px;
            top: 70%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 20%;
            right: 10%;
            animation-delay: 6s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-30px) rotate(5deg);
            }

            66% {
                transform: translateY(15px) rotate(-5deg);
            }
        }

        /* Responsive Design */
        @media (min-width: 576px) {
            .maintenance-container {
                padding: 3rem 2rem;
            }

            .maintenance-card {
                padding: 3rem 2.5rem;
            }

            .maintenance-title {
                font-size: 2.5rem;
            }

            .newsletter-form {
                flex-direction: row;
            }

            .newsletter-input {
                flex: 1;
            }
        }

        @media (min-width: 768px) {
            .maintenance-container {
                padding: 4rem 2rem;
            }

            .maintenance-card {
                padding: 4rem 3rem;
                max-width: 900px;
                /* Más ancho en tablets */
            }

            .maintenance-title {
                font-size: 3rem;
            }

            .countdown-section {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 992px) {
            .maintenance-card {
                max-width: 900px;
                /* Aún más ancho en desktop */
            }

            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1200px) {
            .maintenance-card {
                max-width: 900px;
                /* Máximo ancho en pantallas grandes */
                padding: 5rem 4rem;
            }
        }

        /* Reducir animaciones si el usuario lo prefiere */
        @media (prefers-reduced-motion: reduce) {

            .maintenance-icon,
            .progress-bar,
            .shape {
                animation: none;
            }
        }

        /* Estados de carga */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Maintenance Container -->
    <div class="maintenance-container">
        <div class="maintenance-card">
            <!-- Maintenance Icon -->
            <div class="maintenance-icon">
                <i class="fas fa-tools"></i>
            </div>

            <!-- Main Content -->
            <h1 class="maintenance-title">Estamos Mejorando</h1>
            <p class="maintenance-subtitle">
                Nuestro sitio está temporalmente fuera de línea por mantenimiento programado.
                Estamos trabajando en mejoras emocionantes que estarán disponibles muy pronto.
            </p>

            <!-- Progress Section -->
            <div class="progress-section">
                <div class="progress-title">
                    <i class="fas fa-cog me-2"></i>
                    Progreso del Mantenimiento
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar"></div>
                </div>
                <div class="progress-text">Aproximadamente 75% completado</div>
            </div>

            <!-- Countdown Timer -->
            <div class="countdown-section" id="countdown">
                <div class="countdown-item">
                    <span class="countdown-number" id="hours">--</span>
                    <div class="countdown-label">Horas</div>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="minutes">--</span>
                    <div class="countdown-label">Minutos</div>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="seconds">--</span>
                    <div class="countdown-label">Segundos</div>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number">
                        <i class="fas fa-clock"></i>
                    </span>
                    <div class="countdown-label">Estimado</div>
                </div>
            </div>

            <!-- Features Coming Soon -->
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-title">Seguridad Mejorada</div>
                    <div class="feature-desc">Nuevas medidas de protección y autenticación</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="feature-title">Mayor Velocidad</div>
                    <div class="feature-desc">Optimizaciones para un rendimiento superior</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="feature-title">Mejor Experiencia</div>
                    <div class="feature-desc">Interfaz renovada y más intuitiva</div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <div class="contact-title">
                    <i class="fas fa-headset me-2"></i>
                    ¿Necesitas Ayuda?
                </div>
                <div class="contact-methods">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>soporte@ejemplo.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+1 (555) 123-4567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-comments"></i>
                        <span>Chat en vivo disponible</span>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- Newsletter Subscription -->
            <div class="newsletter-section">
                <div class="newsletter-title">
                    <i class="fas fa-bell me-2"></i>
                    Recibe una Notificación
                </div>
                <form class="newsletter-form" id="notificationForm">
                    <input
                        type="email"
                        class="newsletter-input"
                        placeholder="Tu email"
                        required
                        aria-label="Email para notificaciones">
                    <button type="submit" class="newsletter-btn">
                        <i class="fas fa-paper-plane me-2"></i>
                        Notificarme
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Configuración de countdown (tiempo estimado de finalización)
        const maintenanceEndTime = new Date();
        maintenanceEndTime.setHours(maintenanceEndTime.getHours() + 4); // 4 horas desde ahora

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = maintenanceEndTime.getTime() - now;

            if (distance > 0) {
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            } else {
                // El mantenimiento ha terminado
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';

                // Opcional: recargar la página automáticamente
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }

        // Actualizar countdown cada segundo
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Manejar formulario de notificaciones
        document.getElementById('notificationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button');
            const originalText = button.innerHTML;

            // Mostrar estado de carga
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
            button.disabled = true;

            // Simular envío (aquí harías la llamada real a tu API)
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check me-2"></i>¡Registrado!';
                button.style.background = 'var(--success)';

                // Limpiar formulario
                this.querySelector('input[type="email"]').value = '';

                // Restaurar botón después de 3 segundos
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = 'var(--primary)';
                    button.disabled = false;
                }, 3000);

                // Log para analytics
                console.log('Newsletter subscription:', email);
            }, 2000);
        });

        // Auto-refresh cada 5 minutos para comprobar si el mantenimiento ha terminado
        setInterval(() => {
            fetch(window.location.href, {
                    method: 'HEAD'
                })
                .then(response => {
                    if (response.status !== 503) {
                        window.location.reload();
                    }
                })
                .catch(() => {
                    // Error de red, no hacer nada
                });
        }, 300000); // 5 minutos

        // Log de analytics
        console.log('Maintenance page loaded:', {
            timestamp: new Date().toISOString(),
            estimatedEnd: maintenanceEndTime.toISOString(),
            userAgent: navigator.userAgent
        });

        // Mejorar accesibilidad con teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Opcional: cerrar modales o reset focus
                document.activeElement.blur();
            }
        });

        // Detectar cuando el usuario regresa a la pestaña
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Usuario regresó, actualizar countdown inmediatamente
                updateCountdown();
            }
        });
    </script>
</body>

</html>