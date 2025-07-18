<?php

namespace App\Models;

use PDO;
use App\Helpers\Database;

class User {
    protected $db;

    public function __construct() {
        // Ajusta este helper a tu sistema de conexión
        $this->db = Database::getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, email, username FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBySelector($selector) {
        // Busca el email del usuario a partir del selector en users_resets
        $stmt = $this->db->prepare("
            SELECT u.id, u.email, u.username
            FROM users_resets r
            INNER JOIN users u ON u.id = r.user
            WHERE r.selector = :selector
            LIMIT 1
        ");
        $stmt->execute(['selector' => $selector]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
