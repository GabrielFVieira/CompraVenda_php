<?php

use App\Core\BaseModel;
use App\models\Purchase;

class PurchaseRepository extends BaseModel
{
    private static function ModelFromDBArray($array)
    {
        $product = new Purchase();
        $product->setId($array["id"]);
        $product->setQuantidade($array["quantidade_compra"]);
        $product->setData($array["data_compra"]);
        $product->setValor($array["valor_compra"]);
        $product->setIdFornecedor($array["id_fornecedor"]);
        $product->setIdProduto($array["id_produto"]);
        $product->setIdFuncionario($array["id_funcionario"]);

        if (isset($array["fornecedor"])) {
            $product->setNomeFornecedor($array["fornecedor"]);
        }

        if (isset($array["nome_produto"])) {
            $product->setNomeProduto($array["nome_produto"]);
        }

        if (isset($array["nome_funcionario"])) {
            $product->setNomeFuncionario($array["nome_funcionario"]);
        }

        return $product;
    }

    public function create(Purchase $purchase)
    {
        try {
            $sql = "INSERT INTO compras(quantidade_compra,data_compra,valor_compra,
                    id_fornecedor,id_produto,id_funcionario) VALUES (?,?,?,?,?,?)";
            $conn = PurchaseRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $purchase->getQuantidade());
            $stmt->bindValue(2, $purchase->getData());
            $stmt->bindValue(3, $purchase->getValor());
            $stmt->bindValue(4, $purchase->getIdFornecedor());
            $stmt->bindValue(5, $purchase->getIdProduto());
            $stmt->bindValue(6, $purchase->getIdFuncionario());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar compra: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Purchase $purchase)
    {
        try {
            $sql = "UPDATE compras SET 
                    quantidade_compra = ?, data_compra = ?,
                    valor_compra = ?, id_fornecedor = ?,
                    id_produto = ?, id_funcionario = ?
                    WHERE id = ?";
            $conn = PurchaseRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $purchase->getQuantidade());
            $stmt->bindValue(2, $purchase->getData());
            $stmt->bindValue(3, $purchase->getValor());
            $stmt->bindValue(4, $purchase->getIdFornecedor());
            $stmt->bindValue(5, $purchase->getIdProduto());
            $stmt->bindValue(6, $purchase->getIdFuncionario());
            $stmt->bindValue(7, $purchase->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao atualizar compra: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "Select * from compras where id = ? limit 1";
            $conn = PurchaseRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return PurchaseRepository::ModelFromDBArray($result);
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao buscar compra: ' . $e->getMessage());
            throw $e;
        }
    }

    public function list()
    {
        try {
            $sql = "SELECT c.*, f.nome as nome_funcionario, f2.razao_social as fornecedor, 
                    p.nome_produto as nome_produto FROM compras c
                    INNER JOIN funcionarios f
                    on f.id = c.id_funcionario
                    INNER JOIN fornecedores f2
                    on f2.id = c.id_fornecedor
                    INNER JOIN produtos p
                    on p.id = c.id_produto
                    order by data_compra desc";
            $conn = PurchaseRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, PurchaseRepository::ModelFromDBArray($value));
                }

                return $result;

            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar compras: ' . $e->getMessage());
            throw $e;
        }
    }

    public function listByUser($userId)
    {
        try {
            $sql = "SELECT c.*, f.nome as nome_funcionario, f2.razao_social as fornecedor, 
                    p.nome_produto as nome_produto FROM compras c
                    INNER JOIN funcionarios f
                    on f.id = c.id_funcionario
                    INNER JOIN fornecedores f2
                    on f2.id = c.id_fornecedor
                    INNER JOIN produtos p
                    on p.id = c.id_produto
                    WHERE c.id_funcionario = ?
                    order by data_compra desc";
            $conn = PurchaseRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $userId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, PurchaseRepository::ModelFromDBArray($value));
                }

                return $result;

            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar compras: ' . $e->getMessage());
            throw $e;
        }
    }

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM compras WHERE id = ?";
            $conn = PurchaseRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            error_log('Erro ao remover compra: ' . $e->getMessage());
            throw $e;
        }
    }
}