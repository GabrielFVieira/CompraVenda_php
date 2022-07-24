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
        // if (!Utils::usuarioLogado()) :
        //     Utils::redirect("login");
        //     exit();
        // endif;
    }

    public function index()
    {
        if (!Utils::usuarioLogado()) :
            $productService = $this->service('ProductService');
            $products = $productService->listEnabledForSaleWithAvailableAmount();
            $data = [
                'products' => $products
            ];

            $this->view('dashboard/customer', $data);
            exit();
        endif;

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
                $this->view('dashboard/admin');
                break;
            default:
                Utils::redirect("logout");
                break;
        }
    }

    private function getBuyerData()
    {
        $providerService = $this->service('ProviderService');
        $providers = $providerService->list();

        $productService = $this->service('ProductService');
        $products = $productService->list();

        $data = [
            'providers' => $providers,
            'products' => $products
        ];

        return $data;
    }

    private function getSellerData()
    {
        $customerService = $this->service('CustomerService');
        $customers = $customerService->list();

        $productService = $this->service('ProductService');
        $products = $productService->listEnabledForSale();

        $data = [
            'customers' => $customers,
            'products' => $products
        ];

        return $data;
    }
}
