<?php

/**
 * Funciones Globales del Sistema
 * Frameworkito
 * 
 * Funciones helper disponibles globalmente en toda la aplicación
 * VERSIÓN SEGURA SIN ACCESO A $_SESSION EN LOGGING
 */

// Prevenir ejecución directa
if (!defined('APP_PATH')) {
    die('Acceso no autorizado');
}

/**
 * =============================================================================
 * FUNCIONES DE LOGGING
 * =============================================================================
 */

/**
 * Log en archivos
 * 
 * @param string $type Tipo de log (info, error, debug, warning, auth, critical)
 * @param string $message Mensaje del log
 * @param array $context Información adicional (opcional)
 */
function file_log(string $type, string $message, array $context = []): void {
    static $config = null;
    static $logPath = null;

    // Cargar configuración una sola vez
    if ($config === null) {
        $configFile = CONFIG_PATH . '/logging.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
            $logPath = $config['files']['path'] ?? LOGS_PATH;
        } else {
            $logPath = LOGS_PATH ?? __DIR__ . '/../../logs';
        }
    }

    // Verificar si el logging está habilitado
    if (!($config['log_to_files'] ?? true)) {
        return;
    }

    // Verificar nivel mínimo de log
    $levels = ['debug' => 1, 'info' => 2, 'warning' => 3, 'error' => 4, 'critical' => 5];
    $currentLevel = $levels[$type] ?? 2;
    $minLevel = $levels[$config['level'] ?? 'info'] ?? 2;

    if ($currentLevel < $minLevel) {
        return;
    }

    try {
        // Crear directorio si no existe
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }

        // Obtener configuración del canal
        $channelConfig = $config['files']['channels'][$type] ?? $config['files']['channels']['info'] ?? [];

        // Verificar si el canal está habilitado
        if (!($channelConfig['enabled'] ?? true)) {
            return;
        }

        // Determinar nombre del archivo
        $filename = $channelConfig['filename'] ?? "{$type}.log";

        // Aplicar rotación diaria si está configurada
        if (($config['files']['rotation']['type'] ?? 'daily') === 'daily') {
            $dailyFilename = $channelConfig['daily_filename'] ?? $channelConfig['filename'] ?? "{$type}.log";
            $filename = str_replace('{date}', date('Y-m-d'), $dailyFilename);
        }

        $filePath = $logPath . '/' . $filename;

        // Preparar contexto
        $contextData = [];

        // Agregar contexto automático (SIN ACCESO A $_SESSION)
        if ($config['auto_context']['include'] ?? []) {
            $autoContext = $config['auto_context']['include'];

            if ($autoContext['timestamp'] ?? true) {
                $contextData['timestamp'] = date($config['files']['timestamp_format'] ?? 'Y-m-d H:i:s');
            }

            if ($autoContext['ip_address'] ?? true) {
                $contextData['ip'] = get_client_ip();
            }

            if ($autoContext['user_agent'] ?? true) {
                $contextData['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            }

            if ($autoContext['request_uri'] ?? true) {
                $contextData['uri'] = $_SERVER['REQUEST_URI'] ?? 'unknown';
            }

            if ($autoContext['http_method'] ?? true) {
                $contextData['method'] = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
            }

            // ELIMINADO: No accedemos a $_SESSION en funciones de logging
            // if ($autoContext['user_id'] ?? true) {
            //     $contextData['user_id'] = obtener_user_id_seguro();
            // }

            if ($autoContext['memory_usage'] ?? false) {
                $contextData['memory'] = round(memory_get_usage() / 1024, 2) . 'KB';
            }
        }

        // Combinar contexto proporcionado con contexto automático
        $finalContext = array_merge($contextData, $context);

        // Sanitizar datos sensibles
        $sanitizeRules = $config['auto_context']['sanitize'] ?? [];
        $finalContext = sanitize_sensitive_data($finalContext, $sanitizeRules);

        // Aplicar formato
        $format = $channelConfig['format'] ?? $config['formatting']['level_formats'][$type] ?? $config['formatting']['default_format'] ?? '[{timestamp}] {level}: {message} {context}';

        $logEntry = format_log_entry($format, $type, $message, $finalContext, $channelConfig);

        // Escribir al archivo
        file_put_contents($filePath, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);

        // Verificar rotación por tamaño
        check_file_rotation($filePath, $config);
    } catch (\Exception $e) {
        // Si falla el logging, intentar escribir error básico
        $errorMsg = date('Y-m-d H:i:s') . " [ERROR] Failed to write log: " . $e->getMessage() . PHP_EOL;
        @file_put_contents($logPath . '/logging_errors.log', $errorMsg, FILE_APPEND | LOCK_EX);
    }
}

/**
 * Log en base de datos
 * 
 * @param string $type Tipo de log (auth, security, activity, admin, system, error)
 * @param string $message Mensaje descriptivo
 * @param array $context Datos adicionales (opcional)
 * @param int|null $userId ID del usuario (opcional, debe ser proporcionado explícitamente)
 */
