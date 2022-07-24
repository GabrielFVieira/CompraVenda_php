<?php

use App\Core\BaseRepository;
use App\models\Category;

class CategoryRepository extends BaseRepository
{
    private static function ModelFromDBArray($array)
    {
        $category = new Category();
        $category->setId($array['id']);
        $category->setNome($array['nome_categoria']);
        return $category;
    }

    public function create(Category $category)
    {
        try {
            $sql = "INSERT INTO categorias(nome_categoria) VALUES (?)";
            $conn = CategoryRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $category->getNome());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function update(Category $category)
    {
        try {
            $sql = "UPDATE categorias SET nome_categoria = ? WHERE id = ?";
            $conn = CategoryRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $category->getNome());
            $stmt->bindValue(2, $category->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "Select * from categorias where id = ? limit 1";
            $conn = CategoryRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return CategoryRepository::ModelFromDBArray($result);
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function list()
    {
        try {
            $sql = "SELECT * from categorias";
            $conn = CategoryRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, CategoryRepository::ModelFromDBArray($value));
                }

                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function remove($id)
    {
        try {
            $sql = "DELETE FROM categorias WHERE id = ?";
            $conn = CategoryRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}