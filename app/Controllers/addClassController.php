<?php

namespace App\Controllers;

use Core\Controller;

Use App\Services\ClassService;



class AddClassController extends Controller
{
    public function addClass()
    {
        
        $className = $_POST['name'] ?? '' ;
        $classYear = $_POST['year'] ?? '';

        $service = new ClassService();
        $errors = $service->validate($className , $classYear);
         
        if(isset($errors['errors']) && !empty($errors)){
        $this->render('pages.admin.classes' , [     
        'errors' => $errors['errors'],
        'openModal' => true
        
             ]); 

         return;
        }


      $_SESSION['success'] = 'Classe ajoutée avec succès';
      header('Location: ' . BASE_URL . '/admin/classes');
      exit;




     }

 
}
