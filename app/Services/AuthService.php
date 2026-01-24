<?php

namespace App\Services;

use App\Repositories\AuthRepo;
USE App\Models\Admin;
USE App\Models\Teacher;
USE App\Models\Student;

USE Exception;

class AuthService {


private AuthRepo $AuthRepo ;

public function __construct(){

$this->AuthRepo = new AuthRepo();
}



public function login(string $email , string $password){


if(empty($email) || empty($password)){

throw new Exception("Tous les champs sont requis");
}

$user = $this->AuthRepo->findByEmail($email);

if (!$user || !password_verify($password , $user['password'])) {
     throw new Exception("Email ou mot de passe incorrect!");
  }

return $this->buildUserObject($user);
}

private function buildUserObject(array $user){

        return match ($user['role']) {
            'ADMIN' => new Admin(
                $user['id'],
                $user['first_name'],
                $user['last_name'],
                $user['email']
            ),
            'TEACHER' => new Teacher(
                $user['id'],
                $user['first_name'],
                $user['last_name'],
                $user['email'],
                $user['classe_id']
            ),
            'STUDENT' => new Student(
                $user['id'],
                $user['first_name'],
                $user['last_name'],
                $user['email'],
                $user['classe_id']
            ),


};
}

}