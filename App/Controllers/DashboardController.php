<?php

namespace App\Controllers;

use App\Core\BaseController;
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
        $this->view('dashboard/index');
    }
}