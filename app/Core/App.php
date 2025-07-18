<?php

/**
 * App - Clase Principal de la Aplicación
 * AuthManager Base
 * 
 * Maneja:
 * 1. Inicialización final de la aplicación
 * 2. Coordinación entre servicios
 * 3. Ejecución del ciclo de vida de la request
 */

namespace App\Core;

class App {
    private array $config;
    private array $services;
    private Router $router;
    private ?\PDO $pdo = null;

    public function __construct(array $config, array $services) {
        $this->config = $config;
        $this->services = $services;
        $this->pdo = $services['pdo'] ?? null;

        // Inicializar router
        $this->router = new Router($config, $services);
    }

    /**
     * Ejecutar la aplicación
     */
    public function run(): void {
        try {
            // 1. Iniciar sesión si no está iniciada
            $this->startSession();

            // 2. Procesar la request
            $this->processRequest();

            // 3. Log de la request si está habilitado
            $this->logRequest();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Iniciar sesión
     */
    private function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();

            // Regenerar ID de sesión por seguridad
            $sessionConfig = $this->config['app']['session'];
            if ($sessionConfig['regenerate_id']) {
                // Regenerar cada 15 minutos
                $regenerateInterval = 15 * 60; // 15 minutos
                if (
                    !isset($_SESSION['last_regeneration']) ||
                    time() - $_SESSION['last_regeneration'] > $regenerateInterval
                ) {
                    session_regenerate_id(true);
                    $_SESSION['last_regeneration'] = time();
                }
            }

            // Validar IP de sesión si está configurado
            if ($sessionConfig['validate_ip']) {
                $currentIp = $this->getClientIp();
                if (isset($_SESSION['ip_address'])) {
                    if ($_SESSION['ip_address'] !== $currentIp) {
                        // IP cambió, destruir sesión por seguridad
                        session_destroy();
                        session_start();

                        if (function_exists('file_log')) {
                            file_log('warning', 'Sesión destruida por cambio de IP', [
                                'previous_ip' => $_SESSION['ip_address'] ?? 'unknown',
                                'current_ip' => $currentIp,
                                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
                            ]);
                        }
                    }
                } else {
                    $_SESSION['ip_address'] = $currentIp;
                }
            }

            // Establecer tiempo de último acceso
            $_SESSION['last_activity'] = time();
        }
    }

    /**
     * Obtener IP real del cliente
     */
    private function getClientIp(): string {
        // Headers comunes de proxies y load balancers
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Procesar la request
     */
    private function processRequest(): void {
        // Obtener información de la request
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $this->getRequestUri();

        // Log de inicio de request
        if (function_exists('file_log') && $this->config['app']['debug']) {
            file_log('debug', 'Procesando request', [
                'method' => $method,
                'uri' => $uri,
                'ip' => $this->getClientIp(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
        }

        // Manejar CORS para peticiones OPTIONS
        if ($method === 'OPTIONS') {
            $this->handleCorsOptions();
            return;
        }

        // Verificar método HTTP permitido
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        if (!in_array($method, $allowedMethods)) {
            $this->sendHttpError(405, 'Método no permitido');
            return;
        }

        // Procesar con el router
        $this->router->dispatch($method, $uri);
    }

    /**
     * Obtener URI limpia de la request
     */
    private function getRequestUri(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Remover query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Limpiar barras duplicadas y normalizar
        $uri = preg_replace('#/+#', '/', $uri);

        // Asegurar que empiece con /
        if (!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        // Remover barra final excepto para la raíz
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    /**
     * Manejar peticiones OPTIONS para CORS
     */
    private function handleCorsOptions(): void {
        // Headers básicos de CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-Token');
        header('Access-Control-Max-Age: 86400'); // 24 horas

        http_response_code(200);
        exit();
    }

    /**
     * Enviar error HTTP
     */
    private function sendHttpError(int $code, string $message): void {
        http_response_code($code);

        // Si es una petición AJAX, enviar JSON
        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => true,
                'message' => $message,
                'code' => $code
            ]);
        } else {
            // Mostrar página de error
            $errorFile = APP_PATH . "/Views/errors/{$code}.php";
            if (file_exists($errorFile)) {
                include $errorFile;
            } else {
                include APP_PATH . '/Views/errors/default.php';
            }
        }

        exit();
    }

    /**
     * Verificar si es una petición AJAX
     */
    private function isAjaxRequest(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Log de la request
     */
    private function logRequest(): void {
        // Solo hacer log si está habilitado
        if (
            !function_exists('file_log') ||
            !($this->config['app']['development']['log_performance'] ?? false)
        ) {
            return;
        }

        $executionTime = microtime(true) - APP_START_TIME;
        $memoryUsage = memory_get_peak_usage() - APP_START_MEMORY;

        file_log('info', 'Request completada', [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'uri' => $this->getRequestUri(),
            'response_code' => http_response_code(),
            'execution_time' => round($executionTime * 1000, 2) . 'ms',
            'memory_usage' => round($memoryUsage / 1024, 2) . 'KB',
            'peak_memory' => round(memory_get_peak_usage() / 1024, 2) . 'KB'
        ]);
    }

    /**
     * Manejar excepciones
     */
    private function handleException(\Exception $e): void {
        // Log de la excepción
        if (function_exists('file_log')) {
            file_log('error', 'Excepción en App::run(): ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ]);
        }

        // Determinar código de respuesta
        $responseCode = 500;
        if (method_exists($e, 'getStatusCode')) {
            $responseCode = $e->getStatusCode();
        }

        $this->sendHttpError($responseCode, 'Error interno del servidor');
    }

    /**
     * Obtener configuración
     */
    public function getConfig(string $key = null, $default = null) {
        if ($key === null) {
            return $this->config;
        }

        // Soportar notación de puntos para claves anidadas
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Obtener servicio
     */
    public function getService(string $name) {
        return $this->services[$name] ?? null;
    }

    /**
     * Obtener conexión PDO
     */
    public function getPDO(): ?\PDO {
        return $this->pdo;
    }

    /**
     * Verificar si la aplicación está en modo debug
     */
    public function isDebug(): bool {
        return $this->config['app']['debug'] ?? false;
    }

    /**
     * Verificar entorno de la aplicación
     */
    public function getEnvironment(): string {
        return $this->config['app']['env'] ?? 'production';
    }

    /**
     * Verificar si está en modo producción
     */
    public function isProduction(): bool {
        return $this->getEnvironment() === 'production';
    }

    /**
     * Verificar si está en modo desarrollo
     */
    public function isDevelopment(): bool {
        return $this->getEnvironment() === 'development';
    }

    /**
     * Verificar si está en modo testing
     */
    public function isTesting(): bool {
        return $this->getEnvironment() === 'testing';
    }

    /**
     * Obtener información del sistema para debugging
     */
    public function getSystemInfo(): array {
        return [
            'php_version' => PHP_VERSION,
            'environment' => $this->getEnvironment(),
            'debug_mode' => $this->isDebug(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'extensions' => [
                'pdo' => extension_loaded('pdo'),
                'mbstring' => extension_loaded('mbstring'),
                'openssl' => extension_loaded('openssl'),
                'json' => extension_loaded('json'),
                'gd' => extension_loaded('gd'),
                'curl' => extension_loaded('curl'),
            ],
            'app_start_time' => APP_START_TIME,
            'current_memory' => memory_get_usage(),
            'peak_memory' => memory_get_peak_usage(),
        ];
    }
}
