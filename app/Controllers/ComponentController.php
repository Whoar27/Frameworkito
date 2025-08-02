<?php

/**
 * ComponentController - Controlador para componentes del template
 */

namespace App\Controllers;

class ComponentController extends BaseController {
    /**
     * Toast - Muestra el componente de notificaciones Toast
     */
    public function toast(): void {
        try {
            $this->log('info', 'Cargando componente Toast');

            // Datos de ejemplo para la vista (puedes personalizar según tus necesidades)
            $data = [
                'page_title' => 'Notificaciones Toast',
                'meta_description' => 'Información sobre Notificaciones Toast'
            ];

            $this->view('components/toast', $data);
        } catch (\Exception $e) {
            $this->handleError($e, 'Error cargando la página de Notificaciones Toast');
        }
    }
}
