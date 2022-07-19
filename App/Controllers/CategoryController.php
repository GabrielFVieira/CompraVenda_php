<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Category;
use App\models\Role;

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

    public function create()
    {
        if (Utils::hasPermission(Role::Comprador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            if (Utils::validateInputs($_POST, $this->filters, $this->rules) == false) {
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
        if (Utils::hasPermission(Role::Comprador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if (Utils::validateInputs($_PUT, $this->filters, $this->rules) == false) {
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
            Utils::jsonResponse(405);
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
                $errors = ['Erro ao buscar categoria'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }

    public function remove($data)
    {
        if (Utils::hasPermission(Role::Comprador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            try {
                $id = $data['id'];
                $categoryModel = $this->model('CategoryModel');
                $categoryModel->remove($id);
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover categoria'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }
}
