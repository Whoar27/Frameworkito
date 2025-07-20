-- =====================================================
-- MIGRACIÓN 009: TABLA SYSTEM_SETTINGS (Frameworkito)
-- Archivo: database/migrations/009_create_system_settings_table.sql
-- Descripción: Configuraciones dinámicas del sistema
-- Dependencias: Ninguna (independiente)
-- =====================================================

CREATE TABLE `system_settings` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `value` text COLLATE utf8mb4_unicode_ci,
    `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
    `description` text COLLATE utf8mb4_unicode_ci,
    `group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
    `is_public` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`),
    
    -- Índices para performance
    KEY `group` (`group`),
    KEY `is_public` (`is_public`),
    KEY `type` (`type`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CONFIGURACIONES INICIALES DEL SISTEMA
-- =====================================================

INSERT INTO `system_settings` (`key`, `value`, `type`, `description`, `group`, `is_public`) VALUES

-- CONFIGURACIONES GENERALES
('app_name', 'Frameworkito', 'string', 'Nombre de la aplicación', 'general', 1),
('app_version', '1.0.0', 'string', 'Versión actual del sistema', 'general', 1),
('app_description', 'Sistema de autenticación profesional con PHP', 'string', 'Descripción de la aplicación', 'general', 1),
('app_timezone', 'America/Bogota', 'string', 'Zona horaria del sistema', 'general', 0),
('app_locale', 'es', 'string', 'Idioma por defecto del sistema', 'general', 0),

-- CONFIGURACIONES DE SISTEMA
('maintenance_mode', 'false', 'boolean', 'Modo de mantenimiento activado', 'system', 0),
('maintenance_message', 'El sistema está en mantenimiento. Regresa pronto.', 'string', 'Mensaje de mantenimiento', 'system', 1),
('debug_mode', 'true', 'boolean', 'Modo debug activado', 'system', 0),
('log_level', 'info', 'string', 'Nivel de logging (debug, info, warning, error)', 'system', 0),

-- CONFIGURACIONES DE AUTENTICACIÓN
('registration_enabled', 'true', 'boolean', 'Permitir registro de nuevos usuarios', 'auth', 1),
('email_verification_required', 'true', 'boolean', 'Requerir verificación de email', 'auth', 0),
('password_reset_enabled', 'true', 'boolean', 'Permitir reset de contraseñas', 'auth', 0),
('remember_me_enabled', 'true', 'boolean', 'Permitir función "Recordarme"', 'auth', 0),
('remember_me_duration', '30', 'integer', 'Duración "Recordarme" en días', 'auth', 0),
('default_user_role', 'user', 'string', 'Rol por defecto para nuevos usuarios', 'auth', 0),

-- CONFIGURACIONES DE SEGURIDAD
('max_login_attempts', '5', 'integer', 'Máximo intentos de login antes de bloqueo', 'security', 0),
('login_lockout_duration', '15', 'integer', 'Duración de bloqueo en minutos', 'security', 0),
('session_lifetime', '120', 'integer', 'Duración de sesión en minutos', 'security', 0),
('password_min_length', '8', 'integer', 'Longitud mínima de contraseña', 'security', 1),
('password_require_uppercase', 'true', 'boolean', 'Requerir mayúsculas en contraseña', 'security', 1),
('password_require_lowercase', 'true', 'boolean', 'Requerir minúsculas en contraseña', 'security', 1),
('password_require_numbers', 'true', 'boolean', 'Requerir números en contraseña', 'security', 1),
('password_require_symbols', 'true', 'boolean', 'Requerir símbolos en contraseña', 'security', 1),

-- CONFIGURACIONES DE EMAIL
('email_from_address', 'noreply@authmanager.com', 'string', 'Email remitente por defecto', 'email', 0),
('email_from_name', 'Frameworkito', 'string', 'Nombre remitente por defecto', 'email', 0),
('email_verification_expiry', '24', 'integer', 'Expiración link verificación en horas', 'email', 0),
('password_reset_expiry', '1', 'integer', 'Expiración link reset en horas', 'email', 0),

-- CONFIGURACIONES DE INTERFAZ
('theme', 'default', 'string', 'Tema visual del sistema', 'ui', 1),
('items_per_page', '25', 'integer', 'Elementos por página en listados', 'ui', 0),
('date_format', 'Y-m-d H:i:s', 'string', 'Formato de fecha por defecto', 'ui', 0),
('currency', 'COP', 'string', 'Moneda por defecto', 'ui', 1),

-- CONFIGURACIONES DE API
('api_enabled', 'false', 'boolean', 'API REST habilitada', 'api', 0),
('api_rate_limit', '100', 'integer', 'Límite de requests por hora para API', 'api', 0),
('api_key_required', 'true', 'boolean', 'Requerir API key para endpoints', 'api', 0),

-- CONFIGURACIONES DE BACKUP
('backup_enabled', 'true', 'boolean', 'Sistema de backup automático', 'backup', 0),
('backup_frequency', 'daily', 'string', 'Frecuencia de backup (daily, weekly)', 'backup', 0),
('backup_retention_days', '30', 'integer', 'Días de retención de backups', 'backup', 0),

-- CONFIGURACIONES SOCIALES
('contact_email', 'contact@authmanager.com', 'string', 'Email de contacto público', 'contact', 1),
('support_email', 'support@authmanager.com', 'string', 'Email de soporte técnico', 'contact', 1),
('company_name', 'Frameworkito Team', 'string', 'Nombre de la empresa', 'contact', 1),
('company_address', '', 'text', 'Dirección de la empresa', 'contact', 1);

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla permite configurar el sistema dinámicamente sin modificar código,
facilitando la administración y personalización por parte de usuarios no técnicos.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental
- key: Identificador único de la configuración
- value: Valor de la configuración (almacenado como texto)
- type: Tipo de dato (string, integer, boolean, text, json)
- description: Descripción legible de qué hace esta configuración
- group: Agrupación lógica (auth, security, ui, etc.)
- is_public: Si la configuración es visible públicamente (0=privada, 1=pública)
- created_at: Timestamp de creación
- updated_at: Timestamp de última modificación

TIPOS DE DATOS:

- string: Texto simple
- integer: Números enteros
- boolean: true/false (almacenado como string)
- text: Texto largo
- json: Objetos JSON complejos

GRUPOS DE CONFIGURACIÓN:

- general: Configuraciones básicas de la app
- system: Configuraciones técnicas del sistema
- auth: Configuraciones de autenticación
- security: Configuraciones de seguridad
- email: Configuraciones de email
- ui: Configuraciones de interfaz
- api: Configuraciones de API
- backup: Configuraciones de respaldo
- contact: Información de contacto

CONFIGURACIONES PÚBLICAS (is_public=1):
Son accesibles desde el frontend sin autenticación:
- app_name, app_version, theme
- password_min_length, password policies
- contact_email, company_name

CONFIGURACIONES PRIVADAS (is_public=0):
Solo accesibles por administradores:
- database settings, api keys
- security settings, debug mode

VENTAJAS DEL SISTEMA:

- Flexibilidad: Cambios sin redeployment
- Auditoría: Timestamps de cambios
- Categorización: Grupos lógicos
- Seguridad: Configuraciones públicas/privadas
- Tipado: Validación por tipo de dato

USO EN CÓDIGO:

-- Leer configuración:
$value = getSetting('app_name');
$maxAttempts = getSetting('max_login_attempts', 5);

-- Escribir configuración:
setSetting('maintenance_mode', 'true');

-- Configuraciones por grupo:
$authSettings = getSettingsByGroup('auth');

ÍNDICES:
- key: UNIQUE para búsqueda rápida por clave
- group: Para obtener configuraciones por categoría
- is_public: Para filtrar configuraciones públicas
- type: Para validaciones por tipo de dato

EXTENSIBILIDAD:
Nuevas configuraciones se pueden agregar fácilmente:
- Sin cambios en código
- Solo INSERT en esta tabla
- Disponibles inmediatamente en la aplicación
*/