<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Customer;
use App\models\Role;

class CustomerController extends BaseController
{
    protected $filters = [
        'name' => 'trim|sanitize_string',
        'cpf' => 'trim|sanitize_string',
        'address' => 'trim|sanitize_string',
        'district' => 'trim|sanitize_string',
        'city' => 'trim|sanitize_string',
        'uf' => 'trim|sanitize_string',
        'cep' => 'trim|sanitize_numbers',
        'phone' => 'trim|sanitize_string',
        'email' => 'trim|sanitize_string',
    ];

    protected $rules = [
        'name' => 'required|max_len,50',
        'cpf' => 'required|max_len,14',
        'address' => 'required|max_len,50',
        'district' => 'required|max_len,50',
        'city' => 'required|max_len,50',
        'uf' => 'required|max_len,2',
        'cep' => 'required|max_len,8',
        'phone' => 'required|max_len,20',
        'email' => 'required|max_len,50',
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
        $customerService = $this->service('CustomerService');
        $customers = $customerService->list();

        $data = [
            'customers' => $customers
        ];

        $this->view('customer/index', $data);
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $customerService = $this->service('CustomerService');

            try {
                $customer = $customerService->get($path['id']);

                if (!is_null($customer)) :
                    Utils::jsonResponse(200, $customer);
                else :
                    Utils::returnJsonError(500, 'Cliente não encontrado');
                endif;
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }

    private function updateModelValues(Customer &$model, $data)
    {
        $model->setNome($data['name']);
        $model->setCPF($data['cpf']);
        $model->setEndereco($data['address']);
        $model->setBairro($data['district']);
        $model->setCidade($data['city']);
        $model->setUF($data['uf']);

        $cep = preg_replace('/[^0-9]/', '', $data['cep']);
        $model->setCEP($cep);

        $model->setTelefone($data['phone']);
        $model->setEmail($data['email']);
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

            $customer = new Customer();
            $this->updateModelValues($customer, $_POST);

            try {
                $customerService = $this->service('CustomerService');
                $customerService->create($customer);
                Utils::jsonResponse(201);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
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
                $customer = new Customer();
                $this->updateModelValues($customer, $_PUT);
                $customer->setId($path['id']);

                $customerService = $this->service('CustomerService');
                $customerService->update($customer);

                Utils::jsonResponse();
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::jsonResponse(405);
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
                $customerService = $this->service('CustomerService');
                $customerService->remove($id);
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