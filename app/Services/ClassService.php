<?php

namespace App\Services;
use App\Repositories\ClassRepo;

class ClassService {

public function validate(string $ClassName ,  string $ClassYear){

$errors = [];

if(!preg_match('/^[A-Za-z0-9_#\- ]{3,}$/', $ClassName)){

$errors['classname'] = 'invalid class name';

}
if(!is_numeric($ClassYear)){
$errors['classyear'] = 'invalid year';
}

if($ClassYear <= 2026 || $ClassYear > 2100) {

$errors['classyear'] = 'Year must be between 2026 and 2100';
}
if(!empty($errors)){

return [
'success' =>false,
'errors' =>$errors
];

}
  
 //if data is valid 
 $ClassRepo = new ClassRepo();
 $ClassRepo->addClass($ClassName , $ClassYear);

 return [
    'success' => true
 ];



}


}