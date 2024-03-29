<?php

namespace app\utils;

use App\models\Employee;
use GUMP as Validador;
use App\models\Role;

class Utils
{
    const DefaultPassword = 'Suporte@22';

    public static function gerarTokenCSRF()
    {
        $string_aleatoria = bin2hex(openssl_random_pseudo_bytes(16));
        return hash_hmac('sha256', $string_aleatoria, CSRF_TOKEN_SECRET);
    }

    public static function validarTokenCSRF($token_crsf, $token_session)
    {
        $token_crsf_original = $token_session;
        if (hash_equals($token_crsf_original, $token_crsf)) {
            return true;
        }
        return false;
    }

    public static function redirect($rota = "")
    {
        header("Location:" . BASE_URL . "/" . $rota);
    }

    public static function usuarioLogado()
    {
        return isset($_SESSION['id']) && isset($_SESSION['nomeUsuario']);
    }

    public static function jsonResponse($status = 200, $json = null)
    {
        http_response_code($status);
        if (!is_null($json)) :
            echo json_encode($json, JSON_UNESCAPED_SLASHES);
        else :
            echo json_encode(array());
        endif;
    }

    public static function returnJsonError(int $status, string ...$errors)
    {
        $data = ['errors' => $errors];
        Utils::jsonResponse($status, $data);
    }

    public static function loadPutValues(&$variable)
    {
        parse_str(file_get_contents('php://input'), $variable);
    }

    public static function validateInputs($data, $filters, $rules)
    {
        $validacao = new Validador("pt-br");
        $post_filtrado = $validacao->filter($data, $filters);
        $post_validado = $validacao->validate($post_filtrado, $rules);

        if ($post_validado === true) :
            return true;
        else :
            $errors = $validacao->get_errors_array();

            $formattedErrors = [];
            foreach ($errors as $value) {
                array_push($formattedErrors, $value);
            }

            $data = ['errors' => $formattedErrors];
            Utils::jsonResponse(400, $data);
            return false;
        endif;
    }

    public static function hasPermission(string $role)
    {
        if (Role::fromString($_SESSION['papelUsuario']) == $role) :
            return true;
        endif;

        $errors = ['Usuário não tem permissão para executar essa ação'];
        $data = ['errors' => $errors];
        Utils::jsonResponse(403, $data);

        return false;
    }

    public static function omitPasswords(Employee ...$employees)
    {
        foreach ($employees as $employee) {
            $employee->setSenha(null);
        }

        return $employees;
    }

    public static function getQueryParams()
    {
        $url = "http://localhost{$_SERVER['REQUEST_URI']}";
        $url_components = parse_url($url);
        $params = [];
        if (isset($url_components['query'])) :
            parse_str($url_components['query'], $params);
        endif;

        return $params;
    }

    public static function getFloatAsCurrency($value)
    {
        return number_format($value, 2, ',', '.');
    }
}