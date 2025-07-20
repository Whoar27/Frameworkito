<?php
/**
 * Redirect - Helper de Redirecciones
 * Frameworkito
 * 
 * Maneja redirecciones con:
 * - URLs internas y externas
 * - Mensajes flash
 * - Códigos de estado HTTP
 * - Validaciones de seguridad
 */

namespace App\Helpers;

class Redirect {
    /**
     * Redireccionar a URL
     */
    public static function to(string $url, int $statusCode = 302): void {
        // Validar URL
        $url = self::validateUrl($url);

        // Log de redirección
        file_log('debug', 'Redirección realizada', [
            'url' => $url,
            'status_code' => $statusCode,
            'from_url' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ]);

        // Limpiar output buffer si existe
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Enviar header de redirección
        header("Location: {$url}", true, $statusCode);
        exit();
    }

    /**
     * Redireccionar con mensaje flash
     */
    public static function with(string $url, string $message, string $type = 'info', int $statusCode = 302): void {
        Session::flash($type, $message);
        self::to($url, $statusCode);
    }

    /**
     * Redireccionar con mensaje de éxito
     */
    public static function withSuccess(string $url, string $message, int $statusCode = 302): void {
        self::with($url, $message, 'success', $statusCode);
    }

    /**
     * Redireccionar con mensaje de error
     */
    public static function withError(string $url, string $message, int $statusCode = 302): void {
        self::with($url, $message, 'error', $statusCode);
    }

    /**
     * Redireccionar con mensaje de advertencia
     */
    public static function withWarning(string $url, string $message, int $statusCode = 302): void {
        self::with($url, $message, 'warning', $statusCode);
    }

    /**
     * Redireccionar con mensaje informativo
     */
    public static function withInfo(string $url, string $message, int $statusCode = 302): void {
        self::with($url, $message, 'info', $statusCode);
    }

    /**
     * =============================================================================
     * REDIRECCIONES ESPECÍFICAS
     * =============================================================================
     */

    /**
     * Redireccionar a la página anterior
     */
    public static function back(string $fallback = '/', int $statusCode = 302): void {
        $previousUrl = self::getPreviousUrl();

        if ($previousUrl && self::isSafeUrl($previousUrl)) {
            self::to($previousUrl, $statusCode);
        } else {
            self::to($fallback, $statusCode);
        }
    }

    /**
     * Redireccionar de vuelta con mensaje
     */
    public static function backWith(string $message, string $type = 'info', string $fallback = '/', int $statusCode = 302): void {
        Session::flash($type, $message);
        self::back($fallback, $statusCode);
    }

    /**
     * Redireccionar al home
     */
    public static function home(int $statusCode = 302): void {
        $homeUrl = config('app.routes.home', '/');
        self::to($homeUrl, $statusCode);
    }

    /**
     * Redireccionar al login
     */
    public static function login(int $statusCode = 302): void {
        $loginUrl = config('app.routes.login', '/login');
        self::to($loginUrl, $statusCode);
    }

    /**
     * Redireccionar al dashboard
     */
    public static function dashboard(int $statusCode = 302): void {
        $dashboardUrl = config('app.routes.after_login', '/home');
        self::to($dashboardUrl, $statusCode);
    }

    /**
     * Redireccionar después del logout
     */
    public static function afterLogout(int $statusCode = 302): void {
        $logoutUrl = config('app.routes.after_logout', '/');
        self::to($logoutUrl, $statusCode);
    }

    /**
     * Redireccionar a URL intentada (después del login)
     */
    public static function intended(string $fallback = '/home', int $statusCode = 302): void {
        $intendedUrl = Session::pull('intended_url', $fallback);

        // Validar que la URL intentada sea segura
        if (!self::isSafeUrl($intendedUrl)) {
            $intendedUrl = $fallback;
        }

        self::to($intendedUrl, $statusCode);
    }

