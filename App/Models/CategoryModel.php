<?php

use App\Core\BaseModel;

class CategoryModel extends BaseModel
{
    public function create($category)
    {
        try {
            $sql = "INSERT INTO categorias(nome_categoria) VALUES (?)";
            $conn = CategoryModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $category->getNome());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Erro ao cadastrar compra: ' . $e->getMessage());
        }
    }

    public function update($category)
    {
        try {
            $sql = "UPDATE categorias SET nome_categoria = ? WHERE id = ?";
            $conn = CategoryModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $category->getNome());
            $stmt->bindValue(2, $category->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Erro ao atualizar categoria: ' . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "Select * from categorias where id = ? limit 1";
            $conn = CategoryModel::getConexao();

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
            $sql = "SELECT * from categorias";
            $conn = CategoryModel::getConexao();

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
            $sql = "DELETE FROM categorias WHERE id = ?";
            $conn = CategoryModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }
}