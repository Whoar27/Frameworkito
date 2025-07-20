<!DOCTYPE html>
<html lang="<?= config('app.locale', 'es') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= e($page_title ?? config('app.name', 'Frameworkito')) ?></title>
    <meta name="description" content="<?= e($meta_description ?? 'Sistema de autenticación profesional') ?>">
    <meta name="keywords" content="login, registro, autenticación, seguridad">
    <meta name="author" content="<?= e(config('app.name', 'Frameworkito')) ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= e($page_title ?? config('app.name', 'Frameworkito')) ?>">
    <meta property="og:description" content="<?= e($meta_description ?? 'Sistema de autenticación profesional') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('assets/img/favicon.ico') ?>">
    
    <!-- CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Auth CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/auth.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/components.css') ?>">
    
    <!-- Additional CSS -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ((array) $additional_css as $css): ?>
            <link rel="stylesheet" href="<?= e($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Loading overlay for transitions -->
    <div id="pageLoader" class="d-none">
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
             style="background: rgba(255,255,255,0.9); z-index: 9999;">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                <p>Cargando...</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="<?= asset('assets/vendors/bootstrap/5.3.7/js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Custom Auth JS - TODO UNIFICADO AQUÍ -->
    <script src="<?= asset('assets/js/auth.js') ?>"></script>
    
    <!-- Additional JS -->
    <?php if (isset($additional_js)): ?>
        <?php foreach ((array) $additional_js as $js): ?>
            <script src="<?= e($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Auth Page Initialization Script -->
    <script>
        // Auto-inicialización basada en la página actual
        document.addEventListener('DOMContentLoaded', function() {
            // Detectar tipo de página por URL o elementos presentes
            const currentPath = window.location.pathname;
            const pageConfig = {
                redirectTo: '<?= e($redirect_to ?? '/home') ?>',
                debug: <?= config('app.debug', false) ? 'true' : 'false' ?>,
                csrfToken: Frameworkito.getCsrfToken()
            };

            // Inicializar según la página
            if (currentPath.includes('/login') || document.getElementById('loginForm')) {
                AuthForms.initLogin(pageConfig);
            } else if (currentPath.includes('/register') || document.getElementById('registerForm')) {
                AuthForms.initRegister(pageConfig);
            } else if (currentPath.includes('/forgot-password') || document.getElementById('forgotForm')) {
                AuthForms.initForgotPassword(pageConfig);
            } else if (currentPath.includes('/reset-password') || document.getElementById('resetForm')) {
                // Obtener tokens de la URL para reset
                const urlParams = new URLSearchParams(window.location.search);
                pageConfig.selector = urlParams.get('s') || '';
                pageConfig.token = urlParams.get('t') || '';
                AuthForms.initResetPassword(pageConfig);
            } else if (currentPath.includes('/verify-email') || document.getElementById('verificationContent')) {
                // Obtener tokens de la URL para verificación
                const urlParams = new URLSearchParams(window.location.search);
                pageConfig.selector = urlParams.get('s') || '';
                pageConfig.token = urlParams.get('t') || '';
                AuthForms.initVerifyEmail(pageConfig);
            }

            // Log de debug si está habilitado
            if (pageConfig.debug) {
                console.log('🔐 Frameworkito - Layout auth.php');
                console.log('📱 Página actual:', currentPath);
                console.log('🎯 Configuración:', pageConfig);
            }
        });
    </script>

    <!-- Environment-specific scripts -->
    <?php if (config('app.debug', false)): ?>
        <script>
            console.log('🔐 Frameworkito - Modo Desarrollo');
            console.log('📱 Layout: auth.php');
            console.log('🎯 Página:', '<?= e($page_title ?? "Sin título") ?>');
            console.log('🌐 URL:', window.location.href);
        </script>
    <?php endif; ?>
</body>
</html>