<?php

/**
 * Configuración del Sistema de Logs
 * AuthManager Base - Sistema Dual (Archivos + Base de Datos)
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración General de Logging
    |--------------------------------------------------------------------------
    */

    // Nivel mínimo de log a registrar (debug, info, warning, error, critical)
    'level' => $_ENV['LOG_LEVEL'] ?? 'info',

    // Activar logs en archivos
    'log_to_files' => filter_var($_ENV['LOG_TO_FILES'] ?? true, FILTER_VALIDATE_BOOLEAN),

    // Activar logs en base de datos
    'log_to_database' => filter_var($_ENV['LOG_TO_DATABASE'] ?? true, FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Logs en Archivos
    |--------------------------------------------------------------------------
    */

    'files' => [
        // Directorio base de logs
        'path' => __DIR__ . '/../../logs',

        // Rotación de archivos de log
        'rotation' => [
            // Tipo de rotación (daily, weekly, monthly, size)
            'type' => $_ENV['LOG_ROTATION'] ?? 'daily',

            // Días máximos para mantener logs
            'max_days' => (int)($_ENV['LOG_MAX_DAYS'] ?? 30),

            // Tamaño máximo de archivo en MB (para rotación por tamaño)
            'max_size' => (int)($_ENV['LOG_MAX_SIZE'] ?? 10),

            // Comprimir logs antiguos
            'compress_old' => true,
        ],

        // Configuración específica por tipo de log
        'channels' => [
            'info' => [
                'filename' => 'info.log',
                'daily_filename' => 'info-{date}.log',
                'enabled' => true,
                'format' => '[{timestamp}] {level}: {message} {context}',
            ],

            'error' => [
                'filename' => 'error.log',
                'daily_filename' => 'error-{date}.log',
                'enabled' => true,
                'format' => '[{timestamp}] {level}: {message} {context} {trace}',
                'include_trace' => true,
            ],

            'debug' => [
                'filename' => 'debug.log',
                'daily_filename' => 'debug-{date}.log',
                'enabled' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'format' => '[{timestamp}] {level}: {message} {context} {file}:{line}',
                'include_location' => true,
            ],

            'auth' => [
                'filename' => 'auth.log',
                'daily_filename' => 'auth-{date}.log',
                'enabled' => true,
                'format' => '[{timestamp}] {level}: {message} {context}',
                'separate_file' => true,
            ],

            'warning' => [
                'filename' => 'warning.log',
                'daily_filename' => 'warning-{date}.log',
                'enabled' => true,
                'format' => '[{timestamp}] {level}: {message} {context}',
            ],

            'critical' => [
                'filename' => 'critical.log',
                'daily_filename' => 'critical-{date}.log',
                'enabled' => true,
                'format' => '[{timestamp}] {level}: {message} {context} {trace}',
                'include_trace' => true,
                'send_email' => true, // Enviar email para logs críticos
            ],
        ],

        // Formato de timestamp
        'timestamp_format' => 'Y-m-d H:i:s',

        // Timezone para logs
        'timezone' => $_ENV['APP_TIMEZONE'] ?? 'America/Bogota',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Logs en Base de Datos
    |--------------------------------------------------------------------------
    */

    'database' => [
        // Tabla para almacenar logs
        'table' => 'activity_logs',

        // Campos a registrar
        'fields' => [
            'id' => true,              // ID auto-incremental
            'user_id' => true,         // ID del usuario (null si no está logueado)
            'type' => true,            // Tipo de log (auth, security, activity, etc.)
            'action' => true,          // Acción específica (login, logout, etc.)
            'message' => true,         // Mensaje descriptivo
            'context' => true,         // Datos adicionales en JSON
            'ip_address' => true,      // Dirección IP
            'user_agent' => true,      // User Agent del navegador
            'created_at' => true,      // Timestamp de creación
        ],

        // Tipos de eventos a registrar en BD
        'log_types' => [
            'auth' => [
                'enabled' => true,
                'actions' => [
                    'login_success',
                    'login_failure',
                    'logout',
                    'registration',
                    'email_verification',
                    'password_reset_request',
                    'password_reset_success',
                    'password_change',
                    'account_lockout',
                    'two_factor_enabled',
                    'two_factor_disabled',
                ],
            ],

            'security' => [
                'enabled' => true,
                'actions' => [
                    'unauthorized_access',
                    'csrf_token_mismatch',
                    'suspicious_activity',
                    'ip_blocked',
                    'rate_limit_exceeded',
                    'invalid_token',
                ],
            ],

            'activity' => [
                'enabled' => true,
                'actions' => [
                    'profile_update',
                    'settings_change',
                    'file_upload',
                    'data_export',
                    'content_create',
                    'content_update',
                    'content_delete',
                ],
            ],

            'admin' => [
                'enabled' => true,
                'actions' => [
                    'user_create',
                    'user_update',
                    'user_delete',
                    'role_assign',
                    'permission_grant',
                    'system_setting_change',
                    'backup_create',
                ],
            ],

            'system' => [
                'enabled' => true,
                'actions' => [
                    'application_start',
                    'application_error',
                    'database_error',
                    'email_send_failure',
                    'cache_clear',
                    'maintenance_mode',
                ],
            ],

            'error' => [
                'enabled' => true,
                'actions' => [
                    'exception_thrown',
                    'fatal_error',
                    'database_connection_failed',
                    'file_not_found',
                    'permission_denied',
                ],
            ],
        ],

        // Limpieza automática de logs antiguos
        'cleanup' => [
            'enabled' => true,
            'retain_days' => 90,       // Mantener logs por 90 días
            'batch_size' => 1000,      // Eliminar en lotes de 1000
        ],

        // Indexación de la tabla
        'indexes' => [
            'user_id_index' => ['user_id'],
            'type_index' => ['type'],
            'created_at_index' => ['created_at'],
            'composite_index' => ['user_id', 'type', 'created_at'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Contexto Automático
    |--------------------------------------------------------------------------
    */

    'auto_context' => [
        // Información a incluir automáticamente en todos los logs
        'include' => [
            'timestamp' => true,
            'ip_address' => true,
            'user_agent' => true,
            'session_id' => false,     // Solo para debugging
            'request_uri' => true,
            'http_method' => true,
            'user_id' => true,
            'memory_usage' => false,   // Solo para debugging
        ],

        // Sanitización de datos sensibles
        'sanitize' => [
            'password' => '***HIDDEN***',
            'token' => '***HIDDEN***',
            'secret' => '***HIDDEN***',
            'api_key' => '***HIDDEN***',
            'credit_card' => '***HIDDEN***',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Alertas
    |--------------------------------------------------------------------------
    */

    'alerts' => [
        // Enviar alertas por email para eventos críticos
        'email_alerts' => [
            'enabled' => filter_var($_ENV['LOG_EMAIL_ALERTS'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'recipients' => explode(',', $_ENV['LOG_ALERT_EMAILS'] ?? ''),
            'subjects' => [
                'critical' => '[CRÍTICO] Error en ' . ($_ENV['APP_NAME'] ?? 'AuthManager'),
                'security' => '[SEGURIDAD] Alerta en ' . ($_ENV['APP_NAME'] ?? 'AuthManager'),
                'error' => '[ERROR] Problema en ' . ($_ENV['APP_NAME'] ?? 'AuthManager'),
            ],
        ],

        // Niveles que disparan alertas
        'alert_levels' => ['critical', 'error'],

        // Eventos específicos que disparan alertas
        'alert_events' => [
            'multiple_failed_logins',
            'suspicious_activity',
            'system_error',
            'database_connection_failed',
            'unauthorized_admin_access',
        ],

        // Rate limiting para alertas (evitar spam)
        'rate_limit' => [
            'max_alerts_per_hour' => 10,
            'cooldown_minutes' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Formateo
    |--------------------------------------------------------------------------
    */

    'formatting' => [
        // Formato por defecto para logs de archivo
        'default_format' => '[{timestamp}] {level}: {message} {context}',

        // Formatos específicos por nivel
        'level_formats' => [
            'debug' => '[{timestamp}] DEBUG: {message} | File: {file}:{line} | Context: {context}',
            'info' => '[{timestamp}] INFO: {message} | Context: {context}',
            'warning' => '[{timestamp}] WARNING: {message} | Context: {context}',
            'error' => '[{timestamp}] ERROR: {message} | Context: {context} | Trace: {trace}',
            'critical' => '[{timestamp}] CRITICAL: {message} | Context: {context} | Trace: {trace}',
        ],

        // Límite de caracteres para mensajes
        'max_message_length' => 1000,

        // Límite de caracteres para contexto
        'max_context_length' => 2000,

        // Formato de fecha y hora
        'date_format' => 'Y-m-d H:i:s',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Performance
    |--------------------------------------------------------------------------
    */

    'performance' => [
        // Buffer de logs para escritura en lote
        'buffer_enabled' => true,
        'buffer_size' => 100,          // Escribir cada 100 logs
        'buffer_timeout' => 30,        // O cada 30 segundos

        // Async logging (requiere extensiones adicionales)
        'async_enabled' => false,

        // Cache de configuración
        'cache_config' => true,
        'cache_duration' => 3600,      // 1 hora
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Desarrollo
    |--------------------------------------------------------------------------
    */

    'development' => [
        // Logs adicionales solo en desarrollo
        'debug_enabled' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Mostrar logs en pantalla (solo desarrollo)
        'display_logs' => false,

        // Logging de queries SQL
        'log_queries' => filter_var($_ENV['ENABLE_QUERY_LOG'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Profiling de rendimiento
        'log_performance' => false,
    ],
];
