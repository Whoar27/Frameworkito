<?php
/**
 * FileLogger - Clase para Logging en Archivos
 * AuthManager Base
 * 
 * Maneja el logging avanzado en archivos con:
 * - Rotación automática
 * - Múltiples canales
 * - Formato personalizable
 * - Compresión de logs antiguos
 */

namespace App\Helpers;

class FileLogger {
    private array $config;
    private string $logPath;
    private array $openFiles = [];

    public function __construct(array $config = []) {
        $this->config = $config ?: (config('logging') ?? []);
        $this->logPath = $this->config['files']['path'] ?? LOGS_PATH;

        // Crear directorio si no existe
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }

    /**
     * Escribir log en archivo
     */
    public function log(string $level, string $message, array $context = []): void {
        if (!$this->shouldLog($level)) {
            return;
        }

        try {
            $logEntry = $this->formatLogEntry($level, $message, $context);
            $filePath = $this->getLogFilePath($level);

            $this->writeToFile($filePath, $logEntry);
            $this->checkRotation($filePath, $level);
        } catch (\Exception $e) {
            $this->handleLoggingError($e, $level, $message);
        }
    }

    /**
     * Verificar si se debe hacer log según el nivel
     */
    private function shouldLog(string $level): bool {
        if (!($this->config['log_to_files'] ?? true)) {
            return false;
        }

        $levels = ['debug' => 1, 'info' => 2, 'warning' => 3, 'error' => 4, 'critical' => 5];
        $currentLevel = $levels[$level] ?? 2;
        $minLevel = $levels[$this->config['level'] ?? 'info'] ?? 2;

        return $currentLevel >= $minLevel;
    }

    /**
     * Formatear entrada de log
     */
    private function formatLogEntry(string $level, string $message, array $context): string {
        $channelConfig = $this->config['files']['channels'][$level] ?? [];

        // Verificar si el canal está habilitado
        if (!($channelConfig['enabled'] ?? true)) {
            return '';
        }

        // Preparar contexto con información automática
        $enrichedContext = $this->enrichContext($context);

        // Sanitizar datos sensibles
        $sanitizeRules = $this->config['auto_context']['sanitize'] ?? [];
        $enrichedContext = $this->sanitizeData($enrichedContext, $sanitizeRules);

        // Obtener formato
        $format = $this->getLogFormat($level, $channelConfig);

        return $this->applyFormat($format, $level, $message, $enrichedContext, $channelConfig);
    }

    /**
     * Enriquecer contexto con información automática
     */
    private function enrichContext(array $context): array {
        $autoContext = $this->config['auto_context']['include'] ?? [];
        $enriched = $context;

        if ($autoContext['timestamp'] ?? true) {
            $enriched['timestamp'] = date($this->config['files']['timestamp_format'] ?? 'Y-m-d H:i:s');
        }

        if ($autoContext['ip_address'] ?? true) {
            $enriched['ip'] = get_client_ip();
        }

        if ($autoContext['user_agent'] ?? true) {
            $enriched['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        }

        if ($autoContext['request_uri'] ?? true) {
            $enriched['uri'] = $_SERVER['REQUEST_URI'] ?? 'cli';
        }

        if ($autoContext['http_method'] ?? true) {
            $enriched['method'] = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        }

        if ($autoContext['user_id'] ?? true && isset($_SESSION['user_id'])) {
            $enriched['user_id'] = $_SESSION['user_id'];
        }

        if ($autoContext['memory_usage'] ?? false) {
            $enriched['memory'] = round(memory_get_usage() / 1024, 2) . 'KB';
        }

        return $enriched;
    }

    /**
     * Sanitizar datos sensibles
     */
    private function sanitizeData(array $data, array $rules): array {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeData($value, $rules);
                continue;
            }

            $lowerKey = strtolower($key);
            foreach ($rules as $pattern => $replacement) {
                if (strpos($lowerKey, strtolower($pattern)) !== false) {
                    $data[$key] = $replacement;
                    break;
                }
            }
        }

        return $data;
    }

    /**
     * Obtener formato de log
     */
    private function getLogFormat(string $level, array $channelConfig): string {
        // Formato específico del canal
        if (!empty($channelConfig['format'])) {
            return $channelConfig['format'];
        }

        // Formato específico del nivel
        $levelFormats = $this->config['formatting']['level_formats'] ?? [];
        if (!empty($levelFormats[$level])) {
            return $levelFormats[$level];
        }

        // Formato por defecto
        return $this->config['formatting']['default_format'] ?? '[{timestamp}] {level}: {message} {context}';
    }

