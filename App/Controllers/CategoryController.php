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
        $categoryService = $this->service('CategoryService');
        $categories = $categoryService->list();

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

            try {
                $category = new Category();
                $category->setNome($_POST['name']);

                $categoryService = $this->service('CategoryService');
                $categoryService->create($category);

                Utils::jsonResponse(201);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
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

            try {
                $category = new Category();
                $category->setId($path['id']);
                $category->setNome($_PUT['name']);

                $categoryService = $this->service('CategoryService');
                $categoryService->update($category);

                Utils::jsonResponse();
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::jsonResponse(405);
        endif;
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            try {
                $id = $path['id'];
                $categoryService = $this->service('CategoryService');
                $category = $categoryService->get($id);

                if (!is_null($category)) :
                    Utils::jsonResponse(200, $category);
                else :
                    Utils::returnJsonError(404, 'Categoria não encontrada');
                endif;
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
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
                $categoryService = $this->service('CategoryService');
                $categoryService->remove($id);
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }
}