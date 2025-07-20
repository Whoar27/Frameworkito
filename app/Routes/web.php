<?php

/**
 * Rutas Web - Frameworkito
 * 
 * Define todas las rutas web de la aplicación
 * Syntax: $method($uri, $handler)
 */

// ====================================================================
// RUTAS PÚBLICAS - WEBSITE
// ====================================================================

// Página principal - Landing Page
$get('/', 'PublicController@landing');

// Páginas informativas públicas
$get('/about', 'PublicController@about');
$get('/contact', 'PublicController@contact');
$post('/contact', 'PublicController@contactSubmit');
$get('/faq', 'PublicController@faq');
$get('/privacy', 'PublicController@privacy');
$get('/terms', 'PublicController@terms');

// ====================================================================
// RUTAS PÚBLICAS - DOCUMENTACIÓN
// ====================================================================
// Página índice de documentación
$get('/doc', 'PublicController@documentation');

// Ruta catch-all para cualquier cosa que empiece con /doc/ (antes del cierre del archivo)
if (strpos($_SERVER['REQUEST_URI'], '/doc/') === 0) {
    $controller = new \App\Controllers\PublicController();
    $controller->docViewer(); // Esto se ejecuta directo sin pasar por el router
    exit;
}

// ====================================================================
// RUTAS PÚBLICAS - API
// ====================================================================
// API Status público
$get('/status', 'PublicController@status');

// ====================================================================
// RUTAS DE AUTENTICACIÓN - HABILITADAS
// ====================================================================

// Login
$get('/login', 'AuthController@showLogin');
$post('/login', 'AuthController@login');

// Register
$get('/register', 'AuthController@showRegister');
$post('/register', 'AuthController@register');

// Logout
$get('/logout', 'AuthController@logout');
$post('/logout', 'AuthController@logout');

// Recuperación de contraseña
$get('/forgot-password', 'AuthController@showForgotPassword');
$post('/forgot-password', 'AuthController@forgotPassword');
$get('/confirm-mail', 'AuthController@showSendConfirmMail');
$post('/confirm-mail', 'AuthController@sendConfirmMail');
$get('/reset-password/{selector}/{token}', 'AuthController@showResetPassword');
$get('/reset-password', 'AuthController@showResetPassword');
$post('/reset-password', 'AuthController@resetPassword');

// Verificación de email
$get('/verify-email', 'AuthController@showVerifyEmail');
$post('/verify-email', 'AuthController@verifyEmail');

// ====================================================================
// RUTAS DE DEBUG
// ====================================================================

// Rutas de testing/debugging (solo en desarrollo)
if (config('app.debug', false)) {
    $get('/test', 'HomeController@test');
    $get('/phpinfo', 'HomeController@phpinfo');
}

// Información del sistema (mantener para compatibilidad)
$get('/info', 'HomeController@info');

// Home
$get('/home', 'HomeController@index');

// Profile
$get('/profile', 'ProfileController@index');

// ====================================================================
// RUTAS DE ADMINISTRACIÓN (temporalmente sin middleware)
// ====================================================================

$get('/admin', 'AdminController@dashboard');
$get('/admin/users', 'AdminController@users');
$get('/admin/logs', 'AdminController@logs');

// ====================================================================
// MIDDLEWARE (aplicar cuando tengamos los archivos)
// ====================================================================
// Cuando tengamos los middlewares listos, descomenta:

$middleware('App\\Middlewares\\AuthMiddleware', [
    '/home',
    '/profile',
    '/settings',
    '/admin',
    '/admin/users',
    '/admin/logs'
]);

/*
$middleware('RoleMiddleware:admin', [
    '/admin',
    '/admin/users',
    '/admin/logs'
]);

$middleware('GuestMiddleware', [
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password'
]);
*/