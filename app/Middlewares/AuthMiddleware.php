<?php

namespace App\Middlewares;

class AuthMiddleware {
    public function handle($request, $next) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            // No autenticado, redirigir a login
            header('Location: /login');
            exit;
        }

        // Autenticado, continuar
        return $next($request);
    }
}
