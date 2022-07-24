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
        $productService = $this->service('ProductService');
        $products = $productService->list();

        $categoryService = $this->service('CategoryService');
        $categories = $categoryService->list();

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
                $product = new Product();
                $this->updateModelValues($product, $_POST);

                $productService = $this->service('ProductService');
                $productService->create($product);

                Utils::jsonResponse();
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
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

            try {
                $product = new Product();
                $this->updateModelValues($product, $_PUT);
                $product->setId($path['id']);

                $productService = $this->service('ProductService');

                $oldProduct = $productService->get($product->getId());
                if (is_null($oldProduct)) :
                    throw new Exception('Produto não encontrado');
                endif;

                $product->setQuantidadeDisponivel($oldProduct->getQuantidadeDisponivel());
                $product->setPrecoCompra($oldProduct->getPrecoCompra());

                $productService->update($product);

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
            $productService = $this->service('ProductService');

            try {
                $product = $productService->get($path['id']);

                if (!is_null($product)) :
                    Utils::jsonResponse(200, $product);
                else :
                    Utils::returnJsonError(404, 'Produto não encontrado');
                endif;
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
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
                $productService = $this->service('ProductService');
                $productService->remove($id);

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