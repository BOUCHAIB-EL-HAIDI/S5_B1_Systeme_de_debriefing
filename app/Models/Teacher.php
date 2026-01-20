<?php

namespace App\Models;
USE Models\User;


class Teacher extends User{

      
     protected string $role;

     public function __construct($id , $first_name , $last_name , $email ){

         parent::__construct($id , $first_name , $last_name , $email  , 'TEACHER');

     } 
  
}