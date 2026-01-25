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
}
