<?php

/**
 * ProfileController - Controlador de Páginas de Perfil
 * Frameworkito
 */

namespace App\Controllers;

class ProfileController extends BaseController {
    /**
     * Página de perfil
     */
    public function index(): void {
        try {
            $this->log('info', 'Acceso a página de perfil');

            $data = [
                'title' => 'Perfil',
                'welcome_message' => '¡Bienvenido a tu perfil!',
            ];

            $this->view('profile/index', $data);
        } catch (\Exception $e) {
            $this->handleError($e, 'Error cargando la página de perfil');
        }
    }
}