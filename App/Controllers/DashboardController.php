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
}