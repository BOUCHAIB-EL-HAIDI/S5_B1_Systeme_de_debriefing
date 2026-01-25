<?php

namespace App\Services;

use App\Repositories\BriefRepo;

class BriefService {
    private BriefRepo $briefRepo;

    public function __construct() {
        $this->briefRepo = new BriefRepo();
    }

    public function addCompetence(array $data): array {
        if (empty($data['code']) || empty($data['label'])) {
            return ['success' => false, 'errors' => ['all' => 'Tous les champs sont requis']];
        }

        if (!preg_match('/^C\d+$/i', $data['code'])) {
            return ['success' => false, 'errors' => ['code' => 'Le code doit être au format C suivi d\'un nombre (ex: C1, C2).']];
        }

        if ($this->briefRepo->isCompetenceCodeExists($data['code'])) {
            return ['success' => false, 'errors' => ['code' => 'Ce code de compétence existe déjà.']];
        }

        $success = $this->briefRepo->createCompetence($data['code'], $data['label']);
        return ['success' => $success];
    }

    public function addSprint(array $data): array {
        if (empty($data['name']) || empty($data['duration']) || empty($data['order'])) {
            return ['success' => false, 'errors' => ['all' => 'Tous les champs sont requis']];
        }

        if ($this->briefRepo->isSprintOrderExists((int)$data['order'])) {
            return ['success' => false, 'errors' => ['order' => 'Ce numéro d\'ordre de sprint existe déjà.']];
        }

        $classRepo = new \App\Repositories\ClassRepo();
        $classes = $classRepo->getAllClasses();

        if (empty($classes)) {
            return ['success' => false, 'errors' => ['system' => 'Aucune classe disponible pour assigner le sprint.']];
        }

        $success = true;
        foreach ($classes as $class) {
            $data['classe_id'] = $class['id'];
            if (!$this->briefRepo->createSprint($data)) {
                $success = false;
            }
        }

        return ['success' => $success];
    }

    public function getCompetences(): array {
        return $this->briefRepo->getAllCompetences();
    }

    public function getSprints(): array {
        return $this->briefRepo->getAllSprints();
    }
}
