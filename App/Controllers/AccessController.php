<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Utils\Utils;
use GUMP as Validador;

class AccessController extends BaseController
{
    protected $filters = [
        'cpf' => 'trim|sanitize_string',
        'password' => 'trim|sanitize_string'
    ];

    protected $rules = [
        'cpf'    => 'required|exact_len,14',
        'password'  => 'required',
    ];

    function __construct()
    {
        session_start();
    }

    public function index()
    {
        if (Utils::usuarioLogado()) :
            Utils::redirect();
        endif;

        $this->view('login/index');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") :
            if (Utils::validateInputs($_POST, $this->filters, $this->rules) == false) {
                exit();
            }

            try {
                $accessService = $this->service('AccessService');
                $accessService->login($_POST['cpf'], $_POST['password']);

                Utils::jsonResponse(200);
            } catch (\Exception $e) {
                Utils::returnJsonError(401, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }

    public function logout()
    {
        try {
            $accessService = $this->service('AccessService');
            $accessService->logout();

            Utils::redirect("login");
        } catch (\Exception $e) {
            Utils::returnJsonError(500, $e->getMessage());
        }
    }
}