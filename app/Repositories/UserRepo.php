<?php

namespace App\Repositories;

use Core\Database;
use PDO;

class UserRepo {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO users (first_name, last_name, email, password, role, classe_id) 
                VALUES (:first_name, :last_name, :email, :password, :role, :classe_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'],
            ':classe_id' => $data['classe_id'] ?? null
        ]);
    }

    public function assignBackupClass(int $teacherId, int $classId): bool {
        $sql = "INSERT INTO teacher_classe (teacher_id, classe_id) VALUES (:teacher_id, :classe_id)
                ON CONFLICT (teacher_id, classe_id) DO NOTHING";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':teacher_id' => $teacherId,
            ':classe_id' => $classId
        ]);
    }

    public function countStudentsInClass(int $classId): int {
        $sql = "SELECT COUNT(*) FROM users WHERE classe_id = :class_id AND role = 'STUDENT'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':class_id' => $classId]);
        return (int) $stmt->fetchColumn();
    }

    public function getLastInsertId(): int {
        return (int) $this->pdo->lastInsertId();
    }

    public function getAllUsers(): array {
        $sql = "SELECT u.*, c.name as class_name 
                FROM users u 
                LEFT JOIN classe c ON u.classe_id = c.id 
                ORDER BY u.created_at DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getDashboardStats(): array {
        $stats = [];
        $stats['total_students'] = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'STUDENT'")->fetchColumn();
        $stats['total_teachers'] = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'TEACHER'")->fetchColumn();
        $stats['total_classes'] = $this->pdo->query("SELECT COUNT(*) FROM classe")->fetchColumn();
        $stats['total_sprints'] = $this->pdo->query("SELECT COUNT(*) FROM sprint")->fetchColumn();
        return $stats;
    }

    public function hasPrincipalTeacher(int $classId): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE classe_id = :class_id AND role = 'TEACHER'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':class_id' => $classId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
