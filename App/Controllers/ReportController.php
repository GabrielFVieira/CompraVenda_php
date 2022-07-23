<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\models\Role;
use App\Utils\Utils;

class ReportController extends BaseController
{
    function __construct()
    {
        session_start();
        if (!Utils::usuarioLogado()) :
            Utils::redirect("login");
            exit();
        elseif (Utils::hasPermission(Role::Administrador) == false) :
            exit();
        endif;
    }

    public function products()
    {
        $productModel = $this->model('ProductModel');
        $products = $productModel->list();
        Utils::jsonResponse(200, $products);
    }

    public function sales()
    {
        $saleModel = $this->model('SaleModel');
        $sales = $saleModel->listSalesByMonth();
        Utils::jsonResponse(200, $sales);
    }
}
