<?php

namespace App\Repositories;

use Core\Database;

USE PDO ;

class ClassRepo {

public PDO $pdo ;

public function __construct(){

$this->pdo = Database::getInstance()->getConnection();

}
public function addClass($ClassName , $ClassYear){

$stmt = $this->pdo->prepare('INSERT INTO classe ("name" , "year" ) values (:name , :year)');

$stmt->execute([

':name' =>$ClassName,
':year' =>$ClassYear
]);

}

}
