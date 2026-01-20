<?php

namespace App\Models ; 

USE Models\User;
class Admin extends User {



        
     protected string $role;

     public function __construct($id , $first_name , $last_name , $email , $password ){

         parent::__construct($id , $first_name , $last_name , $email , $password , 'ADMIN');

    }

}