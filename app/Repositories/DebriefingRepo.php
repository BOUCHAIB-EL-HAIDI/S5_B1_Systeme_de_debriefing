<?php
namespace App\Repositories;

use Core\Database;
use PDO;

class DebriefingRepo {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createDebriefing(array $data): int {
        // Use student_id and brief_id as unique key to prevent duplicates
        $sql = "INSERT INTO debriefing (comment, student_id, teacher_id, brief_id) 
                VALUES (:comment, :student_id, :teacher_id, :brief_id)
                ON CONFLICT (student_id, brief_id) 
                DO UPDATE SET comment = EXCLUDED.comment, date = CURRENT_TIMESTAMP";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':comment' => $data['comment'],
            ':student_id' => $data['student_id'],
            ':teacher_id' => $data['teacher_id'],
            ':brief_id' => $data['brief_id']
        ]);
        
        $sql = "SELECT id FROM debriefing WHERE student_id = :student_id AND brief_id = :brief_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':student_id' => $data['student_id'], ':brief_id' => $data['brief_id']]);
        return (int)$stmt->fetchColumn();
    }

    public function saveCompetenceEvaluation(int $debriefingId, string $compCode, string $niveau, string $status): void {
        $sql = "INSERT INTO debriefing_competence (debriefing_id, competence_code, niveau, status) 
                VALUES (:debriefing_id, :comp_code, :niveau, :status)
                ON CONFLICT (debriefing_id, competence_code) 
                DO UPDATE SET niveau = EXCLUDED.niveau, status = EXCLUDED.status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':debriefing_id' => $debriefingId,
            ':comp_code' => $compCode,
            ':niveau' => $niveau,
            ':status' => $status
        ]);
    }

    public function getLivrableStatus(int $studentId, int $briefId): ?string {
        $sql = "SELECT submitted_at FROM livrable WHERE student_id = :student_id AND brief_id = :brief_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':student_id' => $studentId, ':brief_id' => $briefId]);
        return $stmt->fetchColumn() ?: null;
    }

    public function checkDebriefingExists(int $studentId, int $briefId): bool {
        $sql = "SELECT COUNT(*) FROM debriefing WHERE student_id = :student_id AND brief_id = :brief_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':student_id' => $studentId, ':brief_id' => $briefId]);
        return (int)$stmt->fetchColumn() > 0;
    }
    public function getHistoryByStudent(int $studentId): array {
        // Fetch all debriefings for the student
        $sql = "SELECT d.id, d.comment, d.date, 
                b.title as brief_title, 
                t.first_name || ' ' || t.last_name as teacher_name
                FROM debriefing d
                JOIN brief b ON d.brief_id = b.id
                JOIN users t ON d.teacher_id = t.id
                WHERE d.student_id = :student_id
                ORDER BY d.date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':student_id' => $studentId]);
        $debriefings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Attach competences to each debriefing
        foreach ($debriefings as &$debrief) {
            $sql = "SELECT c.code, c.label, dc.niveau, dc.status
                    FROM debriefing_competence dc
                    JOIN competence c ON dc.competence_code = c.code
                    WHERE dc.debriefing_id = :debrief_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':debrief_id' => $debrief['id']]);
            $debrief['competences'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $debriefings;
    }
}
