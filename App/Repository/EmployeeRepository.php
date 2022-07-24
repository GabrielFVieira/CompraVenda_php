<?php

use App\Core\BaseModel;
use App\models\Employee;

class EmployeeRepository extends BaseModel
{
    private static function ModelFromDBArray($array)
    {
        $employee = new Employee();
        $employee->setId($array["id"]);
        $employee->setNome($array["nome"]);
        $employee->setCPF($array["cpf"]);
        $employee->setSenha($array["senha"]);
        $employee->setPapel($array["papel"]);

        return $employee;
    }

    public function create(Employee $employee)
    {
        try {
            $sql = "INSERT INTO funcionarios(nome,cpf,senha,papel) VALUES (?,?,?,?)";
            $conn = EmployeeRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $employee->getNome());
            $stmt->bindValue(2, $employee->getCPF());
            $stmt->bindValue(3, $employee->getSenha());
            $stmt->bindValue(4, $employee->getPapel());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar functionário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "Select * from funcionarios where id = ? limit 1";
            $conn = EmployeeRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return EmployeeRepository::ModelFromDBArray($result);
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao buscar functionário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getEmployeeByCPF($cpf)
    {
        try {
            $sql = "Select * from funcionarios where cpf = ? limit 1";
            $conn = EmployeeRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $cpf);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result =  $resultset[0];

                return EmployeeRepository::ModelFromDBArray($result);
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
            $sql = "Select * from funcionarios order by nome";
            $conn = EmployeeRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, EmployeeRepository::ModelFromDBArray($value));
                }

                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar funcionários: ' . $e->getMessage());
            throw $e;
        }
    }

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM funcionarios WHERE id = ?";
            $conn = EmployeeRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            error_log('Erro ao remover funcionário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Employee $employee)
    {
        try {
            $sql = "UPDATE funcionarios SET
                    nome = ?, cpf = ?, 
                    senha = ?, papel = ?
                    WHERE id = ?";
            $conn = EmployeeRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $employee->getNome());
            $stmt->bindValue(2, $employee->getCPF());
            $stmt->bindValue(3, $employee->getSenha());
            $stmt->bindValue(4, $employee->getPapel());
            $stmt->bindValue(5, $employee->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao atualizar funcionário: ' . $e->getMessage());
            throw $e;
        }
    }
}