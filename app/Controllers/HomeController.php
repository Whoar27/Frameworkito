<?php

/**
 * HomeController - Controlador de Páginas de Inicio
 * AuthManager Base
 */

namespace App\Controllers;

class HomeController extends BaseController {
    /**
     * Página de inicio
     */
    public function index(): void {
        try {
            $this->log('info', 'Acceso a página de inicio');

            $data = [
                'title' => 'Inicio',
                'welcome_message' => '¡Bienvenido a AuthManager Base!',
                'system_status' => $this->getSystemStatus(),
                'features' => $this->getFeatures()
            ];

            $this->view('home/index', $data);
        } catch (\Exception $e) {
            $this->handleError($e, 'Error cargando la página de inicio');
        }
    }

    /**
     * Página de información del sistema
     */
    public function info(): void {
        try {
            $this->log('info', 'Acceso a información del sistema');

            $data = [
                'title' => 'Información del Sistema',
                'system_info' => \App\Helpers\Utils::getSystemInfo(),
                'performance_stats' => \App\Helpers\Utils::getPerformanceStats(),
                'config_status' => $this->getConfigStatus(),
                'file_permissions' => $this->checkFilePermissions()
            ];

            $this->view('home/info', $data);
        } catch (\Exception $e) {
            $this->handleError($e, 'Error cargando información del sistema');
        }
    }

    /**
     * Página de testing (solo en debug)
     */
    public function test(): void {
        if (!($this->config['app']['debug'] ?? false)) {
            $this->redirect('/');
            return;
        }

        try {
            $this->log('debug', 'Acceso a página de testing');

            // Probar funciones del sistema
            $tests = [
                'session' => $this->testSession(),
                'logging' => $this->testLogging(),
                'validation' => $this->testValidation(),
                'utilities' => $this->testUtilities()
            ];

            $data = [
                'title' => 'Testing del Sistema',
                'tests' => $tests,
                'debug_info' => [
                    'memory_usage' => \App\Helpers\Utils::getMemoryUsage(),
                    'execution_time' => defined('APP_START_TIME') ?
                        round((microtime(true) - APP_START_TIME) * 1000, 2) . 'ms' : 'unknown',
                    'request_info' => $this->getRequestInfo()
                ]
            ];

            $this->view('home/test', $data);
        } catch (\Exception $e) {
            $this->handleError($e, 'Error en página de testing');
        }
    }

    /**
     * Mostrar phpinfo (solo en debug)
     */
    public function phpinfo(): void {
        if (!($this->config['app']['debug'] ?? false)) {
            $this->redirect('/');
            return;
        }

        $this->log('debug', 'Acceso a phpinfo');
        phpinfo();
    }

    /**
     * =============================================================================
     * MÉTODOS PRIVADOS
     * =============================================================================
     */

    /**
     * Obtener estado del sistema
     */
    private function getSystemStatus(): array {
        return [
            'core' => [
                'status' => 'OK',
                'message' => 'Núcleo del sistema funcionando correctamente'
            ],
            'configuration' => [
                'status' => file_exists(CONFIG_PATH . '/app.php') ? 'OK' : 'ERROR',
                'message' => 'Archivos de configuración cargados'
            ],
            'helpers' => [
                'status' => class_exists('Session') ? 'OK' : 'ERROR',
                'message' => 'Helpers del sistema disponibles'
            ],
            'logging' => [
                'status' => function_exists('file_log') ? 'OK' : 'ERROR',
                'message' => 'Sistema de logging operativo'
            ],
            'database' => [
                'status' => $this->pdo ? 'OK' : 'PENDING',
                'message' => $this->pdo ? 'Conexión establecida' : 'Sin dependencias aún'
            ]
        ];
    }

