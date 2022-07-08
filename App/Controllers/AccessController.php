<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Utils\Utils;
use GUMP as Validador;

class AccessController extends BaseController
{
    protected $filters = [
        'email' => 'trim|sanitize_email',
        'senha' => 'trim|sanitize_string',
        'captcha' => 'trim|sanitize_string'
    ];

    protected $rules = [
        'email'    => 'required|min_len,8|max_len,255',
        'senha'  => 'required',
        'captcha'  => 'required|validar_CAPTCHA_CODE'
    ];

    function __construct()
    {
        session_start();
    }

    public function index()
    {
        // gera o CAPTCHA_CODE e guarda na sessão 
        $_SESSION['CAPTCHA_CODE'] = Utils::gerarCaptcha();
        $imagem = Utils::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
        // gera o CSRF_token e guarda na sessão
        $_SESSION['CSRF_token'] = Utils::gerarTokenCSRF();
        $data = ['imagem' => $imagem];
        // chama a view
        $this->view('login/index', $data);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") :
            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');

            $validacao = new Validador("pt-br");

            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :  // verificar login

                if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :

                    $senha_enviada = $_POST['senha'];

                    // gera uma senha fake
                    $senha_fake   = random_bytes(64);
                    $hash_senha_fake = password_hash($senha_fake, PASSWORD_ARGON2I);

                    // busca o usuario
                    $usuarioModel = $this->model('UserModel');
                    $usuario = $usuarioModel->getUsuarioEmail($_POST['email']);

                    if (!empty($usuario)) :
                        $senha_hash = $usuario['senha']; // achou o usuário usa hash do banco
                    else :
                        $senha_hash = $hash_senha_fake;  // não achou o usuário usa hash fake
                    endif;

                    if (password_verify($senha_enviada, $senha_hash)) :

                        // apagar CAPTCHA_CODE
                        unset($_SESSION['CAPTCHA_CODE']);

                        // regenerar a sessão
                        session_regenerate_id(true);

                        $_SESSION['id'] = $usuario['id'];
                        $_SESSION['nomeUsuario'] = $usuario['nome'];
                        $_SESSION['emailUsuario'] = $usuario['email'];

                        Utils::redirect("Dashboard");  // acesso área restrita

                    else :
                        $mensagem = ["Usuário e/ou Senha incorreta"];
                        $_SESSION['CAPTCHA_CODE'] = Utils::gerarCaptcha(); // guarda o captcha_code na sessão 
                        $imagem = Utils::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                        $_SESSION['CSRF_token'] = Utils::gerarTokenCSRF();
                        $data = [
                            'imagem' => $imagem,
                            'mensagens' => $mensagem
                        ];

                        $this->view('acessorestrito/login', $data);
                    endif;

                else :  // falha CSRF_token"
                    die("Erro 404");
                endif;
            else : // erro de validação
                $mensagem = $validacao->get_errors_array();
                $_SESSION['CAPTCHA_CODE'] = Utils::gerarCaptcha(); // guarda o captcha_code na sessão 
                $imagem = Utils::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $_SESSION['CSRF_token'] = Utils::gerarTokenCSRF();
                $data = [
                    'imagem' => $imagem,
                    'mensagens' => $mensagem
                ];

                $this->view('acessorestrito/login', $data);
            endif;
        else : // não POST
            Utils::redirect();
        endif;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        Utils::redirect();
    }
}