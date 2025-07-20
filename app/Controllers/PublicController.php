<?php

/**
 * PublicController - Controlador Público
 * Frameworkito
 * 
 * Maneja todas las rutas públicas accesibles sin autenticación
 */

namespace App\Controllers;

class PublicController extends BaseController {

    //============================================================================
    // PAGINAS PÚBLICAS - WEBSITE
    //============================================================================

    /**
     * Landing Page - Página principal pública
     */
    public function landing(): void {
        try {
            $this->log('info', 'Acceso a landing page');

            // ✅ VERIFICAR TIPO DE APLICACIÓN
            $appType = $this->config['app']['app_type'] ?? 'website';

            // Datos para la vista
            $data = [
                'page_title' => 'Bienvenido - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Sistema de autenticación seguro y profesional. Gestiona usuarios, roles y permisos de manera eficiente.',
                'features' => [
                    [
                        'icon' => 'fas fa-shield-alt',
                        'title' => 'Seguridad Avanzada',
                        'description' => 'Autenticación robusta con 2FA, rate limiting y logging completo de actividades.'
                    ],
                    [
                        'icon' => 'fas fa-users-cog',
                        'title' => 'Gestión de Roles',
                        'description' => 'Sistema flexible de roles y permisos para controlar el acceso a diferentes secciones.'
                    ],
                    [
                        'icon' => 'fas fa-chart-line',
                        'title' => 'Analytics & Logs',
                        'description' => 'Monitoreo completo de actividades y generación de reportes de seguridad.'
                    ],
                    [
                        'icon' => 'fas fa-mobile-alt',
                        'title' => 'Responsive Design',
                        'description' => 'Interfaz moderna y adaptable que funciona perfectamente en todos los dispositivos.'
                    ],
                    [
                        'icon' => 'fas fa-code',
                        'title' => 'API REST',
                        'description' => 'API completa para integraciones con otras aplicaciones y servicios externos.'
                    ],
                    [
                        'icon' => 'fas fa-cogs',
                        'title' => 'Fácil Configuración',
                        'description' => 'Setup rápido y configuración flexible para adaptarse a cualquier proyecto.'
                    ]
                ],
                'stats' => [
                    [
                        'number' => '99.9%',
                        'label' => 'Uptime',
                        'icon' => 'fas fa-server'
                    ],
                    [
                        'number' => '< 200ms',
                        'label' => 'Response Time',
                        'icon' => 'fas fa-bolt'
                    ],
                    [
                        'number' => '256-bit',
                        'label' => 'Encryption',
                        'icon' => 'fas fa-lock'
                    ],
                    [
                        'number' => '24/7',
                        'label' => 'Security Monitoring',
                        'icon' => 'fas fa-eye'
                    ]
                ],
                'currentPage' => 'home'
            ];

            // Si APP_TYPE es 'system', redirigir a login
            if ($appType === 'system') {
                // Obtener la ruta actual
                $currentRoute = $_SERVER['REQUEST_URI'] ?? '/';

                // Si la ruta viene vacia o es la raíz, redirigir al login
                if (empty($currentRoute) || $currentRoute === '/') {
                    $this->view('auth/login', $data, 'auth');
                    // $this->view('public/landing', $data, 'guest');
                    return;
                }
            } else {
                // Sitio web → Mostrar landing page
                $this->view('public/landing', $data, 'guest');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página principal');
        }
    }

    /**
     * Página Acerca de
     */
    public function about(): void {
        try {
            $this->log('info', 'Acceso a página About');

            // Datos para la vista (opcionalmente podrías cargar desde un archivo o base de datos)
            $data = [
                'page_title' => 'Acerca de - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Conoce más sobre Frameworkito y las tecnologías que utilizamos.',
                'currentPage' => 'about'
            ];

            $this->view('public/about', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página Acerca de');
        }
    }

    /**
     * Página de Contacto
     */
    public function contact(): void {
        try {
            $this->log('info', 'Acceso a página Contact');

            $data = [
                'page_title' => 'Contacto - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Ponte en contacto con nosotros para soporte técnico o consultas comerciales.',
                'currentPage' => 'contact'
            ];

            $this->view('public/contact', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de contacto');
        }
    }

    /**
     * Procesar formulario de contacto
     */
    public function contactSubmit(): void {
        try {
            // Validar datos del formulario
            $validated = $this->validate([
                'name' => $this->input('name'),
                'email' => $this->input('email'),
                'subject' => $this->input('subject'),
                'message' => $this->input('message')
            ], [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email',
                'subject' => 'required|min:5|max:200',
                'message' => 'required|min:10|max:1000'
            ]);

            // Log del mensaje de contacto
            $this->log('info', 'Formulario de contacto enviado', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject']
            ]);

            // Aquí normalmente enviarías el email
            // EmailService::send($validated);

            // Respuesta según el tipo de request
            if ($this->isAjax()) {
                $this->json([
                    'success' => true,
                    'message' => 'Mensaje enviado correctamente. Te responderemos pronto.'
                ]);
            } else {
                $this->redirectWith('/contact', 'Mensaje enviado correctamente. Te responderemos pronto.', 'success');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al enviar el mensaje de contacto');
        }
    }

    /**
     * Página de Preguntas Frecuentes
     */
    public function faq(): void {
        try {
            $this->log('info', 'Acceso a página FAQ');

            $data = [
                'page_title' => 'FAQ - Preguntas Frecuentes - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Encuentra respuestas rápidas a las preguntas más comunes sobre Frameworkito, instalación, configuración y uso.',
                'currentPage' => 'faq'
            ];

            $this->view('public/faq', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página FAQ');
        }
    }

    /**
     * Política de Privacidad
     */
    public function privacy(): void {
        try {
            $data = [
                'page_title' => 'Política de Privacidad - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Conoce cómo protegemos tu información personal y qué datos recopilamos.'
            ];

            $this->view('public/privacy', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la política de privacidad');
        }
    }

    /**
     * Términos de Servicio
     */
    public function terms(): void {
        try {
            $data = [
                'page_title' => 'Términos de Servicio - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Lee nuestros términos y condiciones de uso del servicio.'
            ];

            $this->view('public/terms', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar los términos de servicio');
        }
    }

    /**
     * API Status - Endpoint público para verificar estado
     */
    public function status(): void {
        try {
            $status = [
                'status' => 'operational',
                'timestamp' => date('c'),
                'version' => $this->config['app']['version'] ?? '1.0.0',
                'environment' => $this->config['app']['environment'] ?? 'production',
                'uptime' => $this->getUptime(),
                'database' => $this->checkDatabaseConnection(),
                'memory_usage' => [
                    'current' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
                    'peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB'
                ]
            ];

            $this->json($status);
        } catch (\Exception $e) {
            $this->json([
                'status' => 'error',
                'message' => 'Service temporarily unavailable',
                'timestamp' => date('c')
            ], 503);
        }
    }

    //============================================================================
    // PÁGINAS PÚBLICAS - DOCUMENTACIÓN
    //============================================================================

    /**
     * Página index de Documentación
     */
    public function documentation(): void {
        try {
            $docRoot = __DIR__ . '/../../documentation';
            $baseUrl = rtrim($_ENV['APP_URL'], '/') . '/doc';

            $docs = $this->scanDocumentation($docRoot, $baseUrl);

            $data = [
                'page_title' => 'Documentación - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Guía completa de uso y configuración de Frameworkito.',
                'docs' => $docs,
                'currentPage' => 'doc'
            ];

            $this->view('public/doc/index', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de documentación');
        }
    }

    // Escanea recursivamente la carpeta /documentation/ para construir el índice
    private function scanDocumentation(string $path, string $baseUrl, string $relative = ''): array {
        $result = [];
        $otherFiles = [];

        $items = scandir($path);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $fullPath = $path . '/' . $item;

            $rawName = pathinfo($item, PATHINFO_FILENAME);
            $normalized = $this->normalizeSlug($rawName); // para URL
            $displayName = $this->cleanDisplayName($rawName); // para mostrar

            if (is_dir($fullPath)) {
                // Subcarpeta
                $result[$displayName] = $this->scanDocumentation(
                    $fullPath,
                    $baseUrl,
                    $relative . $normalized . '/'
                );
            } elseif (is_file($fullPath) && pathinfo($item, PATHINFO_EXTENSION) === 'md') {
                // Archivo suelto .md
                $urlPath = $baseUrl . '/' . $relative . $normalized;

                $otherFiles[] = [
                    'name' => $displayName,
                    'url' => $urlPath
                ];
            }
        }

        // Si hay archivos sueltos, ver si hay además subcarpetas
        if (!empty($otherFiles)) {
            if (!empty($result)) {
                // Hay otras claves además de archivos: agrupar bajo 'Otros'
                $result['Otros'] = $otherFiles;
            } else {
                // Solo hay archivos sueltos: los dejamos directo como contenido principal
                $result = $otherFiles;
            }
        }

        return $result;
    }

    // Convierte nombres con espacios, guiones bajos o símbolos en slugs tipo kebab-case
    private function normalizeSlug(string $name): string {
        $name = strtolower($name);
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name); // quita acentos
        $name = preg_replace('/[^a-z0-9]+/i', '-', $name); // reemplaza no alfanuméricos por -
        return trim($name, '-');
    }

    // Devuelve el nombre legible con capitalización y sin símbolos
    private function cleanDisplayName(string $name): string {
        $name = str_replace(['_', '-', '.'], ' ', $name); // reemplaza guiones por espacio
        $name = preg_replace('/[^A-Za-z0-9 ]/', '', $name); // quita símbolos extraños
        return ucwords(strtolower(trim($name)));
    }

    /**
     * Renderiza cualquier guía Markdown de /documentation/
     */
    public function docViewer(): void {
        try {
            // 1. Obtener la URL actual y extraer el slug
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            $slug = ltrim(str_replace('/doc/', '', parse_url($requestUri, PHP_URL_PATH)), '/');

            if (empty($slug)) {
                $this->log('warning', '404 en documentación - slug vacío en docViewer', [
                    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
                    'ip' => get_client_ip(),
                    'referer' => $_SERVER['HTTP_REFERER'] ?? null
                ]);

                $this->show404([
                    'title' => 'Ruta inválida',
                    'message' => 'No se proporcionó una guía o ruta válida para mostrar.'
                ]);
            }

            // 2. Convertir el slug limpio a nombres de archivo reales
            $pathParts = explode('/', $slug);
            $docRoot = __DIR__ . '/../../documentation';

            // Reconstruir la ruta original basada en los slugs
            $resolvedParts = [];
            $currentDir = $docRoot;

            foreach ($pathParts as $slugPart) {
                $found = false;

                foreach (scandir($currentDir) as $entry) {
                    if ($entry === '.' || $entry === '..') continue;

                    $entryName = pathinfo($entry, PATHINFO_FILENAME);
                    $normalized = $this->normalizeSlug($entryName);

                    if ($normalized === $slugPart) {
                        $resolvedParts[] = $entryName;
                        $currentDir .= '/' . $entry;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $this->log('warning', '404 en documentación - segmento no encontrado', [
                        'slug_segment' => $slugPart,
                        'full_slug' => $slug,
                        'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
                        'ip' => get_client_ip(),
                        'referer' => $_SERVER['HTTP_REFERER'] ?? null
                    ]);

                    $this->show404([
                        'title' => 'Segmento inválido',
                        'message' => 'No se encontró la sección <strong>"' . htmlspecialchars($slugPart) . '"</strong> en la documentación.'
                    ]);
                }
            }

            // 3. Buscar el archivo .md
            $fullPath = $docRoot . '/' . implode('/', $resolvedParts) . '.md';

            if (!file_exists($fullPath)) {
                $this->log('warning', '404 en documentación - archivo no encontrado', [
                    'resolved_path' => $fullPath,
                    'slug' => $slug,
                    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
                    'ip' => get_client_ip(),
                    'referer' => $_SERVER['HTTP_REFERER'] ?? null
                ]);

                $this->show404([
                    'title' => 'Guía no encontrada',
                    'message' => 'El archivo de documentación solicitado no existe: <code>' . basename($fullPath) . '</code>.'
                ]);
            }

            // 4. Renderizar el Markdown con ParsedownExtra
            $parsedownPath = __DIR__ . '/../Helpers/Parsedown.php';
            $parsedownExtraPath = __DIR__ . '/../Helpers/ParsedownExtra.php';
            
            // Cargar Parsedown base primero
            if (!class_exists('Parsedown')) {
                if (file_exists($parsedownPath)) {
                    require_once $parsedownPath;
                } else {
                    throw new \Exception('No se encontró Parsedown.');
                }
            }
            
            // Cargar ParsedownExtra
            if (!class_exists('ParsedownExtra')) {
                if (file_exists($parsedownExtraPath)) {
                    require_once $parsedownExtraPath;
                } else {
                    throw new \Exception('No se encontró ParsedownExtra.');
                }
            }

            $markdown = file_get_contents($fullPath);
            $parsedown = new \ParsedownExtra();
            $parsedown->setSafeMode(true); // Habilitar modo seguro
            $html = $parsedown->text($markdown);

            // 5. Preparar datos para la vista
            $lastSegment = end($resolvedParts);
            $data = [
                'page_title' => $this->cleanDisplayName($lastSegment),
                'content' => $html,
                'currentPage' => 'doc',
            ];

            $this->view('public/doc/view', $data, 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al renderizar guía de documentación');
        }
    }

    /**
     * Verificar conexión a la base de datos
     */
    private function checkDatabaseConnection(): array {
        try {
            if ($this->pdo) {
                $stmt = $this->pdo->query('SELECT 1');
                return [
                    'status' => 'connected',
                    'response_time' => '< 10ms'
                ];
            }
            return ['status' => 'not_configured'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection failed'
            ];
        }
    }

    /**
     * Obtener uptime aproximado
     */
    private function getUptime(): string {
        // Esto es una aproximación simple
        // En producción usarías logs o métricas reales
        $uptimeSeconds = time() - filemtime(__FILE__);
        $days = floor($uptimeSeconds / 86400);
        $hours = floor(($uptimeSeconds % 86400) / 3600);
        $minutes = floor(($uptimeSeconds % 3600) / 60);

        return "{$days}d {$hours}h {$minutes}m";
    }
}
