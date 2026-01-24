<?php
namespace App\Controllers;

use Core\Controller;
use Core\Middleware;
class StudentController extends Controller
{

      public function __construct()
    {
        parent::__construct();
        Middleware::auth();
        Middleware::role('STUDENT');
    }
    public function dashboard()
    {
        $this->render('pages.student.dashboard', [
            'tasks' => ['Finaliser le responsive', 'Pousser sur GitHub', 'Rédiger le README'],
            'activities' => [
                ['time' => '10:30', 'text' => 'Ahmed I. a validé votre débriefing', 'sub' => 'Félicitations ! Vous avez atteint le niveau 2.'],
                ['time' => '16:45', 'text' => 'Nouveau Brief assigné', 'sub' => 'Le brief "DOM Gamification" est disponible.']
            ]
        ]);
    }
      
    public function briefs()
    {
        $this->render('pages.student.briefs', [
            'brief_titles' => ['PixelQuest', 'DOM Gamification', 'Spotify Clone', 'Portfolio 2024']
        ]);
    }

    public function progression()
    {
        $this->render('pages.student.progression', [
            'user_name' => 'Saad El Haidi',
            'skills' => ['Planifier le travail', 'Intégrer une maquette', 'Développer un backend', 'Gérer les bases de données', 'Déployer une application', 'Collaborer via Git']
        ]);
    }
}
