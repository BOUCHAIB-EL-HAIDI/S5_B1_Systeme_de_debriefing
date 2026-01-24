<?php
namespace App\Controllers;

use Core\Controller;
USE App\Services\AuthService;

class AuthController extends Controller
{
    public function showLogin()
    {
        $this->render('pages.auth.login');
    }

    public function login()
    { 

        
         if(session_status() === PHP_SESSION_NONE){

         session_start();
         }

 try {   
       $email = $_POST['email'] ?? '';
       $password = $_POST['password'] ?? '';

        $AuthService = new AuthService();
        $user = $AuthService->login($email , $password);

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['first_name'] = $user->getFirstName();
        $_SESSION['last_name'] = $user->getLastName();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_role'] = $user->getRole();
         $_SESSION['classe_id'] = method_exists($user, 'getClassId')
                ? $user->getClassId()
                : null;

        
        header('Location: /' . strtolower($user->getRole()) . '/dashboard' );
        exit;

     }catch(Exception $e ){
        $_SESSION['error'] = $e->getMessage();
        header('Location: /login');
        exit;
     }
    }

    public function logout()
    {
          $_SESSION = [];
          session_destroy();
          header('Location: /login');
    }
}
