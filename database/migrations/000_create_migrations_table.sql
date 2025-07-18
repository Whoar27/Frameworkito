-- =====================================================
-- MIGRACIÓN 000: TABLA MIGRATIONS (Sistema de Control)
-- Archivo: database/migrations/000_create_migrations_table.sql
-- Descripción: Control de estado de migraciones ejecutadas
-- Dependencias: Ninguna (debe ejecutarse PRIMERA)
-- =====================================================

CREATE TABLE `migrations` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `batch` int(11) NOT NULL,
    `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Índices principales
    PRIMARY KEY (`id`),
    UNIQUE KEY `migration` (`migration`),
    
    -- Índices para performance
    KEY `batch` (`batch`),
    KEY `executed_at` (`executed_at`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS SOBRE LA ESTRUCTURA:
-- =====================================================

/*
PROPÓSITO:
Esta tabla rastrea qué migraciones han sido ejecutadas para evitar
duplicados y permitir rollbacks controlados.

CAMPOS EXPLICADOS:

- id: Clave primaria autoincremental
- migration: Nombre del archivo de migración (ej: "001_create_users_table")
- batch: Número de lote de ejecución (para rollbacks grupales)
- executed_at: Timestamp de cuándo se ejecutó

FUNCIONAMIENTO:

1. Antes de ejecutar migración, verificar si existe en esta tabla
2. Si no existe, ejecutar SQL y registrar en esta tabla
3. Si existe, saltar (ya fue ejecutada)
4. Batch permite agrupar migraciones ejecutadas juntas

EJEMPLO DE DATOS:

id | migration                     | batch | executed_at
---|-------------------------------|-------|------------------
1  | 001_create_users_table        | 1     | 2025-07-14 14:00:00
2  | 002_create_users_confirmations| 1     | 2025-07-14 14:00:01
3  | 003_create_users_remembered   | 1     | 2025-07-14 14:00:02
4  | 006_create_roles_table        | 2     | 2025-07-15 10:30:00

VENTAJAS:

- Idempotencia: Ejecutar script múltiples veces es seguro
- Versionado: Saber exactamente qué está instalado
- Rollback: Revertir por lotes
- Auditoría: Historial de cuándo se ejecutó qué

COMANDOS DEL SISTEMA:

-- Ver migraciones pendientes:
SELECT DISTINCT m.migration 
FROM (
    SELECT '001_create_users_table.sql' as migration
    UNION SELECT '002_create_users_confirmations_table.sql'
    -- ... etc
) m 
LEFT JOIN migrations installed ON m.migration = installed.migration 
WHERE installed.migration IS NULL;

-- Ver última migración ejecutada:
SELECT migration FROM migrations ORDER BY id DESC LIMIT 1;

-- Rollback último batch:
SELECT migration FROM migrations WHERE batch = (
    SELECT MAX(batch) FROM migrations
);
*/