function bd_log(string $type, string $message, array $context = [], ?int $userId = null): void {
    static $config = null;
    static $pdo = null;

    // Cargar configuración una sola vez
    if ($config === null) {
        $configFile = CONFIG_PATH . '/logging.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
        }
    }

    // Verificar si el logging a BD está habilitado
    if (!($config['log_to_database'] ?? true)) {
        return;
    }

    // Verificar si el tipo de log está habilitado
    $typeConfig = $config['database']['log_types'][$type] ?? null;
    if (!$typeConfig || !($typeConfig['enabled'] ?? true)) {
        return;
    }

    try {
        // Obtener conexión PDO
        if ($pdo === null) {
            if (function_exists('getPDO')) {
                $pdo = getPDO();
            } else {
                return; // No hay conexión disponible
            }
        }

        // Determinar acción específica del contexto o usar genérica
        $action = $context['action'] ?? 'general';

        // Preparar datos del contexto
        $contextData = [];

        // Agregar información automática
        if ($config['auto_context']['include'] ?? []) {
            $autoContext = $config['auto_context']['include'];

            if ($autoContext['ip_address'] ?? true) {
                $contextData['ip_address'] = get_client_ip();
            }

            if ($autoContext['user_agent'] ?? true) {
                $contextData['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            }

            if ($autoContext['request_uri'] ?? true) {
                $contextData['request_uri'] = $_SERVER['REQUEST_URI'] ?? 'unknown';
            }

            if ($autoContext['http_method'] ?? true) {
                $contextData['http_method'] = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
            }
        }

        // Combinar contexto
        $finalContext = array_merge($contextData, $context);

        // Sanitizar datos sensibles
        $sanitizeRules = $config['auto_context']['sanitize'] ?? [];
        $finalContext = sanitize_sensitive_data($finalContext, $sanitizeRules);

        // Preparar datos para insertar
        $data = [
            'user_id' => $userId,
            'type' => $type,
            'action' => $action,
            'message' => substr($message, 0, 1000), // Limitar mensaje
            'context' => json_encode($finalContext, JSON_UNESCAPED_UNICODE),
            'ip_address' => get_client_ip(),
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 500),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Nombre de tabla
        $table = $config['database']['table'] ?? 'activity_logs';

        // Preparar query
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $fieldsList = implode(', ', $fields);

        $sql = "INSERT INTO {$table} ({$fieldsList}) VALUES ({$placeholders})";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
    } catch (\Exception $e) {
        // Si falla el logging a BD, hacer log a archivo
        file_log('error', 'Error escribiendo a base de datos: ' . $e->getMessage(), [
            'original_type' => $type,
            'original_message' => $message,
            'original_context' => $context
        ]);
    }
}

/**
 * =============================================================================
 * FUNCIONES HELPER PARA LOGGING
 * =============================================================================
 */

/**
 * Formatear entrada de log
 */
function format_log_entry(string $format, string $level, string $message, array $context, array $channelConfig): string {
    $replacements = [
        '{timestamp}' => $context['timestamp'] ?? date('Y-m-d H:i:s'),
        '{level}' => strtoupper($level),
        '{message}' => $message,
        '{context}' => !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '',
        '{ip}' => $context['ip'] ?? get_client_ip(),
        '{user_id}' => $context['user_id'] ?? 'guest',
        '{uri}' => $context['uri'] ?? ($_SERVER['REQUEST_URI'] ?? 'unknown'),
        '{method}' => $context['method'] ?? ($_SERVER['REQUEST_METHOD'] ?? 'unknown'),
    ];

    // Agregar información de archivo y línea para debug
    if ($channelConfig['include_location'] ?? false) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $trace[2] ?? $trace[1] ?? $trace[0];
        $replacements['{file}'] = basename($caller['file'] ?? 'unknown');
        $replacements['{line}'] = $caller['line'] ?? 'unknown';
    }

    // Agregar stack trace para errores
    if ($channelConfig['include_trace'] ?? false) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $traceString = '';
        foreach ($trace as $i => $frame) {
            if ($i > 0) { // Saltar la función actual
                $file = basename($frame['file'] ?? 'unknown');
                $line = $frame['line'] ?? 'unknown';
                $function = $frame['function'] ?? 'unknown';
                $traceString .= "#{$i} {$file}:{$line} {$function}() ";
            }
        }
        $replacements['{trace}'] = trim($traceString);
    }

    return str_replace(array_keys($replacements), array_values($replacements), $format);
}

/**
 * Sanitizar datos sensibles
 */
function sanitize_sensitive_data(array $data, array $sanitizeRules): array {
    foreach ($data as $key => $value) {
        $lowerKey = strtolower($key);
        foreach ($sanitizeRules as $pattern => $replacement) {
            if (strpos($lowerKey, strtolower($pattern)) !== false) {
                $data[$key] = $replacement;
                break;
            }
        }

        // Sanitizar arrays anidados
        if (is_array($value)) {
            $data[$key] = sanitize_sensitive_data($value, $sanitizeRules);
        }
    }

    return $data;
}

/**
 * Verificar rotación de archivos
 */
