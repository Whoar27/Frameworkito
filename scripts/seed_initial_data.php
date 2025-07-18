<?php
// Cargar autoload de Composer y variables de entorno
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Inicializar Dotenv para cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Seeder de Datos Iniciales - AuthManager Base
 * 
 * Crea datos básicos necesarios para el funcionamiento del sistema
 * 
 * Uso:
 * php seed_initial_data.php
 */

// Definir rutas
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/Config');

// Cargar configuraciones
require_once CONFIG_PATH . '/database.php';

// Cargar Composer (para Delight-im/Auth)
if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
    $usingComposer = true;
} else {
    echo "❌ Composer no encontrado. Ejecuta: composer install\n";
    exit(1);
}

use Delight\Auth\Auth;

class InitialDataSeeder {
    private PDO $pdo;
    private Auth $auth;
    private array $config;

    public function __construct() {
        $this->config = require CONFIG_PATH . '/database.php';
        $this->connectDatabase();
        $this->initAuth();
    }

    /**
     * Conectar a la base de datos
     */
    private function connectDatabase(): void {
        try {
            $connection = $this->config['connections'][$this->config['default']];

            $dsn = "mysql:host={$connection['host']};port={$connection['port']};dbname={$connection['database']};charset={$connection['charset']}";

            $this->pdo = new PDO(
                $dsn,
                $connection['username'],
                $connection['password'],
                $connection['options'] ?? []
            );

            $this->output("✅ Conectado a la base de datos: {$connection['database']}", 'success');
        } catch (PDOException $e) {
            $this->output("❌ Error conectando a la base de datos: " . $e->getMessage(), 'error');
            exit(1);
        }
    }

    /**
     * Inicializar Delight-im/Auth
     */
    private function initAuth(): void {
        try {
            $this->auth = new Auth($this->pdo);
            $this->output("✅ Sistema de autenticación inicializado", 'success');
        } catch (Exception $e) {
            $this->output("❌ Error inicializando Auth: " . $e->getMessage(), 'error');
            exit(1);
        }
    }

    /**
     * Ejecutar seeding completo
     */
    public function seed(): void {
        $this->output("🌱 Iniciando seeding de datos iniciales...", 'info');

        // Verificar que las tablas existan
        $this->verifyTables();

        // Crear usuario administrador
        $this->createAdminUser();

        // Asignar rol al administrador
        $this->assignAdminRole();

        // Crear usuarios de prueba
        $this->createTestUsers();

        // Crear logs de actividad iniciales
        $this->createInitialLogs();

        $this->output("✅ Seeding completado exitosamente", 'success');
        $this->showCredentials();
    }

    /**
     * Verificar que las tablas necesarias existan
     */
    private function verifyTables(): void {
        $requiredTables = [
            'users',
            'roles',
            'users_roles',
            'activity_logs',
            'system_settings'
        ];

        foreach ($requiredTables as $table) {
            $stmt = $this->pdo->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() === 0) {
                $this->output("❌ Tabla requerida no encontrada: {$table}", 'error');
                $this->output("💡 Ejecuta primero: php migrate.php", 'info');
                exit(1);
            }
        }

