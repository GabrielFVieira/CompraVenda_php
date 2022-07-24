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
        $productService = $this->service('ProductService');
        $products = $productService->list();
        Utils::jsonResponse(200, $products);
    }

    public function sales()
    {
        $saleService = $this->service('SaleService');
        $sales = $saleService->listSalesByDay();
        Utils::jsonResponse(200, $sales);
    }
}
