<?PHP

namespace App\Models;

class Debriefing {

    private int $id;
    private string $comment;
    private string $date;
    private int $student_id;
    private int $teacher_id;
    private int $brief_id;
    private string $created_at;

    public function __construct(int $id, string $comment, string $date, int $student_id, int $teacher_id, int $brief_id, string $created_at) {
        $this->id = $id;
        $this->comment = $comment;
        $this->date = $date;
        $this->student_id = $student_id;
        $this->teacher_id = $teacher_id;
        $this->brief_id = $brief_id;
        $this->created_at = $created_at;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getComment(): string {
        return $this->comment;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getStudentId(): int {
        return $this->student_id;
    }

    public function getTeacherId(): int {
        return $this->teacher_id;
    }

    public function getBriefId(): int {
        return $this->brief_id;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }



}