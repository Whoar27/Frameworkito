<?php

/**
 * Router - Sistema de Ruteo
 * Frameworkito
 * 
 * Maneja:
 * 1. Registro y resolución de rutas
 * 2. Middlewares
 * 3. Parámetros dinámicos
 * 4. Ejecución de controladores
 */

namespace App\Core;

class Router {
    private array $routes = [];
    private array $middlewares = [];
    private array $config;
    private array $services;
    private array $globalMiddlewares = [];

    public function __construct(array $config, array $services) {
        $this->config = $config;
        $this->services = $services;

        // Cargar rutas
        $this->loadRoutes();

        // Registrar middlewares globales
        $this->registerGlobalMiddlewares();
    }

    /**
     * Cargar rutas desde archivos
     */
    private function loadRoutes(): void {
        // Cargar rutas web
        $webRoutesFile = APP_PATH . '/Routes/web.php';
        if (file_exists($webRoutesFile)) {
            $this->loadRoutesFromFile($webRoutesFile);
        }

        // Cargar rutas API si están habilitadas
        if ($this->config['app']['api']['enabled'] ?? false) {
            $apiRoutesFile = APP_PATH . '/Routes/api.php';
            if (file_exists($apiRoutesFile)) {
                $this->loadRoutesFromFile($apiRoutesFile, '/api');
            }
        }
    }

    /**
     * Cargar rutas desde un archivo específico
     */
    private function loadRoutesFromFile(string $file, string $prefix = ''): void {
        // Crear contexto para el archivo de rutas
        $router = $this;

        // Funciones helper para definir rutas
        $get = function ($uri, $handler) use ($router, $prefix) {
            $router->addRoute('GET', $prefix . $uri, $handler);
        };

        $post = function ($uri, $handler) use ($router, $prefix) {
            $router->addRoute('POST', $prefix . $uri, $handler);
        };

        $put = function ($uri, $handler) use ($router, $prefix) {
            $router->addRoute('PUT', $prefix . $uri, $handler);
        };

        $delete = function ($uri, $handler) use ($router, $prefix) {
            $router->addRoute('DELETE', $prefix . $uri, $handler);
        };

        $patch = function ($uri, $handler) use ($router, $prefix) {
            $router->addRoute('PATCH', $prefix . $uri, $handler);
        };

        // Función para registrar middlewares en rutas
        $middleware = function ($name, $routes) use ($router) {
            $router->registerMiddleware($name, $routes);
        };

        // Incluir el archivo de rutas
        include $file;
    }