        $this->output("✅ Todas las tablas requeridas están presentes", 'success');
    }

    /**
     * Crear usuario administrador
     */
    private function createAdminUser(): void {
        try {
            // Verificar si ya existe admin
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute(['admin@authmanager.com']);

            if ($stmt->rowCount() > 0) {
                $this->output("⏭️  Usuario admin ya existe, saltando creación", 'warning');
                return;
            }

            // Crear usuario admin
            $userId = $this->auth->register('admin@authmanager.com', 'Admin123!', 'Administrator');

            // Marcar email como verificado automáticamente
            $stmt = $this->pdo->prepare("UPDATE users SET verified = 1 WHERE id = ?");
            $stmt->execute([$userId]);

            $this->output("✅ Usuario administrador creado (ID: {$userId})", 'success');

            // Log de creación
            $this->logActivity($userId, 'admin', 'user_created', 'Usuario administrador creado por seeder', [
                'email' => 'admin@authmanager.com',
                'role' => 'admin',
                'created_by' => 'system_seeder'
            ]);
        } catch (Exception $e) {
            $this->output("❌ Error creando usuario admin: " . $e->getMessage(), 'error');
        }
    }

    /**
     * Asignar rol de administrador
     */
    private function assignAdminRole(): void {
        try {
            // Obtener ID del usuario admin
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute(['admin@authmanager.com']);
            $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$adminUser) {
                $this->output("❌ Usuario admin no encontrado", 'error');
                return;
            }

            // Obtener ID del rol admin
            $stmt = $this->pdo->prepare("SELECT id FROM roles WHERE slug = ?");
            $stmt->execute(['admin']);
            $adminRole = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$adminRole) {
                $this->output("❌ Rol admin no encontrado", 'error');
                return;
            }

            // Verificar si ya tiene el rol asignado
            $stmt = $this->pdo->prepare("SELECT id FROM users_roles WHERE user_id = ? AND role_id = ?");
            $stmt->execute([$adminUser['id'], $adminRole['id']]);

            if ($stmt->rowCount() > 0) {
                $this->output("⏭️  Rol admin ya asignado, saltando", 'warning');
                return;
            }

            // Asignar rol
            $stmt = $this->pdo->prepare("
                INSERT INTO users_roles (user_id, role_id, assigned_by, assigned_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([$adminUser['id'], $adminRole['id'], $adminUser['id']]);

            $this->output("✅ Rol administrador asignado", 'success');

            // Log de asignación de rol
            $this->logActivity($adminUser['id'], 'admin', 'role_assigned', 'Rol administrador asignado por seeder', [
                'role_id' => $adminRole['id'],
                'role_name' => 'admin',
                'assigned_by' => 'system_seeder'
            ]);
        } catch (Exception $e) {
            $this->output("❌ Error asignando rol admin: " . $e->getMessage(), 'error');
        }
    }

    /**
     * Crear usuarios de prueba
     */
    private function createTestUsers(): void {
        $testUsers = [
            [
                'email' => 'user@authmanager.com',
                'password' => 'User123!',
                'username' => 'TestUser',
                'role' => 'user'
            ],
            [
                'email' => 'moderator@authmanager.com',
                'password' => 'Mod123!',
                'username' => 'TestModerator',
                'role' => 'moderator'
            ]
        ];

        foreach ($testUsers as $userData) {
            try {
                // Verificar si ya existe
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$userData['email']]);

                if ($stmt->rowCount() > 0) {
                    $this->output("⏭️  Usuario {$userData['email']} ya existe", 'warning');
                    continue;
                }

                // Crear usuario
                $userId = $this->auth->register($userData['email'], $userData['password'], $userData['username']);

                // Marcar como verificado
                $stmt = $this->pdo->prepare("UPDATE users SET verified = 1 WHERE id = ?");
                $stmt->execute([$userId]);

                // Asignar rol
                $this->assignRoleToUser($userId, $userData['role']);

                $this->output("✅ Usuario de prueba creado: {$userData['email']} (Rol: {$userData['role']})", 'success');

                // Log de creación
                $this->logActivity($userId, 'admin', 'user_created', "Usuario de prueba creado: {$userData['role']}", [
                    'email' => $userData['email'],
                    'role' => $userData['role'],
                    'created_by' => 'system_seeder'
                ]);
            } catch (Exception $e) {
                $this->output("❌ Error creando usuario {$userData['email']}: " . $e->getMessage(), 'error');
            }
        }
    }

    /**
     * Asignar rol a un usuario
     */
    private function assignRoleToUser(int $userId, string $roleSlug): void {
        try {
            // Obtener ID del rol
            $stmt = $this->pdo->prepare("SELECT id FROM roles WHERE slug = ?");
            $stmt->execute([$roleSlug]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$role) {
                $this->output("❌ Rol no encontrado: {$roleSlug}", 'error');
                return;
            }

            // Asignar rol
            $stmt = $this->pdo->prepare("
                INSERT INTO users_roles (user_id, role_id, assigned_by, assigned_at) 
                VALUES (?, ?, 1, NOW())
            ");
            $stmt->execute([$userId, $role['id']]);
        } catch (Exception $e) {
            $this->output("❌ Error asignando rol {$roleSlug}: " . $e->getMessage(), 'error');
        }
    }

    /**
     * Crear logs de actividad iniciales
     */
    private function createInitialLogs(): void {
        $initialLogs = [
            [
                'user_id' => null,
                'type' => 'system',
                'action' => 'system_initialized',
                'message' => 'Sistema AuthManager Base inicializado correctamente',
                'context' => json_encode([
                    'version' => '1.0.0',
                    'initialized_at' => date('Y-m-d H:i:s'),
                    'seeder_run' => true
                ])
            ],
            [
                'user_id' => null,
                'type' => 'system',
                'action' => 'database_seeded',
                'message' => 'Base de datos poblada con datos iniciales',
                'context' => json_encode([
                    'users_created' => 3,
                    'roles_available' => 4,
                    'settings_configured' => true
                ])
            ]
        ];

        foreach ($initialLogs as $log) {
            $this->logActivity(
                $log['user_id'],
                $log['type'],
                $log['action'],
                $log['message'],
                json_decode($log['context'], true)
            );
        }

        $this->output("✅ Logs de actividad iniciales creados", 'success');
    }

    /**
     * Registrar actividad en logs
     */
    private function logActivity(?int $userId, string $type, string $action, string $message, array $context = []): void {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO activity_logs (user_id, type, action, message, context, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $userId,
                $type,
                $action,
                $message,
                json_encode($context),
                '127.0.0.1', // IP del seeder
                'AuthManager Seeder v1.0' // User agent del seeder
            ]);
        } catch (Exception $e) {
            // Silenciar errores de logging para no interrumpir el seeding
        }
    }

    /**
     * Mostrar credenciales de acceso
     */
    private function showCredentials(): void {
        $this->output("\n" . str_repeat("=", 60), 'info');
        $this->output("🔐 CREDENCIALES DE ACCESO CREADAS:", 'info');
        $this->output(str_repeat("=", 60), 'info');

        $this->output("👑 ADMINISTRADOR:", 'success');
        $this->output("   Email: admin@authmanager.com", 'success');
        $this->output("   Contraseña: Admin123!", 'success');
        $this->output("   Rol: Administrador (acceso completo)", 'success');

        $this->output("\n👤 USUARIO DE PRUEBA:", 'info');
        $this->output("   Email: user@authmanager.com", 'info');
        $this->output("   Contraseña: User123!", 'info');
        $this->output("   Rol: Usuario estándar", 'info');

        $this->output("\n🛡️  MODERADOR DE PRUEBA:", 'warning');
        $this->output("   Email: moderator@authmanager.com", 'warning');
        $this->output("   Contraseña: Mod123!", 'warning');
        $this->output("   Rol: Moderador", 'warning');

        $this->output("\n" . str_repeat("=", 60), 'info');
        $this->output("💡 Usa estas credenciales para probar el sistema", 'info');
        $this->output(str_repeat("=", 60) . "\n", 'info');
    }

    /**
     * Mostrar mensaje con color
     */
    private function output(string $message, string $type = 'info'): void {
        $colors = [
            'info' => "\033[36m",     // Cyan
            'success' => "\033[32m",  // Green
            'warning' => "\033[33m",  // Yellow
            'error' => "\033[31m",    // Red
            'reset' => "\033[0m"      // Reset
        ];

        $color = $colors[$type] ?? $colors['info'];
        echo $color . $message . $colors['reset'] . PHP_EOL;
    }
}

// Ejecutar seeder
try {
    $seeder = new InitialDataSeeder();
    $seeder->seed();
} catch (Exception $e) {
    echo "\033[31m❌ Error: " . $e->getMessage() . "\033[0m" . PHP_EOL;
    exit(1);
}
