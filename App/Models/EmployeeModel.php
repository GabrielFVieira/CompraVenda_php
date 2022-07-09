<?php

use App\Core\BaseModel;

class EmployeeModel extends BaseModel
{
    public function getEmployeeByCPF($cpf)
    {
        try {
            $sql = "Select * from funcionarios where cpf = ? limit 1";
            $conn = EmployeeModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $cpf);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];

                $employee = new \App\models\Employee();
                $employee->setId($result["id"]);
                $employee->setNome($result["nome"]);
                $employee->setCPF($result["cpf"]);
                $employee->setSenha($result["senha"]);
                $employee->setPapel($result["papel"]);

                return $employee;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }
}