    /**
     * Obtener características del sistema
     */
    private function getFeatures(): array {
        return [
            [
                'icon' => '🔐',
                'title' => 'Autenticación Completa',
                'description' => 'Login, registro, recuperación de contraseña y 2FA',
                'status' => 'pending'
            ],
            [
                'icon' => '📊',
                'title' => 'Sistema de Logs Dual',
                'description' => 'Logging en archivos y base de datos para auditoría',
                'status' => 'ready'
            ],
            [
                'icon' => '🛡️',
                'title' => 'Seguridad Robusta',
                'description' => 'CSRF, rate limiting, validación de sesiones',
                'status' => 'ready'
            ],
            [
                'icon' => '🎯',
                'title' => 'Validación de Datos',
                'description' => 'Sistema completo de validación con reglas personalizables',
                'status' => 'ready'
            ],
            [
                'icon' => '🔄',
                'title' => 'Reutilizable',
                'description' => 'Base sólida para múltiples proyectos',
                'status' => 'ready'
            ],
            [
                'icon' => '⚡',
                'title' => 'Performance',
                'description' => 'Optimizado para velocidad y eficiencia',
                'status' => 'ready'
            ]
        ];
    }

    /**
     * Verificar estado de configuraciones
     */
    private function getConfigStatus(): array {
        $configs = ['app', 'auth', 'database', 'logging', 'mail'];
        $status = [];

        foreach ($configs as $config) {
            $file = CONFIG_PATH . "/{$config}.php";
            $status[$config] = [
                'exists' => file_exists($file),
                'readable' => file_exists($file) && is_readable($file),
                'size' => file_exists($file) ? filesize($file) : 0
            ];
        }

        return $status;
    }

    /**
     * Verificar permisos de archivos
     */
    private function checkFilePermissions(): array {
        $paths = [
            'logs' => LOGS_PATH,
            'storage' => ROOT_PATH . '/storage',
            'uploads' => PUBLIC_PATH . '/uploads'
        ];

        $permissions = [];

        foreach ($paths as $name => $path) {
            $permissions[$name] = [
                'path' => $path,
                'exists' => file_exists($path),
                'writable' => file_exists($path) && is_writable($path),
                'readable' => file_exists($path) && is_readable($path)
            ];
        }

        return $permissions;
    }

    /**
     * =============================================================================
     * MÉTODOS DE TESTING
     * =============================================================================
     */

    /**
     * Test del sistema de sesiones
     */
    private function testSession(): array {
        try {
            // Test básico de sesión
            \App\Helpers\Session::set('test_key', 'test_value');
            $value = \App\Helpers\Session::get('test_key');
            \App\Helpers\Session::remove('test_key');

            // Test de flash message
            \App\Helpers\Session::flash('test_flash', 'test_message');
            $flashExists = \App\Helpers\Session::hasFlash('test_flash');
            $flashValue = \App\Helpers\Session::getFlash('test_flash');

            return [
                'status' => 'OK',
                'tests' => [
                    'set_get' => $value === 'test_value',
                    'flash_message' => $flashExists && $flashValue === 'test_message',
                    'csrf_token' => !empty(\App\Helpers\Session::getCsrfToken())
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test del sistema de logging
     */
    private function testLogging(): array {
        try {
            // Test de file logging
            file_log('debug', 'Test de logging desde HomeController');

            return [
                'status' => 'OK',
                'tests' => [
                    'file_log_function' => function_exists('file_log'),
                    'log_directory' => is_dir(LOGS_PATH) && is_writable(LOGS_PATH)
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test del sistema de validación
     */
    private function testValidation(): array {
        try {
            $testData = [
                'email' => 'test@example.com',
                'name' => 'Test User',
                'age' => '25'
            ];

            $validator = \App\Helpers\Validator::make($testData, [
                'email' => 'required|email',
                'name' => 'required|min:3',
                'age' => 'required|numeric'
            ]);

            return [
                'status' => 'OK',
                'tests' => [
                    'validation_passes' => $validator->passes(),
                    'email_validation' => \App\Helpers\Validator::isEmail('test@example.com'),
                    'numeric_validation' => \App\Helpers\Validator::isNumeric('123')
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test de utilidades
     */
    private function testUtilities(): array {
        try {
            return [
                'status' => 'OK',
                'tests' => [
                    'slug_generation' => \App\Helpers\Utils::slug('Test String') === 'test-string',
                    'random_string' => strlen(\App\Helpers\Utils::randomString(10)) === 10,
                    'format_bytes' => !empty(\App\Helpers\Utils::formatBytes(1024)),
                    'is_email' => \App\Helpers\Utils::isValidEmail('test@example.com'),
                    'client_ip' => !empty(get_client_ip())
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'error' => $e->getMessage()
            ];
        }
    }
}
