<?php
/**
 * Configuración de Autenticación
 * AuthManager Base - Usando Delight-im/Auth
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración General de Autenticación
    |--------------------------------------------------------------------------
    */

    // Requerir verificación de email al registrarse
    'require_email_verification' => filter_var($_ENV['REQUIRE_EMAIL_VERIFICATION'] ?? true, FILTER_VALIDATE_BOOLEAN),

    // Activar autenticación de dos factores
    'enable_two_factor' => filter_var($_ENV['ENABLE_TWO_FACTOR'] ?? false, FILTER_VALIDATE_BOOLEAN),

    // Duración por defecto para "Recordarme" en días
    'remember_me_duration' => (int)($_ENV['REMEMBER_ME_DURATION'] ?? 30),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        // Máximo de intentos de login fallidos antes de bloquear
        'max_login_attempts' => (int)($_ENV['MAX_LOGIN_ATTEMPTS'] ?? 5),

        // Tiempo de bloqueo en minutos después de exceder intentos
        'lockout_duration' => (int)($_ENV['LOGIN_LOCKOUT_DURATION'] ?? 15),

        // Ventana de tiempo en minutos para contar intentos
        'attempts_window' => 60,

        // Máximo de intentos de registro por IP por hora
        'max_registration_attempts' => 3,

        // Máximo de intentos de recuperación de contraseña por hora
        'max_password_reset_attempts' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Contraseñas
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        // Longitud mínima de contraseña
        'min_length' => 8,

        // Longitud máxima de contraseña
        'max_length' => 72,

        // Requerir al menos una letra minúscula
        'require_lowercase' => true,

        // Requerir al menos una letra mayúscula
        'require_uppercase' => true,

        // Requerir al menos un número
        'require_number' => true,

        // Requerir al menos un carácter especial
        'require_special_char' => true,

        // Caracteres especiales permitidos
        'special_chars' => '!@#$%^&*()_+-=[]{}|;:,.<>?',

        // No permitir contraseñas comunes
        'disallow_common' => true,

        // Lista de contraseñas comunes prohibidas
        'common_passwords' => [
            'password',
            '123456',
            '123456789',
            'qwerty',
            'abc123',
            'password123',
            'admin',
            'root',
            'user',
            'test'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Tokens
    |--------------------------------------------------------------------------
    */

    'tokens' => [
        // Tiempo de expiración de tokens en minutos
        'expiry_minutes' => (int)($_ENV['TOKEN_EXPIRY_MINUTES'] ?? 60),

        // Longitud de tokens de verificación
        'verification_length' => 32,

        // Longitud de tokens de recuperación
        'reset_length' => 32,

        // Tiempo de expiración para verificación de email (horas)
        'email_verification_expiry' => 24,

        // Tiempo de expiración para reset de contraseña (horas)
        'password_reset_expiry' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Roles y Permisos
    |--------------------------------------------------------------------------
    */

    'roles' => [
        // Roles por defecto del sistema
        'default_roles' => [
            'user' => [
                'name' => 'Usuario',
                'description' => 'Usuario estándar del sistema',
                'level' => 1,
            ],
            'moderator' => [
                'name' => 'Moderador',
                'description' => 'Usuario con permisos de moderación',
                'level' => 5,
            ],
            'admin' => [
                'name' => 'Administrador',
                'description' => 'Administrador del sistema',
                'level' => 10,
            ],
            'superadmin' => [
                'name' => 'Super Administrador',
                'description' => 'Super administrador con todos los permisos',
                'level' => 99,
            ],
        ],

        // Rol asignado por defecto a nuevos usuarios
        'default_user_role' => 'user',

        // Jerarquía de roles (roles superiores heredan permisos de inferiores)
        'hierarchy' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Permisos
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        // Permisos por defecto del sistema
        'default_permissions' => [
            // Permisos de usuario básico
            'view_profile',
            'edit_profile',
            'change_password',

            // Permisos de moderador
            'moderate_content',
            'view_reports',

            // Permisos de administrador
            'manage_users',
            'view_logs',
            'manage_settings',

            // Permisos de super administrador
            'manage_roles',
            'manage_permissions',
            'system_admin',
        ],

        // Permisos que se asignan automáticamente por rol
        'role_permissions' => [
            'user' => ['view_profile', 'edit_profile', 'change_password'],
            'moderator' => ['moderate_content', 'view_reports'],
            'admin' => ['manage_users', 'view_logs', 'manage_settings'],
            'superadmin' => ['manage_roles', 'manage_permissions', 'system_admin'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Sesiones de Autenticación
    |--------------------------------------------------------------------------
    */

    'sessions' => [
        // Permitir múltiples sesiones simultáneas por usuario
        'allow_multiple_sessions' => true,

        // Máximo número de sesiones activas por usuario
        'max_concurrent_sessions' => 5,

        // Invalidar otras sesiones al hacer login
        'invalidate_other_sessions' => false,

        // Registrar información de sesiones en logs
        'log_sessions' => true,

        // Información a registrar sobre sesiones
        'session_info' => [
            'ip_address' => true,
            'user_agent' => true,
            'location' => false, // Requiere servicio de geolocalización
            'device_type' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Autenticación de Dos Factores (2FA)
    |--------------------------------------------------------------------------
    */

    'two_factor' => [
        // Métodos de 2FA disponibles
        'methods' => [
            'totp' => true,    // Time-based One-Time Password (Google Authenticator)
            'sms' => false,    // SMS (requiere configuración adicional)
            'email' => true,   // Email con código
        ],

        // Método por defecto
        'default_method' => 'totp',

        // Nombre de la aplicación para TOTP
        'totp_issuer' => $_ENV['APP_NAME'] ?? 'AuthManager Base',

        // Longitud del código de verificación
        'code_length' => 6,

        // Tiempo de validez del código en segundos
        'code_validity' => 300, // 5 minutos

        // Número de códigos de backup generados
        'backup_codes_count' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Logging de Autenticación
    |--------------------------------------------------------------------------
    */

    'logging' => [
        // Eventos a registrar en logs
        'log_events' => [
            'login_success' => true,
            'login_failure' => true,
            'logout' => true,
            'registration' => true,
            'email_verification' => true,
            'password_reset_request' => true,
            'password_reset_success' => true,
            'password_change' => true,
            'account_lockout' => true,
            'two_factor_enabled' => true,
            'two_factor_disabled' => true,
            'role_change' => true,
            'permission_change' => true,
        ],

        // Información adicional a incluir en logs
        'include_context' => [
            'ip_address' => true,
            'user_agent' => true,
            'session_id' => false, // Solo para debugging
            'request_id' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Proveedores OAuth (Opcional)
    |--------------------------------------------------------------------------
    */

    'oauth' => [
        // Activar OAuth
        'enabled' => false,

        // Proveedores disponibles
        'providers' => [
            'google' => [
                'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
                'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
                'enabled' => !empty($_ENV['GOOGLE_CLIENT_ID']),
            ],

            'facebook' => [
                'app_id' => $_ENV['FACEBOOK_APP_ID'] ?? '',
                'app_secret' => $_ENV['FACEBOOK_APP_SECRET'] ?? '',
                'enabled' => !empty($_ENV['FACEBOOK_APP_ID']),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Validación de Email
    |--------------------------------------------------------------------------
    */

    'email_validation' => [
        // Validar formato de email
        'validate_format' => true,

        // Validar que el dominio existe (DNS)
        'validate_domain' => false,

        // Dominios bloqueados
        'blocked_domains' => [
            '10minutemail.com',
            'tempmail.org',
            'guerrillamail.com',
        ],

        // Dominios permitidos (vacío = todos)
        'allowed_domains' => [],

        // Requerir dominios corporativos solamente
        'corporate_only' => false,
    ],
];
