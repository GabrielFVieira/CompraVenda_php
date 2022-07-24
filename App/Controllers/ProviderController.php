<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Provider;
use App\models\Role;

class ProviderController extends BaseController
{
    protected $filters = [
        'corporateName' => 'trim|sanitize_string',
        'cnpj' => 'trim|sanitize_string',
        'address' => 'trim|sanitize_string',
        'district' => 'trim|sanitize_string',
        'city' => 'trim|sanitize_string',
        'uf' => 'trim|sanitize_string',
        'cep' => 'trim|sanitize_string',
        'phone' => 'trim|sanitize_string',
        'email' => 'trim|sanitize_string',
    ];

    protected $rules = [
        'corporateName' => 'required|max_len,50',
        'cnpj' => 'required|max_len,18',
        'address' => 'required|max_len,50',
        'district' => 'required|max_len,50',
        'city' => 'required|max_len,50',
        'uf' => 'required|max_len,2',
        'cep' => 'required|max_len,9',
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
        $providerService = $this->service('ProviderService');
        $providers = $providerService->list();

        $data = [
            'providers' => $providers
        ];

        $this->view('provider/index', $data);
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $providerService = $this->service('ProviderService');

            try {
                $provider = $providerService->get($path['id']);

                if (!is_null($provider)) :
                    Utils::jsonResponse(200, $provider);
                else :
                    Utils::returnJsonError(404, 'Fornecedor não encontrado');
                endif;
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }

    private function updateModelValues(Provider &$model, $data)
    {
        $model->setRazaoSocial($data['corporateName']);
        $model->setCNPJ($data['cnpj']);
        $model->setEndereco($data['address']);
        $model->setBairro($data['district']);
        $model->setCidade($data['city']);
        $model->setUF($data['uf']);
        $model->setCEP($data['cep']);
        $model->setTelefone($data['phone']);
        $model->setEmail($data['email']);
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
                $provider = new Provider();
                $this->updateModelValues($provider, $_POST);

                $providerService = $this->service('ProviderService');
                $providerService->create($provider);
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
        if (Utils::hasPermission(Role::Comprador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if (Utils::validateInputs($_PUT, $this->filters, $this->rules) == false) {
                exit();
            }

            try {
                $provider = new Provider();
                $this->updateModelValues($provider, $_PUT);
                $provider->setId($path['id']);

                $providerService = $this->service('ProviderService');
                $providerService->update($provider);
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
        if (Utils::hasPermission(Role::Comprador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            try {
                $id = $data['id'];
                $providerService = $this->service('ProviderService');
                $providerService->remove($id);
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