<?php

namespace App\Core;

class BaseService
{
    public function repository($model)
    {
        if (file_exists('App/Repository/' . $model . '.php')) :
            require_once 'App/Repository/' . $model . '.php';
            return new $model;
        else :
            echo "Repository not found";
        endif;
    }

    public function service($model)
    {
        if (file_exists('App/Service/' . $model . '.php')) :
            require_once 'App/Service/' . $model . '.php';
            return new $model;
        else :
            echo "Service not found";
        endif;
    }
}