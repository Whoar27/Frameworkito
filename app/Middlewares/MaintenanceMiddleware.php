<?php

/**
 * MaintenanceMiddleware - Middleware de Mantenimiento
 * AuthManager Base
 * 
 * Verifica si el sitio está en modo mantenimiento y muestra
 * la página correspondiente a los usuarios no autorizados.
 */

namespace App\Middlewares;

class MaintenanceMiddleware {

    private array $config;
    private array $allowedIps;
    private array $allowedRoutes;

    /**
     * Constructor
     */
    public function __construct(array $config = []) {
        $this->config = $config;

        // IPs permitidas durante mantenimiento (SOLO desde configuración)
        $this->allowedIps = [
            // Sin IPs por defecto - se cargan desde app.php
        ];

        // Rutas permitidas durante mantenimiento
        $this->allowedRoutes = [
            // Sin IPs por defecto - se cargan desde app.php
        ];
    }

    /**
     * Manejar la request
     */
    public function handle(): void {
        // Verificar si el modo mantenimiento está activado
        if (!$this->isMaintenanceModeEnabled()) {
            return; // Continuar normalmente
        }

        // Verificar si la IP está en la lista de permitidas
        if ($this->isAllowedIp()) {
            return; // Permitir acceso
        }

        // Verificar si la ruta está permitida
        if ($this->isAllowedRoute()) {
            return; // Permitir acceso
        }

        // Verificar si es una request AJAX
        if ($this->isAjaxRequest()) {
            $this->sendMaintenanceJsonResponse();
            return;
        }

        // Mostrar página de mantenimiento
        $this->showMaintenancePage();
    }

    /**
     * Verificar si el modo mantenimiento está habilitado
     */
    private function isMaintenanceModeEnabled(): bool {
        // Leer desde .env o configuración
        $maintenanceMode = $_ENV['APP_MAINTENANCE'] ??
            $this->config['maintenance_mode'] ??
            false;

        // Convertir string a boolean si es necesario
        if (is_string($maintenanceMode)) {
            return strtolower($maintenanceMode) === 'true';
        }

        return (bool) $maintenanceMode;
    }

    /**
     * Verificar si la IP actual está permitida
     */
    private function isAllowedIp(): bool {
        $clientIp = $this->getClientIp();

        return in_array($clientIp, $this->allowedIps, true);
    }

    /**
     * Verificar si la ruta actual está permitida
     */
    private function isAllowedRoute(): bool {
        $currentRoute = $this->getCurrentRoute();

        foreach ($this->allowedRoutes as $allowedRoute) {
            if (strpos($currentRoute, $allowedRoute) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar si es una request AJAX
     */
    private function isAjaxRequest(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Obtener la IP del cliente
     */
    private function getClientIp(): string {
        // Verificar varios headers para obtener la IP real
        $ipKeys = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];

                // Si hay múltiples IPs, tomar la primera
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Validar que sea una IP válida
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Obtener la ruta actual
     */
    private function getCurrentRoute(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Remover query string
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        return $uri;
    }

    /**
     * Enviar respuesta JSON para requests AJAX
     */
    private function sendMaintenanceJsonResponse(): void {
        http_response_code(503);
        header('Content-Type: application/json');
        header('Retry-After: 3600'); // Sugerir reintentar en 1 hora

        $response = [
            'error' => true,
            'code' => 503,
            'message' => 'Sitio en mantenimiento',
            'details' => 'El sitio está temporalmente fuera de línea por mantenimiento programado.',
            'retry_after' => 3600,
            'timestamp' => date('c')
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Mostrar la página de mantenimiento
     */
    private function showMaintenancePage(): void {
        // Establecer código de respuesta HTTP 503
        http_response_code(503);

        // Header para sugerir cuándo reintentar
        header('Retry-After: 3600'); // 1 hora

        // Datos para la vista
        $app_name = $this->config['app']['name'] ?? $_ENV['APP_NAME'] ?? 'AuthManager Base';

        // Verificar si existe la vista de mantenimiento personalizada
        $maintenanceView = APP_PATH . '/Views/maintenance/index.php';

        if (file_exists($maintenanceView)) {
            // Usar vista personalizada
            include $maintenanceView;
        } else {
            // Fallback: página básica de mantenimiento
            $this->showBasicMaintenancePage($app_name);
        }

        exit();
    }

    /**
     * Página básica de mantenimiento como fallback
     */
    private function showBasicMaintenancePage(string $appName): void {
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Sitio en Mantenimiento | <?= htmlspecialchars($appName) ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    padding: 50px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    margin: 0;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .container {
                    max-width: 600px;
                    background: rgba(255, 255, 255, 0.95);
                    color: #333;
                    padding: 40px;
                    border-radius: 20px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                }

                h1 {
                    color: #6366f1;
                    font-size: 2.5em;
                    margin-bottom: 20px;
                }

                p {
                    font-size: 1.2em;
                    margin: 20px 0;
                    line-height: 1.6;
                }

                .icon {
                    font-size: 4em;
                    margin-bottom: 20px;
                }

                .status {
                    background: #f0f9ff;
                    padding: 20px;
                    border-radius: 10px;
                    margin: 20px 0;
                    border-left: 4px solid #6366f1;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div class="icon">🔧</div>
                <h1>Sitio en Mantenimiento</h1>
                <p>
                    Estamos realizando mejoras en nuestro sistema para ofrecerte una mejor experiencia.
                </p>
                <div class="status">
                    <strong>Estado:</strong> Mantenimiento programado<br>
                    <strong>Tiempo estimado:</strong> Algunas horas<br>
                    <strong>Última actualización:</strong> <?= date('d/m/Y H:i:s') ?>
                </div>
                <p>
                    Te pedimos disculpas por las molestias. Volveremos pronto con mejoras.
                </p>
                <p>
                    <small>Si tienes dudas, contacta al administrador del sistema.</small>
                </p>
            </div>
        </body>

        </html>
<?php
    }

    /**
     * Agregar IP permitida
     */
    public function addAllowedIp(string $ip): void {
        if (!in_array($ip, $this->allowedIps, true)) {
            $this->allowedIps[] = $ip;
        }
    }

    /**
     * Agregar ruta permitida
     */
    public function addAllowedRoute(string $route): void {
        if (!in_array($route, $this->allowedRoutes, true)) {
            $this->allowedRoutes[] = $route;
        }
    }

    /**
     * Obtener IPs permitidas
     */
    public function getAllowedIps(): array {
        return $this->allowedIps;
    }

    /**
     * Obtener rutas permitidas
     */
    public function getAllowedRoutes(): array {
        return $this->allowedRoutes;
    }

    /**
     * Log de acceso durante mantenimiento
     */
    private function logMaintenanceAccess(): void {
        if (function_exists('file_log')) {
            file_log('info', 'Acceso durante mantenimiento', [
                'ip' => $this->getClientIp(),
                'route' => $this->getCurrentRoute(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'allowed' => $this->isAllowedIp(),
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
