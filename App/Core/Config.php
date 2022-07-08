<?php
// Configuração do BD
define('HOST', 'localhost'); // onde está o banco de dados
define('DB','estoque');      // nome da base de dados
define('USER','root');    // usuário da base de dados
define('PASSWORD','');          // senha usuário da base de dados

//Constante que indica a URL básica da aplicação

define("BASE_URL", "http://localhost/cursophp/CompraVenda_php");

//Constante que indica a URL básica da images
define("URL_IMG", BASE_URL."/public/images/");

//Constante que indica a URL básica da css
define("URL_CSS", BASE_URL."/public/css/");

//Constante que indica a URL básica da css
define("URL_JS", BASE_URL."/public/js/");

//Constante que indica a URL básica da css
define("FONTAWESOME", BASE_URL."/public/fontawesome/css/all.css");

//Constante usada para gerar CSRF Token
define('CSRF_TOKEN_SECRET', 'iyHS4##SiPcV9tIZ');

// Caminho para a imagem Captcha
define('DIR_IMG_CAPTCHA', "C:/wamp64/www/cursophp/CompraVenda_php/App/writable/");

// Caminho para a imagem upload
define('DIR_IMG_UPLOAD', "C:/wamp64/www/cursophp/CompraVenda_php/public/upload/");

// URL imagem upload
define('URL_IMG_UPLOAD', "http://localhost/cursophp/CompraVenda_php/upload/");

// Quantidade de registros exibidos na página
define("REGISTROS_PAG", 4);

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri) {
        return BASE_URL . "/{$uri}";
    }

    return BASE_URL;
}