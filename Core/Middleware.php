<?php
namespace Core;

class Middleware
{
    public static function auth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Veuillez vous connecter.';
            header('Location: /login');
            exit;
        }
    }

        public static function guest(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $role = strtolower($_SESSION['user_role']);
            header("Location: /{$role}/dashboard");
            exit;
        }
    }
    

    public static function role(string $role): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            http_response_code(403);
            echo 'Accès interdit';
            exit;
        }
    }
}
