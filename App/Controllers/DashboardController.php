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
                $data = $this->getSellerData();
                $this->view('dashboard/seller', $data);
                break;
            case Role::Comprador:
                $data = $this->getBuyerData();
                $this->view('dashboard/buyer', $data);
                break;
            case Role::Administrador:
                $data = $this->getAdminData();
                $this->view('dashboard/admin', $data);
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

    private function getSellerData()
    {
        $customerModel = $this->model('CustomerModel');
        $customers = $customerModel->list();

        $productModel = $this->model('ProductModel');
        $products = $productModel->listEnabledForSale();

        $data = [
            'customers' => $customers,
            'products' => $products
        ];

        return $data;
    }

    private function getAdminData()
    {
        $data = [];

        return $data;
    }
}