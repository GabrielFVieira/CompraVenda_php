<?php

use App\Core\BaseService;
use App\models\Provider;

class ProviderService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('ProviderRepository');
    }

    public function create(Provider $provider)
    {
        try {
            $this->repository->create($provider);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar fornecedor: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar fornecedor');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar fornecedor: ' . $e->getMessage());
            throw new Exception('Erro ao buscar fornecedor');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar fornecedores: ' . $e->getMessage());
            throw new Exception('Erro ao listar fornecedores');
        }
    }

    public function remove(int $id)
    {
        try {
            $this->repository->remove($id);
        } catch (\PDOException $e) {
            error_log('Erro ao remover fornecedor: ' . $e->getMessage());
            throw new Exception('Erro ao remover fornecedor');
        }
    }

    public function update(Provider $provider)
    {
        $oldProvider = $this->get($provider->getId());
        if (is_null($oldProvider)) :
            throw new Exception('Fornecedor não encontrado');
        endif;

        try {
            $this->repository->update($provider);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar fornecedor: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar fornecedor');
        }
    }
}