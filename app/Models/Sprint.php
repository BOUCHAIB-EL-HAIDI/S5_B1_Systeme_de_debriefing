<?php



namespace App\Models;

Class Sprint {

private int $id;
private string $name;
private int $duration ;
private int $order;
private int $ClassId;

public function __construct($id , $name , $duration , $order , $ClassId){

    $this->id = $id;
    $this->name = $name;
    $this->duration = $duration;
    $this->order = $order;
    $this->ClassId = $ClassId;



}



public function getId(): int{
    return $this->id;
}
public function getName(): string{
    return $this->name;
}
public function getDuration(): int{
    return $this->duration;
}
public function getOrder(): int{
    return $this->order;
}
public function getClassId(): int{
    return $this->ClassId;
}







}