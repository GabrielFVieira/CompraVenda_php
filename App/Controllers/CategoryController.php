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

    private function loadCategoryView($errors = null, $messages = null)
    {
        $categoryModel = $this->model('CategoryModel');
        $categories = $categoryModel->list();

        $data = [
            'categories' => $categories
        ];

        if (isset($messages)) :
            $data['messages'] = $messages;
        endif;

        if (isset($errors)) :
            $data["errors"] = $errors;
        endif;

        $this->view('category/index', $data);
    }

    private function jsonResponse($status = 200, $json = null)
    {
        http_response_code($status);
        if (!is_null($json)) :
            echo json_encode($json);
        endif;
    }

    public function index()
    {
        $this->loadCategoryView();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            $validacao = new Validador("pt-br");
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if (!$post_validado) {
                $errors = $validacao->get_errors_array();
                $data = ['errors' => $errors];

                // Validation Error
                $this->jsonResponse(400, $data);
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

            $validacao = new Validador("pt-br");
            $post_filtrado = $validacao->filter($_PUT, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :
                $categoryModel = $this->model('CategoryModel');
                $oldCategory = $categoryModel->get($path['id']);

                if (is_null($oldCategory)) :
                    $errors = ['Categoria não encontrada'];
                    $this->loadCategoryView($errors);
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
                $errors = $validacao->get_errors_array();
                $this->loadCategoryView($errors);
            endif;
        else :
            Utils::redirect();
        endif;
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $id = $path['id'];

            $categoryModel = $this->model('CategoryModel');
            $category = $categoryModel->get($id);

            if (!is_null($category)) :
                echo json_encode($category);
            else :
                $data = array();
                $data['error'] = 'Categoria não encontrada';
                http_response_code(404);
                echo json_encode($data);
            endif;

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
            $categoryModel->remove($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();
        else :
            Utils::redirect();
        endif;
    }
}
