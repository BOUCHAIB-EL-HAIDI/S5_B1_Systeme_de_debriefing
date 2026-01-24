<?php
namespace App\Controllers;

use Core\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->render('pages.admin.dashboard', [
            'user_name' => 'Admin User',
            'actions' => [
                ['icon' => 'user-plus', 'color' => 'indigo', 'text' => 'Nouvel apprenant ajouté'],
                ['icon' => 'target', 'color' => 'emerald', 'text' => 'Compétence "Docker" ajoutée'],
                ['icon' => 'layers', 'color' => 'rose', 'text' => 'Nouveau sprint assigné'],
                ['icon' => 'shield', 'color' => 'slate', 'text' => 'Changement de mot de passe']
            ]
        ]);
    }

    public function users()
    {
        // View for user management
        $this->render('pages.admin.users', [
            'users' => [
                ['name' => 'Saad El Haidi', 'email' => 'saad@example.com', 'role' => 'STUDENT', 'class' => 'WEB-2024-A', 'status' => 'Actif'],
                ['name' => 'Ahmed Instructor', 'email' => 'ahmed@example.com', 'role' => 'TEACHER', 'class' => '-', 'status' => 'Actif'],
                ['name' => 'Imane Bennani', 'email' => 'imane@example.com', 'role' => 'STUDENT', 'class' => 'WEB-2024-A', 'status' => 'Inactif']
            ]
        ]); 
    }

    public function classes()
    {
        
        $this->render('pages.admin.classes');
    }

    public function competences()
    {
        // View for competence management
        $this->render('pages.admin.competences');
    }
}
