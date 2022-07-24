<?php

use App\Core\BaseService;
use App\models\Customer;

class CustomerService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('CustomerRepository');
    }

    public function create(Customer $customer)
    {
        try {
            $this->repository->create($customer);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar cliente: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar cliente');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar cliente: ' . $e->getMessage());
            throw new Exception('Erro ao buscar cliente');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar clientes: ' . $e->getMessage());
            throw new Exception('Erro ao listar clientes');
        }
    }

    public function remove(int $id)
    {
        try {
            $this->repository->remove($id);
        } catch (\PDOException $e) {
            error_log('Erro ao remover cliente: ' . $e->getMessage());
            throw new Exception('Erro ao remover cliente');
        }
    }

    public function update(Customer $customer)
    {
        $oldCustomer = $this->get($customer->getId());
        if (is_null($oldCustomer)) :
            throw new Exception('Cliente não encontrado');
        endif;

        try {
            $this->repository->update($customer);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar cliente: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar cliente');
        }
    }
}