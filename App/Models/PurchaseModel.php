<?php

use App\Core\BaseModel;

class PurchaseModel extends BaseModel
{
    public function create($purchase)
    {
        try {
            $sql = "INSERT INTO compras(quantidade_compra,data_compra,valor_compra,
                    id_fornecedor,id_produto,id_funcionario) VALUES (?,?,?,?,?,?)";
            $conn = PurchaseModel::getConexao();

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
            die('Erro ao cadastrar compra: ' . $e->getMessage());
        }
    }

    public function update($purchase)
    {
        try {
            $sql = "UPDATE compras SET 
                    quantidade_compra = ?, data_compra = ?,
                    valor_compra = ?, id_fornecedor = ?,
                    id_produto = ?, id_funcionario = ?
                    WHERE id = ?";
            $conn = PurchaseModel::getConexao();

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
            die('Erro ao atualizar compra: ' . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "Select * from compras where id = ? limit 1";
            $conn = PurchaseModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
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
            $conn = PurchaseModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $resultset;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            $sql = "DELETE FROM compras WHERE id = ?";
            $conn = PurchaseModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }
}