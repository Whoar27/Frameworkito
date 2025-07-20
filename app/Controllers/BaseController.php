<?php

/**
 * BaseController - Controlador Base
 * Frameworkito
 * 
 * Funcionalidad común para todos los controladores
 */

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\Validator;
use App\Helpers\Redirect;

class BaseController {
    protected array $config = [];
    protected array $services = [];
    protected ?\PDO $pdo = null;

    /**
     * Establecer configuración
     */
    public function setConfig(array $config): void {
        $this->config = $config;
    }

    /**
     * Establecer servicios
     */
    public function setServices(array $services): void {
        $this->services = $services;
        $this->pdo = $services['pdo'] ?? null;
    }

    /**
     * Renderizar vista
     */
    protected function view(string $view, array $data = [], string $layout = 'app'): void {
        // Si no se ha pasado 'user', lo obtenemos de la sesión
        if (!isset($data['user'])) {
            $data['user'] = null;
            if (!empty($_SESSION['user_id'])) {
                // Aquí deberías obtener los datos reales del usuario desde la base de datos si lo deseas
                $data['user'] = [
                    'username' => $_SESSION['auth_username'],
                    'email' => $_SESSION['auth_email'],
                ];
            }
        }

        // Extraer variables para la vista
        extract($data);

        // Datos globales disponibles en todas las vistas
        $app_name = $this->config['app']['name'] ?? 'Frameworkitouuuuu';
        $app_debug = $this->config['app']['debug'] ?? false;
        $currentPage = $data['currentPage'] ?? '';

        // Construir rutas de archivos
        $viewFile = APP_PATH . "/Views/{$view}.php";
        $layoutFile = APP_PATH . "/Views/layouts/{$layout}.php";

        // Verificar que exista la vista
        if (!file_exists($viewFile)) {
            // Log del error 404
            $this->log('warning', "Vista no encontrada: {$view}", [
                'requested_view' => $view,
                'view_path' => $viewFile,
                'request_uri' => $_SERVER['REQUEST_URI'] ?? '/',
                'referer' => $_SERVER['HTTP_REFERER'] ?? null
            ]);

            // Mostrar página 404 usando el sistema de vistas
            $this->show404();
            return;
        }

        // Si hay layout, verificar que exista
        if ($layout && !file_exists($layoutFile)) {
            $this->log('warning', "Layout no encontrado: {$layout}, usando vista sin layout", [
                'requested_layout' => $layout,
                'layout_path' => $layoutFile,
                'view' => $view
            ]);
            $layout = null; // Usar vista sin layout
        }

        // Renderizar vista
        if ($layout && file_exists($layoutFile)) {
            // Capturar contenido de la vista
            ob_start();
            include $viewFile;
            $content = ob_get_clean();

            // Incluir layout con el contenido
            include $layoutFile;
        } else {
            // Solo incluir la vista
            include $viewFile;
        }
    }

    /**
     * Mostrar página de error 404 usando el sistema de vistas
     */
    protected function show404(array $data = []): void{
        // Código de estado HTTP
        http_response_code($data['status_code'] ?? 404);

        // Valores por defecto
        $defaults = [
            'status_code' => 404,
            'title' => 'Página No Encontrada',
            'message' => 'Lo sentimos, la página que buscas no existe o ha sido movida.',
            'app_name' => $this->config['app']['name'] ?? 'Frameworkito',
            'requested_url' => $_SERVER['REQUEST_URI'] ?? '/',
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
        ];

        // Combinar valores recibidos con los por defecto
        $vars = array_merge($defaults, $data);

        // Hacer accesibles como variables
        extract($vars);

        // Ruta de la vista
        $error404File = APP_PATH . '/Views/errors/404.php';

        // Mostrar la vista
        if (file_exists($error404File)) {
            include $error404File;
        } else {
            echo "<h1>{$title}</h1>";
            echo "<p>{$message}</p>";
            echo "<a href='/'>Volver al Inicio</a>";
        }

        exit();
    }

    /**
     * Respuesta JSON
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Redireccionar
     */
    protected function redirect(string $url, int $statusCode = 302): void {
        Redirect::to($url, $statusCode);
    }

    /**
     * Redireccionar con mensaje
     */
    protected function redirectWith(string $url, string $message, string $type = 'info'): void {
        Redirect::with($url, $message, $type);
    }

    /**
     * Redireccionar de vuelta
     */
    protected function back(string $fallback = '/'): void {
        Redirect::back($fallback);
    }

    /**
     * Validar datos
     */
    protected function validate(array $data, array $rules, array $messages = [], $fallback = '/'): array {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            // Si es AJAX, devolver JSON
            if ($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Si es formulario, redireccionar con errores
            Redirect::withErrors(Redirect::getPreviousUrl() ?? $fallback, $validator->errors(), $data);
        }

        return $validator->validated();
    }

    /**
     * Verificar si es request AJAX
     */
    protected function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Obtener input del request
     */
    protected function input(string $key = null, $default = null) {
        $input = array_merge($_GET, $_POST);

        if ($key === null) {
            return $input;
        }

        return $input[$key] ?? $default;
    }

    /**
     * Verificar si hay input
     */
    protected function has(string $key): bool {
        $input = array_merge($_GET, $_POST);
        return isset($input[$key]);
    }

    /**
     * Obtener todos los inputs
     */
    protected function all(): array {
        return array_merge($_GET, $_POST);
    }

    /**
     * Obtener solo ciertos inputs
     */
    protected function only(array $keys): array {
        $input = $this->all();
        return array_intersect_key($input, array_flip($keys));
    }

    /**
     * Obtener todos excepto ciertos inputs
     */
    protected function except(array $keys): array {
        $input = $this->all();
        return array_diff_key($input, array_flip($keys));
    }

    /**
     * Verificar método HTTP
     */
    protected function isMethod(string $method): bool {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }

    /**
     * Obtener información del usuario actual (cuando esté disponible Auth)
     */
    protected function user(): ?array {
        // Temporalmente devolver null hasta que tengamos Auth funcionando
        return null;

        // Cuando esté disponible:
        // return Auth::user();
    }

    /**
     * Verificar si usuario está autenticado
     */
    protected function isAuthenticated(): bool {
        // Temporalmente false hasta que tengamos Auth funcionando
        return false;

        // Cuando esté disponible:
        // return Auth::check();
    }

    /**
     * Log de debugging
     */
    protected function log(string $level, string $message, array $context = []): void {
        if (function_exists('file_log')) {
            file_log($level, $message, array_merge($context, [
                'controller' => static::class,
                'method' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown'
            ]));
        }
    }

    /**
     * Manejar errores del controlador
     */
    protected function handleError(\Exception $e, string $fallbackMessage = 'Ha ocurrido un error'): void {
        $this->log('error', 'Error en controlador: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        if ($this->isAjax()) {
            $this->json([
                'success' => false,
                'message' => $this->config['app']['debug'] ? $e->getMessage() : $fallbackMessage
            ], 500);
        } else {
            if ($this->config['app']['debug']) {
                throw $e;
            } else {
                $this->redirectWith('/', $fallbackMessage, 'error');
            }
        }
    }

    /**
     * Obtener información de la request
     */
    protected function getRequestInfo(): array {
        return [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'uri' => $_SERVER['REQUEST_URI'] ?? '/',
            'ip' => get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
            'is_ajax' => $this->isAjax(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
