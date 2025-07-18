-- =====================================================
-- MIGRACIÓN 004: TABLA USERS_RESETS (Delight-im/Auth)
-- Archivo: database/migrations/004_create_users_resets_table.sql
-- Descripción: Tokens para reset de contraseñas
-- Dependencias: 001_create_users_table.sql
-- =====================================================

CREATE TABLE `users_resets` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user` int(10) unsigned NOT NULL,
    `selector` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `expires` int(10) unsigned NOT NULL,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    
    -- Índices para performance
    KEY `user_expires` (`user`, `expires`),
    KEY `expires` (`expires`)
    
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla almacena los tokens de reset de contraseña que se envían
cuando un usuario olvida su contraseña.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental (BIGINT para muchos resets)
- user: ID del usuario que solicita reset (referencia a users.id)
- selector: Identificador público del token (20 chars)
- token: Hash del token secreto
- expires: Timestamp UNIX de expiración (típicamente 1-24 horas)

FLUJO DE RESET:
1. Usuario solicita "Olvidé mi contraseña"
2. Se genera selector + token único
3. Se envía email con link: /reset-password?s=selector&t=token
4. Usuario hace clic en link antes de expiración
5. Sistema valida selector + token
6. Si válido → permite establecer nueva contraseña
7. Token se elimina después del uso exitoso

DIFERENCIAS CON OTRAS TABLAS:
- Selector: 20 chars (intermedio)
- Duración: Corta (1-24 horas típicamente)
- Uso único: Se elimina tras usar
- Crítico para seguridad

SEGURIDAD:
- Expiración corta para minimizar ventana de ataque
- Token se consume al usar (no reutilizable)
- Un solo reset activo por usuario
- Hash del token en BD

ÍNDICES:
- selector: UNIQUE para búsqueda rápida por URL
- user_expires: Para encontrar resets válidos de un usuario
- expires: Para limpiar tokens expirados automáticamente

LIMPIEZA:
Los tokens expirados deben limpiarse regularmente para:
- Mantener tabla pequeña
- Mejorar performance
- Cumplir políticas de retención de datos
*/