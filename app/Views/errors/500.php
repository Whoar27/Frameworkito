<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del Servidor | <?= htmlspecialchars($app_name ?? 'AuthManager Base') ?></title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Bootstrap 5 CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #f59e0b;
            --primary-light: #fbbf24;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --bg-light: #fffbeb;
            --white: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --danger: #dc2626;
            --success: #059669;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            min-height: 100vh;
            color: var(--text-dark);
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .error-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .error-number {
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .error-description {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .suggestions {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .suggestions h6 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .suggestion-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .suggestion-item:last-child {
            margin-bottom: 0;
        }

        .suggestion-item i {
            color: var(--primary);
            width: 18px;
            margin-right: 0.75rem;
            font-size: 0.85rem;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-light);
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--text-light);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
            color: white;
            transform: translateY(-1px);
        }

        .back-link {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            z-index: 10;
        }

        .back-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .footer-info {
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .footer-info a {
            color: var(--primary);
            text-decoration: none;
        }

        .footer-info a:hover {
            text-decoration: underline;
        }

        .status-info {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            text-align: left;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .status-item:last-child {
            margin-bottom: 0;
        }

        .status-label {
            color: var(--text-light);
        }

        .status-value {
            font-weight: 500;
        }

        .error-id {
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Animación suave */
        .error-card {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (min-width: 576px) {
            body {
                padding: 2rem;
            }

            .error-card {
                padding: 3rem 2.5rem;
            }

            .error-number {
                font-size: 5rem;
            }

            .error-title {
                font-size: 2rem;
            }

            .error-description {
                font-size: 1.1rem;
            }

            .buttons {
                flex-direction: row;
                justify-content: center;
            }

            .btn {
                font-size: 1rem;
                padding: 1rem 2rem;
            }
        }

        @media (min-width: 768px) {
            .error-container {
                max-width: 800px;
            }

            .error-card {
                padding: 4rem 3rem;
            }

            .error-number {
                font-size: 6rem;
            }

            .error-title {
                font-size: 2.25rem;
            }

            .back-link {
                top: 2rem;
                left: 2rem;
                font-size: 1rem;
            }
        }

        @media (min-width: 992px) {
            .error-number {
                font-size: 7rem;
            }

            .error-title {
                font-size: 2.5rem;
            }

            .error-description {
                font-size: 1.2rem;
            }
        }

        /* Estados de enfoque para accesibilidad */
        .btn:focus,
        .back-link:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Reducir movimiento para usuarios que lo prefieren */
        @media (prefers-reduced-motion: reduce) {
            .error-card {
                animation: none;
            }

            .btn:hover,
            .btn-outline:hover,
            .back-link:hover {
                transform: none;
            }
        }
    </style>
</head>

<body>

    <!-- Error Container -->
    <div class="error-container">
        <div class="error-card">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="fas fa-server"></i>
            </div>

            <!-- Error Number -->
            <div class="error-number">500</div>

            <!-- Error Title -->
            <h1 class="error-title">Error del Servidor</h1>

            <!-- Error Description -->
            <p class="error-description">
                Ha ocurrido un error interno en el servidor.
                Nuestro equipo técnico ha sido notificado y está trabajando para solucionarlo.
            </p>

            <!-- Status Info -->
            <div class="status-info">
                <div class="status-item">
                    <span class="status-label">Código de Error:</span>
                    <span class="status-value">HTTP 500</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Tiempo:</span>
                    <span class="status-value"><?= date('Y-m-d H:i:s') ?></span>
                </div>
                <div class="status-item">
                    <span class="status-label">ID de Solicitud:</span>
                    <span class="status-value error-id"><?= substr(md5(uniqid()), 0, 8) ?></span>
                </div>
            </div>

            <!-- Suggestions -->
            <div class="suggestions">
                <h6>¿Qué puedes hacer?</h6>
                <div class="suggestion-item">
                    <i class="fas fa-redo"></i>
                    <span>Recargar la página en unos minutos</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-clock"></i>
                    <span>Esperar a que se resuelva el problema técnico</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-bug"></i>
                    <span>Reportar el error si persiste</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-home"></i>
                    <span>Regresar a la página principal</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="buttons">
                <a href="javascript:location.reload()" class="btn btn-primary">
                    <i class="fas fa-redo"></i>
                    Recargar Página
                </a>
                <a href="/" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    Ir al Inicio
                </a>
                <a href="/contact" class="btn btn-danger">
                    <i class="fas fa-bug"></i>
                    Reportar Error
                </a>
            </div>

            <!-- Footer Info -->
            <div class="footer-info">
                <i class="fas fa-tools me-1"></i>
                Error persistente? <a href="/contact">Contacta soporte técnico</a>
                o consulta nuestro <a href="/status">estado del sistema</a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Log 500 para analytics y monitoreo
        if (typeof console !== 'undefined') {
            console.error('500 Server Error:', {
                page: window.location.pathname,
                referrer: document.referrer || 'direct',
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent,
                errorId: '<?= substr(md5(uniqid()), 0, 8) ?>'
            });
        }

        // Auto-reload después de 30 segundos (opcional)
        let autoReloadTimer;

        function startAutoReload() {
            autoReloadTimer = setTimeout(function() {
                if (confirm('¿Quieres recargar la página automáticamente para ver si el problema se ha solucionado?')) {
                    location.reload();
                }
            }, 30000);
        }

        // Iniciar timer al cargar la página
        startAutoReload();

        // Limpiar timer si el usuario interactúa
        document.addEventListener('click', function() {
            if (autoReloadTimer) {
                clearTimeout(autoReloadTimer);
                autoReloadTimer = null;
            }
        });

        // Navegación con teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                history.back();
            }
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                // Permitir recarga normal
                return true;
            }
        });

        // Enviar error a sistema de monitoreo (si está configurado)
        function reportError() {
            // Aquí puedes integrar con servicios como Sentry, Bugsnag, etc.
            const errorData = {
                type: 'server_error',
                code: 500,
                page: window.location.pathname,
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent,
                referrer: document.referrer || 'direct',
                errorId: '<?= substr(md5(uniqid()), 0, 8) ?>',
                sessionId: sessionStorage.getItem('session_id') || 'anonymous'
            };

            // Ejemplo de integración con servicio de monitoreo
            // fetch('/api/errors/report', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //     },
            //     body: JSON.stringify(errorData)
            // }).catch(err => console.log('Error reporting failed:', err));

            console.log('Error reported:', errorData);
        }

        // Llamar función de reporte automáticamente
        reportError();

        // Detectar si el usuario regresa después de un tiempo (posible solución)
        window.addEventListener('focus', function() {
            const now = Date.now();
            const lastError = localStorage.getItem('last_500_error');

            if (lastError && (now - parseInt(lastError)) > 60000) { // 1 minuto
                // Sugerir recargar si ha pasado tiempo
                const reloadBtn = document.querySelector('.btn-primary');
                if (reloadBtn) {
                    reloadBtn.style.animation = 'pulse 1s infinite';
                    reloadBtn.innerHTML = '<i class="fas fa-redo"></i> ¡Intentar Ahora!';
                }
            }
        });

        // Guardar timestamp del error
        localStorage.setItem('last_500_error', Date.now().toString());

        // Agregar clase para animación de pulse
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>