<?php

/**
 * Bootstrap - Inicializador de la Aplicación
 * AuthManager Base
 * 
 * Se encarga de:
 * 1. Cargar configuraciones
 * 2. Configurar el entorno
 * 3. Inicializar servicios
 * 4. Crear la instancia de la aplicación
 */

namespace App\Core;

use App\Middlewares\MaintenanceMiddleware;

class Bootstrap {
    private array $config = [];
    private array $services = [];

    /**
     * Crear y configurar la aplicación
     */
    public function createApplication(): App {
        // 1. Cargar configuraciones
        $this->loadConfiguration();

        // 2. Verificar modo mantenimiento (ANTES que cualquier otra cosa)
        $this->checkMaintenanceMode();

        // 3. Configurar entorno de PHP
        $this->configureEnvironment();

        // 4. Configurar manejo de errores
        $this->configureErrorHandling();

        // 5. Inicializar servicios básicos
        $this->initializeServices();

        // 6. Configurar sesiones
        $this->configureSessions();

        // 7. Aplicar configuraciones de seguridad
        $this->applySecurity();

        // 8. Crear y retornar la aplicación
        return new App($this->config, $this->services);
    }

    /**
     * Verificar modo mantenimiento
     */
    private function checkMaintenanceMode(): void {
        try {
            // Solo verificar si no estamos en CLI
            if (php_sapi_name() === 'cli') {
                return;
            }

            // Crear middleware de mantenimiento
            $maintenanceMiddleware = new MaintenanceMiddleware($this->config);

            // Agregar IPs adicionales desde configuración si existen
            if (isset($this->config['app']['maintenance']['allowed_ips'])) {
                foreach ($this->config['app']['maintenance']['allowed_ips'] as $ip) {
                    $maintenanceMiddleware->addAllowedIp($ip);
                }
            }

            // Agregar rutas adicionales desde configuración si existen
            if (isset($this->config['app']['maintenance']['allowed_routes'])) {
                foreach ($this->config['app']['maintenance']['allowed_routes'] as $route) {
                    $maintenanceMiddleware->addAllowedRoute($route);
                }
            }

            // Ejecutar verificación de mantenimiento
            $maintenanceMiddleware->handle();
        } catch (\Exception $e) {
            // Log del error pero no fallar el bootstrap por esto
            if (function_exists('file_log')) {
                file_log('error', 'Error en verificación de mantenimiento: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    /**
     * Cargar todas las configuraciones
     */
    private function loadConfiguration(): void {
        $configFiles = [
            'app' => CONFIG_PATH . '/app.php',
            'database' => CONFIG_PATH . '/database.php',
            'auth' => CONFIG_PATH . '/auth.php',
            'logging' => CONFIG_PATH . '/logging.php',
            'mail' => CONFIG_PATH . '/mail.php',
        ];

        foreach ($configFiles as $key => $file) {
            if (file_exists($file)) {
                $this->config[$key] = require $file;
            } else {
                throw new \Exception("Archivo de configuración no encontrado: {$file}");
            }
        }

        // Validar configuraciones críticas
        $this->validateConfiguration();
    }

    /**
     * Validar que las configuraciones críticas estén presentes
     */
    private function validateConfiguration(): void {
        // Validar APP_KEY
        if (empty($this->config['app']['key'])) {
            throw new \Exception('APP_KEY no está configurada. Ejecuta: php generate-key.php');
        }

        if (strlen($this->config['app']['key']) < 32) {
            throw new \Exception('APP_KEY debe tener al menos 32 caracteres.');
        }

        // Validar configuración de base de datos
        if (empty($this->config['database']['connections'][$this->config['database']['default']])) {
            throw new \Exception('Configuración de base de datos no válida.');
        }
    }

    /**
     * Configurar entorno de PHP
     */
    private function configureEnvironment(): void {
        // Configurar zona horaria
        if (!empty($this->config['app']['timezone'])) {
            date_default_timezone_set($this->config['app']['timezone']);
        }

        // Configurar entorno de desarrollo/producción
        if ($this->config['app']['env'] === 'production') {
            // Configuración para producción
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        } else {
            // Configuración para desarrollo
            if ($this->config['app']['development']['display_errors']) {
                ini_set('display_errors', '1');
            }
            if ($this->config['app']['development']['report_all_errors']) {
                error_reporting(E_ALL);
            }
        }

        // Configurar límites de memoria y tiempo
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '30');

        // Configurar uploads
        ini_set('upload_max_filesize', $this->config['app']['files']['max_file_size'] . 'M');
        ini_set('post_max_size', ($this->config['app']['files']['max_file_size'] + 2) . 'M');
    }

    /**
     * Configurar manejo de errores personalizado
     */
    private function configureErrorHandling(): void {
        // Manejador de errores personalizado
        set_error_handler(function ($severity, $message, $file, $line) {
            // No procesar errores suprimidos con @
            if (!(error_reporting() & $severity)) {
                return false;
            }

            $errorTypes = [
                E_ERROR => 'ERROR',
                E_WARNING => 'WARNING',
                E_PARSE => 'PARSE',
                E_NOTICE => 'NOTICE',
                E_CORE_ERROR => 'CORE_ERROR',
                E_CORE_WARNING => 'CORE_WARNING',
                E_COMPILE_ERROR => 'COMPILE_ERROR',
                E_COMPILE_WARNING => 'COMPILE_WARNING',
                E_USER_ERROR => 'USER_ERROR',
                E_USER_WARNING => 'USER_WARNING',
                E_USER_NOTICE => 'USER_NOTICE',
                E_STRICT => 'STRICT',
                E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
                E_DEPRECATED => 'DEPRECATED',
                E_USER_DEPRECATED => 'USER_DEPRECATED',
            ];

            $errorType = $errorTypes[$severity] ?? 'UNKNOWN';

            // Log del error
            if (function_exists('file_log')) {
                file_log('error', "PHP {$errorType}: {$message}", [
                    'file' => $file,
                    'line' => $line,
                    'severity' => $severity
                ]);
            }

            // En desarrollo, mostrar el error
            if ($this->config['app']['debug']) {
                echo "<div style='background:#ffebee;border:1px solid #f44336;padding:10px;margin:10px;border-radius:4px;'>";
                echo "<strong>PHP {$errorType}:</strong> {$message}<br>";
                echo "<strong>Archivo:</strong> {$file}:{$line}";
                echo "</div>";
            }

            return true;
        });

        // Manejador de excepciones no capturadas
        set_exception_handler(function ($exception) {
            if (function_exists('file_log')) {
                file_log('critical', 'Excepción no capturada: ' . $exception->getMessage(), [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString()
                ]);
            }

            // Mostrar página de error
            $this->handleUncaughtException($exception);
        });

        // Manejador de errores fatales
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                if (function_exists('file_log')) {
                    file_log('critical', 'Error fatal: ' . $error['message'], [
                        'file' => $error['file'],
                        'line' => $error['line'],
                        'type' => $error['type']
                    ]);
                }
            }
        });
    }

