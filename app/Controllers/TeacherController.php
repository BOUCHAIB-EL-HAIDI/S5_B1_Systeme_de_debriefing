<?php
namespace App\Controllers;

use Core\Controller;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $this->render('pages.teacher.dashboard', [
            'submissions' => [
                ['name' => 'Saad El Haidi', 'brief' => 'PixelQuest', 'time' => 'Il y a 15 min', 'type' => 'HTML/CSS'],
                ['name' => 'Imane Bennani', 'brief' => 'DOM Gamification', 'time' => 'Il y a 1h', 'type' => 'JS'],
                ['name' => 'Youssef Alami', 'brief' => 'PixelQuest', 'time' => 'Il y a 2h', 'type' => 'HTML/CSS']
            ],
            'classes' => ['WEB-2024-A', 'WEB-2024-B'],
            'active_briefs' => ['PixelQuest', 'DOM Gamification']
        ]);
    }

    public function debriefing()
    {
        $this->render('pages.teacher.debriefing', [
            'user_name' => 'Instructor Ahmed'
        ]);
    }

    public function briefs()
    {
        $this->render('pages.teacher.briefs', [
            'briefs' => [
                ['title' => 'PixelQuest', 'type' => 'Individuel', 'comps' => ['C1', 'C2', 'C6'], 'status' => 'Assigné'],
                ['title' => 'DOM Gamification', 'type' => 'Individuel', 'comps' => ['C3', 'C4'], 'status' => 'Assigné'],
                ['title' => 'API Restful Checkout', 'type' => 'Collectif', 'comps' => ['C5', 'C8'], 'status' => 'Brouillon']
            ]
        ]);
    }

    public function progression()
    {
        $this->render('pages.teacher.progression', [
            'students' => ['Saad El Haidi', 'Imane Bennani', 'Youssef Alami', 'Zineb Kabbaj'],
            'stats' => [
                ['label' => 'Moyenne Classe', 'value' => '72%', 'color' => 'indigo'],
                ['label' => 'Briefs Évalués', 'value' => '142', 'color' => 'indigo'],
                ['label' => 'Compétences Validées', 'value' => '18/24', 'color' => 'emerald'],
                ['label' => 'Retards Moyens', 'value' => '1.2j', 'color' => 'rose']
            ]
        ]);
    }
}
