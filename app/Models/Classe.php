<?php   

namespace App\Models;

class Classe {

private string $id ;
private string $name;
private int $year;

public function __construct($id , $name , $year){

    $this->id = $id;
    $this->name = $name;
    $this->year = $year;
}



public function getId(): string{
    return $this->id;
}
public function getName(): string{
    return $this->name;
}
public function getYear(): int{
    return $this->year;
}


}
