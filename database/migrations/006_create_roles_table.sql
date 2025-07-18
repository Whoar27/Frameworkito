-- =====================================================
-- MIGRACIÓN 006: TABLA ROLES (AuthManager Base)
-- Archivo: database/migrations/006_create_roles_table.sql
-- Descripción: Sistema de roles y permisos
-- Dependencias: Ninguna (independiente)
-- =====================================================

CREATE TABLE `roles` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci,
    `permissions` text COLLATE utf8mb4_unicode_ci,
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    
    -- Índices para performance
    KEY `name` (`name`),
    KEY `is_active` (`is_active`),
    KEY `created_at` (`created_at`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTAR ROLES POR DEFECTO
-- =====================================================

INSERT INTO `roles` (`name`, `slug`, `description`, `permissions`, `is_active`) VALUES
('Administrador', 'admin', 'Acceso completo al sistema con todos los permisos', '["*"]', 1),
('Usuario', 'user', 'Usuario estándar del sistema con permisos básicos', '["dashboard.view", "profile.edit", "profile.view"]', 1),
('Moderador', 'moderator', 'Permisos de moderación de contenido y usuarios', '["dashboard.view", "users.view", "content.moderate", "reports.view"]', 1),
('Invitado', 'guest', 'Acceso limitado de solo lectura a contenido público', '["public.view"]', 1);

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla define los roles del sistema y sus permisos asociados.
Complementa el sistema de roles básico de Delight-im/Auth.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental
- name: Nombre legible del rol (ej: "Administrador")
- slug: Identificador único del rol (ej: "admin")
- description: Descripción detallada del rol
- permissions: JSON array con permisos específicos
- is_active: Si el rol está activo (0=inactivo, 1=activo)
- created_at: Timestamp de creación
- updated_at: Timestamp de última modificación

SISTEMA DE PERMISOS:

JSON Format en campo permissions:
["permission.action", "resource.action", "module.action"]

Ejemplos:
- ["*"] = Todos los permisos (superadmin)
- ["users.view", "users.create"] = Ver y crear usuarios
- ["dashboard.view"] = Solo ver dashboard

PATRONES DE PERMISOS:

Formato: "resource.action"
- users.view, users.create, users.edit, users.delete
- posts.view, posts.create, posts.edit, posts.delete
- admin.access, admin.settings
- reports.view, reports.export

ROLES POR DEFECTO:

1. ADMIN:
   - Permisos: ["*"] (todos)
   - Uso: Administradores del sistema

2. USER:
   - Permisos básicos de usuario normal
   - Dashboard, perfil propio

3. MODERATOR:
   - Permisos intermedios
   - Moderación de contenido

4. GUEST:
   - Permisos mínimos
   - Solo lectura pública

VENTAJAS DEL SISTEMA:

- Flexible: Permisos granulares
- Escalable: Agregar nuevos permisos fácilmente
- Mantenible: Separación clara de responsabilidades
- Auditable: Timestamps de cambios

ÍNDICES:
- slug: UNIQUE para búsqueda rápida por código
- name: Para búsquedas por nombre
- is_active: Para filtrar roles activos
- created_at: Para ordenar por antiguedad

INTEGRACIÓN:
- Se relaciona con users via users_roles
- Compatible con Delight-im/Auth roles_mask
- Extensible para permisos adicionales
*/