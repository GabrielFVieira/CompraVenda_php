<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Utils\Utils;
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            $validacao = new Validador("pt-br");
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :
                $category = new \App\models\Category();
                $category->setNome($_POST['name']);

                $categoryModel = $this->model('CategoryModel');
                $categoryModel->create($category);

                $messages = ['Categoria cadastrada com sucesso'];
                $data = ['messages' => $messages];
                Utils::redirect('categories', $data);
            else :
                $erros = $validacao->get_errors_array();
                $data = ['erros' => $erros];
                $this->view('categories', $data);
            endif;
        else :
            Utils::redirect();
        endif;
    }

    public function update($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            $validacao = new Validador("pt-br");
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :
                $categoryModel = $this->model('CategoryModel');
                $oldCategory = $categoryModel->get($path['id']);

                if (is_null($oldCategory)) :
                    // $erros = ['Categoria não encontrada'];
                    // $data = ['erros' => $erros];
                    Utils::redirect('categories');
                    exit();
                endif;

                $oldCategory->setNome($_POST['name']);
                $categoryModel->update($oldCategory);

                // $messages = ['Categoria atualizada com sucesso'];
                // $data = ['messages' => $messages];
                Utils::redirect('categories');

            else :
                // $erros = $validacao->get_errors_array();
                // $data = ['erros' => $erros];
                Utils::redirect('categories');
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