<?php

use App\Core\BaseService;
use App\models\Product;

class ProductService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('ProductRepository');
    }

    public function create(Product $product)
    {
        try {
            $this->repository->create($product);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar produto: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar produto');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar produto: ' . $e->getMessage());
            throw new Exception('Erro ao buscar produto');
        }
    }

    public function listEnabledForSale()
    {
        try {
            return $this->repository->listEnabledForSale();
        } catch (\PDOException $e) {
            error_log('Erro ao listar produtos: ' . $e->getMessage());
            throw new Exception('Erro ao listar produtos');
        }
    }

    public function listEnabledForSaleWithAvailableAmount()
    {
        try {
            return $this->repository->listEnabledForSaleWithAvailableAmount();
        } catch (\PDOException $e) {
            error_log('Erro ao listar produtos: ' . $e->getMessage());
            throw new Exception('Erro ao listar produtos');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar produtos: ' . $e->getMessage());
            throw new Exception('Erro ao listar produtos');
        }
    }

    public function update(Product $product)
    {
        $oldProduct = $this->get($product->getId());
        if (is_null($oldProduct)) :
            throw new Exception('Produto não encontrado');
        endif;

        try {
            $this->repository->update($product);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar produto: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar produto');
        }
    }

    public function remove(int $id)
    {
        try {
            $this->repository->remove($id);
        } catch (\PDOException $e) {
            error_log('Erro ao remover produto: ' . $e->getMessage());
            throw new Exception('Erro ao remover produto');
        }
    }
}