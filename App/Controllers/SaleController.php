<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\models\Product;
use \App\models\Sale;
use App\models\Role;
use App\Utils\Utils;

class SaleController extends BaseController
{
    protected $filters = [
        'product' =>  'sanitize_numbers',
        'customer' => 'sanitize_numbers',
        'amount' => 'sanitize_numbers',
    ];

    protected $rules = [
        'product' => 'required|integer',
        'customer' => 'required|integer',
        'amount'    => 'required|integer|min_numeric,1',
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
        $saleModel = $this->model('SaleModel');
        $sales = $saleModel->list();

        $customerModel = $this->model('CustomerModel');
        $customers = $customerModel->list();

        $productModel = $this->model('ProductModel');
        $products = $productModel->listEnabledForSale();

        $data = [
            'sales' => $sales,
            'customers' => $customers,
            'products' => $products
        ];

        $this->view('sale/index', $data);
    }

    private function updateModelValues(Sale &$model, $data)
    {
        $model->setEmployeeId($_SESSION['id']);
        $model->setProductId($data['product']);
        $model->setClientId($data['customer']);
        $model->setAmount($data['amount']);

        // $value = str_replace(',', '.', $data['value']);
        // $model->setValue(floatval($value));
    }

    private function validateSale(Sale &$sale, Product $product)
    {
        if (is_null($product)) :
            $errors = ['Produto não encontrado'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(404, $data);
            return false;
        elseif ($product->getQuantidadeDisponivel() <= 0) :
            $errors = ['Produto sem estoque'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(400, $data);
            return false;
        elseif ($product->isLiberadoVenda() == false) :
            $errors = ['Produto não liberado para venda'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(400, $data);
            return false;
        endif;

        $customerModel = $this->model('CustomerModel');
        $customer = $customerModel->get($sale->getClientId());

        if (is_null($customer)) :
            $errors = ['Cliente não encontrado'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(404, $data);
            return false;
        endif;

        return true;
    }

    public function create()
    {
        if (Utils::hasPermission(Role::Vendedor) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            if (Utils::validateInputs($_POST, $this->filters, $this->rules) == false) {
                exit();
            }

            $sale = new Sale();
            $this->updateModelValues($sale, $_POST);
            $sale->setDate(date("Y-m-d"));

            $productModel = $this->model('ProductModel');
            $product = $productModel->get($sale->getProductId());

            if ($this->validateSale($sale, $product) == false) :
                exit();
            endif;

            $sale->setValue($product->getPrecoVenda());

            try {
                $saleModel = $this->model('SaleModel');
                $saleModel->create($sale);

                $newQtd = $product->getQuantidadeDisponivel() - $sale->getAmount();
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

    private function validateEdit(Sale $sale)
    {
        if ($sale->getEmployeeId() != $_SESSION['id']) :
            $errors = ['Vendedor não autorizado a editar essa venda'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(403, $data);
            return false;
        endif;

        return true;
    }

    public function update($path)
    {
        if (Utils::hasPermission(Role::Vendedor) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if (Utils::validateInputs($_PUT, $this->filters, $this->rules) == false) {
                exit();
            }

            $saleModel = $this->model('SaleModel');
            $oldSale = $saleModel->get($path['id']);

            if (is_null($saleModel)) :
                $errors = ['Venda não encontrada'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
                exit();
            endif;

            if ($this->validateEdit($oldSale) == false) :
                exit();
            endif;

            $oldProductId = $oldSale->getProductId();
            $qtd = $_PUT['amount'];
            $qtdDiff = $qtd - $oldSale->getAmount();

            $this->updateModelValues($oldSale, $_PUT);

            $productModel = $this->model('ProductModel');
            $product = $productModel->get($oldSale->getProductId());

            if ($oldProductId != $oldSale->getProductId()) :
                $oldSale->setValue($product->getPrecoVenda());
            endif;

            if ($this->validateSale($oldSale, $product) == false) :
                exit();
            endif;

            try {
                $saleModel->update($oldSale);

                $newQtd = $product->getQuantidadeDisponivel() - $qtdDiff;
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
            $saleModel = $this->model('SaleModel');

            try {
                $sale = $saleModel->get($path['id']);

                if (!is_null($sale)) :
                    Utils::jsonResponse(200, $sale);
                else :
                    $errors = ['Venda não encontrada'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao buscar venda'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }
        else :
            Utils::redirect();
        endif;
    }

    public function remove($data)
    {
        if (Utils::hasPermission(Role::Vendedor) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            try {
                $id = $data['id'];
                $saleModel = $this->model('SaleModel');

                $sale = $saleModel->get($id);
                if (!is_null($sale)) :
                    if ($this->validateEdit($sale) == false) :
                        exit();
                    endif;
                else :
                    $errors = ['Venda não encontrada'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;

                $saleModel->remove($id);
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover venda'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::redirect();
        endif;
    }
}
