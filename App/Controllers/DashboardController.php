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
            $productRepository = $this->model('ProductRepository');
            $products = $productRepository->listEnabledForSaleWithAvailableAmount();
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
        $providerRepository = $this->model('ProviderRepository');
        $providers = $providerRepository->list();

        $productRepository = $this->model('ProductRepository');
        $products = $productRepository->list();

        $data = [
            'providers' => $providers,
            'products' => $products
        ];

        return $data;
    }

    private function getSellerData()
    {
        $customerRepository = $this->model('CustomerRepository');
        $customers = $customerRepository->list();

        $productRepository = $this->model('ProductRepository');
        $products = $productRepository->listEnabledForSale();

        $data = [
            'customers' => $customers,
            'products' => $products
        ];

        return $data;
    }
}