    /**
     * Aplicar formato al log
     */
    private function applyFormat(string $format, string $level, string $message, array $context, array $channelConfig): string {
        $replacements = [
            '{timestamp}' => $context['timestamp'] ?? date('Y-m-d H:i:s'),
            '{level}' => strtoupper($level),
            '{message}' => $this->limitLength($message, $this->config['formatting']['max_message_length'] ?? 1000),
            '{context}' => $this->formatContext($context),
            '{ip}' => $context['ip'] ?? 'unknown',
            '{user_id}' => $context['user_id'] ?? 'guest',
            '{uri}' => $context['uri'] ?? 'unknown',
            '{method}' => $context['method'] ?? 'unknown',
        ];

        // Información de archivo y línea para debug
        if ($channelConfig['include_location'] ?? false) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $caller = $this->findCaller($trace);
            $replacements['{file}'] = basename($caller['file'] ?? 'unknown');
            $replacements['{line}'] = $caller['line'] ?? 'unknown';
        }

        // Stack trace para errores
        if ($channelConfig['include_trace'] ?? false) {
            $replacements['{trace}'] = $this->formatStackTrace();
        }

        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }

    /**
     * Formatear contexto para display
     */
    private function formatContext(array $context): string {
        // Remover campos ya incluidos en el formato
        $excluded = ['timestamp', 'ip', 'user_id', 'uri', 'method', 'user_agent'];
        $filtered = array_diff_key($context, array_flip($excluded));

        if (empty($filtered)) {
            return '';
        }

        $contextString = json_encode($filtered, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this->limitLength($contextString, $this->config['formatting']['max_context_length'] ?? 2000);
    }

    /**
     * Limitar longitud de string
     */
    private function limitLength(string $text, int $maxLength): string {
        if (strlen($text) <= $maxLength) {
            return $text;
        }

        return substr($text, 0, $maxLength - 3) . '...';
    }

    /**
     * Encontrar el caller real del log (no las funciones helper)
     */
    private function findCaller(array $trace): array {
        $skipFunctions = ['file_log', 'log', 'formatLogEntry', 'applyFormat'];

        foreach ($trace as $frame) {
            if (!in_array($frame['function'] ?? '', $skipFunctions)) {
                return $frame;
            }
        }

        return $trace[0] ?? [];
    }

    /**
     * Formatear stack trace
     */
    private function formatStackTrace(int $limit = 10): string {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit + 5);
        $formatted = [];

        $count = 0;
        foreach ($trace as $i => $frame) {
            if ($count >= $limit) break;

            // Saltar funciones internas de logging
            if (in_array($frame['function'] ?? '', ['file_log', 'log', 'formatLogEntry'])) {
                continue;
            }

            $file = basename($frame['file'] ?? 'unknown');
            $line = $frame['line'] ?? 'unknown';
            $function = $frame['function'] ?? 'unknown';
            $class = isset($frame['class']) ? $frame['class'] . '::' : '';

            $formatted[] = "#{$count} {$file}:{$line} {$class}{$function}()";
            $count++;
        }

        return implode(' | ', $formatted);
    }

    /**
     * Obtener ruta del archivo de log
     */
    private function getLogFilePath(string $level): string {
        $channelConfig = $this->config['files']['channels'][$level] ?? [];
        $filename = $channelConfig['filename'] ?? "{$level}.log";

        // Aplicar rotación diaria
        if (($this->config['files']['rotation']['type'] ?? 'daily') === 'daily') {
            $dailyFilename = $channelConfig['daily_filename'] ?? $filename;
            $filename = str_replace('{date}', date('Y-m-d'), $dailyFilename);
        }

        return $this->logPath . '/' . $filename;
    }

    /**
     * Escribir al archivo
     */
    private function writeToFile(string $filePath, string $content): void {
        if (empty($content)) {
            return;
        }

        // Buffer para performance si está habilitado
        if ($this->config['performance']['buffer_enabled'] ?? false) {
            $this->bufferWrite($filePath, $content);
        } else {
            file_put_contents($filePath, $content . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * Escritura con buffer
     */
    private function bufferWrite(string $filePath, string $content): void {
        static $buffers = [];
        static $lastFlush = [];

        $bufferSize = $this->config['performance']['buffer_size'] ?? 100;
        $bufferTimeout = $this->config['performance']['buffer_timeout'] ?? 30;

        if (!isset($buffers[$filePath])) {
            $buffers[$filePath] = [];
            $lastFlush[$filePath] = time();
        }

        $buffers[$filePath][] = $content;

        // Flush si se alcanza el tamaño o el tiempo límite
        if (
            count($buffers[$filePath]) >= $bufferSize ||
            (time() - $lastFlush[$filePath]) >= $bufferTimeout
        ) {

            $content = implode(PHP_EOL, $buffers[$filePath]) . PHP_EOL;
            file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);

            $buffers[$filePath] = [];
            $lastFlush[$filePath] = time();
        }
    }

    /**
     * Verificar y aplicar rotación
     */
    private function checkRotation(string $filePath, string $level): void {
        $rotationConfig = $this->config['files']['rotation'] ?? [];
        $rotationType = $rotationConfig['type'] ?? 'daily';

        if ($rotationType === 'size') {
            $this->checkSizeRotation($filePath, $rotationConfig);
        } elseif ($rotationType === 'daily') {
            $this->cleanOldDailyLogs($rotationConfig);
        }
    }

    /**
     * Rotación por tamaño
     */
    private function checkSizeRotation(string $filePath, array $config): void {
        if (!file_exists($filePath)) {
            return;
        }

        $maxSize = ($config['max_size'] ?? 10) * 1024 * 1024; // MB a bytes

        if (filesize($filePath) > $maxSize) {
            $timestamp = date('Y-m-d-H-i-s');
            $rotatedPath = $filePath . '.' . $timestamp;

            rename($filePath, $rotatedPath);

            // Comprimir si está habilitado
            if ($config['compress_old'] ?? false) {
                $this->compressFile($rotatedPath);
            }
        }
    }

    /**
     * Limpiar logs diarios antiguos
     */
    private function cleanOldDailyLogs(array $config): void {
        $maxDays = $config['max_days'] ?? 30;
        $cutoffDate = date('Y-m-d', strtotime("-{$maxDays} days"));

        $files = glob($this->logPath . '/*.log');
        foreach ($files as $file) {
            // Buscar patrón de fecha en el nombre del archivo
            if (preg_match('/(\d{4}-\d{2}-\d{2})/', basename($file), $matches)) {
                $fileDate = $matches[1];
                if ($fileDate < $cutoffDate) {
                    if ($config['compress_old'] ?? false) {
                        $this->compressFile($file);
                    } else {
                        unlink($file);
                    }
                }
            }
        }
    }

    /**
     * Comprimir archivo
     */
    private function compressFile(string $filePath): void {
        if (!function_exists('gzopen') || !file_exists($filePath)) {
            return;
        }

        $compressedPath = $filePath . '.gz';
        $source = fopen($filePath, 'rb');
        $dest = gzopen($compressedPath, 'wb9');

        if ($source && $dest) {
            while (!feof($source)) {
                gzwrite($dest, fread($source, 8192));
            }

            fclose($source);
            gzclose($dest);
            unlink($filePath);
        }
    }

    /**
     * Manejar errores de logging
     */
    private function handleLoggingError(\Exception $e, string $level, string $message): void {
        $errorMsg = date('Y-m-d H:i:s') . " [LOGGING_ERROR] Level: {$level}, Message: {$message}, Error: " . $e->getMessage() . PHP_EOL;

        // Intentar escribir a archivo de errores de logging
        $errorFile = $this->logPath . '/logging_errors.log';
        @file_put_contents($errorFile, $errorMsg, FILE_APPEND | LOCK_EX);
    }

    /**
     * Flush buffers pendientes
     */
    public function flush(): void {
        // Esta función se puede llamar al final del request para asegurar que todos los buffers se escriban
        if ($this->config['performance']['buffer_enabled'] ?? false) {
            // Implementar flush de buffers pendientes
        }
    }

    /**
     * Obtener estadísticas de logging
     */
    public function getStats(): array {
        $stats = [
            'log_path' => $this->logPath,
            'files' => [],
            'total_size' => 0
        ];

        $files = glob($this->logPath . '/*.log*');
        foreach ($files as $file) {
            $size = filesize($file);
            $stats['files'][basename($file)] = [
                'size' => $size,
                'size_formatted' => format_bytes($size),
                'modified' => date('Y-m-d H:i:s', filemtime($file))
            ];
            $stats['total_size'] += $size;
        }

        $stats['total_size_formatted'] = format_bytes($stats['total_size']);
        $stats['file_count'] = count($stats['files']);

        return $stats;
    }

    /**
     * Destructor - asegurar que se escriban los buffers
     */
    public function __destruct() {
        $this->flush();
    }
}
