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
        $saleRepository = $this->model('SaleRepository');
        $sales = $saleRepository->list();

        $customerRepository = $this->model('CustomerRepository');
        $customers = $customerRepository->list();

        $productRepository = $this->model('ProductRepository');
        $products = $productRepository->listEnabledForSale();

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
    }

    private function validateSale(Sale &$sale, Product $product)
    {
        if (is_null($product)) :
            $errors = ['Produto não encontrado'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(404, $data);
            return false;
        elseif ($product->isLiberadoVenda() == false) :
            $errors = ['Produto não liberado para venda'];
            $data = ['errors' => $errors];
            Utils::jsonResponse(400, $data);
            return false;
        endif;

        $customerRepository = $this->model('CustomerRepository');
        $customer = $customerRepository->get($sale->getClientId());

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

            $productRepository = $this->model('ProductRepository');
            $product = $productRepository->get($sale->getProductId());

            if ($this->validateSale($sale, $product) == false) :
                exit();
            endif;

            if ($product->getQuantidadeDisponivel() < $sale->getAmount()) :
                $errors = ['O produto selecionado só possui ' .
                    $product->getQuantidadeDisponivel() . ' unidades em estoque'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(400, $data);
                exit();
            endif;

            $sale->setValue($product->getPrecoVenda());

            try {
                $saleRepository = $this->model('SaleRepository');
                $saleRepository->create($sale);

                $newQtd = $product->getQuantidadeDisponivel() - $sale->getAmount();
                $product->setQuantidadeDisponivel($newQtd);

                $productRepository->update($product);

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

            $saleRepository = $this->model('SaleRepository');
            $oldSale = $saleRepository->get($path['id']);

            if (is_null($saleRepository)) :
                $errors = ['Venda não encontrada'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
                exit();
            endif;

            if ($this->validateEdit($oldSale) == false) :
                exit();
            endif;

            $oldProductId = $oldSale->getProductId();
            $oldSaleAmount = $oldSale->getAmount();

            $this->updateModelValues($oldSale, $_PUT);

            $productRepository = $this->model('ProductRepository');
            $product = $productRepository->get($oldSale->getProductId());

            if ($this->validateSale($oldSale, $product) == false) :
                exit();
            endif;

            if ($oldProductId == $product->getId()) :
                $qtdDiff = $oldSale->getAmount() - $oldSaleAmount;
                $newQtd = $product->getQuantidadeDisponivel() - $qtdDiff;
            else :
                $newQtd = $product->getQuantidadeDisponivel() - $oldSale->getAmount();
            endif;

            if ($newQtd < 0) :
                $errors = [$product->getQuantidadeDisponivel() <= 0 ? ('Produto sem estoque') : ('O produto selecionado só possui ' . $product->getQuantidadeDisponivel() . ' unidades em estoque')];
                $data = ['errors' => $errors];
                Utils::jsonResponse(400, $data);
                exit();
            endif;

            try {
                $saleRepository->update($oldSale);

                if ($oldProductId != $product->getId()) :
                    $oldProduct = $productRepository->get($oldProductId);
                    $oldProduct->setQuantidadeDisponivel($oldProduct->getQuantidadeDisponivel() + $oldSaleAmount);
                    $productRepository->update($oldProduct);
                endif;

                $product->setQuantidadeDisponivel($newQtd);
                $productRepository->update($product);

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
            $saleRepository = $this->model('SaleRepository');

            try {
                $sale = $saleRepository->get($path['id']);

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
                $saleRepository = $this->model('SaleRepository');

                $sale = $saleRepository->get($id);
                if (!is_null($sale)) :
                    if ($this->validateEdit($sale) == false) :
                        exit();
                    endif;
                else :
                    $errors = ['Venda não encontrada'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;

                $saleRepository->remove($id);

                $productRepository = $this->model('ProductRepository');
                $product = $productRepository->get($sale->getProductId());
                $product->setQuantidadeDisponivel($product->getQuantidadeDisponivel() + $sale->getAmount());
                $productRepository->update($product);

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

    public function listByUser()
    {
        if (Utils::hasPermission(Role::Vendedor) == false) :
            exit();
        endif;

        $saleRepository = $this->model('SaleRepository');
        $sales = $saleRepository->listByUser($_SESSION['id']);
        Utils::jsonResponse(200, $sales);
    }
}
