<?php

use App\Core\BaseModel;

class ProviderModel extends BaseModel
{
    public function list()
    {
        try {
            $sql = "Select * from fornecedores order by razao_social";
            $conn = ProviderModel::getConexao();

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
}