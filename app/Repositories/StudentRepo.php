<?php

namespace App\Repositories;

use Core\Database;
use PDO;

class StudentRepo {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAssignedBriefs(int $studentId): array {
        // Fetch briefs assigned to the student's class via sprints
        // Use subquery to get only the LATEST livrable to avoid duplicates
        $sql = "SELECT b.*, s.name as sprint_name, 
                l.submitted_at, l.content as livrable_url,
                CASE WHEN l.id IS NOT NULL THEN 'Soumis' ELSE 'À faire' END as status,
                CASE WHEN b.end_date < CURRENT_DATE THEN true ELSE false END as is_overdue
                FROM brief b
                JOIN sprint s ON b.sprint_id = s.id
                JOIN users u ON u.classe_id = s.classe_id
                LEFT JOIN (
                    SELECT DISTINCT ON (student_id, brief_id) student_id, brief_id, content, submitted_at, id
                    FROM livrable
                    ORDER BY student_id, brief_id, submitted_at DESC
                ) l ON l.brief_id = b.id AND l.student_id = u.id
                WHERE u.id = :student_id
                ORDER BY b.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    public function getBriefDetails(int $id, int $studentId)
    {
        $sql = "SELECT b.*, s.name as sprint_name, 
                l.submitted_at, l.content as livrable_url,
                CASE WHEN l.id IS NOT NULL THEN 'Soumis' ELSE 'À faire' END as status,
                CASE WHEN b.end_date < CURRENT_DATE THEN true ELSE false END as is_overdue,
                (SELECT string_agg(c.code || ':' || c.label, '|') 
                 FROM brief_competence bc 
                 JOIN competence c ON bc.competence_code = c.code 
                 WHERE bc.brief_id = b.id) as competences
                FROM brief b
                JOIN sprint s ON b.sprint_id = s.id
                JOIN users u ON u.classe_id = s.classe_id
                LEFT JOIN (
                    SELECT DISTINCT ON (student_id, brief_id) student_id, brief_id, content, submitted_at, id
                    FROM livrable
                    ORDER BY student_id, brief_id, submitted_at DESC
                ) l ON l.brief_id = b.id AND l.student_id = u.id
                WHERE b.id = :id AND u.id = :student_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':student_id' => $studentId]);
        return $stmt->fetch();
    }

    public function submitLivrable(int $studentId, int $briefId, string $url): bool {
        // Check if brief exists and is not overdue
        $briefSql = "SELECT end_date FROM brief WHERE id = :id";
        $stmt = $this->pdo->prepare($briefSql);
        $stmt->execute([':id' => $briefId]);
        $endDate = $stmt->fetchColumn();

        if (!$endDate) return false;
        
        $today = date('Y-m-d');
        if ($today > $endDate) {
            return false; // Submission closed
        }

        // Insert new submission (history)
        $sql = "INSERT INTO livrable (student_id, brief_id, content, submitted_at) 
                VALUES (:student_id, :brief_id, :content, CURRENT_TIMESTAMP)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':student_id' => $studentId,
            ':brief_id' => $briefId,
            ':content' => $url
        ]);
    }

    public function getCompetenceProgression(int $studentId): array {
        // Fetch unique competences with their highest validated niveau and latest status
        $sql = "SELECT 
                    c.code, 
                    c.label, 
                    (SELECT MAX(dc_val.niveau) 
                     FROM debriefing_competence dc_val 
                     JOIN debriefing d_val ON dc_val.debriefing_id = d_val.id 
                     WHERE dc_val.competence_code = c.code 
                     AND d_val.student_id = :student_id_val
                     AND dc_val.status = 'VALIDEE') as highest_validated,
                    (SELECT dc_latest.niveau
                     FROM debriefing_competence dc_latest 
                     JOIN debriefing d_latest ON dc_latest.debriefing_id = d_latest.id 
                     WHERE dc_latest.competence_code = c.code 
                     AND d_latest.student_id = :student_id_latest
                     ORDER BY d_latest.date DESC 
                     LIMIT 1) as latest_niveau,
                    (SELECT dc2.status 
                     FROM debriefing_competence dc2 
                     JOIN debriefing d2 ON dc2.debriefing_id = d2.id 
                     WHERE dc2.competence_code = c.code 
                     AND d2.student_id = :student_id2
                     ORDER BY d2.date DESC 
                     LIMIT 1) as status,
                    (SELECT b.title 
                     FROM debriefing d3
                     JOIN brief b ON d3.brief_id = b.id
                     JOIN debriefing_competence dc3 ON dc3.debriefing_id = d3.id
                     WHERE dc3.competence_code = c.code 
                     AND d3.student_id = :student_id3
                     ORDER BY d3.date DESC 
                     LIMIT 1) as brief_title
                FROM debriefing_competence dc
                JOIN debriefing d ON dc.debriefing_id = d.id
                JOIN competence c ON dc.competence_code = c.code
                WHERE d.student_id = :student_id
                GROUP BY c.code, c.label
                ORDER BY c.code ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':student_id' => $studentId,
            ':student_id_val' => $studentId,
            ':student_id_latest' => $studentId,
            ':student_id2' => $studentId,
            ':student_id3' => $studentId
        ]);
        return $stmt->fetchAll();
    }

    public function getRecentDebriefings(int $studentId, int $limit = 5): array {
        $sql = "SELECT d.id, d.date, d.comment, b.title as brief_title
                FROM debriefing d
                JOIN brief b ON d.brief_id = b.id
                WHERE d.student_id = :student_id
                ORDER BY d.date DESC
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $debriefings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($debriefings as &$deb) {
            $sqlComp = "SELECT c.code, dc.niveau, dc.status
                        FROM debriefing_competence dc
                        JOIN competence c ON dc.competence_code = c.code
                        WHERE dc.debriefing_id = :deb_id";
            $stmtComp = $this->pdo->prepare($sqlComp);
            $stmtComp->execute([':deb_id' => $deb['id']]);
            $deb['evaluations'] = $stmtComp->fetchAll(PDO::FETCH_ASSOC);
        }

        return $debriefings;
    }

    public function getPendingEvaluations(int $studentId): array {
        $sql = "SELECT b.title, MAX(l.submitted_at) as submitted_at
                FROM brief b
                JOIN livrable l ON l.brief_id = b.id
                LEFT JOIN debriefing d ON d.brief_id = b.id AND d.student_id = l.student_id
                WHERE l.student_id = :student_id
                AND d.id IS NULL
                GROUP BY b.id, b.title
                ORDER BY submitted_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll();
    }
}