function check_file_rotation(string $filePath, array $config): void {
    $rotationConfig = $config['files']['rotation'] ?? [];

    if (($rotationConfig['type'] ?? 'daily') === 'size') {
        $maxSize = ($rotationConfig['max_size'] ?? 10) * 1024 * 1024; // MB a bytes

        if (file_exists($filePath) && filesize($filePath) > $maxSize) {
            $rotatedPath = $filePath . '.' . date('Y-m-d-H-i-s');
            rename($filePath, $rotatedPath);

            // Comprimir si está habilitado
            if ($rotationConfig['compress_old'] ?? false && function_exists('gzopen')) {
                $gz = gzopen($rotatedPath . '.gz', 'wb9');
                gzwrite($gz, file_get_contents($rotatedPath));
                gzclose($gz);
                unlink($rotatedPath);
            }
        }
    }
}

/**
 * =============================================================================
 * FUNCIONES DE UTILIDAD GENERAL
 * =============================================================================
 */

/**
 * Obtener IP real del cliente
 */
function get_client_ip(): string {
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
 * =============================================================================
 * FUNCIONES DE SESIÓN (CON PROTECCIÓN COMPLETA)
 * =============================================================================
 */

/**
 * Función segura para obtener user_id
 */
function obtener_user_id_seguro(): ?int {
    try {
        if (session_status() == 2 && isset($_SESSION) && is_array($_SESSION) && array_key_exists('user_id', $_SESSION)) {
            return $_SESSION['user_id'];
        }
    } catch (\Exception $e) {
        // Silenciar cualquier error
    }
    return null;
}

/**
 * Generar token CSRF
 */
function csrf_token(): string {
    try {
        // Asegurar que la sesión esté iniciada
        if (session_status() == 1) {
            session_start();
        }

        // Verificar que $_SESSION esté disponible
        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }

        if (!array_key_exists('_token', $_SESSION)) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    } catch (\Exception $e) {
        // Generar token temporal si falla
        return bin2hex(random_bytes(32));
    }
}

/**
 * Verificar token CSRF
 */
function verify_csrf_token(string $token): bool {
    try {
        // Verificar que la sesión esté iniciada y $_SESSION disponible
        if (session_status() != 2 || !isset($_SESSION) || !is_array($_SESSION)) {
            return false;
        }

        return array_key_exists('_token', $_SESSION) && hash_equals($_SESSION['_token'], $token);
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Generar campo oculto de token CSRF para formularios
 */
function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

/**
 * Escapar HTML para prevenir XSS
 */
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redireccionar y terminar ejecución
 */
function redirect(string $url, int $statusCode = 302): void {
    header("Location: {$url}", true, $statusCode);
    exit();
}

/**
 * Generar URL base de la aplicación
 */
function base_url(string $path = ''): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');

    $url = "{$protocol}://{$host}{$basePath}";

    if ($path) {
        $path = ltrim($path, '/');
        $url .= '/' . $path;
    }

    return $url;
}

/**
 * Formatear bytes a formato legible
 */
function format_bytes(int $bytes, int $precision = 2): string {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Verificar si string es JSON válido
 */
function is_json(string $string): bool {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Obtener configuración por clave con notación de puntos
 */
function config(string $key, $default = null) {
    static $config = null;

    if ($config === null) {
        // Cargar configuraciones
        $configFiles = [
            'app' => CONFIG_PATH . '/app.php',
            'database' => CONFIG_PATH . '/database.php',
            'auth' => CONFIG_PATH . '/auth.php',
            'logging' => CONFIG_PATH . '/logging.php',
            'mail' => CONFIG_PATH . '/mail.php',
        ];

        $config = [];
        foreach ($configFiles as $name => $file) {
            if (file_exists($file)) {
                $config[$name] = require $file;
            }
        }
    }

    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (!is_array($value) || !array_key_exists($k, $value)) {
            return $default;
        }
        $value = $value[$k];
    }

    return $value;
}

/**
 * Debug helper - solo en desarrollo
 */
function dd(...$vars): void {
    if (config('app.debug', false)) {
        echo '<pre style="background: #f5f5f5; border: 1px solid #ddd; padding: 10px; margin: 10px; border-radius: 4px;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        exit(1);
    }
}

/**
 * Verificar si la aplicación está en modo debug
 */
function is_debug(): bool {
    return config('app.debug', false);
}

/**
 * Obtener valor de variable de entorno
 */
function env(string $key, $default = null) {
    return $_ENV[$key] ?? $default;
}

/**
 * Genera la URL absoluta para un recurso público (assets).
 * Solo aplica versión a archivos modificables propios (CSS y JS), no a vendors ni imágenes.
 */
function asset(string $path): string {
    // Si es una URL externa, devolver tal cual
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $path = ltrim($path, '/');
    $appUrl = rtrim(env('APP_URL', ''), '/');
    $url = $appUrl . '/' . $path;

    // Aplicar versión solo si es CSS o JS propio, no en vendors u otros
    if (
        (str_starts_with($path, 'assets/css/') || str_starts_with($path, 'assets/js/')) &&
        !str_starts_with($path, 'assets/vendors/')
    ) {
        $version = config('app.version', '1.0.0');
        $url .= '?v=' . $version;
    }

    return $url;
}