    /**
     * =============================================================================
     * REDIRECCIONES CON DATOS
     * =============================================================================
     */

    /**
     * Redireccionar con datos de input
     */
    public static function withInput(string $url, array $input = null, int $statusCode = 302): void {
        $input = $input ?? $_POST ?? [];

        // Sanitizar datos sensibles
        $sensitiveFields = ['password', 'password_confirmation', '_token', 'csrf_token'];
        foreach ($sensitiveFields as $field) {
            unset($input[$field]);
        }

        Session::flash('old_input', $input);
        self::to($url, $statusCode);
    }

    /**
     * Redireccionar con errores de validación
     */
    public static function withErrors(string $url, array $errors, array $input = null, int $statusCode = 302): void {
        Session::flash('validation_errors', $errors);

        if ($input !== null) {
            self::withInput($url, $input, $statusCode);
        } else {
            self::to($url, $statusCode);
        }
    }

    /**
     * Redireccionar con múltiples datos flash
     */
    public static function withFlash(string $url, array $flashData, int $statusCode = 302): void {
        foreach ($flashData as $key => $value) {
            Session::flash($key, $value);
        }

        self::to($url, $statusCode);
    }

    /**
     * =============================================================================
     * REDIRECCIONES ESPECIALES
     * =============================================================================
     */

    /**
     * Redirección permanente (301)
     */
    public static function permanent(string $url): void {
        self::to($url, 301);
    }

    /**
     * Redirección temporal (307) - mantiene método HTTP
     */
    public static function temporary(string $url): void {
        self::to($url, 307);
    }

    /**
     * Redirección de emergencia (para errores críticos)
     */
    public static function emergency(string $message = 'Ha ocurrido un error inesperado'): void {
        Session::flash('error', $message);

        // Intentar redireccionar al home, si falla ir a página básica
        try {
            self::home();
        } catch (\Exception $e) {
            self::to('/');
        }
    }

    /**
     * Redirección AJAX
     */
    public static function ajax(string $url, array $data = [], int $statusCode = 200): void {
        if (!self::isAjaxRequest()) {
            self::to($url);
            return;
        }

        $response = [
            'redirect' => true,
            'url' => $url,
            'status' => $statusCode
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }

    /**
     * =============================================================================
     * UTILIDADES PRIVADAS
     * =============================================================================
     */

    /**
     * Validar y normalizar URL
     */
    private static function validateUrl(string $url): string {
        // Si es URL relativa, convertir a absoluta
        if (!preg_match('/^https?:\/\//', $url)) {
            // Asegurar que empiece con /
            if (!str_starts_with($url, '/')) {
                $url = '/' . $url;
            }

            // Construir URL completa
            $protocol = self::isHttps() ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $url = "{$protocol}://{$host}{$url}";
        }

        // Validar que sea una URL válida
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("URL inválida: {$url}");
        }

        return $url;
    }

    /**
     * Verificar si URL es segura para redirección
     */
    private static function isSafeUrl(string $url): bool {
        // URL vacía no es segura
        if (empty($url)) {
            return false;
        }

        // URLs externas requieren validación adicional
        if (preg_match('/^https?:\/\//', $url)) {
            $currentHost = $_SERVER['HTTP_HOST'] ?? '';
            $urlHost = parse_url($url, PHP_URL_HOST);

            // Solo permitir URLs del mismo dominio
            return $urlHost === $currentHost;
        }

        // URLs relativas son generalmente seguras
        return true;
    }

