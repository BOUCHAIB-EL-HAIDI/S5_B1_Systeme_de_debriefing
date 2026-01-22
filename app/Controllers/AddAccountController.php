<?php

namespace App\Controllers;

use Core\Controller;

class AddAccountController extends Controller
{
    public function addAccount()
    {
        $this->render('pages.admin.users');
    }

    public function createAccount()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $class = $_POST['class'];
    }
}
