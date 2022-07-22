<?php

namespace App\Controllers;

use \Exception;
use \App\models\Product;
use App\Core\BaseController;
use App\models\Role;
use App\Utils\Utils;

class ReportController extends BaseController
{
    protected $filters = [
        'name' => 'trim|sanitize_string',
        'description' => 'trim|sanitize_string',
        'active' => 'trim|boolean',
        'sellValue' => 'trim|sanitize_numbers',
        'category' =>  'sanitize_numbers',
    ];

    protected $rules = [
        'name' => 'required|max_len,100',
        'description' => 'required|max_len,255',
        'active' => 'boolean',
        'category' => 'required|integer',
    ];

    function __construct()
    {
        session_start();
        // if (!Utils::usuarioLogado()) :
        //     Utils::redirect("login");
        //     exit();
        // elseif (Utils::hasPermission(Role::Administrador) == false) :
        //     exit();
        // endif;
    }

    public function products()
    {
        // $params = Utils::getQueryParams();

        $productModel = $this->model('ProductModel');
        $products = $productModel->list();


        Utils::jsonResponse(200, $products);
    }
}