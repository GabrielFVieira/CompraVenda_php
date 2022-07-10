<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Role;
use App\Utils\Utils;
use GUMP as Validador;

class PurchaseController extends BaseController
{
    protected $filters = [
        'product' =>  'sanitize_numbers',
        'provider' => 'sanitize_numbers',
        'amount' => 'sanitize_numbers',
        'value' => 'sanitize_numbers'
    ];

    protected $rules = [
        'product' => 'required|integer',
        'provider' => 'required|integer',
        'amount'    => 'required|integer|min_numeric,1',
        'value'    => 'required|integer|min_numeric,0'
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
        $purchaseModel = $this->model('PurchaseModel');
        $purchases = $purchaseModel->list();

        $providerModel = $this->model('ProviderModel');
        $providers = $providerModel->list();

        $productModel = $this->model('ProductModel');
        $products = $productModel->list();

        $data = [
            'purchases' => $purchases,
            'providers' => $providers,
            'products' => $products
        ];

        $this->view('purchase/index', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            $validacao = new Validador("pt-br");
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :
                $purchase = new \App\models\Purchase();
                $purchase->setIdFuncionario($_SESSION['id']);
                $purchase->setIdProduto($_POST['product']);
                $purchase->setIdFornecedor($_POST['provider']);
                $purchase->setQuantidade($_POST['amount']);
                $purchase->setValor(floatval($_POST['value']));
                $purchase->setData(date("Y-m-d"));

                $productModel = $this->model('ProductModel');
                $product = $productModel->get($purchase->getIdProduto());

                if (is_null($product)) :
                    $erros = ['Produto não encontrado'];
                    $data = ['erros' => $erros];
                    $this->view('purchases', $data);
                else :
                    $purchaseModel = $this->model('PurchaseModel');
                    $purchaseModel->create($purchase);

                    $newQtd = $product->getQuantidadeDisponivel() + $purchase->getQuantidade();
                    $product->setQuantidadeDisponivel($newQtd);
                    $product->setPrecoCompra($purchase->getValor());

                    $productModel->update($product);

                    $messages = ['Compra cadastrada com sucesso'];
                    $data = ['messages' => $messages];
                    Utils::redirect('purchases', $data);
                endif;

            else :
                $erros = $validacao->get_errors_array();
                $data = ['erros' => $erros];
                $this->view('purchases', $data);
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
                $purchaseModel = $this->model('PurchaseModel');
                $oldPurchase = $purchaseModel->get($path['id']);

                if (is_null($oldPurchase)) :
                    $erros = ['Compra não encontrado'];
                    $data = ['erros' => $erros];
                    Utils::redirect('purchases', $data);
                    exit();
                endif;

                $productModel = $this->model('ProductModel');
                $product = $productModel->get($oldPurchase['id_produto']);

                if (is_null($product)) :
                    $erros = ['Produto não encontrado'];
                    $data = ['erros' => $erros];
                    Utils::redirect('purchases', $data);
                else :
                    $qtd = $_POST['amount'];
                    $qtdDiff = $qtd - $oldPurchase['quantidade_compra'];

                    $purchase = new \App\models\Purchase();
                    $purchase->setIdFuncionario($_SESSION['id']);
                    $purchase->setIdProduto($_POST['product']);
                    $purchase->setIdFornecedor($_POST['provider']);
                    $purchase->setQuantidade($qtd);
                    $purchase->setValor(floatval($_POST['value']));
                    $purchase->setData($oldPurchase['data_compra']);
                    $purchase->setId($oldPurchase['id']);

                    $purchaseModel->update($purchase);

                    $newQtd = $product->getQuantidadeDisponivel() + $qtdDiff;
                    $product->setQuantidadeDisponivel($newQtd);
                    $product->setPrecoCompra($purchase->getValor());

                    $productModel->update($product);

                    $messages = ['Compra atualizada com sucesso'];
                    $data = ['messages' => $messages];
                    Utils::redirect('purchases', $data);
                endif;

            else :
                $erros = $validacao->get_errors_array();
                $data = ['erros' => $erros];
                Utils::redirect('purchases', $data);
            endif;
        else :
            Utils::redirect();
        endif;
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $id = $path['id'];

            $purchaseModel = $this->model('PurchaseModel');
            $purchase = $purchaseModel->get($id);

            if (!is_null($purchase)) :
                echo json_encode($purchase);
            else :
                $data = array();
                $data['error'] = 'Compra não encontrada';
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

            $purchaseModel = $this->model('PurchaseModel');
            $purchaseModel->remove($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();
        else :
            Utils::redirect();
        endif;
    }
}