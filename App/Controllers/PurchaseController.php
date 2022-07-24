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
        $purchaseService = $this->service('PurchaseService');
        $purchases = $purchaseService->list();

        $providerService = $this->service('ProviderService');
        $providers = $providerService->list();

        $productService = $this->service('ProductService');
        $products = $productService->list();

        $data = [
            'purchases' => $purchases,
            'providers' => $providers,
            'products' => $products
        ];

        $this->view('purchase/index', $data);
    }

    private function updateModelValues(Purchase &$model, $data)
    {
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

            try {
                $purchase = new Purchase();
                $this->updateModelValues($purchase, $_POST);

                $purchaseService = $this->service('PurchaseService');
                $purchaseService->create($purchase);

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
                $purchase = new Purchase();
                $this->updateModelValues($purchase, $_PUT);
                $purchase->setId($path['id']);

                $purchaseService = $this->service('PurchaseService');
                $purchaseService->update($purchase);

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
            $purchaseService = $this->service('PurchaseService');

            try {
                $purchase = $purchaseService->get($path['id']);

                if (!is_null($purchase)) :
                    Utils::jsonResponse(200, $purchase);
                else :
                    Utils::returnJsonError(404, 'Compra não encontrada');
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
                $purchaseService = $this->service('PurchaseService');
                $purchaseService->remove($id);

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
        if (Utils::hasPermission(Role::Comprador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            try {
                $purchaseService = $this->service('PurchaseService');
                $purchases = $purchaseService->listByUser($_SESSION['id']);

                Utils::jsonResponse(200, $purchases);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }
}