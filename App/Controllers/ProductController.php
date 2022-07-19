<?php

namespace App\Controllers;

use \Exception;
use \App\models\Product;
use App\Core\BaseController;
use App\models\Role;
use App\Utils\Utils;

class ProductController extends BaseController
{
    protected $filters = [
        'name' => 'trim|sanitize_string',
        'description' => 'trim|sanitize_string',
        'active' => 'trim|boolean',
        'sellValue' => 'trim|sanitize_numbers',
        'category' =>  'sanitize_numbers',
    ];

    protected $rules = [
        'name' => 'required|max_len,100',
        'description' => 'required|max_len,255',
        'active' => 'boolean',
        'category' => 'required|integer',
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
        $productModel = $this->model('ProductModel');
        $products = $productModel->list();

        $categoryModel = $this->model('CategoryModel');
        $categories = $categoryModel->list();

        $data = [
            'products' => $products,
            'categories' => $categories
        ];

        $this->view('product/index', $data);
    }

    private function updateModelValues(Product &$model, $data)
    {
        $model->setNome($data['name']);
        $model->setDescricao($data['description']);

        if (isset($data['active'])) :
            $model->setLiberadoVendaBool(boolval($data['active']));
        else :
            $model->setLiberadoVendaBool(false);
        endif;

        $model->setIdCategoria($data['category']);

        $value = str_replace(',', '.', $data['sellValue']);
        $model->setPrecoVenda(floatval($value));
        $model->setPrecoCompra(0);
        $model->setQuantidadeDisponivel(0);
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

            $product = new Product();
            $this->updateModelValues($product, $_POST);
            $productModel = $this->model('ProductModel');

            try {
                $productModel->create($product);
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

            $productModel = $this->model('ProductModel');
            $oldProduct = $productModel->get($path['id']);

            if (is_null($oldProduct)) :
                $erros = ['Produto não encontrado'];
                $data = ['erros' => $erros];
                Utils::redirect('products', $data);
                exit();
            endif;

            $this->updateModelValues($oldProduct, $_PUT);

            try {
                $productModel->update($oldProduct);
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
            $productModel = $this->model('ProductModel');

            try {
                $product = $productModel->get($path['id']);

                if (!is_null($product)) :
                    Utils::jsonResponse(200, $product);
                else :
                    $errors = ['Produto não encontrado'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao buscar produto'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }
        else :
            Utils::redirect();
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
                $productModel = $this->model('ProductModel');
                $productModel->remove($id);
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover produto'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }
}
