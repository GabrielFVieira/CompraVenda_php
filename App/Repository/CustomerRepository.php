<?php

use App\Core\BaseRepository;
use App\models\Customer;

class CustomerRepository extends BaseRepository
{
    private static function ModelFromDBArray($array)
    {
        $customer = new Customer();
        $customer->setId($array["id"]);
        $customer->setNome($array["nome"]);
        $customer->setCPF($array["cpf"]);
        $customer->setEndereco($array["endereco"]);
        $customer->setBairro($array["bairro"]);
        $customer->setCidade($array["cidade"]);
        $customer->setUF($array["uf"]);
        $customer->setCEP($array["cep"]);
        $customer->setTelefone($array["telefone"]);
        $customer->setEmail($array["email"]);

        return $customer;
    }

    public function create(Customer $customer)
    {
        try {
            $sql = "INSERT INTO clientes(nome,cpf,endereco,
                    bairro,cidade,uf,cep,telefone,email) VALUES (?,?,?,?,?,?,?,?,?)";
            $conn = CustomerRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $customer->getNome());
            $stmt->bindValue(2, $customer->getCPF());
            $stmt->bindValue(3, $customer->getEndereco());
            $stmt->bindValue(4, $customer->getBairro());
            $stmt->bindValue(5, $customer->getCidade());
            $stmt->bindValue(6, $customer->getUF());
            $stmt->bindValue(7, $customer->getCEP());
            $stmt->bindValue(8, $customer->getTelefone());
            $stmt->bindValue(9, $customer->getEmail());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar cliente: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "Select * from clientes where id = ? limit 1";
            $conn = CustomerRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result =  $resultset[0];
                return CustomerRepository::ModelFromDBArray($result);
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao buscar cliente: ' . $e->getMessage());
            throw $e;
        }
    }

    public function list()
    {
        try {
            $sql = "Select * from clientes order by nome";
            $conn = CustomerRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $result = [];
                foreach ($resultset as $value) {
                    array_push($result, CustomerRepository::ModelFromDBArray($value));
                }

                return $result;
            else :
                return;
            endif;
        } catch (\PDOException $e) {
            error_log('Erro ao listar clientes: ' . $e->getMessage());
            throw $e;
        }
    }

    public function remove(int $id)
    {
        try {
            $sql = "DELETE FROM clientes WHERE id = ?";
            $conn = CustomerRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (\PDOException $e) {
            error_log('Erro ao remover cliente: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Customer $customer)
    {
        try {
            $sql = "UPDATE clientes SET
                    nome = ?, cpf = ?, 
                    endereco = ?, bairro = ?,
                    cidade = ?, uf = ?, 
                    cep = ?, telefone = ?,
                    email = ?
                    WHERE id = ?";
            $conn = CustomerRepository::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $customer->getNome());
            $stmt->bindValue(2, $customer->getCPF());
            $stmt->bindValue(3, $customer->getEndereco());
            $stmt->bindValue(4, $customer->getBairro());
            $stmt->bindValue(5, $customer->getCidade());
            $stmt->bindValue(6, $customer->getUF());
            $stmt->bindValue(7, $customer->getCEP());
            $stmt->bindValue(8, $customer->getTelefone());
            $stmt->bindValue(9, $customer->getEmail());
            $stmt->bindValue(10, $customer->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            error_log('Erro ao atualizar cliente: ' . $e->getMessage());
            throw $e;
        }
    }
}
