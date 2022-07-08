<?php

namespace App\Core;

class BaseController {

    public function model($model){
        if (file_exists('App/Models/'.$model.'.php')) :
          require_once 'App/Models/'.$model.'.php';
          return new $model;
        else:
            echo "Model not found";
        endif;
    }

    public function view($view, $data=[], $js=null){
        require_once 'App/Views/template.php';
    }
}