<?php

namespace app\utils;

class Utils
{
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
            echo json_encode($json);
        else :
            echo json_encode(array());
        endif;
    }

    public static function loadPutValues(&$variable)
    {
        parse_str(file_get_contents('php://input'), $variable);
    }
}