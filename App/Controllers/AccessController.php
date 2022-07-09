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
            $validacao = new Validador("pt-br");

            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :
                $senha_enviada = $_POST['password'];
                $model = $this->model('EmployeeModel');
                $usuario = $model->getEmployeeByCPF($_POST['cpf']);

                if (!empty($usuario) && $senha_enviada == $usuario->getSenha()) :
                    session_regenerate_id(true);

                    $_SESSION['id'] = $usuario->getId();
                    $_SESSION['nomeUsuario'] = $usuario->getNome();
                    $_SESSION['cpfUsuario'] = $usuario->getCPF();
                    $_SESSION['papelUsuario'] = $usuario->getPapelString();

                    Utils::redirect(); // redirect to dashboard

                else :
                    $mensagem = ["Usuário e/ou Senha incorreta"];
                    $data = [
                        'mensagens' => $mensagem
                    ];

                    $this->view('login/index', $data);
                endif;
            else :
                $mensagem = $validacao->get_errors_array();
                $data = [
                    'mensagens' => $mensagem
                ];
                $_SESSION['token'] = Utils::gerarTokenCSRF();

                $this->view('login/index', $data);
            endif;
        else :
            Utils::redirect();  // redirect to dashboard
        endif;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        Utils::redirect("login");
    }
}