<?php

namespace Core;

use PDO;
use PDOException;
use RuntimeException;

require_once __DIR__ . "/../config/env.php";

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct() {}

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            try {
                $dsn = sprintf(
                    "pgsql:host=%s;port=%s;dbname=%s",
                    $_ENV['DB_HOST'],
                    $_ENV['DB_PORT'] ?? 5432,
                    $_ENV['DB_NAME']
                );

                $this->connection = new PDO(
                    $dsn,
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );

            } catch (PDOException $e) {
                throw new RuntimeException("Database connection failed");
            }
        }

        return $this->connection;
    }
}
