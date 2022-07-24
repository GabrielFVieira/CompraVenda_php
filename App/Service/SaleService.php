<?php

use App\Core\BaseService;
use App\models\Product;
use App\models\Sale;

class SaleService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('SaleRepository');
    }

    private function validateEdit(Sale $sale)
    {
        if ($sale->getEmployeeId() != $_SESSION['id']) :
            throw new Exception('Vendedor não autorizado a editar essa venda');
        endif;
    }

    private function validateSale(Sale &$sale, Product $product)
    {
        if (is_null($product)) :
            throw new Exception('Produto não encontrado');
        elseif ($product->isLiberadoVenda() == false) :
            throw new Exception('Produto não liberado para venda');
        endif;

        $customerService = $this->service('CustomerService');
        $customer = $customerService->get($sale->getClientId());

        if (is_null($customer)) :
            throw new Exception('Cliente não encontrado');
        endif;
    }

    public function create(Sale $sale)
    {
        $sale->setDate(date("Y-m-d"));
        $sale->setEmployeeId($_SESSION['id']);

        $productService = $this->service('ProductService');
        $product = $productService->get($sale->getProductId());

        $this->validateSale($sale, $product);

        if ($product->getQuantidadeDisponivel() < $sale->getAmount()) :
            $msg = 'O produto selecionado só possui ' .
                $product->getQuantidadeDisponivel() . ' unidades em estoque';

            throw new Exception($msg);
        endif;

        $sale->setValue($product->getPrecoVenda());

        try {
            $this->repository->create($sale);

            $newQtd = $product->getQuantidadeDisponivel() - $sale->getAmount();
            $product->setQuantidadeDisponivel($newQtd);

            $productService->update($product);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar venda: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar venda');
        }
    }

    public function update(Sale $sale)
    {
        $oldSale = $this->get($sale->getId());
        if (is_null($oldSale)) :
            throw new Exception('Venda não encontrada');
        endif;

        $this->validateEdit($oldSale);

        $sale->setDate($oldSale->getDate());
        $sale->setEmployeeId($_SESSION['id']);
        $sale->setValue($oldSale->getValue());

        $oldProductId = $oldSale->getProductId();
        $oldSaleAmount = $oldSale->getAmount();

        $productService = $this->service('ProductService');
        $product = $productService->get($sale->getProductId());

        $this->validateSale($sale, $product);

        if ($oldProductId == $product->getId()) :
            $qtdDiff = $sale->getAmount() - $oldSaleAmount;
            $newQtd = $product->getQuantidadeDisponivel() - $qtdDiff;
        else :
            $newQtd = $product->getQuantidadeDisponivel() - $sale->getAmount();
        endif;

        if ($newQtd < 0) :
            $msg = $product->getQuantidadeDisponivel() <= 0 ? ('Produto sem estoque') : ('O produto selecionado só possui ' . $product->getQuantidadeDisponivel() . ' unidades em estoque');
            throw new Exception($msg);
        endif;

        try {
            $this->repository->update($sale);

            if ($oldProductId != $product->getId()) :
                $oldProduct = $productService->get($oldProductId);
                $oldProduct->setQuantidadeDisponivel($oldProduct->getQuantidadeDisponivel() + $oldSaleAmount);
                $productService->update($oldProduct);
            endif;

            $product->setQuantidadeDisponivel($newQtd);
            $productService->update($product);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar venda: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar venda');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar venda: ' . $e->getMessage());
            throw new Exception('Erro ao buscar venda');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar vendas: ' . $e->getMessage());
            throw new Exception('Erro ao listar vendas');
        }
    }

    public function listByUser($userId)
    {
        try {
            return $this->repository->listByUser($userId);
        } catch (\PDOException $e) {
            error_log('Erro ao listar vendas: ' . $e->getMessage());
            throw new Exception('Erro ao listar vendas');
        }
    }

    public function listSalesByDay()
    {
        try {
            return $this->repository->listSalesByDay();
        } catch (\PDOException $e) {
            error_log('Erro ao listar vendas: ' . $e->getMessage());
            throw new Exception('Erro ao listar vendas');
        }
    }

    public function remove(int $id)
    {
        try {
            $sale = $this->get($id);
            if (is_null($sale)) :
                throw new Exception('Venda não encontrada');
            endif;

            $this->validateEdit($sale) == false;

            $this->repository->remove($id);

            $productService = $this->service('ProductService');
            $product = $productService->get($sale->getProductId());
            $product->setQuantidadeDisponivel($product->getQuantidadeDisponivel() + $sale->getAmount());

            $productService->update($product);
        } catch (\PDOException $e) {
            error_log('Erro ao remover venda: ' . $e->getMessage());
            throw new Exception('Erro ao remover venda');
        }
    }
}