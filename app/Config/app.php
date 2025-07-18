<?php

/**
 * Configuración General de la Aplicación
 * AuthManager Base
 */

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../../.env')) {
    $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Saltar comentarios
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("{$name}={$value}");
            }
        }
    }
}

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de la Aplicación
    |--------------------------------------------------------------------------
    */

    // Nombre de la aplicación
    'name' => $_ENV['APP_NAME'],

    // Entorno de la aplicación (development, production, testing)
    'env' => $_ENV['APP_ENV'],

    // Modo debug (mostrar errores detallados)
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),

    // Modo mantenimiento (mostrar página de mantenimiento)
    'maintenance_mode' => filter_var($_ENV['APP_MAINTENANCE'] ?? false, FILTER_VALIDATE_BOOLEAN),

    // URL base de la aplicación
    'url' => $_ENV['APP_URL'],

    // Clave secreta de la aplicación (32 caracteres)
    'key' => $_ENV['APP_KEY'],

    // Zona horaria de la aplicación
    'timezone' => $_ENV['APP_TIMEZONE'],

    // Tipo de aplicación (website o system)
    'app_type' => $_ENV['APP_TYPE'],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Mantenimiento
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        // IPs permitidas durante mantenimiento (pueden acceder normalmente)
        'allowed_ips' => [
            '127.0.0.1',        // Localhost IPv4
            '::1',              // Localhost IPv6
            '192.168.1.1',      // Red local común
            '192.168.0.1',      // Red local común
            '10.0.0.1',         // Red privada
            // Agregar IPs de administradores aquí
        ],

        // Rutas permitidas durante mantenimiento
        'allowed_routes' => [
            '/maintenance',      // Vista de mantenimiento
            '/status',          // Estado del sistema
            '/api/status',      // API de estado
            '/api/health',      // Health check
            '/health',          // Health check alternativo
            '/admin/maintenance', // Panel de admin para mantenimiento
        ],

        // Configuración de notificaciones
        'notifications' => [
            // Email para recibir notificaciones cuando termine el mantenimiento
            'admin_email' => $_ENV['MAIL_FROM_ADDRESS'],

            // Enviar email cuando se active/desactive mantenimiento
            'notify_on_toggle' => true,
        ],

        // Configuración de la página de mantenimiento
        'page' => [
            // Tiempo estimado de finalización en horas (desde ahora)
            'estimated_hours' => 4,

            // Mensaje personalizado
            'custom_message' => 'Estamos realizando mejoras importantes en nuestro sistema.',

            // Mostrar progreso estimado
            'show_progress' => true,

            // Contacto de soporte
            'support_email' => $_ENV['MAIL_FROM_ADDRESS'],
            'support_phone' => '+1 (555) 123-4567',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Sesiones
    |--------------------------------------------------------------------------
    */

    'session' => [
        // Duración de la sesión en minutos (0 = hasta cerrar navegador)
        'lifetime' => (int)($_ENV['SESSION_LIFETIME']),

        // Nombre de la cookie de sesión
        'cookie_name' => 'authmanager_session',

        // Dominio de la cookie (null para dominio actual)
        'cookie_domain' => null,

        // Ruta de la cookie
        'cookie_path' => '/',

        // Cookie solo por HTTPS
        'cookie_secure' => filter_var($_ENV['FORCE_HTTPS'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Cookie solo accesible por HTTP (no JavaScript)
        'cookie_httponly' => true,

        // SameSite cookie policy
        'cookie_samesite' => 'Lax',

        // Regenerar ID de sesión automáticamente
        'regenerate_id' => true,

        // Validar IP de sesión
        'validate_ip' => filter_var($_ENV['VALIDATE_SESSION_IP'] ?? true, FILTER_VALIDATE_BOOLEAN),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Seguridad
    |--------------------------------------------------------------------------
    */

    'security' => [
        // Forzar HTTPS
        'force_https' => filter_var($_ENV['FORCE_HTTPS'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Protección CSRF
        'csrf_protection' => filter_var($_ENV['CSRF_PROTECTION'] ?? true, FILTER_VALIDATE_BOOLEAN),

        // Duración del token CSRF en minutos
        'csrf_token_lifetime' => (int)($_ENV['CSRF_TOKEN_LIFETIME']),

        // Headers de seguridad
        'security_headers' => filter_var($_ENV['SECURITY_HEADERS'] ?? true, FILTER_VALIDATE_BOOLEAN),

        // Lista de headers de seguridad a aplicar
        'headers' => [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'security_headers' => false,
            // 'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net cdnjs.cloudflare.com; font-src 'self' fonts.gstatic.com cdnjs.cloudflare.com; img-src 'self' data:;"
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Archivos
    |--------------------------------------------------------------------------
    */

    'files' => [
        // Directorio de uploads
        'upload_path' => $_ENV['UPLOAD_PATH'],

        // Tamaño máximo de archivo en MB
        'max_file_size' => (int)($_ENV['MAX_FILE_SIZE']),

        // Tipos de archivo permitidos para avatares
        'avatar_allowed_types' => explode(',', $_ENV['AVATAR_ALLOWED_TYPES']),

        // Tamaño máximo para avatares en MB
        'avatar_max_size' => (int)($_ENV['AVATAR_MAX_SIZE']),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de API
    |--------------------------------------------------------------------------
    */

    'api' => [
        // Activar API REST
        'enabled' => filter_var($_ENV['ENABLE_API'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Prefijo de rutas de API
        'prefix' => $_ENV['API_PREFIX'] ?? 'api',

        // Versión de API por defecto
        'version' => $_ENV['API_VERSION'] ?? 'v1',

        // Rate limiting para API (requests per minute)
        'rate_limit' => (int)($_ENV['API_RATE_LIMIT'] ?? 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Caché
    |--------------------------------------------------------------------------
    */

    'cache' => [
        // Driver de caché (file, redis, memcached, array)
        'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',

        // Tiempo de caché por defecto en minutos
        'default_ttl' => (int)($_ENV['CACHE_DEFAULT_TTL'] ?? 60),

        // Directorio para caché de archivos
        'file_path' => __DIR__ . '/../../storage/cache',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Desarrollo
    |--------------------------------------------------------------------------
    */

    'development' => [
        // Mostrar errores de PHP
        'display_errors' => filter_var($_ENV['DISPLAY_ERRORS'] ?? true, FILTER_VALIDATE_BOOLEAN),

        // Reportar todos los errores
        'report_all_errors' => filter_var($_ENV['REPORT_ALL_ERRORS'] ?? true, FILTER_VALIDATE_BOOLEAN),

        // Activar profiling de consultas
        'enable_query_log' => filter_var($_ENV['ENABLE_QUERY_LOG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rutas Principales
    |--------------------------------------------------------------------------
    */

    'routes' => [
        // Ruta después del login exitoso
        'after_login' => '/home',

        // Ruta después del logout
        'after_logout' => '/',

        // Ruta de login
        'login' => '/login',

        // Ruta de registro
        'register' => '/register',

        // Ruta de inicio
        'home' => '/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Proveedores
    |--------------------------------------------------------------------------
    */

    'providers' => [
        // Servicios que se cargan automáticamente
        'auto_load' => [
            'LogService',
            'AuthService',
            'SessionManager'
        ]
    ],
];
