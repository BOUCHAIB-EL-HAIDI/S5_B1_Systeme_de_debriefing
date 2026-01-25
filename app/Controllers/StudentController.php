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
        $studentId = $_SESSION['user_id'];
        $repo = new \App\Repositories\StudentRepo();
        
        $allBriefs = $repo->getAssignedBriefs($studentId);
        
        $activeBrief = null;
        $historyBriefs = [];

        // Logic to pick the "active" brief (e.g. first one not overdue or just the first one)
        // Since getAssignedBriefs orders by date, we can pop the first one as active
        if (!empty($allBriefs)) {
            $activeBrief = $allBriefs[0];
            $historyBriefs = array_slice($allBriefs, 1);
        }

        // Get recent activity (evaluations)
        $recentDebriefs = $repo->getRecentDebriefings($studentId, 3);
        $activities = [];
        foreach ($recentDebriefs as $deb) {
            $activities[] = [
                'time' => date('H:i', strtotime($deb['date'])),
                'text' => 'Retour sur ' . $deb['brief_title'],
                'sub' => '"' . substr($deb['comment'], 0, 50) . '..."'
            ];
        }

        if (empty($activities)) {
            $activities[] = ['time' => '', 'text' => 'Aucune activité récente', 'sub' => 'Commencez un brief !'];
        }

        $this->render('pages.student.dashboard', [
            'active_brief' => $activeBrief,
            'history_briefs' => $historyBriefs,
            'activities' => $activities
        ]);
    }
      
    public function briefs()
    {
        $studentId = $_SESSION['user_id'];
        $repo = new \App\Repositories\StudentRepo();
        $briefs = $repo->getAssignedBriefs($studentId);

        $this->render('pages.student.briefs', [
            'briefs' => $briefs
        ]);
    }

    public function briefDetails()
    {
        $briefId = $_GET['id'] ?? null;
        if (!$briefId) {
            header('Location: ' . BASE_URL . '/student/briefs');
            exit;
        }

        $studentId = $_SESSION['user_id'];
        $repo = new \App\Repositories\StudentRepo();
        $brief = $repo->getBriefDetails((int)$briefId, $studentId);

        if (!$brief) {
            header('Location: ' . BASE_URL . '/student/briefs');
            exit;
        }

        $this->render('pages.student.brief_details', [
            'brief' => $brief
        ]);
    }

    public function submit()
    {
        $studentId = $_SESSION['user_id'];
        $briefId = $_POST['brief_id'] ?? null;
        $url = $_POST['livrable_url'] ?? '';

        if (!$briefId || empty($url)) {
            $_SESSION['error'] = "Veuillez fournir un lien valide.";
            header('Location: ' . BASE_URL . '/student/briefs');
            exit;
        }

        $repo = new \App\Repositories\StudentRepo();
        $success = $repo->submitLivrable($studentId, (int)$briefId, $url);

        if ($success) {
            $_SESSION['success'] = "Travail rendu avec succès !";
        } else {
            $_SESSION['error'] = "Impossible de rendre le travail (Brief expiré ou erreur serveur).";
        }
        
        header('Location: ' . BASE_URL . '/student/briefs');
        exit;
    }


    public function progression()
    {
        $studentId = $_SESSION['user_id'];
        $repo = new \App\Repositories\StudentRepo();
        $progression = $repo->getCompetenceProgression($studentId);
        $recent = $repo->getRecentDebriefings($studentId);
        $pending = $repo->getPendingEvaluations($studentId);

        $this->render('pages.student.progression', [
            'progression' => $progression,
            'recent' => $recent,
            'pending' => $pending
        ]);
    }
}
