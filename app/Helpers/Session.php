<?php
/**
 * Session - Helper de Manejo de Sesiones
 * Frameworkito
 * 
 * Maneja:
 * - Operaciones seguras de sesión
 * - Flash messages
 * - Datos temporales
 * - Validaciones de seguridad
 */

namespace App\Helpers;

class Session {
    private static bool $started = false;
    private static array $config = [];

    /**
     * Inicializar sesión si no está iniciada
     */
    public static function start(): void {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        // Cargar configuración
        self::$config = config('app.session', []);

        // Configurar parámetros de sesión
        self::configureSession();

        // Iniciar sesión
        session_start();
        self::$started = true;

        // Validaciones de seguridad
        self::validateSession();

        // Regenerar ID si es necesario
        self::handleRegeneration();
    }

    /**
     * Configurar parámetros de sesión
     */
    private static function configureSession(): void {
        $config = self::$config;

        // Configurar cookie de sesión
        ini_set('session.cookie_lifetime', ($config['lifetime'] ?? 120) * 60);
        ini_set('session.cookie_path', $config['cookie_path'] ?? '/');
        ini_set('session.cookie_secure', $config['cookie_secure'] ?? false ? '1' : '0');
        ini_set('session.cookie_httponly', $config['cookie_httponly'] ?? true ? '1' : '0');
        ini_set('session.cookie_samesite', $config['cookie_samesite'] ?? 'Lax');

        // Configurar nombre de sesión
        session_name($config['cookie_name'] ?? 'authmanager_session');

        // Configuraciones de seguridad
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.use_only_cookies', '1');
    }

    /**
     * Validar sesión por seguridad
     */
    private static function validateSession(): void {
        // Validar IP si está configurado
        if (self::$config['validate_ip'] ?? true) {
            self::validateIpAddress();
        }

        // Verificar expiración de sesión
        self::checkSessionExpiry();

        // Actualizar último acceso
        self::set('last_activity', time());
    }

    /**
     * Validar dirección IP
     */
    private static function validateIpAddress(): void {
        $currentIp = get_client_ip();
        $sessionIp = self::get('ip_address');

        if ($sessionIp === null) {
            self::set('ip_address', $currentIp);
        } elseif ($sessionIp !== $currentIp) {
            // IP cambió, destruir sesión por seguridad
            file_log('warning', 'Sesión destruida por cambio de IP', [
                'previous_ip' => $sessionIp,
                'current_ip' => $currentIp,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);

            self::destroy();
            self::start(); // Reiniciar sesión limpia
            self::set('ip_address', $currentIp);
        }
    }

    /**
     * Verificar expiración de sesión
     */
    private static function checkSessionExpiry(): void {
        $lifetime = (self::$config['lifetime'] ?? 120) * 60;
        $lastActivity = self::get('last_activity', time());

        if ((time() - $lastActivity) > $lifetime) {
            file_log('info', 'Sesión expirada por inactividad', [
                'last_activity' => date('Y-m-d H:i:s', $lastActivity),
                'lifetime' => $lifetime . ' segundos'
            ]);

            self::destroy();
            self::start();
        }
    }

    /**
     * Manejar regeneración de ID de sesión
     */
    private static function handleRegeneration(): void {
        if (!self::$config['regenerate_id'] ?? true) {
            return;
        }

        $regenerateInterval = 15 * 60; // 15 minutos
        $lastRegeneration = self::get('last_regeneration', 0);

        if ((time() - $lastRegeneration) > $regenerateInterval) {
            session_regenerate_id(true);
            self::set('last_regeneration', time());
        }
    }

    /**
     * Obtener valor de sesión
     */
    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Establecer valor de sesión
     */
    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Verificar si existe una clave en sesión
     */
    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remover valor de sesión
     */
    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Obtener y remover valor (pull)
     */
    public static function pull(string $key, $default = null) {
        $value = self::get($key, $default);
        self::remove($key);
        return $value;
    }

    /**
     * Obtener todos los datos de sesión
     */
    public static function all(): array {
        self::start();
        return $_SESSION;
    }

    /**
     * Limpiar toda la sesión
     */
    public static function clear(): void {
        self::start();
        $_SESSION = [];
    }

    /**
     * Destruir sesión completamente
     */
    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];

