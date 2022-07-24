<?php

use App\Core\BaseService;
use App\models\Purchase;

class PurchaseService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('PurchaseRepository');
    }

    public function create(Purchase $purchase)
    {
        try {
            $purchase->setData(date("Y-m-d"));
            $purchase->setIdFuncionario($_SESSION['id']);

            $productService = $this->service('ProductService');
            $product = $productService->get($purchase->getIdProduto());

            if (is_null($product)) :
                throw new Exception('Produto não encontrado');
            endif;

            $this->repository->create($purchase);

            $newQtd = $product->getQuantidadeDisponivel() + $purchase->getQuantidade();
            $product->setQuantidadeDisponivel($newQtd);
            $product->setPrecoCompra($purchase->getValor());

            $productService->update($product);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar compra: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar compra');
        }
    }

    public function update(Purchase $purchase)
    {
        $oldPurchase = $this->get($purchase->getId());
        if (is_null($oldPurchase)) :
            throw new Exception('Compra não encontrada');
        endif;

        $this->validateEdit($oldPurchase);

        $purchase->setData($oldPurchase->getData());
        $purchase->setIdFuncionario($_SESSION['id']);

        $oldProductId = $oldPurchase->getIdProduto();
        $oldPurchaseAmount = $oldPurchase->getQuantidade();

        $productService = $this->service('ProductService');
        $product = $productService->get($purchase->getIdProduto());

        if (is_null($product)) :
            throw new Exception('Produto não encontrado');
        endif;

        if ($oldProductId == $product->getId()) :
            $qtdDiff = $purchase->getQuantidade() - $oldPurchaseAmount;
            $newQtd = $product->getQuantidadeDisponivel() + $qtdDiff;
        else :
            $newQtd = $product->getQuantidadeDisponivel() + $purchase->getQuantidade();
        endif;

        try {
            $this->repository->update($purchase);

            if ($oldProductId != $product->getId()) :
                $oldProduct = $productService->get($oldProductId);
                $oldProduct->setQuantidadeDisponivel($oldProduct->getQuantidadeDisponivel() - $oldPurchaseAmount);
                $productService->update($oldProduct);
            endif;

            $product->setQuantidadeDisponivel($newQtd);
            $product->setPrecoCompra($purchase->getValor());

            $productService->update($product);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar compra: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar compra');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar compra: ' . $e->getMessage());
            throw new Exception('Erro ao buscar compra');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar compras: ' . $e->getMessage());
            throw new Exception('Erro ao listar compras');
        }
    }

    public function listByUser($userId)
    {
        try {
            return $this->repository->listByUser($userId);
        } catch (\PDOException $e) {
            error_log('Erro ao listar compras: ' . $e->getMessage());
            throw new Exception('Erro ao listar compras');
        }
    }

    public function remove(int $id)
    {
        try {
            $purchase = $this->get($id);
            if (is_null($purchase)) :
                throw new Exception('Compra não encontrada');
            endif;

            $this->validateEdit($purchase) == false;

            $this->repository->remove($id);

            $productService = $this->service('ProductService');
            $product = $productService->get($purchase->getIdProduto());
            $product->setQuantidadeDisponivel($product->getQuantidadeDisponivel() - $purchase->getQuantidade());

            $productService->update($product);
        } catch (\PDOException $e) {
            error_log('Erro ao remover compra: ' . $e->getMessage());
            throw new Exception('Erro ao remover compra');
        }
    }

    private function validateEdit(Purchase $purchase)
    {
        if ($purchase->getIdFuncionario() != $_SESSION['id']) :
            throw new Exception('Vendedor não autorizado a editar essa venda');
        endif;
    }
}