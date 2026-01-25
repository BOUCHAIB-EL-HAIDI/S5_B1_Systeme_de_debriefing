<?php

namespace App\Repositories;

use Core\Database;
use PDO;

class BriefRepo {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // --- Competence Methods ---

    public function isCompetenceCodeExists(string $code): bool {
        $sql = "SELECT COUNT(*) FROM competence WHERE code = :code";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':code' => $code]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function createCompetence(string $code, string $label): bool {
        $sql = "INSERT INTO competence (code, label) VALUES (:code, :label)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':code' => $code,
            ':label' => $label
        ]);
    }

    public function getAllCompetences(): array {
        $sql = "SELECT * FROM competence ORDER BY created_at DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // --- Sprint Methods ---

    public function isSprintOrderExists(int $order): bool {
        $sql = "SELECT COUNT(*) FROM sprint WHERE \"order\" = :order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':order' => $order]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function createSprint(array $data): bool {
        $sql = "INSERT INTO sprint (name, duration, \"order\", classe_id) 
                VALUES (:name, :duration, :order, :classe_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':duration' => $data['duration'],
            ':order' => $data['order'],
            ':classe_id' => $data['classe_id']
        ]);
    }

    public function getAllSprints(): array {
        // Return unique sprints by order/name/duration since they are duplicated across classes
        $sql = "SELECT name, duration, \"order\", MIN(created_at) as created_at
                FROM sprint 
                GROUP BY name, duration, \"order\"
                ORDER BY \"order\" ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getBriefsByClass(int $classId): array {
        $sql = "SELECT b.*, s.name as sprint_name 
                FROM brief b
                JOIN sprint s ON b.sprint_id = s.id
                WHERE s.classe_id = :class_id
                ORDER BY b.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':class_id' => $classId]);
        return $stmt->fetchAll();
    }

    public function getSprintsByClasses(array $classIds): array {
        if (empty($classIds)) return [];
        $placeholders = implode(',', array_fill(0, count($classIds), '?'));
        $sql = "SELECT s.*, c.name as class_name 
                FROM sprint s 
                JOIN classe c ON s.classe_id = c.id
                WHERE s.classe_id IN ($placeholders)
                ORDER BY c.name ASC, s.\"order\" ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($classIds);
        return $stmt->fetchAll();
    }

    public function createBrief(array $data): int {
        $sql = "INSERT INTO brief (title, content, start_date, end_date, type, sprint_id, teacher_id) 
                VALUES (:title, :content, :start_date, :end_date, :type, :sprint_id, :teacher_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':type' => $data['type'],
            ':sprint_id' => $data['sprint_id'],
            ':teacher_id' => $data['teacher_id']
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function assignCompetencesToBrief(int $briefId, array $competences): void {
        $sql = "INSERT INTO brief_competence (brief_id, competence_code) VALUES (:brief_id, :competence_code)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($competences as $code) {
            $stmt->execute([
                ':brief_id' => $briefId,
                ':competence_code' => $code
            ]);
        }
    }

    public function getBriefsByTeacher(int $teacherId): array {
        $sql = "SELECT b.*, s.name as sprint_name, c.name as class_name,
                (SELECT string_agg(competence_code, ',') FROM brief_competence WHERE brief_id = b.id) as competence_codes
                FROM brief b
                JOIN sprint s ON b.sprint_id = s.id
                JOIN classe c ON s.classe_id = c.id
                WHERE b.teacher_id = :teacher_id
                ORDER BY b.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getCompetencesByBriefId(int $briefId): array {
        $sql = "SELECT c.* FROM competence c
                JOIN brief_competence bc ON c.code = bc.competence_code
                WHERE bc.brief_id = :brief_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':brief_id' => $briefId]);
        return $stmt->fetchAll();
    }

    public function getAllBriefsForTeacher(int $teacherId): array {
        $sql = "SELECT b.*, s.name as sprint_name, c.name as class_name,
                (SELECT COUNT(*) FROM livrable l WHERE l.brief_id = b.id) as submission_count,
                (SELECT COUNT(*) FROM users u WHERE u.classe_id = c.id AND u.role = 'STUDENT') as total_students
                FROM brief b
                JOIN sprint s ON b.sprint_id = s.id
                JOIN classe c ON s.classe_id = c.id
                WHERE b.teacher_id = :teacher_id
                ORDER BY b.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getRecentDeliverables(int $teacherId, int $limit = 5): array {
        // Union of submitted deliverables and missing ones for expired briefs
        $sql = "
            (SELECT 
                l.id as id,
                u.first_name || ' ' || u.last_name as student_name,
                b.title as brief_title,
                l.submitted_at as event_date,
                'Soumis' as status,
                b.type as brief_type
            FROM livrable l
            JOIN brief b ON l.brief_id = b.id
            JOIN users u ON l.student_id = u.id
            WHERE b.teacher_id = :t1)
            
            UNION ALL
            
            (SELECT 
                0 as id,
                u.first_name || ' ' || u.last_name as student_name,
                b.title as brief_title,
                b.end_date as event_date,
                'Non Rendu' as status,
                b.type as brief_type
            FROM brief b
            JOIN sprint s ON b.sprint_id = s.id
            JOIN users u ON u.classe_id = s.classe_id AND u.role = 'STUDENT'
            WHERE b.teacher_id = :t2 
            AND b.end_date < CURRENT_DATE
            AND NOT EXISTS (SELECT 1 FROM livrable l WHERE l.brief_id = b.id AND l.student_id = u.id)
            )
            
            ORDER BY event_date DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':t1', $teacherId, PDO::PARAM_INT);
        $stmt->bindValue(':t2', $teacherId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBriefDetailsForTeacher(int $briefId, int $teacherId) {
        $sql = "SELECT b.*, s.name as sprint_name, c.name as class_name,
                (SELECT COUNT(*) FROM livrable l WHERE l.brief_id = b.id) as submission_count,
                (SELECT COUNT(*) FROM users u WHERE u.classe_id = c.id AND u.role = 'STUDENT') as total_students,
                (SELECT string_agg(comp.code || ':' || comp.label, '|') 
                 FROM brief_competence bc 
                 JOIN competence comp ON bc.competence_code = comp.code 
                 WHERE bc.brief_id = b.id) as competences
                FROM brief b
                JOIN sprint s ON b.sprint_id = s.id
                JOIN classe c ON s.classe_id = c.id
                WHERE b.id = :brief_id AND b.teacher_id = :teacher_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':brief_id' => $briefId, ':teacher_id' => $teacherId]);
        return $stmt->fetch();
    }
}
