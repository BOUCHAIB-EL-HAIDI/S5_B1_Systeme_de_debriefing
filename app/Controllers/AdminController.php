<?php
namespace App\Controllers;

use Core\Controller;
use Core\Middleware;
use App\Services\AdminService;
use App\Services\BriefService;
use App\Services\ClassService;

class AdminController extends Controller

{

    private AdminService $adminService;
    private BriefService $briefService;
    private ClassService $classService;

    public function __construct()
    {
        parent::__construct();
        Middleware::auth();
        Middleware::role('ADMIN');
        $this->adminService = new AdminService();
        $this->briefService = new BriefService();
        $this->classService = new \App\Services\ClassService();
    }

    public function dashboard()
    {
        $stats = $this->adminService->getDashboardStats();
        $this->render('pages.admin.dashboard', [
            'stats' => $stats,
            'actions' => [
                ['icon' => 'user-plus', 'color' => 'indigo', 'text' => 'Gestion des utilisateurs opérationnelle'],
                ['icon' => 'target', 'color' => 'emerald', 'text' => 'Référentiel de compétences à jour'],
                ['icon' => 'layers', 'color' => 'rose', 'text' => 'Planification des sprints active']
            ]
        ]);
    }

    public function users()
    {
        $users = $this->adminService->getUsersList();
        $this->render('pages.admin.users', [
            'users' => $users
        ]); 
    }

    public function classes()
    {
        $repo = new \App\Repositories\ClassRepo();
        $classes = $repo->getAllClassesWithStats();
        $this->render('pages.admin.classes', [
            'classes' => $classes
        ]);
    }

    public function competences()
    {
        $competences = $this->briefService->getCompetences();
        $this->render('pages.admin.competences', [
            'competences' => $competences
        ]);
    }

    public function storeCompetence()
    {
        $result = $this->briefService->addCompetence($_POST);
        if (!$result['success']) {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $_POST;
        } else {
            $_SESSION['success'] = 'Compétence enregistrée avec succès.';
        }
        header('Location: ' . BASE_URL . '/admin/competences');
        exit;
    }

    public function createUser()
    {
        $repo = new \App\Repositories\ClassRepo();
        $this->render('pages.admin.createuser', [
            'classes' => $repo->getAllClasses(),
            'available_classes' => $repo->getAvailableClassesForPrincipal()
        ]);
    }

    public function storeUser()
    {
        $result = $this->adminService->registerUser($_POST);
        if ($result['success']) {
            header('Location: ' . BASE_URL . '/admin/users');
        } else {
            // In a real app, we would flash errors to session
            $repo = new \App\Repositories\ClassRepo();
            $this->render('pages.admin.createuser', [
                'errors' => $result['errors'],
                'classes' => $repo->getAllClasses(),
                'old' => $_POST
            ]);
        }
        exit;
    }

    public function sprints()
    {
        $sprints = $this->briefService->getSprints();
        $this->render('pages.admin.sprints', [
            'sprints' => $sprints
        ]);
    }

    public function storeSprint()
    {
        $result = $this->briefService->addSprint($_POST);
        if (!$result['success']) {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $_POST;
        } else {
            $_SESSION['success'] = 'Sprints assignés à toutes les classes avec succès.';
        }
        header('Location: ' . BASE_URL . '/admin/sprints');
        exit;
    }
}
