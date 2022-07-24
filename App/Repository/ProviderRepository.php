<?php

use App\Core\BaseRepository;
use App\models\Provider;

class ProviderRepository extends BaseRepository
{
    private static function ModelFromDBArray($array)
    {
        $provider = new Provider();
        $provider->setId($array["id"]);
        $provider->setRazaoSocial($array["razao_social"]);
        $provider->setCNPJ($array["cnpj"]);
        $provider->setEndereco($array["endereco"]);
        $provider->setBairro($array["bairro"]);
        $provider->setCidade($array["cidade"]);
        $provider->setUF($array["uf"]);
        $provider->setCEP($array["cep"]);
        $provider->setTelefone($array["telefone"]);
        $provider->setEmail($array["email"]);

        return $provider;
    }

    public function create(Provider $provider)
    {
        try {
            $sql = "INSERT INTO fornecedores(razao_social,cnpj,endereco,
                    bairro,cidade,uf,cep,telefone,email) VALUES (?,?,?,?,?,?,?,?,?)";
            $conn = ProviderRepository::getConexao();

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
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar fornecedor: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "Select * from fornecedores where id = ? limit 1";
            $conn = ProviderRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return ProviderRepository::ModelFromDBArray($result);
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao buscar fornecedor: ' . $e->getMessage());
            throw $e;
        }
    }

    public function list()
    {
        try {
            $sql = "Select * from fornecedores order by razao_social";
            $conn = ProviderRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, ProviderRepository::ModelFromDBArray($value));
                }

                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar fornecedores: ' . $e->getMessage());
            throw $e;
        }
    }

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM fornecedores WHERE id = ?";
            $conn = ProviderRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            error_log('Erro ao remover fornecedor: ' . $e->getMessage());
            throw $e;
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
            $conn = ProviderRepository::getConexao();

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
            error_log('Erro ao atualizar fornecedor: ' . $e->getMessage());
            throw $e;
        }
    }
}
