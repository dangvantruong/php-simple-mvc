<?php

namespace App\Core;

use PDO;

class Database extends PDO
{
    public function __construct()
    {
        try {
            $config = config('database');
            $dns = sprintf("%s:host=%s;dbname=%s;port=%d;charset=%s",
                $config['driver'],
                $config['host'],
                $config['db'],
                $config['port'],
                $config['charset']);

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            parent::__construct($dns, $config['user'], $config['password'], $options);
        } catch (\Exception $e) {
            error_log("Could not connect to database: " . $e->getMessage());
        }
    }
}
