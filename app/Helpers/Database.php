<?php

namespace App\Helpers;

use PDO;

class Database {
    public static function getConnection() {
        $config = config('database');
        $default = $config['default'];
        $db = $config['connections'][$default];

        $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']};charset={$db['charset']}";
        return new PDO($dsn, $db['username'], $db['password'], $db['options']);
    }
}
