<?php

namespace App\Core;

class BaseController
{
    public function service($model)
    {
        if (file_exists('App/Service/' . $model . '.php')) :
            require_once 'App/Service/' . $model . '.php';
            return new $model;
        else :
            echo "Service not found";
        endif;
    }

    public function view($view, $data = [], $js = null)
    {
        require_once 'App/Views/template.php';
    }
}