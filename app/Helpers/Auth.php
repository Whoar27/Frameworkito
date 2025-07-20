<?php

/**
 * Auth - Helper de Autenticación
 * Frameworkito
 * 
 * Wrapper para Delight-im/Auth que proporciona:
 * - Interfaz simplificada
 * - Logging automático
 * - Validaciones adicionales
 * - Integración con sesiones
 */

namespace App\Helpers;

use Delight\Auth\Auth as DelightAuth;

class Auth {
    private static ?DelightAuth $auth = null;
    private static array $config = [];

    /**
     * Inicializar Auth - CORREGIDO FINAL
     */
    private static function init(): void {
        if (self::$auth !== null) {
            return;
        }

        // Cargar configuración
        self::$config = config('auth', []);

        // Obtener conexión PDO desde el servicio de la aplicación
        global $app;
        if (isset($app) && method_exists($app, 'getPDO')) {
            $pdo = $app->getPDO();
        } else {
            // Fallback: usar el helper Database centralizado
            $pdo = \App\Helpers\Database::getConnection();
        }

        // Crear instancia de Delight Auth
        self::$auth = new DelightAuth($pdo);

        // Inicializar sesión si no está iniciada
        Session::start();
    }

    /**
     * =============================================================================
     * AUTENTICACIÓN
     * =============================================================================
     */

    /**
     * Iniciar sesión
     */
    public static function login(string $email, string $password, bool $remember = false): array {
        self::init();

        try {
            // Verificar rate limiting
            self::checkRateLimit('login', $email);

            // Intentar login - CORREGIDO: usar email en lugar de username
            if ($remember) {
                $duration = (self::$config['remember_me_duration'] ?? 30) * 24 * 3600; // días a segundos
                self::$auth->login($email, $password, $duration);
            } else {
                self::$auth->login($email, $password);
            }

            $user = self::getCurrentUser();

            // Log exitoso
            file_log('auth', 'Login exitoso', ['email' => $email, 'user_id' => $user['id']]);
            bd_log('auth', 'Usuario logueado exitosamente', [
                'action' => 'login_success',
                'email' => $email,
                'remember' => $remember
            ], $user['id']);

            // Actualizar información de sesión
            Session::set('user_id', $user['id']);
            Session::set('user_email', $user['email']);
            Session::set('login_time', time());

            return [
                'success' => true,
                'message' => 'Login exitoso',
                'user_id' => $user['id'],
                'user' => $user
            ];
        } catch (\Delight\Auth\InvalidEmailException $e) {
            return self::handleLoginError('Correo o contraseña incorrectos', $email, 'invalid_email');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            return self::handleLoginError('Correo o contraseña incorrectos', $email, 'invalid_password');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            return self::handleLoginError('Email no verificado', $email, 'email_not_verified');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return self::handleLoginError('Demasiados intentos. Intenta más tarde', $email, 'rate_limited');
        } catch (\Exception $e) {
            return self::handleLoginError('Error interno: ' . $e->getMessage(), $email, 'system_error');
        }
    }

    /**
     * Manejar errores de login
     */
    private static function handleLoginError(string $message, string $email, string $reason): array {
        // Log del error
        file_log('auth', 'Login fallido: ' . $reason, ['email' => $email]);
        bd_log('security', 'Intento de login fallido', [
            'action' => 'login_failure',
            'email' => $email,
            'reason' => $reason,
            'ip' => get_client_ip()
        ]);

        // Incrementar contador de intentos fallidos
        self::incrementFailedAttempts($email);

        return [
            'success' => false,
            'message' => $message,
            'reason' => $reason
        ];
    }

    /**
     * Cerrar sesión
     */
    public static function logout(): array {
        self::init();

        $user = self::getCurrentUser();

        try {
            self::$auth->logOut();

            // Log del logout
            if ($user) {
                file_log('auth', 'Logout exitoso', ['user_id' => $user['id']]);
                bd_log('auth', 'Usuario cerró sesión', [
                    'action' => 'logout',
                ], $user['id']);
            }

            // Limpiar datos de sesión
            Session::remove('user_id');
            Session::remove('user_email');
            Session::remove('login_time');

            return [
                'success' => true,
                'message' => 'Sesión cerrada exitosamente'
            ];
        } catch (\Exception $e) {
            file_log('error', 'Error en logout: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error cerrando sesión'
            ];
        }
    }

    /**
     * =============================================================================
     * REGISTRO
     * =============================================================================
     */

