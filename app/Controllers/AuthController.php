<?php
namespace App\Controllers;

use Core\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        $this->render('pages.auth.login');
    }

    public function login()
    {
        // Logic for authentication would go here
        // For now, redirect to a dashboard based on role or just admin for demo
        header('Location: /admin/dashboard');
    }

    public function logout()
    {
        header('Location: /login');
    }
}
