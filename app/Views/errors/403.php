<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Prohibido | <?= htmlspecialchars($app_name ?? 'AuthManager Base') ?></title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Bootstrap 5 CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #dc2626;
            --primary-light: #ef4444;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --bg-light: #fef2f2;
            --white: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
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

        .btn-secondary {
            background: var(--warning);
            color: white;
        }

        .btn-secondary:hover {
            background: #d97706;
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

        .alert-box {
            background: #fef3cd;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #92400e;
        }

        .alert-box i {
            color: var(--warning);
            margin-right: 0.5rem;
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
                <i class="fas fa-ban"></i>
            </div>
            
            <!-- Error Number -->
            <div class="error-number">403</div>
            
            <!-- Error Title -->
            <h1 class="error-title">Acceso Prohibido</h1>
            
            <!-- Error Description -->
            <p class="error-description">
                No tienes permisos para acceder a esta página. 
                Puede que necesites iniciar sesión o que tu cuenta no tenga los privilegios necesarios.
            </p>

            <!-- Alert Box -->
            <div class="alert-box">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Acceso restringido:</strong> Esta sección requiere permisos especiales.
            </div>

            <!-- Suggestions -->
            <div class="suggestions">
                <h6>¿Qué puedes hacer?</h6>
                <div class="suggestion-item">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Iniciar sesión con una cuenta autorizada</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-user-check"></i>
                    <span>Verificar que tu cuenta tenga los permisos correctos</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-envelope"></i>
                    <span>Contactar al administrador para solicitar acceso</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-home"></i>
                    <span>Regresar a una zona permitida</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="buttons">
                <a href="/login" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
                <a href="/" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Ir al Inicio
                </a>
                <a href="javascript:history.back()" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Página Anterior
                </a>
            </div>

            <!-- Footer Info -->
            <div class="footer-info">
                <i class="fas fa-shield-alt me-1"></i>
                ¿Problemas de acceso? 
                <a href="/contact">Contacta soporte</a> o revisa tu <a href="/profile">perfil</a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Log 403 para analytics y seguridad
        if (typeof console !== 'undefined') {
            console.log('403 Access Denied:', {
                page: window.location.pathname,
                referrer: document.referrer || 'direct',
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent
            });
        }

        // Navegación con teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                history.back();
            }
            if (e.key === 'Enter' && e.target.classList.contains('btn-primary')) {
                window.location.href = '/login';
            }
        });
    </script>
</body>
</html>