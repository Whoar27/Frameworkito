-- =====================================================
-- MIGRACIÓN 001: TABLA USERS (Delight-im/Auth)
-- Archivo: database/migrations/001_create_users_table.sql
-- Descripción: Tabla principal de usuarios para Delight-im/Auth
-- Dependencias: Ninguna
-- =====================================================

CREATE TABLE `users` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
    `verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `resettable` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `roles_mask` int(10) unsigned NOT NULL DEFAULT '0',
    `registered` int(10) unsigned NOT NULL,
    `last_login` int(10) unsigned DEFAULT NULL,
    `force_logout` mediumint(7) unsigned NOT NULL DEFAULT '0',
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    
    -- Índices para performance
    INDEX `status_verified` (`status`, `verified`),
    INDEX `registered` (`registered`),
    INDEX `last_login` (`last_login`)
    
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental
- email: Email único del usuario (hasta 249 caracteres)
- password: Hash de contraseña (bcrypt/argon2)
- username: Nombre de usuario opcional
- status: Estado del usuario (0=normal, 1=archivado, 2=banned, etc.)
- verified: Si el email ha sido verificado (0=no, 1=sí)
- resettable: Si puede resetear contraseña (0=no, 1=sí)
- roles_mask: Máscara de bits para roles (usado por Delight-im/Auth)
- registered: Timestamp UNIX de registro
- last_login: Timestamp UNIX de último login
- force_logout: Fuerza logout en todas las sesiones

ÍNDICES:
- email: UNIQUE para evitar duplicados
- status_verified: Para consultas de usuarios activos verificados
- registered: Para ordenar por fecha de registro
- last_login: Para consultas de actividad reciente

CHARSET:
- utf8mb4_unicode_ci: Soporte completo para Unicode
- latin1_general_cs: Para passwords (case sensitive)

ENGINE:
- MyISAM: Requerido por Delight-im/Auth para esta tabla específica
*/