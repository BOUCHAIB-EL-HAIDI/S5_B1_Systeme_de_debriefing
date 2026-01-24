<?php

namespace App\Repositories;

use Core\Database;
USE PDO;
class AuthRepo {


private PDO $pdo ;

public function __construct(){

$this->pdo = Database::getInstance()->getConnection();

}


public function findByEmail($email){

$stmt = $this->pdo->prepare('SELECT * FROM users where email = :email Limit 1');

$stmt->execute([
   'email' =>$email
 ]);
return $stmt->fetch();

}















}