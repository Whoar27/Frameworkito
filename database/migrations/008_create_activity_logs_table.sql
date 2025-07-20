-- =====================================================
-- MIGRACIÓN 008: TABLA ACTIVITY_LOGS (Frameworkito)
-- Archivo: database/migrations/008_create_activity_logs_table.sql
-- Descripción: Logs de actividad del sistema para auditoría
-- Dependencias: 001_create_users_table.sql
-- =====================================================

CREATE TABLE `activity_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned DEFAULT NULL,
    `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `context` json DEFAULT NULL,
    `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `user_agent` text COLLATE utf8mb4_unicode_ci,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    
    -- Índices para performance
    KEY `user_id` (`user_id`),
    KEY `type` (`type`),
    KEY `action` (`action`),
    KEY `created_at` (`created_at`),
    KEY `type_created` (`type`, `created_at`),
    KEY `user_type_created` (`user_id`, `type`, `created_at`),
    KEY `ip_created` (`ip_address`, `created_at`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- FOREIGN KEY CONSTRAINT (OPCIONAL)
-- =====================================================

-- Descomenta esta línea si prefieres integridad referencial:
-- ALTER TABLE `activity_logs` 
--     ADD CONSTRAINT `fk_activity_logs_user` 
--     FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
--     ON DELETE SET NULL ON UPDATE CASCADE;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla registra todas las actividades importantes del sistema
para auditoría, seguridad y análisis de comportamiento.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental (BIGINT para millones de logs)
- user_id: ID del usuario que realizó la acción (NULL para acciones del sistema)
- type: Categoría del log (auth, security, admin, user, system)
- action: Acción específica realizada
- message: Descripción legible de la actividad
- context: Datos adicionales en formato JSON
- ip_address: Dirección IP del usuario (IPv4/IPv6)
- user_agent: Navegador/cliente del usuario
- created_at: Timestamp de cuándo ocurrió la actividad

TIPOS DE LOGS (FIELD: type):

1. AUTH: Actividades de autenticación
   - login_success, login_failure
   - logout, password_change
   - email_verification, password_reset

2. SECURITY: Eventos de seguridad
   - suspicious_activity, rate_limit_exceeded
   - unauthorized_access, token_manipulation

3. ADMIN: Acciones administrativas
   - user_created, user_deleted, role_assigned
   - settings_changed, system_maintenance

4. USER: Actividades de usuario
   - profile_updated, content_created
   - file_uploaded, data_exported

5. SYSTEM: Eventos del sistema
   - scheduled_task, backup_created
   - error_occurred, maintenance_mode

EJEMPLOS DE REGISTROS:

-- Login exitoso:
type='auth', action='login_success', 
message='Usuario logueado exitosamente',
context='{"email":"user@domain.com","remember":true}'

-- Cambio de rol:
type='admin', action='role_assigned',
message='Rol moderator asignado al usuario juan',
context='{"target_user_id":123,"role":"moderator","assigned_by":1}'

-- Acceso denegado:
type='security', action='unauthorized_access',
message='Intento de acceso a área restringida',
context='{"requested_url":"/admin/users","required_role":"admin"}'

CAMPO CONTEXT (JSON):
Almacena datos estructurados adicionales:
- IDs relacionados
- Valores antes/después de cambios
- Metadatos específicos de la acción
- Información técnica relevante

VENTAJAS DEL DISEÑO:

- Escalable: BIGINT id para millones de registros
- Flexible: JSON context para datos variables
- Consultable: Índices optimizados para búsquedas comunes
- Completo: IP y user agent para análisis forense

CONSULTAS COMUNES:

-- Actividad de un usuario:
SELECT * FROM activity_logs 
WHERE user_id = ? 
ORDER BY created_at DESC LIMIT 50;

-- Logs de seguridad recientes:
SELECT * FROM activity_logs 
WHERE type = 'security' 
AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR);

-- Actividad por IP:
SELECT * FROM activity_logs 
WHERE ip_address = '192.168.1.100' 
ORDER BY created_at DESC;

ÍNDICES EXPLICADOS:

- user_id: Para logs de un usuario específico
- type: Para filtrar por categoría
- action: Para acciones específicas
- created_at: Para ordenar cronológicamente
- type_created: Para logs de una categoría en rango de tiempo
- user_type_created: Para actividad de usuario por tipo
- ip_created: Para análisis forense por IP

RETENCIÓN DE DATOS:
Se recomienda estrategia de archivado:
- Mantener 90 días en tabla principal
- Archivar logs antiguos en tabla histórica
- Comprimir/eliminar logs muy antiguos según políticas

PRIVACIDAD:
- No almacenar datos sensibles en context
- Anonimizar IPs según regulaciones
- Encriptar user_agent si contiene info personal
*/