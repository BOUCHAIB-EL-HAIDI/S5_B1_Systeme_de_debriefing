<?php

namespace App\Models;
USE Models\User;


class Student extends User{

      
     protected string $role;
     protected int $ClassId;
     public function __construct($id , $first_name , $last_name , $email , $ClassId ){

         parent::__construct($id , $first_name , $last_name , $email  , 'STUDENT');
         $this->ClassId = $ClassId;

     }

     public function getClassId(){
     return $this->ClassId;

     }
   

}
