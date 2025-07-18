-- =====================================================
-- MIGRACIÓN 002: TABLA USERS_CONFIRMATIONS (Delight-im/Auth)
-- Archivo: database/migrations/002_create_users_confirmations_table.sql
-- Descripción: Tokens para verificación de email
-- Dependencias: 001_create_users_table.sql
-- =====================================================

CREATE TABLE `users_confirmations` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned NOT NULL,
    `email` varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL,
    `selector` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `expires` int(10) unsigned NOT NULL,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    
    -- Índices para performance
    KEY `email_expires` (`email`, `expires`),
    KEY `user_id` (`user_id`),
    KEY `expires` (`expires`)
    
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla almacena los tokens de verificación de email que se envían
cuando un usuario se registra o cambia su email.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental
- user_id: ID del usuario (referencia a users.id)
- email: Email a verificar
- selector: Identificador público del token (seguro para URLs)
- token: Hash del token secreto
- expires: Timestamp UNIX de expiración

FLUJO DE VERIFICACIÓN:
1. Usuario se registra/cambia email
2. Se genera selector + token
3. Se envía email con link: /verify?s=selector&t=token
4. Usuario hace clic → sistema verifica selector+token
5. Si válido → marca email como verificado
6. Token se elimina después del uso

ÍNDICES:
- selector: UNIQUE para búsqueda rápida por URL
- email_expires: Para limpiar tokens expirados por email
- user_id: Para encontrar tokens de un usuario específico
- expires: Para limpiar tokens expirados globalmente

SEGURIDAD:
- Selector es público (en URL)
- Token es secreto (hasheado en BD)
- Expires previene uso indefinido
- Case sensitive para mayor entropía
*/