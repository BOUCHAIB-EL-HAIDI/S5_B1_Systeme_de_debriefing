<?php

namespace App\Models;

class Livrable {

    private int $id;
    private string $content;
    private string $submitted_at;
    private int $student_id;
    private int $brief_id;

    public function __construct(int $id, string $content, string $submitted_at, int $student_id, int $brief_id) {
        $this->id = $id;
        $this->content = $content;
        $this->submitted_at = $submitted_at;
        $this->student_id = $student_id;
        $this->brief_id = $brief_id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getSubmittedAt(): string {
        return $this->submitted_at;
    }

    public function getStudentId(): int {
        return $this->student_id;
    }

    public function getBriefId(): int {
        return $this->brief_id;
    }


}
