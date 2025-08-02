<?php

/**
 * Punto de Entrada Principal
 * Frameworkito
 * 
 * Este archivo actúa como bootstrap de la aplicación:
 * 1. Configura el entorno
 * 2. Carga las dependencias (con o sin Composer)
 * 3. Inicializa la aplicación
 * 4. Maneja errores globales con display elegante
 */

use Dotenv\Dotenv;

// Definir constantes de tiempo
define('APP_START_TIME', microtime(true));
define('APP_START_MEMORY', memory_get_usage());

// Definir rutas del sistema
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('CONFIG_PATH', APP_PATH . '/Config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('LOGS_PATH', ROOT_PATH . '/logs');

/**
 * Crear directorios necesarios si no existen
 */
function ensureDirectories() {
    $directories = [
        LOGS_PATH,
        STORAGE_PATH,
        STORAGE_PATH . '/cache',
        STORAGE_PATH . '/sessions',
        PUBLIC_PATH . '/uploads',
        PUBLIC_PATH . '/uploads/avatars',
        PUBLIC_PATH . '/uploads/documents'
    ];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

/**
 * Autoloader manual PSR-4 para cuando no hay Composer
 */
function manualAutoloader($className) {
    if (strpos($className, 'App\\') !== 0) {
        return false;
    }

    $classPath = str_replace('App\\', '', $className);
    $classPath = str_replace('\\', '/', $classPath);
    $filePath = APP_PATH . '/' . $classPath . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
        return true;
    }

    $classMap = [
        'App\Core\Bootstrap' => APP_PATH . '/Core/Bootstrap.php',
        'App\Core\App' => APP_PATH . '/Core/App.php',
        'App\Core\Router' => APP_PATH . '/Core/Router.php',
        'App\Controllers\BaseController' => APP_PATH . '/Controllers/BaseController.php',
        'App\Controllers\HomeController' => APP_PATH . '/Controllers/HomeController.php',
        'App\Controllers\PublicController' => APP_PATH . '/Controllers/PublicController.php',
        'App\Controllers\AuthController' => APP_PATH . '/Controllers/AuthController.php',
        'App\Controllers\UserController' => APP_PATH . '/Controllers/UserController.php',
        'App\Controllers\ComponentController' => APP_PATH . '/Controllers/ComponentController.php',
        'App\Controllers\ProfileController' => APP_PATH . '/Controllers/ProfileController.php',
        'App\Helpers\Auth' => APP_PATH . '/Helpers/Auth.php',
        'App\Helpers\Session' => APP_PATH . '/Helpers/Session.php',
        'App\Helpers\FileLogger' => APP_PATH . '/Helpers/FileLogger.php',
        'App\Helpers\DatabaseLogger' => APP_PATH . '/Helpers/DatabaseLogger.php',
        'App\Helpers\Redirect' => APP_PATH . '/Helpers/Redirect.php',
        'App\Helpers\UserAgentParser' => APP_PATH . '/Helpers/UserAgentParser.php',
        'App\Helpers\Utils' => APP_PATH . '/Helpers/Utils.php',
        'App\Helpers\Validator' => APP_PATH . '/Helpers/Validator.php',
        'App\Models\BaseModel' => APP_PATH . '/Models/BaseModel.php',
        'App\Models\User' => APP_PATH . '/Models/User.php',
        'App\Models\ActivityLog' => APP_PATH . '/Models/ActivityLog.php',
        'App\Services\AuthService' => APP_PATH . '/Services/AuthService.php',
        'App\Services\UserService' => APP_PATH . '/Services/UserService.php',
        'App\Services\EmailService' => APP_PATH . '/Services/EmailService.php',
        'App\Services\LogService' => APP_PATH . '/Services/LogService.php',
        'App\Services\ActivityLogService' => APP_PATH . '/Services/ActivityLogService.php',
        'App\Validators\LoginValidator' => APP_PATH . '/Validators/LoginValidator.php',
        'App\Validators\RegisterValidator' => APP_PATH . '/Validators/RegisterValidator.php',
        'App\Validators\UserValidator' => APP_PATH . '/Validators/UserValidator.php',
        'App\Middlewares\AuthMiddleware' => APP_PATH . '/Middlewares/AuthMiddleware.php',
        'App\Middlewares\GuestMiddleware' => APP_PATH . '/Middlewares/GuestMiddleware.php',
        'App\Middlewares\RoleMiddleware' => APP_PATH . '/Middlewares/RoleMiddleware.php',
        'App\Middlewares\CSRFMiddleware' => APP_PATH . '/Middlewares/CSRFMiddleware.php',
        'App\Middlewares\MaintenanceMiddleware' => APP_PATH . '/Middlewares/MaintenanceMiddleware.php'
    ];

    if (isset($classMap[$className]) && file_exists($classMap[$className])) {
        require_once $classMap[$className];
        return true;
    }

    return false;
}

/**
 * Mostrar error elegante en pantalla
 */
function displayError($e, $isDebug = false) {
    http_response_code(500);

    $debugInfo = [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'timestamp' => date('Y-m-d H:i:s'),
        'memory_usage' => round(memory_get_usage() / 1024, 2) . ' KB',
        'php_version' => PHP_VERSION
    ];

    require_once APP_PATH . '/Views/errors/error-index.php';
}

/**
 * Obtener sugerencias basadas en el error
 */
function getSuggestions($errorMessage) {
    $suggestions = [];

    if (strpos($errorMessage, 'Composer') !== false) {
        $suggestions[] = 'Ejecuta <code>composer install</code> para instalar las dependencias.';
        $suggestions[] = 'O continúa sin Composer - el sistema puede funcionar sin él temporalmente.';
    }

    if (strpos($errorMessage, 'file_exists') !== false || strpos($errorMessage, 'No such file') !== false) {
        $suggestions[] = 'Verifica que todos los archivos del proyecto estén en su lugar.';
        $suggestions[] = 'Asegúrate de que la estructura de carpetas sea correcta.';
    }

    if (strpos($errorMessage, 'Permission denied') !== false) {
        $suggestions[] = 'Verifica los permisos de las carpetas (755 para directorios, 644 para archivos).';
    }

    if (strpos($errorMessage, 'Class') !== false && strpos($errorMessage, 'not found') !== false) {
        $suggestions[] = 'Verifica que el archivo de la clase exista en la ubicación correcta.';
        $suggestions[] = 'Revisa el autoloader manual en index.php.';
    }

    if (empty($suggestions)) {
        $suggestions[] = 'Revisa el log de errores para más detalles.';
        $suggestions[] = 'Verifica la configuración en los archivos .env y /Config/.';
    }

    return $suggestions;
}

try {
    if (version_compare(PHP_VERSION, '8.0.0', '<')) {
        throw new \Exception('Frameworkito requiere PHP 8.0 o superior. Versión actual: ' . PHP_VERSION);
    }

    $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'json'];
    foreach ($requiredExtensions as $extension) {
        if (!extension_loaded($extension)) {
            throw new \Exception("Extensión PHP requerida no encontrada: {$extension}");
        }
    }

    ensureDirectories();

    date_default_timezone_set('America/Bogota');

    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', LOGS_PATH . '/php_errors.log');

    $autoloaderPath = ROOT_PATH . '/vendor/autoload.php';
    $usingComposer = false;

    if (file_exists($autoloaderPath)) {
        require_once $autoloaderPath;
        $usingComposer = true;

        // ✅ Cargar variables de entorno
        $dotenv = Dotenv::createImmutable(ROOT_PATH);
        $dotenv->safeLoad();
    } else {
        spl_autoload_register('manualAutoloader');
    }

    $helpersPath = APP_PATH . '/Helpers/Functions.php';
    if (file_exists($helpersPath)) {
        require_once $helpersPath;
    } else {
        throw new \Exception("Archivo de funciones helper no encontrado: {$helpersPath}");
    }

    if (!file_exists(CONFIG_PATH . '/app.php')) {
        throw new \Exception("Archivo de configuración principal no encontrado: " . CONFIG_PATH . '/app.php');
    }

    $bootstrap = new \App\Core\Bootstrap();
    $app = $bootstrap->createApplication();

    if (function_exists('file_log')) {
        file_log('info', 'Aplicación iniciada correctamente', [
            'using_composer' => $usingComposer,
            'php_version' => PHP_VERSION,
            'memory_usage' => round(memory_get_usage() / 1024, 2) . ' KB'
        ]);
    }

    $app->run();
} catch (\Exception $e) {
    $isDebug = false;

    if (file_exists(ROOT_PATH . '/.env')) {
        $envContent = file_get_contents(ROOT_PATH . '/.env');
        if (preg_match('/APP_DEBUG\s*=\s*true/i', $envContent)) {
            $isDebug = true;
        }
    }

    if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true') {
        $isDebug = true;
    }

    $errorMessage = date('Y-m-d H:i:s') . " [CRITICAL] Bootstrap Error: " . $e->getMessage() . "\n";
    $errorMessage .= "Stack trace: " . $e->getTraceAsString() . "\n";
    $errorMessage .= "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";
    $errorMessage .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "\n\n";

    if (is_dir(LOGS_PATH) && is_writable(LOGS_PATH)) {
        file_put_contents(LOGS_PATH . '/bootstrap_errors.log', $errorMessage, FILE_APPEND | LOCK_EX);
    }

    displayError($e, $isDebug);
    exit(1);
}

register_shutdown_function(function () {
    $executionTime = microtime(true) - APP_START_TIME;
    $memoryUsage = memory_get_peak_usage() - APP_START_MEMORY;

    if (
        function_exists('file_log') &&
        isset($_ENV['APP_DEBUG']) &&
        $_ENV['APP_DEBUG'] === 'true'
    ) {
        file_log('debug', "Request completed", [
            'execution_time' => round($executionTime * 1000, 2) . 'ms',
            'memory_usage' => round($memoryUsage / 1024, 2) . 'KB',
            'peak_memory' => round(memory_get_peak_usage() / 1024, 2) . 'KB',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ]);
    }
});
