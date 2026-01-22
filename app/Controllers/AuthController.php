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
        
        header('Location: /admin/dashboard');
    }

    public function logout()
    {
        header('Location: /login');
    }
}
