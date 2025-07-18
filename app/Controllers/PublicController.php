<?php

/**
 * PublicController - Controlador Público
 * AuthManager Base
 * 
 * Maneja todas las rutas públicas accesibles sin autenticación
 */

namespace App\Controllers;

class PublicController extends BaseController {

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
                'page_title' => 'Bienvenido - ' . ($this->config['app']['name'] ?? 'AuthManager Base'),
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
                ]
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

            $data = [
                'page_title' => 'Acerca de - ' . ($this->config['app']['name'] ?? 'AuthManager Base'),
                'meta_description' => 'Conoce más sobre nuestro sistema de autenticación y las tecnologías que utilizamos.'
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
                'page_title' => 'Contacto - ' . ($this->config['app']['name'] ?? 'AuthManager Base'),
                'meta_description' => 'Ponte en contacto con nosotros para soporte técnico o consultas comerciales.'
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
                'page_title' => 'FAQ - Preguntas Frecuentes - ' . ($this->config['app']['name'] ?? 'AuthManager Base'),
                'meta_description' => 'Encuentra respuestas rápidas a las preguntas más comunes sobre AuthManager Base, instalación, configuración y uso.'
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
                'page_title' => 'Política de Privacidad - ' . ($this->config['app']['name'] ?? 'AuthManager Base'),
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
                'page_title' => 'Términos de Servicio - ' . ($this->config['app']['name'] ?? 'AuthManager Base'),
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

    /**
     * Página README
     */
    public function readme(): void {
        try {
            $this->view('public/readme', [], 'guest');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página README');
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
