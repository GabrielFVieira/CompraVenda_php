<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Provider;

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
        $providerModel = $this->model('ProviderModel');
        $providers = $providerModel->list();

        $data = [
            'providers' => $providers
        ];

        $this->view('provider/index', $data);
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $providerModel = $this->model('ProviderModel');

            try {
                $provider = $providerModel->get($path['id']);

                if (!is_null($provider)) :
                    Utils::jsonResponse(200, $provider);
                else :
                    $errors = ['Fornecedor não encontrado'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao buscar fornecedor'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            if (Utils::validateInputs($_POST, $this->filters, $this->rules) == false) {
                exit();
            }

            $provider = new Provider();
            $this->updateModelValues($provider, $_POST);

            try {
                $providerModel = $this->model('ProviderModel');
                $providerModel->create($provider);
                Utils::jsonResponse(201);
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }
        else :
            Utils::redirect();
        endif;
    }

    public function update($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if (Utils::validateInputs($_PUT, $this->filters, $this->rules) == false) {
                exit();
            }

            $providerModel = $this->model('ProviderModel');
            $oldProvider = $providerModel->get($path['id']);

            if (is_null($oldProvider)) :
                $errors = ['Fornecedor não encontrado'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(404, $data);
                exit();
            endif;

            $this->updateModelValues($oldProvider, $_PUT);

            try {
                $providerModel->update($oldProvider);
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

    public function remove($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            try {
                $id = $data['id'];
                $providerModel = $this->model('ProviderModel');
                $providerModel->remove($id);
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover fornecedor'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }
}