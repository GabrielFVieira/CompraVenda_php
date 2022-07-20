<?php

use App\Core\BaseModel;
use App\models\Sale;

class SaleModel extends BaseModel
{
    private static function ModelFromDBArray($array)
    {
        $sale = new Sale();
        $sale->setId($array["id"]);
        $sale->setAmount($array["quantidade_venda"]);
        $sale->setDate($array["data_venda"]);
        $sale->setValue($array["valor_venda"]);
        $sale->setClientId($array["id_cliente"]);
        $sale->setProductId($array["id_produto"]);
        $sale->setEmployeeId($array["id_funcionario"]);

        if (isset($array["nome_cliente"])) {
            $sale->setClientName($array["nome_cliente"]);
        }

        if (isset($array["nome_produto"])) {
            $sale->setProductName($array["nome_produto"]);
        }

        if (isset($array["nome_funcionario"])) {
            $sale->setEmployeeName($array["nome_funcionario"]);
        }

        return $sale;
    }

    public function create(Sale $sale)
    {
        try {
            $sql = "INSERT INTO vendas(quantidade_venda,data_venda,valor_venda,
                    id_cliente,id_produto,id_funcionario) VALUES (?,?,?,?,?,?)";
            $conn = SaleModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $sale->getAmount());
            $stmt->bindValue(2, $sale->getDate());
            $stmt->bindValue(3, $sale->getValue());
            $stmt->bindValue(4, $sale->getClientId());
            $stmt->bindValue(5, $sale->getProductId());
            $stmt->bindValue(6, $sale->getEmployeeId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar venda: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Sale $sale)
    {
        try {
            $sql = "UPDATE vendas SET 
                    quantidade_venda = ?, data_venda = ?,
                    valor_venda = ?, id_cliente = ?,
                    id_produto = ?, id_funcionario = ?
                    WHERE id = ?";
            $conn = SaleModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $sale->getAmount());
            $stmt->bindValue(2, $sale->getDate());
            $stmt->bindValue(3, $sale->getValue());
            $stmt->bindValue(4, $sale->getClientId());
            $stmt->bindValue(5, $sale->getProductId());
            $stmt->bindValue(6, $sale->getEmployeeId());
            $stmt->bindValue(7, $sale->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao atualizar venda: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "Select * from vendas where id = ? limit 1";
            $conn = SaleModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return SaleModel::ModelFromDBArray($result);
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
            $sql = "SELECT v.*, f.nome as nome_funcionario, c.nome as nome_cliente, 
                    p.nome_produto as nome_produto FROM vendas v
                    INNER JOIN funcionarios f
                    on f.id = v.id_funcionario
                    INNER JOIN clientes c
                    on c.id = v.id_cliente
                    INNER JOIN produtos p
                    on p.id = v.id_produto
                    order by data_venda desc";
            $conn = SaleModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, SaleModel::ModelFromDBArray($value));
                }

                return $result;

            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar vendas: ' . $e->getMessage());
            throw $e;
        }
    }

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM vendas WHERE id = ?";
            $conn = SaleModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            error_log('Erro ao remover venda: ' . $e->getMessage());
            throw $e;
        }
    }
}
