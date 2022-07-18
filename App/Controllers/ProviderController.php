<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Role;
use App\Utils\Utils;

class ProviderController extends BaseController
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
                $this->view('dashboard/buyer');
                break;
            default:
                Utils::redirect("logout");
                break;
        }
    }

    public function list()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $model = $this->model('ProviderModel');

            $providers = $model->getProviders();

            echo json_encode($providers);
            exit();
        else :
            Utils::redirect();
        endif;
    }
}