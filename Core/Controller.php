<?php

namespace Core;

USE eftec\bladeone\BladeOne;
                            
Class Controller {

protected BladeOne $blade;


public function __construct(){

   $views = __DIR__ . '/../Views';
   $cache = __DIR__ . '/../cache/views';
    
   if(!is_dir($cache)){

   mkdir($cache , 0777 , true);
   }


   $this->blade = new BladeOne($views , $cache , BladeOne::MODE_AUTO);

}

   protected function render($view , $data = []){
    $data['baseUrl'] = defined('BASE_URL') ? BASE_URL : '';
    $data['auth'] = [
        'id' => $_SESSION['user_id'] ?? null,
        'first_name' => $_SESSION['first_name'] ?? 'Guest',
        'last_name' => $_SESSION['last_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'GUEST'
    ];
    try {
       echo $this->blade->run($view , $data);
    }catch(\Exception $e){

    echo "Error rendering view '$view':" . $e->getMessage();
    }

   }


}