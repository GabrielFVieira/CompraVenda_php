<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Utils\Utils;
use GUMP as Validador;

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

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            $validacao = new Validador("pt-br");
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :
                $product = new \App\models\Product();
                $product->setNome($_POST['name']);
                $product->setDescricao($_POST['description']);

                if (isset($_POST['active'])) :
                    $product->setLiberadoVendaBool(boolval($_POST['active']));
                else :
                    $product->setLiberadoVendaBool(false);
                endif;

                $product->setIdCategoria($_POST['category']);
                $product->setPrecoVenda(floatval($_POST['sellValue']));
                $product->setPrecoCompra(0);
                $product->setQuantidadeDisponivel(0);

                $productModel = $this->model('ProductModel');
                $productModel->create($product);

                $messages = ['Produto cadastrado com sucesso'];
                $data = ['messages' => $messages];
                Utils::redirect("products");
            else :
                $erros = $validacao->get_errors_array();
                $data = ['errors' => $erros];
                $this->view('product/index', $data);
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
                $productModel = $this->model('ProductModel');
                $oldProduct = $productModel->get($path['id']);

                if (is_null($oldProduct)) :
                    $erros = ['Produto não encontrado'];
                    $data = ['erros' => $erros];
                    Utils::redirect('products', $data);
                    exit();
                endif;


                $oldProduct->setNome($_POST['name']);
                $oldProduct->setDescricao($_POST['description']);

                if (isset($_POST['active'])) :
                    $oldProduct->setLiberadoVendaBool(boolval($_POST['active']));
                else :
                    $oldProduct->setLiberadoVendaBool(false);
                endif;

                $oldProduct->setIdCategoria($_POST['category']);
                $oldProduct->setPrecoVenda(floatval($_POST['sellValue']));

                $productModel->update($oldProduct);

                // $messages = ['Produto atualizado com sucesso'];
                // $data = ['messages' => $messages];
                Utils::redirect('products');
            else :
                $erros = $validacao->get_errors_array();
                $data = ['erros' => $erros];
                $this->view('product/index', $data);
            endif;
        else :
            Utils::redirect();
        endif;
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $productModel = $this->model('ProductModel');
            $product = $productModel->getRaw($path['id']);

            if (!is_null($product)) :
                echo json_encode($product);
            else :
                $data = array();
                $data['error'] = 'Produto não encontrado';
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

            $productModel = $this->model('ProductModel');
            $productModel->remove($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();
        else :
            Utils::redirect();
        endif;
    }
}