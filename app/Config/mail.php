<?php
/**
 * Configuración de Email
 * Frameworkito - Usando PHPMailer
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración General de Email
    |--------------------------------------------------------------------------
    */

    // Driver de email (smtp, mail, sendmail)
    'driver' => $_ENV['MAIL_DRIVER'] ?? 'smtp',

    // Email "desde" por defecto
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@localhost',
        'name' => $_ENV['MAIL_FROM_NAME'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración SMTP
    |--------------------------------------------------------------------------
    */

    'smtp' => [
        // Host del servidor SMTP
        'host' => $_ENV['MAIL_HOST'] ?? 'localhost',

        // Puerto del servidor SMTP
        'port' => (int)($_ENV['MAIL_PORT'] ?? 587),

        // Usuario del servidor SMTP
        'username' => $_ENV['MAIL_USERNAME'] ?? '',

        // Contraseña del servidor SMTP
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',

        // Tipo de encriptación (tls, ssl, null)
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',

        // Timeout para conexión SMTP en segundos
        'timeout' => 30,

        // Activar autenticación SMTP
        'auth' => true,

        // Mantener conexión viva
        'keep_alive' => false,

        // Configuraciones específicas para proveedores comunes
        'providers' => [
            'gmail' => [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'encryption' => 'tls',
                'auth' => true,
            ],

            'outlook' => [
                'host' => 'smtp-mail.outlook.com',
                'port' => 587,
                'encryption' => 'tls',
                'auth' => true,
            ],

            'yahoo' => [
                'host' => 'smtp.mail.yahoo.com',
                'port' => 587,
                'encryption' => 'tls',
                'auth' => true,
            ],

            'sendgrid' => [
                'host' => 'smtp.sendgrid.net',
                'port' => 587,
                'encryption' => 'tls',
                'auth' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Templates de Email
    |--------------------------------------------------------------------------
    */

    'templates' => [
        // Directorio de templates
        'path' => __DIR__ . '/../Views/emails',

        // Template por defecto
        'default' => 'default',

        // Configuración específica por tipo de email
        'types' => [
            'welcome' => [
                'subject' => 'Bienvenido a {app_name}',
                'template' => 'auth/welcome',
                'from_name' => 'Equipo de {app_name}',
            ],

            'email_verification' => [
                'subject' => 'Verifica tu dirección de email',
                'template' => 'auth/verify-email',
                'expires_in' => 24, // horas
            ],

            'password_reset' => [
                'subject' => 'Restablecer contraseña',
                'template' => 'auth/reset-password',
                'expires_in' => 2, // horas
            ],

            'password_changed' => [
                'subject' => 'Contraseña cambiada exitosamente',
                'template' => 'auth/password-changed',
            ],

            'two_factor_code' => [
                'subject' => 'Código de verificación',
                'template' => 'auth/two-factor-code',
                'expires_in' => 5, // minutos
            ],

            'login_notification' => [
                'subject' => 'Nuevo inicio de sesión detectado',
                'template' => 'security/login-notification',
            ],

            'security_alert' => [
                'subject' => 'Alerta de seguridad en tu cuenta',
                'template' => 'security/security-alert',
                'priority' => 'high',
            ],

            'account_locked' => [
                'subject' => 'Tu cuenta ha sido bloqueada',
                'template' => 'security/account-locked',
                'priority' => 'high',
            ],
        ],

        // Variables globales disponibles en todos los templates
        'global_variables' => [
            'app_name' => $_ENV['APP_NAME'],
            'app_url' => $_ENV['APP_URL'],
            'support_email' => $_ENV['SUPPORT_EMAIL'],
            'company_name' => $_ENV['COMPANY_NAME'],
            'company_address' => $_ENV['COMPANY_ADDRESS'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Colas de Email
    |--------------------------------------------------------------------------
    */

    'queue' => [
        // Activar cola de emails
        'enabled' => filter_var($_ENV['MAIL_QUEUE_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Driver de cola (file, database, redis)
        'driver' => $_ENV['MAIL_QUEUE_DRIVER'] ?? 'file',

        // Directorio para cola de archivos
        'file_path' => __DIR__ . '/../../storage/mail_queue',

        // Número máximo de intentos de envío
        'max_attempts' => 3,

        // Tiempo de espera entre intentos (minutos)
        'retry_delay' => 5,

        // Procesar cola automáticamente
        'auto_process' => true,

        // Emails por lote al procesar
        'batch_size' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Logging de Email
    |--------------------------------------------------------------------------
    */

    'logging' => [
        // Registrar todos los emails enviados
        'log_all' => true,

        // Registrar errores de envío
        'log_errors' => true,

        // Incluir contenido del email en logs (solo para debug)
        'log_content' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Archivo de log específico para emails
        'log_file' => __DIR__ . '/../../logs/email.log',

        // Información a registrar
        'log_info' => [
            'to' => true,
            'subject' => true,
            'template' => true,
            'status' => true,
            'error_message' => true,
            'send_time' => true,
            'ip_address' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Validación de Email
    |--------------------------------------------------------------------------
    */

    'validation' => [
        // Validar sintaxis de email
        'validate_syntax' => true,

        // Validar que el dominio existe (DNS lookup)
        'validate_domain' => false,

        // Validar que el MX record existe
        'validate_mx' => false,

        // Dominios bloqueados para registro
        'blocked_domains' => [
            '10minutemail.com',
            'tempmail.org',
            'guerrillamail.com',
            'mailinator.com',
            'temp-mail.org',
            'throwaway.email',
        ],

        // Dominios permitidos (vacío = todos permitidos)
        'allowed_domains' => [],

        // Solo permitir emails corporativos
        'corporate_only' => false,

        // Lista de dominios corporativos conocidos
        'corporate_domains' => [
            'gmail.com',
            'outlook.com',
            'hotmail.com',
            'yahoo.com',
            'empresa.com', // Agregar dominios de tu empresa
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        // Activar rate limiting para emails
        'enabled' => true,

        // Máximo de emails por hora por usuario
        'max_per_hour_per_user' => 5,

        // Máximo de emails por hora total
        'max_per_hour_total' => 100,

        // Tipos de email con límites específicos
        'specific_limits' => [
            'password_reset' => [
                'max_per_hour' => 3,
                'max_per_day' => 5,
            ],
            'email_verification' => [
                'max_per_hour' => 2,
                'max_per_day' => 3,
            ],
            'two_factor_code' => [
                'max_per_hour' => 10,
                'max_per_day' => 20,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Seguridad
    |--------------------------------------------------------------------------
    */

    'security' => [
        // Firmar emails con DKIM (requiere configuración adicional)
        'dkim_enabled' => false,
        'dkim_private_key' => '',
        'dkim_selector' => 'default',
        'dkim_domain' => '',

        // Encriptar emails sensibles
        'encrypt_sensitive' => false,

        // Lista de tipos de email considerados sensibles
        'sensitive_types' => [
            'password_reset',
            'two_factor_code',
            'security_alert',
            'account_locked',
        ],

        // Headers de seguridad adicionales
        'security_headers' => [
            'X-Mailer' => 'Frameworkito Mailer',
            'X-Priority' => '3',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Testing
    |--------------------------------------------------------------------------
    */

    'testing' => [
        // Modo de prueba (no enviar emails reales)
        'enabled' => filter_var($_ENV['MAIL_TESTING'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Archivo donde guardar emails de prueba
        'file_path' => __DIR__ . '/../../storage/testing_emails',

        // Mostrar emails en log cuando está en modo prueba
        'log_testing_emails' => true,

        // Email de prueba por defecto
        'default_recipient' => $_ENV['MAIL_TESTING_EMAIL'] ?? 'test@localhost',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Notificaciones Push (Opcional)
    |--------------------------------------------------------------------------
    */

    'push_notifications' => [
        // Activar notificaciones push como alternativa al email
        'enabled' => false,

        // Servicio de push notifications
        'service' => 'firebase', // firebase, pusher, etc.

        // Tipos de email que también envían push
        'push_types' => [
            'two_factor_code',
            'security_alert',
            'login_notification',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Backup de Emails
    |--------------------------------------------------------------------------
    */

    'backup' => [
        // Guardar copia de todos los emails enviados
        'enabled' => false,

        // Directorio para backups
        'path' => __DIR__ . '/../../storage/email_backups',

        // Retener backups por X días
        'retention_days' => 30,

        // Comprimir backups antiguos
        'compress_old' => true,

        // Tipos de email a respaldar
        'backup_types' => [
            'password_reset',
            'email_verification',
            'security_alert',
        ],
    ],
];
