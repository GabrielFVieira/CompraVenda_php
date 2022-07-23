<?php

use App\Core\BaseModel;
use App\models\Product;

class ProductModel extends BaseModel
{
    private static function ModelFromDBArray($array)
    {
        $product = new Product();
        $product->setId($array["id"]);
        $product->setNome($array["nome_produto"]);
        $product->setDescricao($array["descricao"]);
        $product->setPrecoCompra($array["preco_compra"]);
        $product->setPrecoVenda($array["preco_venda"]);
        $product->setQuantidadeDisponivel($array["quantidade_disponível"]);
        $product->setLiberadoVenda($array["liberado_venda"]);
        $product->setIdCategoria($array["id_categoria"]);

        if (isset($array["nome_categoria"])) {
            $product->setNomeCategoria($array["nome_categoria"]);
        }

        return $product;
    }

    public function create(Product $product)
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

    public function get(int $id)
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
                return ProductModel::ModelFromDBArray($result);
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao buscar produto: ' . $e->getMessage());
            throw $e;
        }
    }

    public function listEnabledForSale()
    {
        try {
            $sql = "SELECT p.*, c.nome_categoria as nome_categoria FROM produtos p
                        INNER JOIN categorias c
                        ON c.id = p.id_categoria
                        WHERE p.liberado_venda = 'S'
                        ORDER BY p.nome_produto";
            $conn = ProductModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, ProductModel::ModelFromDBArray($value));
                }

                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar produtos: ' . $e->getMessage());
            throw $e;
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

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, ProductModel::ModelFromDBArray($value));
                }

                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar produtos: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Product $product)
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
            error_log('Erro ao atualizar produto: ' . $e->getMessage());
            throw $e;
        }
    }

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM produtos WHERE id = ?";
            $conn = ProductModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            error_log('Erro ao remover produto: ' . $e->getMessage());
            throw $e;
        }
    }
}
