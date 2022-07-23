<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use \App\models\Purchase;
use App\models\Role;
use App\Utils\Utils;

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
            exit();
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

    private function updateModelValues(Purchase &$model, $data)
    {
        $model->setIdFuncionario($_SESSION['id']);
        $model->setIdProduto($data['product']);
        $model->setIdFornecedor($data['provider']);
        $model->setQuantidade($data['amount']);
        $model->setValor(floatval($data['value']));
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

            $purchase = new Purchase();
            $this->updateModelValues($purchase, $_POST);
            $purchase->setData(date("Y-m-d"));

            $productModel = $this->model('ProductModel');
            $product = $productModel->get($purchase->getIdProduto());

            if (is_null($product)) :
                $errors = ['Produto não encontrado'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(404, $data);
                exit();
            endif;


            try {
                $purchaseModel = $this->model('PurchaseModel');
                $purchaseModel->create($purchase);

                $newQtd = $product->getQuantidadeDisponivel() + $purchase->getQuantidade();
                $product->setQuantidadeDisponivel($newQtd);
                $product->setPrecoCompra($purchase->getValor());

                $productModel->update($product);

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

    private function validateEdit(Purchase $purchase)
    {
        if ($purchase->getIdFuncionario() != $_SESSION['id']) :
            $errors = ['Comprador não autorizado a editar essa compra'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(403, $data);
            return false;
        endif;

        return true;
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

            $purchaseModel = $this->model('PurchaseModel');
            $oldPurchase = $purchaseModel->get($path['id']);

            if (is_null($oldPurchase)) :
                $errors = ['Compra não encontrada'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
                exit();
            endif;

            if ($this->validateEdit($oldPurchase) == false) :
                exit();
            endif;


            $oldProductId = $oldPurchase->getIdProduto();
            $oldPurchaseAmount = $oldPurchase->getQuantidade();

            $this->updateModelValues($oldPurchase, $_PUT);

            $productModel = $this->model('ProductModel');
            $product = $productModel->get($oldPurchase->getIdProduto());

            if (is_null($product)) :
                $errors = ['Produto não encontrado'];
                $data = ['erros' => $errors];
                Utils::jsonResponse(500, $data);
                exit();
            endif;

            if ($oldProductId == $product->getId()) :
                $qtdDiff = $oldPurchase->getQuantidade() - $oldPurchaseAmount;
                $newQtd = $product->getQuantidadeDisponivel() + $qtdDiff;
            else :
                $newQtd = $product->getQuantidadeDisponivel() + $oldPurchase->getQuantidade();
            endif;

            try {
                $purchaseModel->update($oldPurchase);

                if ($oldProductId != $product->getId()) :
                    $oldProduct = $productModel->get($oldProductId);
                    $oldProduct->setQuantidadeDisponivel($oldProduct->getQuantidadeDisponivel() - $oldPurchaseAmount);
                    $productModel->update($oldProduct);
                endif;

                $product->setQuantidadeDisponivel($newQtd);
                $productModel->update($product);

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
            $purchaseModel = $this->model('PurchaseModel');

            try {
                $purchase = $purchaseModel->get($path['id']);

                if (!is_null($purchase)) :
                    Utils::jsonResponse(200, $purchase);
                else :
                    $errors = ['Compra não encontrada'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao buscar compra'];
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
                $purchaseModel = $this->model('PurchaseModel');

                $purchase = $purchaseModel->get($id);
                if (!is_null($purchase)) :
                    if ($this->validateEdit($purchase) == false) :
                        exit();
                    endif;
                else :
                    $errors = ['Compra não encontrada'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;

                $purchaseModel->remove($id);

                $productModel = $this->model('ProductModel');
                $product = $productModel->get($purchase->getIdProduto());
                $product->setQuantidadeDisponivel($product->getQuantidadeDisponivel() - $purchase->getQuantidade());
                $productModel->update($product);

                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover compra'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::redirect();
        endif;
    }
}
