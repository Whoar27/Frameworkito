<?php

/**
 * Configuración de Base de Datos
 * Frameworkito
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Conexión de Base de Datos por Defecto
    |--------------------------------------------------------------------------
    */

    'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Configuraciones de Conexiones de Base de Datos
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'database' => $_ENV['DB_DATABASE'] ?? 'proyecto_base',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
            'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
            'strict' => true,
            'engine' => null,

            // Opciones de PDO
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'"
            ],
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => $_ENV['DB_DATABASE'] ?? __DIR__ . '/../../database/database.sqlite',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
            'foreign_key_constraints' => true,

            // Opciones de PDO
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => $_ENV['DB_CHARSET'],
            'prefix' => $_ENV['DB_PREFIX'],
            'schema' => 'public',
            'sslmode' => 'prefer',

            // Opciones de PDO
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Pool de Conexiones
    |--------------------------------------------------------------------------
    */

    'pool' => [
        // Número máximo de conexiones concurrentes
        'max_connections' => 10,

        // Tiempo de vida de una conexión en segundos
        'connection_timeout' => 30,

        // Tiempo máximo de espera por una conexión
        'wait_timeout' => 5,

        // Reconectar automáticamente si se pierde la conexión
        'auto_reconnect' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Migraciones
    |--------------------------------------------------------------------------
    */

    'migrations' => [
        // Tabla para registrar migraciones ejecutadas
        'table' => 'migrations',

        // Directorio donde están las migraciones
        'path' => __DIR__ . '/../../database/migrations',

        // Orden de ejecución de migraciones
        'order' => [
            '001_create_users_table.sql',
            '002_create_roles_table.sql',
            '003_create_permissions_table.sql',
            '004_create_activity_logs_table.sql',
            '005_create_user_roles_table.sql',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Seeds
    |--------------------------------------------------------------------------
    */

    'seeds' => [
        // Directorio donde están los seeds
        'path' => __DIR__ . '/../../database/seeds',

        // Orden de ejecución de seeds
        'order' => [
            'default_roles.sql',
            'admin_user.sql',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Logs de Base de Datos
    |--------------------------------------------------------------------------
    */

    'logging' => [
        // Activar logging de consultas
        'enabled' => filter_var($_ENV['ENABLE_QUERY_LOG'] ?? false, FILTER_VALIDATE_BOOLEAN),

        // Archivo donde guardar los logs
        'file' => __DIR__ . '/../../logs/database.log',

        // Nivel de logging (all, slow, errors)
        'level' => $_ENV['DB_LOG_LEVEL'] ?? 'errors',

        // Tiempo mínimo en ms para considerar una consulta lenta
        'slow_query_time' => 1000,

        // Máximo número de consultas a loggear por request
        'max_queries_logged' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Backups
    |--------------------------------------------------------------------------
    */

    'backup' => [
        // Directorio donde guardar backups
        'path' => __DIR__ . '/../../storage/backups',

        // Mantener backups por X días
        'retention_days' => 30,

        // Comprimir backups
        'compress' => true,

        // Incluir estructura y datos
        'include_data' => true,
        'include_structure' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración Específica para Delight-im/Auth
    |--------------------------------------------------------------------------
    */

    'auth_tables' => [
        // Nombres de tablas que usa Delight-im/Auth
        'users' => 'users',
        'users_confirmations' => 'users_confirmations',
        'users_remembered' => 'users_remembered',
        'users_resets' => 'users_resets',
        'users_throttling' => 'users_throttling',
        'roles' => 'roles',
        'users_roles' => 'users_roles',
    ],
];

/**
 * Función helper para obtener instancia PDO
 * 
 * @param string $connection Nombre de la conexión
 * @return PDO
 */
if (!function_exists('getPDO')) {
    function getPDO($connection = null) {
        static $instances = [];

        $config = include __DIR__ . '/database.php';
        $connectionName = $connection ?? $config['default'];

        if (!isset($instances[$connectionName])) {
            $connectionConfig = $config['connections'][$connectionName];

            try {
                switch ($connectionConfig['driver']) {
                    case 'mysql':
                        $dsn = "mysql:host={$connectionConfig['host']};port={$connectionConfig['port']};dbname={$connectionConfig['database']};charset={$connectionConfig['charset']}";
                        break;

                    case 'sqlite':
                        $dsn = "sqlite:{$connectionConfig['database']}";
                        break;

                    case 'pgsql':
                        $dsn = "pgsql:host={$connectionConfig['host']};port={$connectionConfig['port']};dbname={$connectionConfig['database']}";
                        break;

                    default:
                        throw new \Exception("Driver de base de datos no soportado: {$connectionConfig['driver']}");
                }

                $instances[$connectionName] = new PDO(
                    $dsn,
                    $connectionConfig['username'] ?? null,
                    $connectionConfig['password'] ?? null,
                    $connectionConfig['options'] ?? []
                );

                // Logging de conexión exitosa (solo si la función existe)
                if (($config['logging']['enabled'] ?? false) && function_exists('file_log')) {
                    file_log('info', "Conexión a base de datos establecida: {$connectionName}");
                }
            } catch (PDOException $e) {
                // Logging de error de conexión (solo si la función existe)
                if (function_exists('file_log')) {
                    file_log('error', "Error conectando a base de datos {$connectionName}: " . $e->getMessage());
                }
                throw new \Exception("Error de conexión a base de datos: " . $e->getMessage());
            }
        }

        return $instances[$connectionName];
    }
}
