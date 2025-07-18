-- =====================================================
-- MIGRACIÓN 007: TABLA USERS_ROLES (AuthManager Base)
-- Archivo: database/migrations/007_create_users_roles_table.sql
-- Descripción: Relación muchos a muchos entre usuarios y roles
-- Dependencias: 001_create_users_table.sql, 006_create_roles_table.sql
-- =====================================================

CREATE TABLE `users_roles` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned NOT NULL,
    `role_id` int(10) unsigned NOT NULL,
    `assigned_by` int(10) unsigned DEFAULT NULL,
    `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_role_unique` (`user_id`, `role_id`),
    
    -- Índices para performance
    KEY `user_id` (`user_id`),
    KEY `role_id` (`role_id`),
    KEY `assigned_by` (`assigned_by`),
    KEY `is_active` (`is_active`),
    KEY `expires_at` (`expires_at`),
    KEY `assigned_at` (`assigned_at`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- FOREIGN KEY CONSTRAINTS (OPCIONAL)
-- =====================================================

-- Descomenta estas líneas si prefieres integridad referencial estricta:
-- ALTER TABLE `users_roles` 
--     ADD CONSTRAINT `fk_users_roles_user` 
--     FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
--     ON DELETE CASCADE ON UPDATE CASCADE;

-- ALTER TABLE `users_roles` 
--     ADD CONSTRAINT `fk_users_roles_role` 
--     FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) 
--     ON DELETE CASCADE ON UPDATE CASCADE;

-- ALTER TABLE `users_roles` 
--     ADD CONSTRAINT `fk_users_roles_assigned_by` 
--     FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) 
--     ON DELETE SET NULL ON UPDATE CASCADE;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla establece la relación muchos a muchos entre usuarios y roles,
permitiendo que un usuario tenga múltiples roles y un rol sea asignado
a múltiples usuarios.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental
- user_id: ID del usuario (referencia a users.id)
- role_id: ID del rol (referencia a roles.id)
- assigned_by: ID del admin que asignó el rol (opcional)
- assigned_at: Timestamp de cuándo se asignó el rol
- expires_at: Timestamp de expiración del rol (NULL = permanente)
- is_active: Si la asignación está activa (0=inactiva, 1=activa)

CASOS DE USO:

1. ASIGNACIÓN BÁSICA:
   user_id=123, role_id=2, expires_at=NULL
   → Usuario 123 tiene rol "user" permanentemente

2. ASIGNACIÓN TEMPORAL:
   user_id=456, role_id=3, expires_at='2024-12-31 23:59:59'
   → Usuario 456 es "moderator" hasta fin de año

3. ASIGNACIÓN AUDITADA:
   assigned_by=1 → El admin con ID 1 asignó este rol

4. ASIGNACIÓN DESACTIVADA:
   is_active=0 → Rol suspendido temporalmente

VENTAJAS DEL DISEÑO:

- Flexibilidad: Múltiples roles por usuario
- Auditoría: Quién asignó qué y cuándo
- Temporal: Roles con fecha de expiración
- Reversible: Activar/desactivar sin eliminar

CONSULTAS COMUNES:

-- Roles activos de un usuario:
SELECT r.* FROM roles r 
JOIN users_roles ur ON r.id = ur.role_id 
WHERE ur.user_id = ? AND ur.is_active = 1 
AND (ur.expires_at IS NULL OR ur.expires_at > NOW());

-- Usuarios con un rol específico:
SELECT u.* FROM users u 
JOIN users_roles ur ON u.id = ur.user_id 
WHERE ur.role_id = ? AND ur.is_active = 1;

-- Roles expirados para limpieza:
SELECT * FROM users_roles 
WHERE expires_at IS NOT NULL AND expires_at < NOW();

ÍNDICES EXPLICADOS:

- user_role_unique: Previene asignaciones duplicadas
- user_id: Para consultas "roles de un usuario"
- role_id: Para consultas "usuarios con un rol"
- assigned_by: Para auditoría de asignaciones
- is_active: Para filtrar asignaciones activas
- expires_at: Para encontrar roles expirados
- assigned_at: Para ordenar por fecha de asignación

FOREIGN KEYS:
- Opcionales pero recomendadas en producción
- Garantizan integridad referencial
- CASCADE elimina asignaciones si se borra usuario/rol
- SET NULL preserva histórico si se borra admin

LIMPIEZA AUTOMÁTICA:
Se recomienda job periódico para:
- Desactivar roles expirados
- Limpiar asignaciones muy antiguas
- Consolidar histórico de cambios
*/