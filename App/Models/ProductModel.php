<?php

use App\Core\BaseModel;

class ProductModel extends BaseModel
{
    public function create($product)
    {
        try {
            $sql = "INSERT INTO produtos(nome_produto,descricao,preco_compra,
                    preco_venda,quantidade_disponível,liberado_venda,id_categoria) VALUES (?,?,?,?,?,?,?)";
            $conn = ProductModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $product->getNome());
            $stmt->bindValue(2, $product->getDescricao());
            $stmt->bindValue(3, $product->getPrecoCompra());
            $stmt->bindValue(4, $product->getPrecoVenda());
            $stmt->bindValue(5, $product->getQuantidadeDisponivel());
            $stmt->bindValue(6, $product->getLiberadoVenda());
            $stmt->bindValue(7, $product->getIdCategoria());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Erro ao cadastrar produto: ' . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "Select * from produtos where id = ? limit 1";
            $conn = ProductModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];

                $product = new \App\models\Product();
                $product->setId($result["id"]);
                $product->setNome($result["nome_produto"]);
                $product->setDescricao($result["descricao"]);
                $product->setPrecoCompra($result["preco_compra"]);
                $product->setPrecoVenda($result["preco_venda"]);
                $product->setQuantidadeDisponivel($result["quantidade_disponível"]);
                $product->setLiberadoVenda($result["liberado_venda"]);
                $product->setIdCategoria($result["id_categoria"]);

                return $product;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }

    public function getRaw($id)
    {
        try {
            $sql = "Select * from produtos where id = ? limit 1";
            $conn = ProductModel::getConexao();

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
            $sql = "SELECT p.*, c.nome_categoria as nome_categoria FROM produtos p
                    INNER JOIN categorias c
                    ON c.id = p.id_categoria 
                    ORDER BY nome_produto";
            $conn = ProductModel::getConexao();

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

    public function update($product)
    {
        try {
            $sql = "UPDATE produtos SET
                    nome_produto = ?, descricao = ?, 
                    preco_compra = ?, preco_venda = ?,
                    quantidade_disponível = ?, liberado_venda = ?, 
                    id_categoria = ?
                    WHERE id = ?";
            $conn = ProductModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $product->getNome());
            $stmt->bindValue(2, $product->getDescricao());
            $stmt->bindValue(3, $product->getPrecoCompra());
            $stmt->bindValue(4, $product->getPrecoVenda());
            $stmt->bindValue(5, $product->getQuantidadeDisponivel());
            $stmt->bindValue(6, $product->getLiberadoVenda());
            $stmt->bindValue(7, $product->getIdCategoria());
            $stmt->bindValue(8, $product->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            $sql = "DELETE FROM produtos WHERE id = ?";
            $conn = ProductModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }
}