<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Role;
use App\Utils\Utils;

class DashboardController extends BaseController
{
    function __construct()
    {
        session_start();
        if (!Utils::usuarioLogado()) :
            Utils::redirect("login");
            exit();
        endif;
    }

    public function index()
    {
        $papel = Role::fromString($_SESSION['papelUsuario']);

        switch ($papel) {
            case Role::Vendedor:
                $this->view('dashboard/seller');
                break;
            case Role::Comprador:
                $data = $this->getBuyerData();
                $this->view('dashboard/buyer', $data);
                break;
            default:
                Utils::redirect("logout");
                break;
        }
    }

    private function getBuyerData()
    {
        $providerModel = $this->model('ProviderModel');
        $providers = $providerModel->list();

        $productModel = $this->model('ProductModel');
        $products = $productModel->list();

        $data = [
            'providers' => $providers,
            'products' => $products
        ];

        return $data;
    }
}