    /**
     * Obtener URL anterior
     */
    public static function getPreviousUrl(): ?string {
        // Intentar obtener de diferentes fuentes
        $sources = [
            $_SERVER['HTTP_REFERER'] ?? null,
            Session::get('previous_url'),
            Session::get('_previous_url')
        ];

        foreach ($sources as $url) {
            if ($url && self::isSafeUrl($url)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Verificar si es request AJAX
     */
    private static function isAjaxRequest(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Verificar si la conexión es HTTPS
     */
    private static function isHttps(): bool {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            $_SERVER['SERVER_PORT'] == 443 ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }

    /**
     * =============================================================================
     * HELPERS PÚBLICOS
     * =============================================================================
     */

    /**
     * Construir URL con query parameters
     */
    public static function url(string $path, array $params = []): string {
        $url = self::validateUrl($path);

        if (!empty($params)) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . http_build_query($params);
        }

        return $url;
    }

    /**
     * Construir URL relativa con base path
     */
    public static function route(string $path, array $params = []): string {
        // Asegurar que empiece con /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        if (!empty($params)) {
            $separator = strpos($path, '?') !== false ? '&' : '?';
            $path .= $separator . http_build_query($params);
        }

        return $path;
    }

    /**
     * Verificar si se puede hacer redirección
     */
    public static function canRedirect(): bool {
        // No se puede redireccionar si ya se enviaron headers
        if (headers_sent()) {
            return false;
        }

        // No se puede redireccionar si hay output buffer con contenido
        if (ob_get_level() > 0 && ob_get_length() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Redirección segura (solo si es posible)
     */
    public static function safe(string $url, string $fallbackMessage = null): void {
        if (self::canRedirect()) {
            self::to($url);
        } else {
            // Si no se puede redireccionar, mostrar link manual
            $message = $fallbackMessage ?? 'Si no eres redirigido automáticamente, <a href="' . e($url) . '">haz clic aquí</a>';

            echo '<div style="text-align: center; margin: 20px; padding: 20px; border: 1px solid #ddd;">';
            echo '<p>' . $message . '</p>';
            echo '<script>setTimeout(function(){ window.location.href = "' . e($url) . '"; }, 100);</script>';
            echo '</div>';
            exit();
        }
    }

    /**
     * =============================================================================
     * GESTIÓN DE HISTORIA DE NAVEGACIÓN
     * =============================================================================
     */

    /**
     * Guardar URL actual para referencia futura
     */
    public static function saveCurrentUrl(): void {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';

        // No guardar URLs de POST o AJAX
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || self::isAjaxRequest()) {
            return;
        }

        // No guardar URLs de logout o login
        $excludePaths = ['/logout', '/login', '/register'];
        foreach ($excludePaths as $path) {
            if (str_starts_with($currentUrl, $path)) {
                return;
            }
        }

        Session::set('previous_url', $currentUrl);
    }

    /**
     * Limpiar URLs guardadas
     */
    public static function clearSavedUrls(): void {
        Session::remove('previous_url');
        Session::remove('intended_url');
    }

    /**
     * =============================================================================
     * REDIRECCIONES CON VALIDACIÓN
     * =============================================================================
     */

    /**
     * Redireccionar solo si la URL de origen coincide
     */
    public static function ifFrom(string $expectedFrom, string $url, int $statusCode = 302): void {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';

        if ($currentUrl === $expectedFrom) {
            self::to($url, $statusCode);
        }
    }

    /**
     * Redireccionar solo si el usuario está autenticado
     */
    public static function ifAuthenticated(string $url, int $statusCode = 302): void {
        if (class_exists('Auth') && Auth::check()) {
            self::to($url, $statusCode);
        }
    }

    /**
     * Redireccionar solo si el usuario es invitado
     */
    public static function ifGuest(string $url, int $statusCode = 302): void {
        if (class_exists('Auth') && Auth::guest()) {
            self::to($url, $statusCode);
        }
    }

    /**
     * Redireccionar con condición personalizada
     */
    public static function ifCondition(callable $condition, string $url, int $statusCode = 302): void {
        if ($condition()) {
            self::to($url, $statusCode);
        }
    }

    /**
     * =============================================================================
     * REDIRECCIONES DIFERIDAS
     * =============================================================================
     */

    /**
     * Programar redirección para después del procesamiento
     */
    public static function after(string $url, int $delay = 0): void {
        Session::set('pending_redirect', [
            'url' => $url,
            'delay' => $delay,
            'timestamp' => time()
        ]);
    }

    /**
     * Ejecutar redirección pendiente si existe
     */
    public static function processPending(): void {
        $pending = Session::pull('pending_redirect');

        if (!$pending) {
            return;
        }

        $delay = $pending['delay'] ?? 0;
        $url = $pending['url'] ?? '/';

        if ($delay > 0) {
            // Redirección con JavaScript para delay
            echo '<script>setTimeout(function(){ window.location.href = "' . e($url) . '"; }, ' . ($delay * 1000) . ');</script>';
        } else {
            self::to($url);
        }
    }

    /**
     * =============================================================================
     * UTILIDADES DE DEBUGGING
     * =============================================================================
     */

    /**
     * Obtener información de redirección para debugging
     */
    public static function getDebugInfo(): array {
        return [
            'can_redirect' => self::canRedirect(),
            'headers_sent' => headers_sent(),
            'output_buffer_level' => ob_get_level(),
            'output_buffer_length' => ob_get_length(),
            'current_url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'previous_url' => Session::get('previous_url'),
            'intended_url' => Session::get('intended_url'),
            'pending_redirect' => Session::get('pending_redirect'),
            'is_ajax' => self::isAjaxRequest(),
            'is_https' => self::isHttps(),
            'http_host' => $_SERVER['HTTP_HOST'] ?? 'unknown',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown'
        ];
    }

    /**
     * Mostrar información de debugging (solo en modo debug)
     */
    public static function debug(): void {
        if (!is_debug()) {
            return;
        }

        $info = self::getDebugInfo();

        echo '<div style="background: #f0f0f0; border: 1px solid #ccc; padding: 10px; margin: 10px; font-family: monospace; font-size: 12px;">';
        echo '<h4>Redirect Debug Info:</h4>';
        echo '<pre>' . print_r($info, true) . '</pre>';
        echo '</div>';
    }

    /**
     * =============================================================================
     * MÉTODOS ESTÁTICOS ADICIONALES
     * =============================================================================
     */

    /**
     * Redirección con refresh header (alternativa a Location)
     */
    public static function refresh(string $url, int $delay = 0): void {
        if ($delay > 0) {
            header("Refresh: {$delay}; url={$url}");
        } else {
            header("Refresh: 0; url={$url}");
        }
        exit();
    }

    /**
     * Redirección con meta refresh (para casos especiales)
     */
    public static function metaRefresh(string $url, int $delay = 0, string $message = null): void {
        $message = $message ?? 'Redirigiendo...';

        echo '<!DOCTYPE html>';
        echo '<html><head>';
        echo '<meta charset="UTF-8">';
        echo '<meta http-equiv="refresh" content="' . $delay . ';url=' . e($url) . '">';
        echo '<title>Redirigiendo...</title>';
        echo '</head><body>';
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h2>' . e($message) . '</h2>';
        echo '<p>Si no eres redirigido automáticamente, <a href="' . e($url) . '">haz clic aquí</a>.</p>';
        echo '</div>';
        echo '</body></html>';
        exit();
    }

    /**
     * Obtener todas las URLs de redirección configuradas
     */
    public static function getConfiguredUrls(): array {
        return [
            'home' => config('app.routes.home', '/'),
            'login' => config('app.routes.login', '/login'),
            'after_login' => config('app.routes.after_login', '/home'),
            'after_logout' => config('app.routes.after_logout', '/'),
            'register' => config('app.routes.register', '/register')
        ];
    }

    /**
     * Verificar si una URL es una ruta del sistema
     */
    public static function isSystemRoute(string $url): bool {
        $systemRoutes = self::getConfiguredUrls();

        foreach ($systemRoutes as $route) {
            if (str_contains($url, $route)) {
                return true;
            }
        }

        return false;
    }
}
