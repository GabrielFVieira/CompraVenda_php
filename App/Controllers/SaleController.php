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
        $saleService = $this->service('SaleService');
        $sales = $saleService->list();

        $customerService = $this->service('CustomerService');
        $customers = $customerService->list();

        $productService = $this->service('ProductService');
        $products = $productService->listEnabledForSale();

        $data = [
            'sales' => $sales,
            'customers' => $customers,
            'products' => $products
        ];

        $this->view('sale/index', $data);
    }

    private function updateModelValues(Sale &$model, $data)
    {
        $model->setProductId($data['product']);
        $model->setClientId($data['customer']);
        $model->setAmount($data['amount']);
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

            try {
                $sale = new Sale();
                $this->updateModelValues($sale, $_POST);

                $saleService = $this->service('SaleService');
                $saleService->create($sale);

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
        if (Utils::hasPermission(Role::Vendedor) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if (Utils::validateInputs($_PUT, $this->filters, $this->rules) == false) {
                exit();
            }

            try {
                $sale = new Sale();
                $this->updateModelValues($sale, $_PUT);
                $sale->setId($path['id']);

                $saleService = $this->service('SaleService');
                $saleService->update($sale);

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
            $saleService = $this->service('SaleService');

            try {
                $sale = $saleService->get($path['id']);

                if (!is_null($sale)) :
                    Utils::jsonResponse(200, $sale);
                else :
                    Utils::returnJsonError(404, 'Venda não encontrada');
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
        if (Utils::hasPermission(Role::Vendedor) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            try {
                $id = $data['id'];
                $saleService = $this->service('SaleService');
                $saleService->remove($id);

                Utils::jsonResponse(204);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
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

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            try {
                $saleService = $this->service('SaleService');
                $sales = $saleService->listByUser($_SESSION['id']);

                Utils::jsonResponse(200, $sales);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }
}