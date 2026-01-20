<?php

namespace App\Models;

class Competence {

private string $code ;
private string $label ;

public function __construct(string $code , $label){

$this->code = $code;
$this->label = $label;


}
 
public function getCode(){
    return $this->code;
}

public function getLabel(){

return $this->label;
}


}