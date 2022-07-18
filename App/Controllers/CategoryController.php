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
            exit();
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
            Utils::jsonResponse(400, $data);
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
                Utils::jsonResponse(201);
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }
        else :
            Utils::redirect();
        endif;
    }

    public function update($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if ($this->validateInput($_PUT) == false) {
                exit();
            }

            $categoryModel = $this->model('CategoryModel');
            $oldCategory = $categoryModel->get($path['id']);

            if (is_null($oldCategory)) :
                $errors = ['Categoria não encontrada'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(404, $data);
                exit();
            endif;

            $oldCategory->setNome($_PUT['name']);

            try {
                $categoryModel->update($oldCategory);
                Utils::jsonResponse();
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
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
                    Utils::jsonResponse(200, $category);
                else :
                    $errors = ['Categoria não encontrada'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao listar categorias'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
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
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover categoria'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::redirect();
        endif;
    }
}