    /**
     * Registrar nuevo usuario
     */
    public static function register(string $email, string $password, string $username = null): array {
        self::init();

        try {
            // Verificar rate limiting
            self::checkRateLimit('register', $email);

            // Validar contraseña
            $passwordValidation = self::validatePassword($password);
            if (!$passwordValidation['valid']) {
                return [
                    'success' => false,
                    'message' => 'Contraseña no válida',
                    'errors' => $passwordValidation['errors']
                ];
            }

            // Registrar usuario
            $userId = self::$auth->register($email, $password, $username);

            // Log del registro
            file_log('auth', 'Usuario registrado', ['email' => $email, 'user_id' => $userId]);
            bd_log('auth', 'Usuario registrado exitosamente', [
                'action' => 'registration',
                'email' => $email
            ], $userId);

            // Enviar email de verificación si está habilitado
            if (self::$config['require_email_verification'] ?? true) {
                self::sendVerificationEmail($email);
            }

            return [
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'user_id' => $userId,
                'requires_verification' => self::$config['require_email_verification'] ?? true
            ];
        } catch (\Delight\Auth\InvalidEmailException $e) {
            return ['success' => false, 'message' => 'Email inválido'];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            return ['success' => false, 'message' => 'Contraseña inválida'];
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return ['success' => false, 'message' => 'El usuario ya existe'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return ['success' => false, 'message' => 'Demasiados intentos. Intenta más tarde'];
        } catch (\Exception $e) {
            file_log('error', 'Error en registro: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno'];
        }
    }

    /**
     * =============================================================================
     * VERIFICACIÓN DE EMAIL
     * =============================================================================
     */

    /**
     * Verificar email con token
     */
    public static function verifyEmail(string $selector, string $token): array {
        self::init();

        try {
            self::$auth->confirmEmail($selector, $token);

            $user = self::getCurrentUser();

            // Log de verificación
            file_log('auth', 'Email verificado', ['user_id' => $user['id'] ?? 'unknown']);
            bd_log('auth', 'Email verificado exitosamente', [
                'action' => 'email_verification'
            ], $user['id'] ?? null);

            return [
                'success' => true,
                'message' => 'Email verificado exitosamente'
            ];
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            return ['success' => false, 'message' => 'Token de verificación inválido'];
        } catch (\Delight\Auth\TokenExpiredException $e) {
            return ['success' => false, 'message' => 'Token de verificación expirado'];
        } catch (\Exception $e) {
            file_log('error', 'Error verificando email: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno'];
        }
    }

    /**
     * Reenviar email de verificación
     */
    public static function resendVerification(string $email): array {
        self::init();

        try {
            // CORREGIDO: método correcto de Delight-im/Auth
            self::$auth->resendConfirmationForEmail($email, function ($selector, $token) use ($email) {
                file_log('auth', 'Token de verificación generado para reenvío', [
                    'email' => $email,
                    'selector' => $selector
                ]);
            });

            file_log('auth', 'Email de verificación reenviado', ['email' => $email]);

            return [
                'success' => true,
                'message' => 'Email de verificación enviado'
            ];
        } catch (\Exception $e) {
            file_log('error', 'Error reenviando verificación: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error enviando email'];
        }
    }

    /**
     * =============================================================================
     * RECUPERACIÓN DE CONTRASEÑA
     * =============================================================================
     */

    /**
     * Solicitar recuperación de contraseña
     */
    public static function forgotPassword(string $email): array {
        self::init();

        try {
            // Rate limit
            self::checkRateLimit('password_reset', $email);

            $result = [];
            self::$auth->forgotPassword($email, function ($selector, $token) use (&$result) {
                $result['selector'] = $selector;
                $result['token'] = $token;
            });

            bd_log('auth', 'Solicitud de recuperación de contraseña', [
                'action' => 'password_reset_request',
                'email' => $email
            ]);

            if (!empty($result['selector']) && !empty($result['token'])) {
                return [
                    'success' => true,
                    'selector' => $result['selector'],
                    'token' => $result['token'],
                    'message' => 'Token generado'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se pudo generar token de recuperación'
                ];
            }
        } catch (\Delight\Auth\InvalidEmailException $e) {
            return ['success' => false, 'message' => 'Email inválido'];
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            return ['success' => false, 'message' => 'Email no verificado'];
        } catch (\Delight\Auth\ResetDisabledException $e) {
            return ['success' => false, 'message' => 'Reset de contraseña deshabilitado'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return ['success' => false, 'message' => 'Demasiados intentos. Intenta más tarde'];
        } catch (\Exception $e) {
            file_log('error', 'Error en forgot password: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno'];
        }
    }

    /**
     * Restablecer contraseña con token
     */
    public static function resetPassword(string $selector, string $token, string $newPassword): array {
        self::init();

        try {
            // Validar nueva contraseña
            $passwordValidation = self::validatePassword($newPassword);
            if (!$passwordValidation['valid']) {
                return [
                    'success' => false,
                    'message' => 'Contraseña no válida',
                    'errors' => $passwordValidation['errors']
                ];
            }

            self::$auth->resetPassword($selector, $token, $newPassword);

            $user = self::getCurrentUser();

            // Log del reset
            file_log('auth', 'Contraseña restablecida', ['user_id' => $user['id'] ?? 'unknown']);
            bd_log('auth', 'Contraseña restablecida exitosamente', [
                'action' => 'password_reset_success'
            ], $user['id'] ?? null);

            return [
                'success' => true,
                'message' => 'Contraseña restablecida exitosamente'
            ];
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            return ['success' => false, 'message' => 'Token de reset inválido'];
        } catch (\Delight\Auth\TokenExpiredException $e) {
            return ['success' => false, 'message' => 'Token de reset expirado'];
        } catch (\Delight\Auth\ResetDisabledException $e) {
            return ['success' => false, 'message' => 'Reset de contraseña deshabilitado'];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            return ['success' => false, 'message' => 'Contraseña inválida'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return ['success' => false, 'message' => 'Demasiados intentos. Intenta más tarde'];
        } catch (\Exception $e) {
            file_log('error', 'Error en reset password: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno'];
        }
    }

    /**
     * =============================================================================
     * ESTADO DE USUARIO
     * =============================================================================
     */

    /**
     * Verificar si usuario está logueado
     */
    public static function isLoggedIn(): bool {
        self::init();
        return self::$auth->isLoggedIn();
    }

    /**
     * Verificar si usuario está logueado (alias)
     */
    public static function check(): bool {
        return self::isLoggedIn();
    }

    /**
     * Verificar si usuario es invitado
     */
    public static function guest(): bool {
        return !self::isLoggedIn();
    }

    /**
     * Obtener usuario actual
     */
    public static function getCurrentUser(): ?array {
        self::init();

        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => self::$auth->getUserId(),
            'email' => self::$auth->getEmail(),
            'username' => self::$auth->getUsername(),
            'status' => self::$auth->getStatus(),
            // 'verified' => self::$auth->isEmailAddressVerified(),
            'registered' => time(), // TODO: obtener fecha real de registro
            'last_login' => Session::get('login_time')
        ];
    }

    /**
     * Obtener usuario actual (alias)
     */
    public static function user(): ?array {
        return self::getCurrentUser();
    }

    /**
     * Obtener ID del usuario actual
     */
    public static function getUserId(): ?int {
        self::init();
        return self::isLoggedIn() ? self::$auth->getUserId() : null;
    }

    /**
     * Obtener ID del usuario actual (alias)
     */
    public static function id(): ?int {
        return self::getUserId();
    }

    /**
     * Obtener email del usuario actual
     */
    public static function getUserEmail(): ?string {
        $user = self::getCurrentUser();
        return $user['email'] ?? null;
    }

    /**
     * =============================================================================
     * ROLES Y PERMISOS
     * =============================================================================
     */

    /**
     * Verificar si usuario tiene rol
     */
    public static function hasRole(string $role): bool {
        self::init();
        return self::isLoggedIn() && self::$auth->hasRole($role);
    }

    /**
     * Verificar si usuario tiene cualquiera de los roles
     */
    public static function hasAnyRole(array $roles): bool {
        foreach ($roles as $role) {
            if (self::hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verificar si usuario tiene todos los roles
     */
    public static function hasAllRoles(array $roles): bool {
        foreach ($roles as $role) {
            if (!self::hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * =============================================================================
     * UTILIDADES PRIVADAS
     * =============================================================================
     */

    /**
     * Validar contraseña según configuración
     */
    private static function validatePassword(string $password): array {
        $config = self::$config['passwords'] ?? [];
        $errors = [];

        // Verificar longitud mínima
        $minLength = $config['min_length'] ?? 8;
        if (strlen($password) < $minLength) {
            $errors[] = "La contraseña debe tener al menos {$minLength} caracteres";
        }

        // Verificar longitud máxima
        $maxLength = $config['max_length'] ?? 72;
        if (strlen($password) > $maxLength) {
            $errors[] = "La contraseña no debe exceder {$maxLength} caracteres";
        }

        // Verificar letra minúscula
        if ($config['require_lowercase'] ?? true) {
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = "La contraseña debe contener al menos una letra minúscula";
            }
        }

        // Verificar letra mayúscula
        if ($config['require_uppercase'] ?? true) {
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = "La contraseña debe contener al menos una letra mayúscula";
            }
        }

        // Verificar número
        if ($config['require_number'] ?? true) {
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = "La contraseña debe contener al menos un número";
            }
        }

        // Verificar carácter especial
        if ($config['require_special_char'] ?? true) {
            $specialChars = preg_quote($config['special_chars'] ?? '!@#$%^&*()_+-=[]{}|;:,.<>?', '/');
            if (!preg_match("/[{$specialChars}]/", $password)) {
                $errors[] = "La contraseña debe contener al menos un carácter especial";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Verificar rate limiting
     */
    private static function checkRateLimit(string $action, string $identifier): void {
        $rateLimitConfig = self::$config['rate_limiting'] ?? [];
        $key = "rate_limit_{$action}_" . md5($identifier . get_client_ip());

        $maxAttempts = match ($action) {
            'login' => $rateLimitConfig['max_login_attempts'] ?? 5,
            'register' => $rateLimitConfig['max_registration_attempts'] ?? 3,
            'password_reset' => $rateLimitConfig['max_password_reset_attempts'] ?? 5,
            default => 5
        };

        $windowMinutes = $rateLimitConfig['attempts_window'] ?? 60;
        $attempts = Session::getTemp($key, []);
        $now = time();

        // Limpiar intentos antiguos
        $attempts = array_filter($attempts, function ($timestamp) use ($now, $windowMinutes) {
            return ($now - $timestamp) < ($windowMinutes * 60);
        });

        if (count($attempts) >= $maxAttempts) {
            bd_log('security', 'Rate limit excedido', [
                'action' => 'rate_limit_exceeded',
                'type' => $action,
                'identifier' => $identifier,
                'attempts' => count($attempts),
                'max_attempts' => $maxAttempts
            ]);

            throw new \Delight\Auth\TooManyRequestsException();
        }

        // Agregar intento actual
        $attempts[] = $now;
        Session::setTemp($key, $attempts, $windowMinutes * 60);
    }

    /**
     * Incrementar intentos fallidos
     */
    private static function incrementFailedAttempts(string $email): void {
        $key = 'failed_attempts_' . md5($email);
        $attempts = Session::getTemp($key, 0);
        Session::setTemp($key, $attempts + 1, 3600); // 1 hora
    }

    /**
     * Enviar email de verificación
     */
    private static function sendVerificationEmail(string $email): void {
        // Placeholder para envío de email
        file_log('info', 'Email de verificación pendiente', ['email' => $email]);
    }

    /**
     * =============================================================================
     * MÉTODOS ADICIONALES
     * =============================================================================
     */

    /**
     * Verificar si el email está verificado
     */
    // public static function isEmailVerified(): bool {
    //     self::init();
    //     return self::isLoggedIn() && self::$auth->isEmailAddressVerified();
    // }

    /**
     * Obtener roles del usuario actual
     */
    public static function roles(): array {
        self::init();
        return self::isLoggedIn() ? self::$auth->getRoles() : [];
    }

    /**
     * Middleware de autenticación requerida
     */
    public static function requireAuth(): void {
        if (self::guest()) {
            bd_log('security', 'Intento de acceso no autorizado', [
                'action' => 'unauthorized_access',
                'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ]);

            Session::set('intended_url', $_SERVER['REQUEST_URI'] ?? '/');
            redirect('/login');
        }
    }

    /**
     * Middleware de rol requerido
     */
    public static function requireRole(string $role): void {
        self::requireAuth();

        if (!self::hasRole($role)) {
            bd_log('security', 'Acceso denegado por rol insuficiente', [
                'action' => 'role_access_denied',
                'required_role' => $role,
                'user_roles' => self::roles(),
                'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ], self::getUserId());

            http_response_code(403);
            exit('403 - Acceso Denegado');
        }
    }

    /**
     * Procesar confirmación de correo electrónico
     */
    public static function sendConfirmMail(): array {
        self::init();
        try {
            if (!isset($_GET['selector']) || !isset($_GET['token'])) {
                return ['success' => false, 'message' => 'Faltan parámetros de confirmación'];
            }
            self::$auth->confirmEmail($_GET['selector'], $_GET['token']);
            return [
                'success' => true,
                'user_id' => self::$auth->getUserId(),
                'message' => 'Correo confirmado correctamente'
            ];
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            return ['success' => false, 'message' => 'Token de confirmación inválido'];
        } catch (\Delight\Auth\TokenExpiredException $e) {
            return ['success' => false, 'message' => 'El token de confirmación ha expirado'];
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return ['success' => false, 'message' => 'El usuario ya confirmó su correo'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return ['success' => false, 'message' => 'Demasiados intentos. Intenta más tarde'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error interno: ' . $e->getMessage()];
        }
    }

    /**
     * Obtener instancia de Delight Auth
     */
    public static function getAuthInstance(): DelightAuth {
        self::init();
        return self::$auth;
    }
}
