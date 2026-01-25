<?php

namespace App\Services;

use App\Repositories\UserRepo;
use App\Repositories\ClassRepo;

class AdminService {
    private UserRepo $userRepo;
    private ClassRepo $classRepo;

    public function __construct() {
        $this->userRepo = new UserRepo();
        $this->classRepo = new ClassRepo();
    }

    public function registerUser(array $data): array {
        $errors = $this->validateUserData($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business Logic: Principal Teacher Limit (One per class)
        if ($data['role'] === 'TEACHER' && !empty($data['classe_id'])) {
            if ($this->userRepo->hasPrincipalTeacher($data['classe_id'])) {
                return ['success' => false, 'errors' => ['classe_id' => 'Cette classe a déjà un formateur principal.']];
            }
        }

        // Business Logic: Student Limit (26)
        if ($data['role'] === 'STUDENT' && isset($data['classe_id'])) {
            $count = $this->userRepo->countStudentsInClass($data['classe_id']);
            if ($count >= 26) {
                return ['success' => false, 'errors' => ['classe_id' => 'Cette classe a déjà atteint sa capacité maximale (26 étudiants).']];
            }
        }

        // Create User
        $success = $this->userRepo->create($data);
        if (!$success) {
            return ['success' => false, 'errors' => ['system' => 'Erreur lors de la création de l\'utilisateur.']];
        }

        $userId = $this->userRepo->getLastInsertId();

        // Handle Teacher Backup Classes
        if ($data['role'] === 'TEACHER' && !empty($data['backup_classes'])) {
            foreach ($data['backup_classes'] as $classId) {
                // Ensure backup class is not the same as principal class
                if ($classId != ($data['classe_id'] ?? null)) {
                    $this->userRepo->assignBackupClass($userId, $classId);
                }
            }
        }

        return ['success' => true];
    }

    private function validateUserData(array $data): array {
        $errors = [];
        if (empty($data['first_name'])) $errors['first_name'] = 'Prénom requis';
        if (empty($data['last_name'])) $errors['last_name'] = 'Nom requis';
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email invalide';
        if (empty($data['password']) || strlen($data['password']) < 6) $errors['password'] = 'Mot de passe trop court';
        
        if ($data['role'] === 'STUDENT' && empty($data['classe_id'])) {
            $errors['classe_id'] = 'Classe requise pour un étudiant';
        }

        return $errors;
    }

    public function getDashboardStats(): array {
        return $this->userRepo->getDashboardStats();
    }

    public function getUsersList(): array {
        return $this->userRepo->getAllUsers();
    }
}