            // Eliminar cookie de sesión
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );

            session_destroy();
        }

        self::$started = false;
    }

    /**
     * Regenerar ID de sesión
     */
    public static function regenerate(bool $deleteOldSession = true): void {
        self::start();
        session_regenerate_id($deleteOldSession);
        self::set('last_regeneration', time());
    }

    /**
     * =============================================================================
     * FLASH MESSAGES
     * =============================================================================
     */

    /**
     * Establecer mensaje flash
     */
    public static function flash(string $key, $value): void {
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Obtener mensaje flash
     */
    public static function getFlash(string $key, $default = null) {
        self::start();
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /**
     * Verificar si existe mensaje flash
     */
    public static function hasFlash(string $key): bool {
        self::start();
        return isset($_SESSION['_flash'][$key]);
    }

    /**
     * Obtener todos los mensajes flash
     */
    public static function getAllFlash(): array {
        self::start();
        $flash = $_SESSION['_flash'] ?? [];
        $_SESSION['_flash'] = [];
        return $flash;
    }

    /**
     * Establecer mensaje de éxito
     */
    public static function success(string $message): void {
        self::flash('success', $message);
    }

    /**
     * Establecer mensaje de error
     */
    public static function error(string $message): void {
        self::flash('error', $message);
    }

    /**
     * Establecer mensaje de advertencia
     */
    public static function warning(string $message): void {
        self::flash('warning', $message);
    }

    /**
     * Establecer mensaje informativo
     */
    public static function info(string $message): void {
        self::flash('info', $message);
    }

    /**
     * =============================================================================
     * DATOS TEMPORALES
     * =============================================================================
     */

    /**
     * Establecer datos temporales que se eliminan automáticamente
     */
    public static function setTemp(string $key, $value, int $ttl = 300): void {
        self::start();
        $_SESSION['_temp'][$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
    }

    /**
     * Obtener datos temporales
     */
    public static function getTemp(string $key, $default = null) {
        self::start();

        if (!isset($_SESSION['_temp'][$key])) {
            return $default;
        }

        $temp = $_SESSION['_temp'][$key];

        // Verificar si expiró
        if (time() > $temp['expires']) {
            unset($_SESSION['_temp'][$key]);
            return $default;
        }

        return $temp['value'];
    }

    /**
     * Limpiar datos temporales expirados
     */
    public static function cleanupTemp(): void {
        self::start();

        if (!isset($_SESSION['_temp'])) {
            return;
        }

        $now = time();
        foreach ($_SESSION['_temp'] as $key => $temp) {
            if ($now > $temp['expires']) {
                unset($_SESSION['_temp'][$key]);
            }
        }
    }

    /**
     * =============================================================================
     * TOKENS CSRF
     * =============================================================================
     */

    /**
     * Generar token CSRF
     */
    public static function generateCsrfToken(): string {
        $token = bin2hex(random_bytes(32));
        self::set('_token', $token);
        return $token;
    }

    /**
     * Obtener token CSRF actual
     */
    public static function getCsrfToken(): string {
        $token = self::get('_token');
        if (!$token) {
            $token = self::generateCsrfToken();
        }
        return $token;
    }

    /**
     * Verificar token CSRF
     */
    public static function verifyCsrfToken(string $token): bool {
        $sessionToken = self::get('_token');
        return $sessionToken && hash_equals($sessionToken, $token);
    }

    /**
     * =============================================================================
     * UTILIDADES
     * =============================================================================
     */

    /**
     * Obtener ID de sesión
     */
    public static function getId(): string {
        self::start();
        return session_id();
    }

    /**
     * Verificar si la sesión está activa
     */
    public static function isActive(): bool {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Obtener información de la sesión
     */
    public static function getInfo(): array {
        self::start();

        return [
            'id' => session_id(),
            'name' => session_name(),
            'status' => session_status(),
            'started' => self::$started,
            'cookie_params' => session_get_cookie_params(),
            'ip_address' => self::get('ip_address'),
            'last_activity' => self::get('last_activity'),
            'last_regeneration' => self::get('last_regeneration'),
            'data_count' => count($_SESSION),
            'memory_usage' => memory_get_usage()
        ];
    }

    /**
     * Obtener estadísticas de sesión
     */
    public static function getStats(): array {
        self::start();

        $lastActivity = self::get('last_activity', time());
        $sessionAge = time() - $lastActivity;

        return [
            'session_id' => session_id(),
            'is_active' => self::isActive(),
            'age_seconds' => $sessionAge,
            'age_formatted' => self::formatDuration($sessionAge),
            'data_keys' => array_keys($_SESSION),
            'flash_messages' => count($_SESSION['_flash'] ?? []),
            'temp_data' => count($_SESSION['_temp'] ?? []),
            'csrf_token_set' => self::has('_token'),
            'ip_address' => self::get('ip_address'),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
    }

    /**
     * Formatear duración en formato legible
     */
    private static function formatDuration(int $seconds): string {
        if ($seconds < 60) {
            return $seconds . ' segundos';
        } elseif ($seconds < 3600) {
            return round($seconds / 60) . ' minutos';
        } else {
            return round($seconds / 3600, 1) . ' horas';
        }
    }

    /**
     * Middleware para limpiar sesión automáticamente
     */
    public static function middleware(): void {
        self::start();
        self::cleanupTemp();

        // Log de actividad de sesión si está habilitado
        if (config('logging.database.log_types.activity.enabled', false)) {
            bd_log('activity', 'Sesión accedida', [
                'session_id' => session_id(),
                'action' => 'session_access'
            ]);
        }
    }
}
