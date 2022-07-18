<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Category;
use GUMP as Validador;

class CategoryController extends BaseController
{
    protected $filters = [
        'name' => 'trim|sanitize_string',
    ];

    protected $rules = [
        'name' => 'required|max_len,50',
    ];

    function __construct()
    {
        session_start();
        if (!Utils::usuarioLogado()) :
            Utils::redirect("login");
        endif;
    }

    private function jsonResponse($status = 200, $json = null)
    {
        http_response_code($status);
        if (!is_null($json)) :
            echo json_encode($json);
        else :
            echo json_encode(array());
        endif;
    }

    public function index()
    {
        $categoryModel = $this->model('CategoryModel');
        $categories = $categoryModel->list();

        $data = [
            'categories' => $categories
        ];

        $this->view('category/index', $data);
    }

    private function validateInput($data)
    {
        $validacao = new Validador("pt-br");
        $post_filtrado = $validacao->filter($data, $this->filters);
        $post_validado = $validacao->validate($post_filtrado, $this->rules);

        if ($post_validado === true) :
            return true;
        else :
            $errors = $validacao->get_errors_array();

            $formattedErrors = [];
            foreach ($errors as $value) {
                array_push($formattedErrors, $value);
            }

            $data = ['errors' => $formattedErrors];
            $this->jsonResponse(400, $data);
            return false;
        endif;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            if ($this->validateInput($_POST) == false) {
                exit();
            }

            $category = new Category();
            $category->setNome($_POST['name']);
            $categoryModel = $this->model('CategoryModel');

            try {
                $categoryModel->create($category);
                $this->jsonResponse(201);
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                $this->jsonResponse(500, $data);
            }
        else :
            Utils::redirect();
        endif;
    }

    public function update($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            parse_str(file_get_contents('php://input'), $_PUT);
            if ($this->validateInput($_PUT) == false) {
                exit();
            }

            $categoryModel = $this->model('CategoryModel');
            $oldCategory = $categoryModel->get($path['id']);

            if (is_null($oldCategory)) :
                $errors = ['Categoria não encontrada'];
                $data = ['errors' => $errors];
                $this->jsonResponse(404, $data);
                exit();
            endif;

            $oldCategory->setNome($_PUT['name']);

            try {
                $categoryModel->update($oldCategory);
                $this->jsonResponse();
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                $this->jsonResponse(500, $data);
            }
        else :
            Utils::redirect();
        endif;
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $id = $path['id'];
            $categoryModel = $this->model('CategoryModel');

            try {
                $category = $categoryModel->get($id);

                if (!is_null($category)) :
                    $this->jsonResponse(200, $category);
                else :
                    $errors = ['Categoria não encontrada'];
                    $data = ['errors' => $errors];
                    $this->jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao listar categorias'];
                $data = ['errors' => $errors];
                $this->jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::redirect();
        endif;
    }

    public function remove($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            $id = $data['id'];

            $categoryModel = $this->model('CategoryModel');

            try {
                $categoryModel->remove($id);
                $this->jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover categoria'];
                $data = ['errors' => $errors];
                $this->jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::redirect();
        endif;
    }
}
