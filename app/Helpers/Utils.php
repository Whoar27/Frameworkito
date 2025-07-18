<?php
/**
 * Utils - Utilidades Generales
 * AuthManager Base
 * 
 * Colección de funciones útiles para:
 * - Manipulación de strings
 * - Validaciones
 * - Formateo de datos
 * - Operaciones de archivos
 * - Utilidades de tiempo
 */

namespace App\Helpers;

class Utils {
    /**
     * =============================================================================
     * MANIPULACIÓN DE STRINGS
     * =============================================================================
     */

    /**
     * Convertir string a slug (URL amigable)
     */
    public static function slug(string $text, string $separator = '-'): string {
        // Convertir a minúsculas
        $text = strtolower($text);

        // Reemplazar caracteres especiales
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        // Remover caracteres no alfanuméricos
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);

        // Reemplazar espacios con separador
        $text = preg_replace('/\s+/', $separator, trim($text));

        // Remover separadores duplicados
        $text = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $text);

        return trim($text, $separator);
    }

    /**
     * Truncar texto con elipsis
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length - mb_strlen($suffix)) . $suffix;
    }

    /**
     * Truncar texto por palabras
     */
    public static function truncateWords(string $text, int $words = 20, string $suffix = '...'): string {
        $wordArray = explode(' ', $text);

        if (count($wordArray) <= $words) {
            return $text;
        }

        return implode(' ', array_slice($wordArray, 0, $words)) . $suffix;
    }

    /**
     * Capitalizar primera letra de cada palabra
     */
    public static function title(string $text): string {
        return mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Convertir a camelCase
     */
    public static function camelCase(string $text): string {
        $text = str_replace(['-', '_'], ' ', $text);
        $text = ucwords($text);
        $text = str_replace(' ', '', $text);
        return lcfirst($text);
    }

    /**
     * Convertir a snake_case
     */
    public static function snakeCase(string $text): string {
        $text = preg_replace('/([A-Z])/', '_$1', $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9_]/', '_', $text);
        $text = preg_replace('/_+/', '_', $text);
        return trim($text, '_');
    }

    /**
     * Generar string aleatorio
     */
    public static function randomString(int $length = 10, string $characters = null): string {
        $characters = $characters ?: '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    /**
     * Limpiar y sanitizar string
     */
    public static function sanitize(string $text, bool $removeSpecialChars = false): string {
        // Remover espacios extra
        $text = trim($text);
        $text = preg_replace('/\s+/', ' ', $text);

        // Remover caracteres de control
        $text = preg_replace('/[\x00-\x1F\x7F]/', '', $text);

        if ($removeSpecialChars) {
            // Remover caracteres especiales
            $text = preg_replace('/[^\w\s-._@]/', '', $text);
        }

        return $text;
    }

    /**
     * =============================================================================
     * VALIDACIONES
     * =============================================================================
     */

    /**
     * Validar email
     */
    public static function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validar URL
     */
    public static function isValidUrl(string $url): bool {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validar IP
     */
    public static function isValidIp(string $ip): bool {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validar fecha
     */
    public static function isValidDate(string $date, string $format = 'Y-m-d'): bool {
        $dateObj = DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) === $date;
    }

    /**
     * Validar número de teléfono básico
     */
    public static function isValidPhone(string $phone): bool {
        // Patrón básico para números internacionales
        $pattern = '/^[\+]?[1-9][\d]{0,15}$/';
        $cleanPhone = preg_replace('/[^\d\+]/', '', $phone);
        return preg_match($pattern, $cleanPhone);
    }

    /**
     * Validar que string contenga solo letras
     */
    public static function isAlpha(string $text): bool {
        return ctype_alpha($text);
    }

    /**
     * Validar que string contenga solo números
     */
    public static function isNumeric(string $text): bool {
        return is_numeric($text);
    }

    /**
     * Validar que string contenga solo letras y números
     */
    public static function isAlphaNumeric(string $text): bool {
        return ctype_alnum($text);
    }

    /**
     * =============================================================================
     * FORMATEO DE DATOS
     * =============================================================================
     */

    /**
     * Formatear número con separadores de miles
     */
    public static function formatNumber(float $number, int $decimals = 0, string $decimalPoint = '.', string $thousandsSep = ','): string {
        return number_format($number, $decimals, $decimalPoint, $thousandsSep);
    }

    /**
     * Formatear bytes a formato legible
     */
    public static function formatBytes(int $bytes, int $precision = 2): string {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Formatear porcentaje
     */
    public static function formatPercentage(float $value, int $decimals = 1): string {
        return self::formatNumber($value, $decimals) . '%';
    }

    /**
     * Formatear moneda
     */
    public static function formatCurrency(float $amount, string $currency = 'USD', string $locale = 'en_US'): string {
        if (class_exists('NumberFormatter')) {
            $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($amount, $currency);
        }

        // Fallback básico
        $symbol = match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'COP' => '$',
            default => $currency . ' '
        };

        return $symbol . self::formatNumber($amount, 2);
    }

    /**
     * =============================================================================
     * UTILIDADES DE TIEMPO
     * =============================================================================
     */

    /**
     * Formatear fecha de manera legible
     */
    public static function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string {
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }

    /**
     * Obtener diferencia de tiempo legible (hace X tiempo)
     */
    public static function timeAgo(string $datetime): string {
        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return 'hace ' . $time . ' segundos';
        } elseif ($time < 3600) {
            return 'hace ' . round($time / 60) . ' minutos';
        } elseif ($time < 86400) {
            return 'hace ' . round($time / 3600) . ' horas';
        } elseif ($time < 2592000) {
            return 'hace ' . round($time / 86400) . ' días';
        } elseif ($time < 31536000) {
            return 'hace ' . round($time / 2592000) . ' meses';
        } else {
            return 'hace ' . round($time / 31536000) . ' años';
        }
    }

    /**
     * Verificar si fecha está en rango
     */
    public static function isDateInRange(string $date, string $startDate, string $endDate): bool {
        $checkDate = strtotime($date);
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);

        return $checkDate >= $startTime && $checkDate <= $endTime;
    }

    /**
     * Obtener edad desde fecha de nacimiento
     */
    public static function getAge(string $birthDate): int {
        $birth = new DateTime($birthDate);
        $today = new DateTime();
        return $birth->diff($today)->y;
    }

    /**
     * =============================================================================
     * OPERACIONES DE ARCHIVOS
     * =============================================================================
     */

    /**
     * Obtener extensión de archivo
     */
    public static function getFileExtension(string $filename): string {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Generar nombre único para archivo
     */
    public static function generateUniqueFilename(string $originalName, string $directory = ''): string {
        $extension = self::getFileExtension($originalName);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $basename = self::slug($basename);

        $filename = $basename . '.' . $extension;
        $counter = 1;

        while (file_exists($directory . '/' . $filename)) {
            $filename = $basename . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return $filename;
    }

    /**
     * Verificar si archivo es imagen
     */
    public static function isImage(string $filename): bool {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        $extension = self::getFileExtension($filename);
        return in_array($extension, $imageExtensions);
    }

    /**
     * Verificar si archivo es documento
     */
    public static function isDocument(string $filename): bool {
        $docExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];
        $extension = self::getFileExtension($filename);
        return in_array($extension, $docExtensions);
    }

    /**
     * Leer archivo como base64
     */
    public static function fileToBase64(string $filePath): string {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Archivo no encontrado: {$filePath}");
        }

        $content = file_get_contents($filePath);
        return base64_encode($content);
    }

    /**
     * =============================================================================
     * UTILIDADES DE ARRAYS
     * =============================================================================
     */

    /**
     * Obtener valor de array con notación de puntos
     */
    public static function arrayGet(array $array, string $key, $default = null) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Establecer valor en array con notación de puntos
     */
    public static function arraySet(array &$array, string $key, $value): void {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
    }

    /**
     * Aplanar array multidimensional
     */
    public static function arrayFlatten(array $array, string $prefix = ''): array {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . '.' . $key : $key;

            if (is_array($value)) {
                $result = array_merge($result, self::arrayFlatten($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Filtrar array por claves
     */
    public static function arrayOnly(array $array, array $keys): array {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Excluir claves de array
     */
    public static function arrayExcept(array $array, array $keys): array {
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * =============================================================================
     * UTILIDADES DE SISTEMA
     * =============================================================================
     */

    /**
     * Obtener información del sistema
     */
    public static function getSystemInfo(): array {
        return [
            'php_version' => PHP_VERSION,
            'php_sapi' => PHP_SAPI,
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'extensions' => get_loaded_extensions(),
            'timezone' => date_default_timezone_get(),
            'current_memory_usage' => self::formatBytes(memory_get_usage()),
            'peak_memory_usage' => self::formatBytes(memory_get_peak_usage()),
        ];
    }

    /**
     * Verificar si extensión PHP está cargada
     */
    public static function hasExtension(string $extension): bool {
        return extension_loaded($extension);
    }

    /**
     * Ejecutar comando de sistema de manera segura
     */
    public static function execSafe(string $command, array $allowedCommands = []): array {
        // Solo permitir comandos específicos por seguridad
        if (!empty($allowedCommands)) {
            $commandBase = explode(' ', $command)[0];
            if (!in_array($commandBase, $allowedCommands)) {
                throw new InvalidArgumentException("Comando no permitido: {$commandBase}");
            }
        }

        $output = [];
        $returnCode = 0;

        exec(escapeshellcmd($command), $output, $returnCode);

        return [
            'output' => $output,
            'return_code' => $returnCode,
            'success' => $returnCode === 0
        ];
    }

    /**
     * =============================================================================
     * UTILIDADES DE DEBUGGING
     * =============================================================================
     */

    /**
     * Dump variable con formato legible
     */
    public static function dump($var, bool $die = false): void {
        echo '<pre style="background: #f5f5f5; border: 1px solid #ddd; padding: 10px; margin: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;">';
        var_dump($var);
        echo '</pre>';

        if ($die) {
            exit(1);
        }
    }

    /**
     * Obtener trace de llamadas
     */
    public static function getBacktrace(int $limit = 10): array {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit + 1);
        array_shift($trace); // Remover la llamada actual

        return array_map(function ($frame) {
            return [
                'file' => basename($frame['file'] ?? 'unknown'),
                'line' => $frame['line'] ?? 'unknown',
                'function' => $frame['function'] ?? 'unknown',
                'class' => $frame['class'] ?? null
            ];
        }, $trace);
    }

    /**
     * Medir tiempo de ejecución
     */
    public static function benchmark(callable $callback): array {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $result = $callback();

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        return [
            'result' => $result,
            'execution_time' => round(($endTime - $startTime) * 1000, 2) . 'ms',
            'memory_used' => self::formatBytes($endMemory - $startMemory),
            'peak_memory' => self::formatBytes(memory_get_peak_usage())
        ];
    }

    /**
     * =============================================================================
     * UTILIDADES MISCELÁNEAS
     * =============================================================================
     */

    /**
     * Generar UUID v4
     */
    public static function generateUuid(): string {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // version 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variant bits

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Generar hash seguro
     */
    public static function generateHash(string $data, string $algorithm = 'sha256'): string {
        return hash($algorithm, $data);
    }

    /**
     * Verificar hash
     */
    public static function verifyHash(string $data, string $hash, string $algorithm = 'sha256'): bool {
        return hash_equals($hash, hash($algorithm, $data));
    }

    /**
     * Generar token seguro
     */
    public static function generateToken(int $length = 32): string {
        return bin2hex(random_bytes($length));
    }

    /**
     * Escapar HTML de manera segura
     */
    public static function escapeHtml(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Limpiar texto para uso en JavaScript
     */
    public static function escapeJs(string $text): string {
        return json_encode($text, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    /**
     * =============================================================================
     * UTILIDADES DE CONFIGURACIÓN
     * =============================================================================
     */

    /**
     * Parsear archivo INI de manera segura
     */
    public static function parseIniFile(string $filePath, bool $processSections = false): array {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Archivo INI no encontrado: {$filePath}");
        }

        $config = parse_ini_file($filePath, $processSections);

        if ($config === false) {
            throw new RuntimeException("Error parseando archivo INI: {$filePath}");
        }

        return $config;
    }

    /**
     * Escribir array a archivo INI
     */
    public static function writeIniFile(string $filePath, array $data): bool {
        $content = '';

        foreach ($data as $section => $values) {
            if (is_array($values)) {
                $content .= "[{$section}]\n";
                foreach ($values as $key => $value) {
                    $content .= "{$key} = " . self::formatIniValue($value) . "\n";
                }
                $content .= "\n";
            } else {
                $content .= "{$section} = " . self::formatIniValue($values) . "\n";
            }
        }

        return file_put_contents($filePath, $content) !== false;
    }

    /**
     * Formatear valor para archivo INI
     */
    private static function formatIniValue($value): string {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_string($value) && (strpos($value, ' ') !== false || strpos($value, '"') !== false)) {
            return '"' . str_replace('"', '\"', $value) . '"';
        } else {
            return (string) $value;
        }
    }

    /**
     * Convertir objeto a array recursivamente
     */
    public static function objectToArray($object): array {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }

        if (is_array($object)) {
            return array_map([self::class, 'objectToArray'], $object);
        }

        return $object;
    }

    /**
     * Convertir array a objeto recursivamente
     */
    public static function arrayToObject(array $array): object {
        $object = new stdClass();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $object->$key = self::arrayToObject($value);
            } else {
                $object->$key = $value;
            }
        }

        return $object;
    }

    /**
     * =============================================================================
     * UTILIDADES DE RED
     * =============================================================================
     */

    /**
     * Hacer petición HTTP simple
     */
    public static function httpRequest(string $url, array $options = []): array {
        $defaults = [
            'method' => 'GET',
            'headers' => [],
            'data' => null,
            'timeout' => 30,
            'follow_redirects' => true,
            'verify_ssl' => true
        ];

        $options = array_merge($defaults, $options);

        $context = stream_context_create([
            'http' => [
                'method' => $options['method'],
                'header' => implode("\r\n", $options['headers']),
                'content' => $options['data'],
                'timeout' => $options['timeout'],
                'follow_location' => $options['follow_redirects'],
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => $options['verify_ssl'],
                'verify_peer_name' => $options['verify_ssl']
            ]
        ]);

        $response = file_get_contents($url, false, $context);
        $httpCode = 0;
        $headers = [];

        if (isset($http_response_header)) {
            $headers = $http_response_header;
            // Extraer código de respuesta
            if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $headers[0], $matches)) {
                $httpCode = (int) $matches[1];
            }
        }

        return [
            'success' => $response !== false,
            'data' => $response,
            'http_code' => $httpCode,
            'headers' => $headers
        ];
    }

    /**
     * Verificar si URL es accesible
     */
    public static function isUrlAccessible(string $url, int $timeout = 10): bool {
        $result = self::httpRequest($url, [
            'method' => 'HEAD',
            'timeout' => $timeout
        ]);

        return $result['success'] && $result['http_code'] < 400;
    }

    /**
     * Obtener IP pública del servidor
     */
    public static function getPublicIp(): ?string {
        $services = [
            'https://ipinfo.io/ip',
            'https://icanhazip.com',
            'https://ipecho.net/plain'
        ];

        foreach ($services as $service) {
            $result = self::httpRequest($service, ['timeout' => 5]);
            if ($result['success']) {
                $ip = trim($result['data']);
                if (self::isValidIp($ip)) {
                    return $ip;
                }
            }
        }

        return null;
    }

    /**
     * =============================================================================
     * UTILIDADES DE CACHE
     * =============================================================================
     */

    /**
     * Cache simple en archivos
     */
    public static function cache(string $key, $value = null, int $ttl = 3600) {
        $cacheDir = ROOT_PATH . '/storage/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cacheFile = $cacheDir . '/' . md5($key) . '.cache';

        // Si se proporciona valor, guardar en cache
        if ($value !== null) {
            $data = [
                'expires' => time() + $ttl,
                'value' => $value
            ];
            file_put_contents($cacheFile, serialize($data));
            return $value;
        }

        // Obtener del cache
        if (file_exists($cacheFile)) {
            $data = unserialize(file_get_contents($cacheFile));

            if ($data['expires'] > time()) {
                return $data['value'];
            } else {
                unlink($cacheFile);
            }
        }

        return null;
    }

    /**
     * Limpiar cache
     */
    public static function clearCache(string $pattern = '*'): int {
        $cacheDir = ROOT_PATH . '/storage/cache';
        if (!is_dir($cacheDir)) {
            return 0;
        }

        $files = glob($cacheDir . '/' . $pattern . '.cache');
        $cleared = 0;

        foreach ($files as $file) {
            if (unlink($file)) {
                $cleared++;
            }
        }

        return $cleared;
    }

    /**
     * =============================================================================
     * UTILIDADES DE LOCALIZACIÓN
     * =============================================================================
     */

    /**
     * Detectar idioma del navegador
     */
    public static function detectLanguage(array $supportedLanguages = ['en', 'es']): string {
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

        if (empty($acceptLanguage)) {
            return $supportedLanguages[0];
        }

        // Parsear Accept-Language header
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';q=', trim($lang));
            $code = substr($parts[0], 0, 2);
            $quality = isset($parts[1]) ? (float) $parts[1] : 1.0;
            $languages[$code] = $quality;
        }

        // Ordenar por calidad
        arsort($languages);

        // Buscar primer idioma soportado
        foreach ($languages as $lang => $quality) {
            if (in_array($lang, $supportedLanguages)) {
                return $lang;
            }
        }

        return $supportedLanguages[0];
    }

    /**
     * Formatear número según localización
     */
    public static function formatLocaleNumber(float $number, string $locale = 'es_CO'): string {
        if (class_exists('NumberFormatter')) {
            $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
            return $formatter->format($number);
        }

        // Fallback para locales específicos
        return match ($locale) {
            'es_CO', 'es_ES' => number_format($number, 2, ',', '.'),
            'en_US' => number_format($number, 2, '.', ','),
            default => number_format($number, 2)
        };
    }

    /**
     * =============================================================================
     * UTILIDADES DE PERFORMANCE
     * =============================================================================
     */

    /**
     * Medir uso de memoria
     */
    public static function getMemoryUsage(bool $formatted = true): string|int {
        $memory = memory_get_usage(true);
        return $formatted ? self::formatBytes($memory) : $memory;
    }

    /**
     * Obtener estadísticas de performance
     */
    public static function getPerformanceStats(): array {
        return [
            'memory_usage' => self::getMemoryUsage(),
            'peak_memory' => self::formatBytes(memory_get_peak_usage(true)),
            'execution_time' => defined('APP_START_TIME') ?
                round((microtime(true) - APP_START_TIME) * 1000, 2) . 'ms' : 'unknown',
            'included_files' => count(get_included_files()),
            'declared_classes' => count(get_declared_classes()),
            'loaded_extensions' => count(get_loaded_extensions())
        ];
    }

    /**
     * Optimizar memoria liberando variables grandes
     */
    public static function freeMemory(): void {
        // Forzar garbage collection
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }

        // Limpiar buffers de output
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    /**
     * =============================================================================
     * UTILIDADES DE LOGGING PERSONALIZADAS
     * =============================================================================
     */

    /**
     * Log personalizado con contexto automático
     */
    public static function log(string $level, string $message, array $context = []): void {
        // Agregar información de contexto automática
        $autoContext = [
            'timestamp' => date('Y-m-d H:i:s'),
            'memory_usage' => self::getMemoryUsage(),
            'file' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['file'] ?? 'unknown',
            'line' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['line'] ?? 'unknown'
        ];

        $finalContext = array_merge($autoContext, $context);

        if (function_exists('file_log')) {
            file_log($level, $message, $finalContext);
        }
    }

    /**
     * =============================================================================
     * UTILIDADES DE TESTING Y DEBUGGING
     * =============================================================================
     */

    /**
     * Generar datos de prueba
     */
    public static function generateTestData(string $type, int $count = 1): array {
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $data[] = match ($type) {
                'user' => [
                    'name' => 'User ' . ($i + 1),
                    'email' => 'user' . ($i + 1) . '@example.com',
                    'age' => random_int(18, 65),
                    'created_at' => date('Y-m-d H:i:s')
                ],
                'post' => [
                    'title' => 'Post Title ' . ($i + 1),
                    'content' => 'Lorem ipsum dolor sit amet...',
                    'published' => (bool) random_int(0, 1),
                    'created_at' => date('Y-m-d H:i:s')
                ],
                'product' => [
                    'name' => 'Product ' . ($i + 1),
                    'price' => random_int(100, 10000) / 100,
                    'stock' => random_int(0, 100),
                    'category' => ['Electronics', 'Clothing', 'Books'][random_int(0, 2)]
                ],
                default => ['id' => $i + 1, 'data' => 'Test data ' . ($i + 1)]
            };
        }

        return $count === 1 ? $data[0] : $data;
    }

    /**
     * Verificar configuración del sistema
     */
    public static function checkSystemRequirements(): array {
        $requirements = [
            'php_version' => [
                'required' => '8.0.0',
                'current' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.0.0', '>=')
            ],
            'extensions' => []
        ];

        $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'json'];
        foreach ($requiredExtensions as $ext) {
            $requirements['extensions'][$ext] = [
                'required' => true,
                'status' => extension_loaded($ext)
            ];
        }

        return $requirements;
    }

    /**
     * Diagnóstico completo del sistema
     */
    public static function systemDiagnostic(): array {
        return [
            'system_info' => self::getSystemInfo(),
            'requirements' => self::checkSystemRequirements(),
            'performance' => self::getPerformanceStats(),
            'disk_space' => [
                'total' => self::formatBytes(disk_total_space('.')),
                'free' => self::formatBytes(disk_free_space('.')),
                'used_percentage' => round((1 - disk_free_space('.') / disk_total_space('.')) * 100, 1) . '%'
            ],
            'configuration' => [
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'default_timezone' => date_default_timezone_get()
            ]
        ];
    }
}
