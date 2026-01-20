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

    try {
       echo $this->blade->run($view , $data);
    }catch(\Exception $e){

    echo "Error rendering view '$view':" . $e->getMessage();
    }

   }


}