    /**
     * Manejar excepciones no capturadas
     */
    private function handleUncaughtException(\Throwable $exception): void {
        http_response_code(500);

        if ($this->config['app']['debug']) {
            // Mostrar error detallado en desarrollo
            include APP_PATH . '/Views/errors/debug.php';
        } else {
            // Mostrar error genérico en producción
            include APP_PATH . '/Views/errors/500.php';
        }

        exit(1);
    }

    /**
     * Inicializar servicios básicos
     */
    private function initializeServices(): void {
        // Crear directorio de logs si no existe
        if (!is_dir(LOGS_PATH)) {
            mkdir(LOGS_PATH, 0755, true);
        }

        // Crear directorio de caché si no existe
        $cachePath = $this->config['app']['cache']['file_path'] ?? ROOT_PATH . '/storage/cache';
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0755, true);
        }

        // Crear directorio de uploads si no existe
        $uploadPath = PUBLIC_PATH . '/../' . ($this->config['app']['files']['upload_path'] ?? 'uploads');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Inicializar conexión a base de datos
        try {
            $this->services['pdo'] = $this->initializeDatabase();
        } catch (\Exception $e) {
            if (function_exists('file_log')) {
                file_log('critical', 'Error inicializando base de datos: ' . $e->getMessage());
            }
            throw new \Exception('Error de conexión a base de datos: ' . $e->getMessage());
        }

        // Inicializar otros servicios según configuración
        $autoLoadServices = $this->config['app']['providers']['auto_load'] ?? [];
        foreach ($autoLoadServices as $service) {
            // Aquí se cargarían automáticamente los servicios configurados
            // Por ahora solo registramos que deben cargarse
            $this->services['auto_load'][] = $service;
        }
    }

    /**
     * Inicializar conexión a base de datos
     * SOLUCIÓN FINAL - IGNORAR opciones del config y usar solo las nuestras
     */
    private function initializeDatabase(): \PDO {
        $defaultConnection = $this->config['database']['default'];
        $connectionConfig = $this->config['database']['connections'][$defaultConnection];

        // Crear DSN según el driver
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

        // SOLUCIÓN: Usar SOLO nuestras opciones, IGNORAR las del config
        $finalOptions = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];

        // Solo agregar charset para MySQL
        if ($connectionConfig['driver'] === 'mysql' && !empty($connectionConfig['charset'])) {
            $finalOptions[\PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$connectionConfig['charset']}";
        }

        try {
            return new \PDO(
                $dsn,
                $connectionConfig['username'] ?? null,
                $connectionConfig['password'] ?? null,
                $finalOptions
            );
        } catch (\PDOException $e) {
            // Log del error específico de PDO
            if (function_exists('file_log')) {
                file_log('critical', 'Error PDO en Bootstrap: ' . $e->getMessage(), [
                    'dsn' => $dsn,
                    'options' => $finalOptions,
                    'config' => $connectionConfig
                ]);
            }
            throw new \Exception("Error de conexión PDO: " . $e->getMessage());
        }
    }

    /**
     * Configurar sesiones
     */
    private function configureSessions(): void {
        $sessionConfig = $this->config['app']['session'];

        // Configurar parámetros de sesión
        ini_set('session.cookie_lifetime', $sessionConfig['lifetime'] * 60);
        ini_set('session.cookie_path', $sessionConfig['cookie_path']);
        ini_set('session.cookie_secure', $sessionConfig['cookie_secure'] ? '1' : '0');
        ini_set('session.cookie_httponly', $sessionConfig['cookie_httponly'] ? '1' : '0');
        ini_set('session.cookie_samesite', $sessionConfig['cookie_samesite']);
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_trans_sid', '0');

        // Configurar nombre de sesión
        session_name($sessionConfig['cookie_name']);

        // Configurar directorio de sesiones si es necesario
        $sessionPath = ROOT_PATH . '/storage/sessions';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0755, true);
        }
        ini_set('session.save_path', $sessionPath);
    }

    /**
     * Aplicar configuraciones de seguridad
     */
    private function applySecurity(): void {
        $securityConfig = $this->config['app']['security'];

        // Forzar HTTPS si está configurado
        if ($securityConfig['force_https'] && !$this->isHttps()) {
            $redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: {$redirectUrl}", true, 301);
            exit();
        }

        // Aplicar headers de seguridad
        if ($securityConfig['security_headers']) {
            foreach ($securityConfig['headers'] as $header => $value) {
                header("{$header}: {$value}");
            }
        }

        // Configurar otras medidas de seguridad
        ini_set('expose_php', '0');
    }

    /**
     * Verificar si la conexión es HTTPS
     */
    private function isHttps(): bool {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            $_SERVER['SERVER_PORT'] == 443 ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
}
