<?php

use App\Core\BaseModel;
use App\models\Provider;

class ProviderModel extends BaseModel
{
    public function get(int $id)
    {
        try {
            $sql = "Select * from fornecedores where id = ? limit 1";
            $conn = ProviderModel::getConexao();

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

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM fornecedores WHERE id = ?";
            $conn = ProviderModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }

    public function update(Provider $provider)
    {
        try {
            $sql = "UPDATE fornecedores SET
                    razao_social = ?, cnpj = ?, 
                    endereco = ?, bairro = ?,
                    cidade = ?, uf = ?, 
                    cep = ?, telefone = ?,
                    email = ?
                    WHERE id = ?";
            $conn = ProviderModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $provider->getRazaoSocial());
            $stmt->bindValue(2, $provider->getCNPJ());
            $stmt->bindValue(3, $provider->getEndereco());
            $stmt->bindValue(4, $provider->getBairro());
            $stmt->bindValue(5, $provider->getCidade());
            $stmt->bindValue(6, $provider->getUF());
            $stmt->bindValue(7, $provider->getCEP());
            $stmt->bindValue(8, $provider->getTelefone());
            $stmt->bindValue(9, $provider->getEmail());
            $stmt->bindValue(10, $provider->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Erro ao atualizar fornecedor: ' . $e->getMessage());
        }
    }
}
