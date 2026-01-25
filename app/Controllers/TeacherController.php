<?php
namespace App\Controllers;

use Core\Controller;
use Core\Middleware;

class TeacherController extends Controller
{


     public function __construct()
    {
        parent::__construct();
        Middleware::auth();
        Middleware::role('TEACHER');
    }


    public function dashboard()
    {
        $teacherId = $_SESSION['user_id'];
        $userRepo = new \App\Repositories\UserRepo();
        $briefRepo = new \App\Repositories\BriefRepo();
        
        $classes = $userRepo->getTeacherClasses($teacherId);
        
        // Enhance classes with student count and sprint count
        foreach ($classes as &$cls) {
            $cls['student_count'] = $userRepo->countStudentsInClass($cls['id']);
            $sprints = $briefRepo->getSprintsByClasses([$cls['id']]);
            $cls['sprint_count'] = count($sprints);
        }

        $allBriefs = $briefRepo->getAllBriefsForTeacher($teacherId);
        $deliverables = $briefRepo->getRecentDeliverables($teacherId);

        $this->render('pages.teacher.dashboard', [
            'submissions' => $deliverables,
            'classes' => $classes,
            'active_briefs' => $allBriefs
        ]);
    }

    public function debriefing()
    {
        $teacherId = $_SESSION['user_id'];
        $userRepo = new \App\Repositories\UserRepo();
        $briefRepo = new \App\Repositories\BriefRepo();

        $classes = $userRepo->getTeacherClasses($teacherId);
        $classIds = array_column($classes, 'id');

        $students = $userRepo->getStudentsByClasses($classIds);
        $briefs = $briefRepo->getBriefsByTeacher($teacherId);

        $this->render('pages.teacher.debriefing', [
            'students' => $students,
            'briefs' => $briefs
        ]);
    }

    public function getBriefCompetences()
    {
        $briefId = $_GET['brief_id'] ?? null;
        if (!$briefId) {
            echo json_encode([]);
            return;
        }

        $repo = new \App\Repositories\BriefRepo();
        $competences = $repo->getCompetencesByBriefId((int)$briefId);
        header('Content-Type: application/json');
        echo json_encode($competences);
        exit;
    }

    public function getStudentLivrableStatus()
    {
        $studentId = $_GET['student_id'] ?? null;
        $briefId = $_GET['brief_id'] ?? null;
        
        if (!$studentId || !$briefId) {
            echo json_encode(['status' => null]);
            exit;
        }

        $repo = new \App\Repositories\DebriefingRepo();
        $submittedAt = $repo->getLivrableStatus((int)$studentId, (int)$briefId);
        $isDebriefed = $repo->checkDebriefingExists((int)$studentId, (int)$briefId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $submittedAt ? 'Soumis ✓' : 'Non soumis',
            'is_debriefed' => $isDebriefed
        ]);
        exit;
    }

    public function storeDebriefing()
    {
        $teacherId = $_SESSION['user_id'];
        $studentId = $_POST['student_id'] ?? null;
        $briefId = $_POST['brief_id'] ?? null;
        $comment = $_POST['comment'] ?? '';
        $evaluations = $_POST['evaluations'] ?? [];

        if (!$studentId || !$briefId) {
            $_SESSION['error'] = "Veuillez sélectionner un étudiant et un brief.";
            header('Location: ' . BASE_URL . '/teacher/debriefing');
            exit;
        }

        $repo = new \App\Repositories\DebriefingRepo();

        // Enforce: Cannot debrief if no delivered work
        $submittedAt = $repo->getLivrableStatus((int)$studentId, (int)$briefId);
        if (!$submittedAt) {
            $_SESSION['error'] = "Impossible de débriefing : L'étudiant n'a pas rendu son travail.";
            header('Location: ' . BASE_URL . '/teacher/debriefing');
            exit;
        }
        
        try {
            $debriefingId = $repo->createDebriefing([
                'comment' => $comment,
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'brief_id' => $briefId
            ]);

            foreach ($evaluations as $compCode => $eval) {
                if (isset($eval['niveau']) && isset($eval['status'])) {
                    $repo->saveCompetenceEvaluation(
                        $debriefingId, 
                        $compCode, 
                        $eval['niveau'], 
                        $eval['status']
                    );
                }
            }

            $_SESSION['success'] = "Débriefing enregistré avec succès.";
            header('Location: ' . BASE_URL . '/teacher/debriefing');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header('Location: ' . BASE_URL . '/teacher/debriefing');
            exit;
        }
    }

    public function briefs()
    {
        $teacherId = $_SESSION['user_id'];
        $repo = new \App\Repositories\BriefRepo();
        $briefs = $repo->getBriefsByTeacher($teacherId);

        $this->render('pages.teacher.briefs', [
            'briefs' => $briefs
        ]);
    }

    public function briefDetails()
    {
        $teacherId = $_SESSION['user_id'];
        $briefId = $_GET['id'] ?? null;

        if (!$briefId) {
            header('Location: ' . BASE_URL . '/teacher/briefs');
            exit;
        }

        $repo = new \App\Repositories\BriefRepo();
        $brief = $repo->getBriefDetailsForTeacher((int)$briefId, $teacherId);

        if (!$brief) {
            $_SESSION['error'] = "Brief introuvable ou accès refusé.";
            header('Location: ' . BASE_URL . '/teacher/briefs');
            exit;
        }

        $this->render('pages.teacher.brief_details', [
            'brief' => $brief
        ]);
    }

    public function createBrief()
    {
        $teacherId = $_SESSION['user_id'];
        $userRepo = new \App\Repositories\UserRepo();
        $briefRepo = new \App\Repositories\BriefRepo();
        
        $classes = $userRepo->getTeacherClasses($teacherId);
        $classIds = array_column($classes, 'id');
        
        $sprints = $briefRepo->getSprintsByClasses($classIds);
        $competences = $briefRepo->getAllCompetences();

        $this->render('pages.teacher.createbrief', [
            'sprints' => $sprints,
            'competences' => $competences
        ]);
    }

    public function storeBrief()
    {
        $teacherId = $_SESSION['user_id'];
        $repo = new \App\Repositories\BriefRepo();

        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'type' => $_POST['type'],
            'sprint_id' => $_POST['sprint_id'],
            'teacher_id' => $teacherId
        ];

        try {
            $briefId = $repo->createBrief($data);
            
            if (!empty($_POST['competences'])) {
                $repo->assignCompetencesToBrief($briefId, $_POST['competences']);
            }

            $_SESSION['success'] = "Brief créé et assigné avec succès.";
            header('Location: ' . BASE_URL . '/teacher/briefs');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erreur lors de la création du brief: " . $e->getMessage();
            header('Location: ' . BASE_URL . '/teacher/briefs/create');
            exit;
        }
    }

    public function progression()
    {
        $teacherId = $_SESSION['user_id'];
        $userRepo = new \App\Repositories\UserRepo();
        $classes = $userRepo->getTeacherClasses($teacherId);
        $classIds = array_column($classes, 'id');
        $students = $userRepo->getStudentsByClasses($classIds);

        $this->render('pages.teacher.progression', [
            'students' => $students
        ]);
    }

    public function getStudentHistory()
    {
        $studentId = $_GET['student_id'] ?? null;
        if (!$studentId) {
            echo json_encode([]);
            exit;
        }

        $repo = new \App\Repositories\DebriefingRepo();
        $history = $repo->getHistoryByStudent((int)$studentId);
        
        header('Content-Type: application/json');
        echo json_encode($history);
        exit;
    }
}
