<?php

namespace App\Models;

class Evaluation {

    private int $debriefing_id;
    private string $competence_code;
    private string $niveau;
    private string $status;

    public function __construct(int $debriefing_id, string $competence_code, string $niveau, string $status) {
        $this->debriefing_id = $debriefing_id;
        $this->competence_code = $competence_code;
        $this->niveau = $niveau;
        $this->status = $status;
    }

    public function getDebriefingId(): int {
        return $this->debriefing_id;
    }

    public function getCompetenceCode(): string {
        return $this->competence_code;
    }

    public function getNiveau(): string {
        return $this->niveau;
    }

    public function getStatus(): string {
        return $this->status;
    }
}
