<?php
class Database {
    private static $conn = null;

    public static function getConnection(): PDO {
        if (self::$conn === null) {

            $config = require __DIR__ . '/../config/db.php';

            self::$conn = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$conn;
    }
}
