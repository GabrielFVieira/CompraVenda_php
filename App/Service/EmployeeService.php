<?php

use App\Core\BaseService;
use App\models\Employee;
use app\utils\Utils;

class EmployeeService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('EmployeeRepository');
    }

    public function create(Employee $employee)
    {
        try {
            $employee->setSenha(Utils::DefaultPassword);
            $this->repository->create($employee);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar funcionário: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar funcionário');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar funcionário: ' . $e->getMessage());
            throw new Exception('Erro ao buscar funcionário');
        }
    }

    public function getEmployeeByCPF($cpf)
    {
        try {
            return $this->repository->getEmployeeByCPF($cpf);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar funcionário: ' . $e->getMessage());
            throw new Exception('Erro ao buscar funcionário');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar funcionários: ' . $e->getMessage());
            throw new Exception('Erro ao listar funcionários');
        }
    }

    public function remove(int $id)
    {
        try {
            $this->repository->remove($id);
        } catch (\PDOException $e) {
            error_log('Erro ao remover funcionário: ' . $e->getMessage());
            throw new Exception('Erro ao remover funcionário');
        }
    }

    public function update(Employee $employee)
    {
        $oldEmployee = $this->get($employee->getId());
        if (is_null($oldEmployee)) :
            throw new Exception('Funcionário não encontrado');
        endif;

        try {
            $this->repository->update($employee);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar funcionário: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar funcionário');
        }
    }
}