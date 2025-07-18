-- =====================================================
-- MIGRACIÓN 005: TABLA USERS_THROTTLING (Delight-im/Auth)
-- Archivo: database/migrations/005_create_users_throttling_table.sql
-- Descripción: Rate limiting y protección contra ataques
-- Dependencias: Ninguna (independiente)
-- =====================================================

CREATE TABLE `users_throttling` (
    `bucket` varchar(44) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `tokens` float unsigned NOT NULL,
    `replenished_at` int(10) unsigned NOT NULL,
    `expires_at` int(10) unsigned NOT NULL,
    
    -- Índices principales
    PRIMARY KEY (`bucket`),
    
    -- Índices para performance
    KEY `expires_at` (`expires_at`)
    
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla implementa un sistema de "token bucket" para rate limiting,
protegiendo contra ataques de fuerza bruta y spam.

CAMPOS EXPLICADOS:

- bucket: Identificador único del bucket (hash de IP/email/acción)
- tokens: Número de tokens disponibles (float para precisión)
- replenished_at: Última vez que se rellenaron tokens (UNIX timestamp)
- expires_at: Cuándo expira este bucket (UNIX timestamp)

ALGORITMO TOKEN BUCKET:

1. Cada acción consume 1 token
2. Tokens se recargan automáticamente con el tiempo
3. Si no hay tokens → acción bloqueada
4. Diferentes límites para diferentes acciones

TIPOS DE BUCKETS:

- Login por IP: "login_ip_192.168.1.1"
- Login por email: "login_email_user@domain.com"
- Registro por IP: "register_ip_192.168.1.1"
- Reset por email: "reset_email_user@domain.com"

CONFIGURACIÓN TÍPICA:

Login:
- Capacidad: 5 tokens
- Recarga: 1 token cada 2 minutos
- Ventana: 10 minutos

Registro:
- Capacidad: 3 tokens
- Recarga: 1 token cada 5 minutos
- Ventana: 15 minutos

VENTAJAS DEL SISTEMA:

- Flexible: Diferentes límites por acción
- Gradual: No bloqueo absoluto, sino ralentización
- Automático: Se autorrepara con el tiempo
- Eficiente: Un registro por bucket

BUCKET NAMING:
El campo bucket contiene hash SHA1 de:
- Tipo de acción (login, register, reset)
- Identificador (IP, email, user_id)
- Sal opcional para seguridad

LIMPIEZA:
- expires_at determina cuándo limpiar buckets viejos
- Proceso de limpieza debe ejecutarse periódicamente
- Buckets expirados = no hay actividad reciente

SEGURIDAD:
- Previene ataques de fuerza bruta
- Protege contra enumeración de usuarios
- Limita spam de registros/emails
- Escalable a millones de IPs
*/