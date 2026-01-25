<?php

namespace App\Controllers;

use Core\Controller;

Use App\Services\ClassService;



class AddClassController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        \Core\Middleware::auth();
        \Core\Middleware::role('ADMIN');
    }

    public function addClass()
    {
        
        
        $className = $_POST['name'] ?? '' ;
        $classYear = $_POST['year'] ?? '';

        $service = new ClassService();
        $errors = $service->validate($className , $classYear);
         
        if(isset($errors['errors']) && !empty($errors)){
            $repo = new \App\Repositories\ClassRepo();
            $classes = $repo->getAllClassesWithStats();
            $this->render('pages.admin.classes' , [     
                'errors' => $errors['errors'],
                'openModal' => true,
                'classes' => $classes,
                'old' => $_POST
            ]); 
            return;
        }


      $_SESSION['success'] = 'Classe ajoutée avec succès';
      header('Location: ' . BASE_URL . '/admin/classes');
      exit;




     }

 
}
