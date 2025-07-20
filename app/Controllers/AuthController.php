<?php

/**
 * AuthController - Controlador de Autenticación REAL
 * Frameworkito
 * 
 * Maneja todas las funciones de autenticación usando Delight-im/Auth
 */

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\Validator;
use App\Helpers\Redirect;
use App\Helpers\Auth;
use App\Services\EmailService;

class AuthController extends BaseController {

    /**
     * Mostrar formulario de login
     */
    public function showLogin(): void {
        try {
            // Verificar si ya está logueado
            if (Auth::isLoggedIn()) {
                $this->log('info', 'Usuario ya logueado intenta acceder a login', ['user_id' => Auth::getUserId()]);
                $this->redirectWith('/home', 'Ya tienes una sesión activa', 'info');
                return;
            }

            $this->log('info', 'Acceso a página de login');

            $data = [
                'page_title' => 'Iniciar Sesión - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Accede a tu cuenta en Frameworkito',
                'redirect_to' => $this->input('redirect', '/home'),
                'app_name' => $this->config['app']['name'] ?? 'Frameworkito',
                'app_debug' => $this->config['app']['debug'] ?? false
            ];

            $this->view('auth/login', $data, 'auth');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de login');
        }
    }

    /**
     * Procesar login
     */
    public function login(): void {
        try {
            // Verificar si ya está logueado
            if (Auth::isLoggedIn()) {
                $this->redirectWith('/home', 'Ya tienes una sesión activa', 'info');
                return;
            }

            $this->log('info', 'Intento de login', [
                'login' => $this->input('login'),
                'ip' => get_client_ip()
            ]);

            // Validar datos del formulario
            $validated = $this->validate([
                'login' => $this->input('login'),
                'password' => $this->input('password'),
                'remember' => (bool) $this->input('remember', 0)
            ], [
                'login' => 'required',
                'password' => 'required|min:6'
            ]);

            $validated['remember'] = $validated['remember'] ?? false;

            // Detectar si es email o username
            $loginInput = $validated['login'];
            $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

            // Intentar login con Delight-im/Auth
            if ($isEmail) {
                $result = Auth::login(
                    $loginInput,
                    $validated['password'],
                    (bool) $validated['remember']
                );
            } else {
                $result = Auth::login(
                    $loginInput,
                    $validated['password'],
                    (bool) $validated['remember']
                );
            }

            if ($result['success']) {
                $this->log('info', 'Login exitoso', [
                    'email' => $validated['login'],
                    'user_id' => $result['user_id'],
                    'remember' => $validated['remember']
                ]);

                // Redireccionar
                $redirectTo = $this->input('redirect', '/home');
                $this->redirectWith($redirectTo, 'Bienvenido de vuelta!', 'success');
            } else {
                $this->log('warning', 'Login fallido', [
                    'email' => $validated['login'],
                    'reason' => $result['message'],
                    'ip' => get_client_ip()
                ]);

                $this->redirectWith('/login', $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error procesando el login');
        }
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister(): void {
        try {
            // Verificar si ya está logueado
            if (Auth::isLoggedIn()) {
                $this->redirectWith('/home', 'Ya tienes una sesión activa', 'info');
                return;
            }

            // Verificar si el registro está habilitado
            if (!config('auth.registration_enabled', true)) {
                $this->redirectWith('/login', 'El registro está temporalmente deshabilitado', 'warning');
                return;
            }

            $this->log('info', 'Acceso a página de registro');

            $data = [
                'page_title' => 'Crear Cuenta - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Crea tu cuenta en Frameworkito',
                'app_name' => $this->config['app']['name'] ?? 'Frameworkito',
                'app_debug' => $this->config['app']['debug'] ?? false,
                'password_requirements' => [
                    'min_length' => config('auth.password_min_length', 8),
                    'require_uppercase' => config('auth.password_require_uppercase', true),
                    'require_lowercase' => config('auth.password_require_lowercase', true),
                    'require_numbers' => config('auth.password_require_numbers', true),
                    'require_symbols' => config('auth.password_require_symbols', true)
                ]
            ];

            $this->view('auth/register', $data, 'auth');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de registro');
        }
    }

    /**
     * Procesar registro
     */
    public function register(): void {
        try {
            // Verificar si ya está logueado
            if (Auth::isLoggedIn()) {
                $this->redirectWith('/home', 'Ya tienes una sesión activa', 'info');
                return;
            }

            // Verificar si el registro está habilitado
            if (!config('auth.registration_enabled', true)) {
                $this->redirectWith('/login', 'El registro está temporalmente deshabilitado', 'warning');
                return;
            }

            $this->log('info', 'Intento de registro', [
                'email' => $this->input('email'),
                'ip' => get_client_ip()
            ]);

            // Validar datos del formulario
            $validated = $this->validate([
                'name' => $this->input('name'),
                'email' => $this->input('email'),
                'password' => $this->input('password'),
                'password_confirmation' => $this->input('password_confirmation'),
                'terms' => $this->input('terms', false)
            ], [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
                'terms' => 'required'
            ]);

            // Intentar registro con Delight-im/Auth
            $result = Auth::register(
                $validated['email'],
                $validated['password'],
                $validated['name']
            );

            if ($result['success']) {
                $this->log('info', 'Registro exitoso', [
                    'email' => $validated['email'],
                    'user_id' => $result['user_id'],
                    'requires_verification' => $result['requires_verification'] ?? false
                ]);

                if ($result['requires_verification'] ?? false) {
                    $this->redirectWith('/login', 'Cuenta creada exitosamente. Revisa tu email para verificar tu cuenta.', 'success');
                } else {
                    $this->redirectWith('/login', 'Cuenta creada exitosamente. Puedes iniciar sesión.', 'success');
                }
            } else {
                $this->log('warning', 'Registro fallido', [
                    'email' => $validated['email'],
                    'reason' => $result['message'],
                    'ip' => get_client_ip()
                ]);

                $this->redirectWith('/register', $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error procesando el registro');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void {
        try {
            $user_id = Auth::getUserId();
            $user_email = Auth::getUserEmail();

            $this->log('info', 'Logout de usuario', [
                'user_id' => $user_id,
                'email' => $user_email
            ]);

            // Cerrar sesión con Delight-im/Auth
            $result = Auth::logout();

            if ($result['success']) {
                $this->log('info', 'Logout exitoso', ['user_id' => $user_id]);
                $this->redirectWith('/', 'Sesión cerrada exitosamente', 'success');
            } else {
                $this->log('error', 'Error en logout', ['reason' => $result['message']]);
                $this->redirectWith('/home', 'Error al cerrar sesión: ' . $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error cerrando sesión');
        }
    }

    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgotPassword(): void {
        try {
            // Verificar si ya está logueado
            if (Auth::isLoggedIn()) {
                $this->redirectWith('/home', 'Ya tienes una sesión activa', 'info');
                return;
            }

            $this->log('info', 'Acceso a página de recuperación de contraseña');

            $data = [
                'page_title' => 'Recuperar Contraseña - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Recupera el acceso a tu cuenta',
                'app_name' => $this->config['app']['name'] ?? 'Frameworkito'
            ];

            $this->view('auth/forgot-password', $data, 'auth');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de recuperación');
        }
    }

    /**
     * Procesar solicitud de recuperación de contraseña
     */
    public function forgotPassword(): void {
        try {
            $email = $this->input('email');

            $this->log('info', 'Solicitud de recuperación de contraseña', ['email' => $email]);

            // Validar email
            $validated = $this->validate([
                'email' => $email
            ], [
                'email' => 'required|email'
            ]);

            // Procesar con Delight-im/Auth
            $result = Auth::forgotPassword($validated['email']);

            // Si el resultado incluye selector/token y success, enviar correo de recuperación
            if ($result['success'] && isset($result['selector'], $result['token'])) {
                // Construir enlace seguro
                $appUrl = getenv('APP_URL');
                // $resetLink = rtrim($appUrl, '/') . "/reset-password/id={$result['selector']}&token={$result['token']}";
                $resetLink = rtrim($appUrl, '/') . "/reset-password/{$result['selector']}/{$result['token']}";

                // Obtener nombre del usuario si está disponible (ajustar según tu modelo de usuario)
                $userName = 'Apreciado usuario/a';
                if (class_exists('App\\Models\\User')) {
                    $userModel = new \App\Models\User();
                    $user = $userModel->findByEmail($validated['email']);
                    if ($user && isset($user['username'])) {
                        $userName = $user['username'];
                    }
                }

                // Nombre de empresa
                $companyName = $this->config['app']['name'];

                // Enviar email de recuperación
                try {
                    $emailService = new \App\Services\EmailService();
                    $emailService->sendPasswordReset($validated['email'], $userName, $resetLink, $companyName);
                } catch (\Exception $ex) {
                    $this->log('error', 'Error enviando email de recuperación', [
                        'email' => $validated['email'],
                        'error' => $ex->getMessage()
                    ]);
                }
            }

            // Si hay error SMTP, pásalo como flash estándar
            if (!empty($_SESSION['_flash']['email_error'])) {
                \App\Helpers\Session::error($_SESSION['_flash']['email_error']);
            }

            // Siempre mostrar mensaje de éxito por seguridad (no revelar si el email existe)
            $this->log('info', 'Solicitud de recuperación procesada', [
                'email' => $validated['email'],
                'success' => $result['success'],
                'companyName' => $companyName
            ]);

            $this->redirectWith(
                '/confirm-mail',
                'Si el email existe en nuestro sistema, recibirás instrucciones para recuperar tu contraseña.',
                'success'
            );
        } catch (\Exception $e) {
            $this->handleError($e, 'Error procesando recuperación de contraseña');
        }
    }

    /**
     * Mostrar formulario de confirmación de correo
     */
    public function showSendConfirmMail(): void {
        try {
            // Verificar si ya está logueado
            if (Auth::isLoggedIn()) {
                $this->redirectWith('/home', 'Ya tienes una sesión activa', 'info');
                return;
            }

            $this->log('info', 'Acceso a página de confirmación de correo');

            $data = [
                'page_title' => 'Confirmación de Correo - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Confirma tu correo electrónico',
                'app_name' => $this->config['app']['name'] ?? 'Frameworkito'
            ];

            $this->view('auth/confirm-mail', $data, 'auth');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de confirmación de correo');
        }
    }

    /**
     * Procesar confirmación de correo
     */
    public function sendConfirmMail(): void {
        try {
            $this->log('info', 'Intento de confirmación de correo');

            // Procesar con AuthHelper
            $result = Auth::sendConfirmMail();

            if ($result['success']) {
                $this->log('info', 'Confirmación exitosa', ['user_id' => $result['user_id']]);
                $this->redirectWith('/login', 'Correo confirmado exitosamente. Puedes iniciar sesión.', 'success');
            } else {
                $this->log('error', 'Error en confirmación', ['reason' => $result['message']]);
                $this->redirectWith('/register', 'Error al confirmar correo: ' . $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error procesando confirmación de correo');
        }
    }

    /**
     * Mostrar formulario de reset de contraseña
     */
    public function showResetPassword($selector = null, $token = null): void {
        try {
            // Si no llegan por la URL, intenta obtenerlos de la query string (soporta ambos formatos)
            if (!$selector) {
                $selector = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['selector']) ? $_GET['selector'] : null);
            }
            if (!$token) {
                $token = isset($_GET['token']) ? $_GET['token'] : null;
            }

            if (!$selector || !$token) {
                $this->redirectWith('/forgot-password', 'Link de recuperación inválido', 'error');
                return;
            }

            $this->log('info', 'Acceso a página de reset de contraseña', ['selector' => $selector, 'token' => $token]);

            $data = [
                'page_title' => 'Nueva Contraseña - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Establece tu nueva contraseña',
                'selector' => $selector,
                'token' => $token,
                'app_name' => $this->config['app']['name'] ?? 'Frameworkito'
            ];

            $this->view('auth/reset-password', $data, 'auth');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de reset');
        }
    }


    /**
     * Procesar reset de contraseña
     */
    public function resetPassword(): void {
        try {
            $selector = $this->input('selector');
            $token = $this->input('token');
            $password = $this->input('password');

            $this->log('info', 'Intento de reset de contraseña', ['selector' => $selector]);

            // Validar datos
            $validated = $this->validate([
                'selector' => $selector,
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $this->input('password_confirmation')
            ], [
                'selector' => 'required',
                'token' => 'required',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password'
            ]);

            // Obtener email y nombre ANTES del reset
            $userEmail = '';
            $userName = 'Apreciado usuario/a';
            $user = null;
            if (class_exists('App\\Models\\User')) {
                $userModel = new \App\Models\User();
                $user = $userModel->findBySelector($selector);
                if ($user && isset($user['email'])) {
                    $userEmail = $user['email'];
                    $userName = $user['username'] ?? $userEmail;
                }
            }

            // Procesar con Delight-im/Auth
            $result = Auth::resetPassword(
                $validated['selector'],
                $validated['token'],
                $validated['password']
            );

            if ($result['success']) {
                $this->log('info', 'Reset de contraseña exitoso', ['selector' => $selector]);

                $companyName = $this->config['app']['name'] ?? 'Frameworkito';

                // Enviar email de confirmación de cambio de contraseña
                if ($userEmail) {
                    try {
                        $this->log('info', 'Correo de confirmación de cambio de contraseña enviado', [
                            'email' => $userEmail
                        ]);
                        $emailService = new \App\Services\EmailService();
                        $emailService->sendPasswordChanged($userEmail, $userName, $companyName);
                    } catch (\Exception $ex) {
                        $this->log('error', 'Error enviando email de confirmación de cambio de contraseña', [
                            'email' => $userEmail,
                            'error' => $ex->getMessage()
                        ]);
                    }
                }

                $this->log('info', 'Contraseña restablecida exitosamente wha', [
                    'selector' => $selector,
                    'user_id' => $user['id'] ?? 'unknown',
                    'user_email' => $userEmail
                ]);

                $this->redirectWith('/login', 'Contraseña restablecida exitosamente. Puedes iniciar sesión.', 'success');
            } else {
                $this->log('warning', 'Reset de contraseña fallido', [
                    'selector' => $selector,
                    'reason' => $result['message']
                ]);
                $this->redirectWith('/forgot-password', $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error procesando reset de contraseña');
        }
    }

    /**
     * Mostrar página de verificación de email
     */
    public function showVerifyEmail(): void {
        try {
            $selector = $this->input('s');
            $token = $this->input('t');

            $this->log('info', 'Acceso a página de verificación de email', ['selector' => $selector]);

            $data = [
                'page_title' => 'Verificar Email - ' . ($this->config['app']['name'] ?? 'Frameworkito'),
                'meta_description' => 'Verifica tu dirección de email',
                'selector' => $selector,
                'token' => $token,
                'app_name' => $this->config['app']['name'] ?? 'Frameworkito'
            ];

            $this->view('auth/verify-email', $data, 'auth');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error al cargar la página de verificación');
        }
    }

    /**
     * Procesar verificación de email
     */
    public function verifyEmail(): void {
        try {
            $selector = $this->input('s');
            $token = $this->input('t');

            $this->log('info', 'Intento de verificación de email', ['selector' => $selector]);

            if (!$selector || !$token) {
                $this->redirectWith('/login', 'Link de verificación inválido', 'error');
                return;
            }

            // Procesar con Delight-im/Auth
            $result = Auth::verifyEmail($selector, $token);

            if ($result['success']) {
                $this->log('info', 'Verificación de email exitosa', ['selector' => $selector]);
                $this->redirectWith('/login', 'Email verificado exitosamente. Puedes iniciar sesión.', 'success');
            } else {
                $this->log('warning', 'Verificación de email fallida', [
                    'selector' => $selector,
                    'reason' => $result['message']
                ]);
                $this->redirectWith('/register', $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->handleError($e, 'Error procesando verificación de email');
        }
    }

    /**
     * API endpoint para verificación AJAX
     */
    public function verifyEmailAjax(): void {
        try {
            $selector = $this->input('s');
            $token = $this->input('t');

            if (!$selector || !$token) {
                echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
                return;
            }

            $result = Auth::verifyEmail($selector, $token);

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Reenviar email de verificación
     */
    public function resendVerification(): void {
        try {
            $email = $this->input('email');

            if (!$email) {
                $this->redirectWith('/register', 'Email requerido para reenvío', 'error');
                return;
            }

            $this->log('info', 'Solicitud de reenvío de verificación', ['email' => $email]);

            $result = Auth::resendVerification($email);

            $this->redirectWith('/login', 'Si el email existe y requiere verificación, se ha reenviado el enlace.', 'success');
        } catch (\Exception $e) {
            $this->handleError($e, 'Error reenviando verificación');
        }
    }
}
