<?php

namespace App\config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

class Database
{
    private static $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();

            $host = $_ENV["DB_HOST"] ?? "127.0.0.1";
            $db   = $_ENV["DB_NAME"] ?? "usuarios_db";
            $user = $_ENV["DB_USER"] ?? "root";
            $pass = $_ENV["DB_PASS"] ?? "";
            $charset = "utf8mb4";

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
