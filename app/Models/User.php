<?php

namespace App\Models;

abstract class User{

protected int $id;
protected string $first_name;
protected string $last_name;
protected string $email;
protected string $password;
protected string $role;

public function __construct(int $id , string $first_name , string $last_name , string $email  , string $role){

    $this->id = $id;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
    $this->role = $role;
}
         

public function getId(): string{
    return $this->id;
}
public function getFirstName(): string{
    return $this->first_name;
}
public function getLastName(): string{
    return $this->last_name;
}
public function getEmail(): string{
    return $this->email;
}
public function getRole(): string{
    return $this->role;
}


}