    /**
     * Agregar una ruta
     */
    public function addRoute(string $method, string $uri, $handler, array $middlewares = []): void {
        // Normalizar URI
        $uri = $this->normalizeUri($uri);

        // Convertir parámetros dinámicos a regex
        $pattern = $this->convertToRegex($uri);

        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares,
            'parameters' => $this->extractParameterNames($uri)
        ];
    }

    /**
     * Normalizar URI
     */
    private function normalizeUri(string $uri): string {
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
     * Convertir URI con parámetros a expresión regular
     */
    private function convertToRegex(string $uri): string {
        // Reemplazar {param} por grupo de captura
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $uri);

        // Reemplazar {param?} por grupo opcional
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\?\}/', '([^/]*)', $pattern);

        return '#^' . $pattern . '$#';
    }

    /**
     * Extraer nombres de parámetros de la URI
     */
    private function extractParameterNames(string $uri): array {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\??}/', $uri, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Despachar la request
     */
    public function dispatch(string $method, string $uri): void {
        // Ejecutar middlewares globales
        $this->executeGlobalMiddlewares();

        // Buscar ruta coincidente
        $route = $this->findRoute($method, $uri);

        if (!$route) {
            $this->handleNotFound($uri);
            return;
        }

        // Ejecutar middlewares específicos de la ruta
        $this->executeRouteMiddlewares($route);
    }

    /**
     * Buscar ruta que coincida
     */
    private function findRoute(string $method, string $uri): ?array {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extraer parámetros
                array_shift($matches); // Remover match completo
                $parameters = array_combine($route['parameters'], $matches);

                $route['matched_parameters'] = $parameters;
                return $route;
            }
        }

        return null;
    }

    /**
     * Registrar middlewares globales
     */
    private function registerGlobalMiddlewares(): void {
        // Middleware de seguridad CSRF para POST/PUT/PATCH/DELETE
        $this->globalMiddlewares[] = function () {
            if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $this->validateCsrfToken();
            }
        };

        // Middleware de rate limiting básico
        $this->globalMiddlewares[] = function () {
            $this->checkRateLimit();
        };
    }

    /**
     * Ejecutar middlewares globales
     */
    private function executeGlobalMiddlewares(): void {
        foreach ($this->globalMiddlewares as $middleware) {
            $middleware();
        }
    }

    /**
     * Ejecutar middlewares específicos de la ruta
     */
    private function executeRouteMiddlewares(array $route): void {
        $request = $_REQUEST;
        $middlewares = $route['middlewares'];
        if (!empty($middlewares)) {
            $middlewareName = array_shift($middlewares);
            $next = function ($req) use ($route) {
                $this->executeHandler($route);
            };
            $this->executeMiddleware($middlewareName, $request, $next);
            // NO ejecutar el handler aquí de nuevo
        } else {
            $this->executeHandler($route);
        }
    }

    /**
     * Ejecutar un middleware específico
     */
    private function executeMiddleware(string $name, $request, $next): void {
        // Si la clase existe (autoload Composer), úsala directamente
        if (class_exists($name)) {
            $middleware = new $name();
            if (!method_exists($middleware, 'handle')) {
                throw new \Exception("Método 'handle' no encontrado en middleware: {$name}");
            }
            $middleware->handle($request, $next);
            return;
        }

        // Si no, busca el archivo manualmente (legacy)
        $middlewareFile = APP_PATH . "/Middlewares/{$name}.php";
        if (!file_exists($middlewareFile)) {
            throw new \Exception("Middleware no encontrado: {$name}");
        }
        require_once $middlewareFile;

        $middlewareClass = $name;
        if (!class_exists($middlewareClass)) {
            throw new \Exception("Clase de middleware no encontrada: {$middlewareClass}");
        }
        $middleware = new $middlewareClass();
        if (!method_exists($middleware, 'handle')) {
            throw new \Exception("Método 'handle' no encontrado en middleware: {$middlewareClass}");
        }
        $middleware->handle($request, $next);
    }

    /**
     * Ejecutar handler de la ruta
     */
    private function executeHandler(array $route): void {
        $handler = $route['handler'];
        $parameters = $route['matched_parameters'] ?? [];

        if (is_string($handler)) {
            // Handler tipo "ControllerClass@method"
            if (strpos($handler, '@') !== false) {
                [$controllerClass, $method] = explode('@', $handler, 2);
                $this->executeControllerMethod($controllerClass, $method, $parameters);
            } else {
                // Handler tipo función
                $this->executeFunction($handler, $parameters);
            }
        } elseif (is_callable($handler)) {
            // Handler tipo closure
            call_user_func_array($handler, array_values($parameters));
        } else {
            throw new \Exception("Tipo de handler no válido: " . gettype($handler));
        }
    }

    /**
     * Ejecutar método de controlador
     */
    /**
     * Ejecutar método de controlador
     */
    private function executeControllerMethod(string $controllerClass, string $method, array $parameters): void {
        $controllerFile = APP_PATH . "/Controllers/{$controllerClass}.php";

        if (!file_exists($controllerFile)) {
            throw new \Exception("Controlador no encontrado: {$controllerClass}");
        }

        require_once $controllerFile;

        $fullControllerClass = "\\App\\Controllers\\{$controllerClass}";
        if (!class_exists($fullControllerClass)) {
            throw new \Exception("Clase de controlador no encontrada: {$controllerClass}");
        }

        $controller = new $fullControllerClass();

        if (!method_exists($controller, $method)) {
            throw new \Exception("Método no encontrado: {$controllerClass}@{$method}");
        }

        // Inyectar dependencias básicas al controlador
        if (method_exists($controller, 'setConfig')) {
            $controller->setConfig($this->config);
        }

        if (method_exists($controller, 'setServices')) {
            $controller->setServices($this->services);
        }

        // Ejecutar método del controlador
        call_user_func_array([$controller, $method], array_values($parameters));
    }

    /**
     * Ejecutar función
     */
    private function executeFunction(string $functionName, array $parameters): void {
        if (!function_exists($functionName)) {
            throw new \Exception("Función no encontrada: {$functionName}");
        }

        call_user_func_array($functionName, array_values($parameters));
    }

    /**
     * Manejar ruta no encontrada
     */
    private function handleNotFound(string $uri): void {
        http_response_code(404);

        if (function_exists('file_log')) {
            file_log('warning', 'Ruta no encontrada', [
                'uri' => $uri,
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
        }

        // Si es AJAX, devolver JSON
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => true,
                'message' => 'Ruta no encontrada',
                'code' => 404
            ]);
        } else {
            // Mostrar página 404
            $notFoundFile = APP_PATH . '/Views/errors/404.php';
            if (file_exists($notFoundFile)) {
                include $notFoundFile;
            } else {
                echo "<h1>404 - Página no encontrada</h1>";
                echo "<p>La ruta solicitada no existe: " . htmlspecialchars($uri) . "</p>";
            }
        }

        exit();
    }

    /**
     * Validar token CSRF
     */
    private function validateCsrfToken(): void {
        if (!($this->config['app']['security']['csrf_protection'] ?? true)) {
            return;
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!$token || !isset($_SESSION['_token']) || !hash_equals($_SESSION['_token'], $token)) {
            if (function_exists('file_log')) {
                file_log('warning', 'Token CSRF inválido', [
                    'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
            }

            http_response_code(419);
            echo json_encode(['error' => 'Token CSRF inválido']);
            exit();
        }
    }

    /**
     * Verificar rate limiting básico
     */
    private function checkRateLimit(): void {
        // Implementación básica de rate limiting por IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $key = 'rate_limit_' . md5($ip);

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'reset_time' => time() + 60];
        }

        $rateLimit = $_SESSION[$key];

        // Reset del contador si ha pasado el tiempo
        if (time() > $rateLimit['reset_time']) {
            $_SESSION[$key] = ['count' => 0, 'reset_time' => time() + 60];
            $rateLimit = $_SESSION[$key];
        }

        // Incrementar contador
        $_SESSION[$key]['count']++;

        // Verificar límite (60 requests por minuto por defecto)
        $maxRequests = $this->config['app']['api']['rate_limit'] ?? 60;
        if ($_SESSION[$key]['count'] > $maxRequests) {
            if (function_exists('file_log')) {
                file_log('warning', 'Rate limit excedido', [
                    'ip' => $ip,
                    'requests' => $_SESSION[$key]['count'],
                    'limit' => $maxRequests
                ]);
            }

            http_response_code(429);
            header('Retry-After: ' . ($rateLimit['reset_time'] - time()));
            echo json_encode(['error' => 'Demasiadas peticiones']);
            exit();
        }
    }

    /**
     * Registrar middleware para múltiples rutas
     */
    public function registerMiddleware(string $name, array $routes): void {
        foreach ($routes as $route) {
            // Buscar y actualizar la ruta con el middleware
            for ($i = 0; $i < count($this->routes); $i++) {
                if ($this->routes[$i]['uri'] === $route) {
                    $this->routes[$i]['middlewares'][] = $name;
                    break;
                }
            }
        }
    }

    /**
     * Obtener todas las rutas registradas
     */
    public function getRoutes(): array {
        return $this->routes;
    }

    /**
     * Generar URL para una ruta nombrada
     */
    public function url(string $name, array $parameters = []): string {
        // Esta funcionalidad se puede implementar más adelante
        // para rutas nombradas
        return '/';
    }

    /**
     * Verificar si una ruta existe
     */
    public function hasRoute(string $method, string $uri): bool {
        return $this->findRoute($method, $uri) !== null;
    }
}
