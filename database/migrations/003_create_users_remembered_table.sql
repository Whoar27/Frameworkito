-- =====================================================
-- MIGRACIÓN 003: TABLA USERS_REMEMBERED (Delight-im/Auth)
-- Archivo: database/migrations/003_create_users_remembered_table.sql
-- Descripción: Tokens para función "Recordarme"
-- Dependencias: 001_create_users_table.sql
-- =====================================================

CREATE TABLE `users_remembered` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user` int(10) unsigned NOT NULL,
    `selector` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `expires` int(10) unsigned NOT NULL,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    
    -- Índices para performance
    KEY `user` (`user`),
    KEY `expires` (`expires`),
    KEY `user_expires` (`user`, `expires`)
    
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla almacena los tokens de "Recordarme" que permiten a los usuarios
mantener sesión activa por períodos largos (ej: 30 días).

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental (BIGINT para muchos tokens)
- user: ID del usuario (referencia a users.id)
- selector: Identificador público del token
- token: Hash del token secreto
- expires: Timestamp UNIX de expiración del token

FLUJO DE "RECORDARME":
1. Usuario hace login marcando "Recordarme"
2. Se genera selector + token único
3. Se guarda token hasheado en BD
4. Se envía cookie con selector + token plano
5. En futuras visitas, se valida cookie contra BD
6. Si válido → login automático sin contraseña

SEGURIDAD:
- Un token por dispositivo/navegador
- Tokens expiran automáticamente
- Se invalidan al hacer logout manual
- Hash del token en BD (no plano)
- Selector público + token secreto

DIFERENCIAS CON CONFIRMATIONS:
- Selector más largo (24 vs 16 chars)
- BIGINT id (más tokens esperados)
- Duración más larga (días vs horas)
- No se elimina al usar (se renueva)

ÍNDICES:
- selector: UNIQUE para búsqueda por cookie
- user: Para encontrar todos los tokens de un usuario
- expires: Para limpiar tokens expirados
- user_expires: Para tokens activos de un usuario
*/