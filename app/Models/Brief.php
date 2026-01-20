<?php

namespace App\Models;

class Brief {

private string $id;
private string $title;
private string $content;
private string $start_date;
private string $end_date;
private string $type;
private bool $is_assigned;
private int $sprint_id;
private int $teacher_id;

public function __construct($id , $title , $content , $start_date , $end_date , $type , $is_assigned , $sprint_id , $teacher_id){

    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->start_date = $start_date;
    $this->end_date = $end_date;
    $this->type = $type;
    $this->is_assigned = $is_assigned;
    $this->sprint_id = $sprint_id;
    $this->teacher_id = $teacher_id;
}



public function getId(): string{
    return $this->id;
}
public function getTitle(): string{
    return $this->title;
}
public function getContent(): string{
    return $this->content;
}
public function getStartDate(): string{
    return $this->start_date;
}
public function getEndDate(): string{
    return $this->end_date;
}
public function getType(): string{
    return $this->type;
}
public function getIsAssigned(): bool{
    return $this->is_assigned;
}
public function getSprintId(): int{
    return $this->sprint_id;
}
public function getTeacherId(): int{
    return $this->teacher_id;
}






}   