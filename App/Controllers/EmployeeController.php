<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Employee;
use App\models\Role;

class EmployeeController extends BaseController
{
    const DefaultPassword = Utils::DefaultPassword;

    protected $filters = [
        'name' => 'trim|sanitize_string',
        'cpf' => 'trim|sanitize_string',
        'role' => 'trim|sanitize_string',
    ];

    protected $rules = [
        'name' => 'required|max_len,50',
        'cpf' => 'required|max_len,14',
        'role' => 'required|integer|min_numeric,0|max_numeric,2',
    ];

    function __construct()
    {
        session_start();
        if (!Utils::usuarioLogado()) :
            Utils::redirect("login");
            exit();
        endif;
    }

    public function index()
    {
        $employeeService = $this->service('EmployeeService');
        $employees = $employeeService->list();

        $data = [
            'employees' => $employees
        ];

        $this->view('employee/index', $data);
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $employeeService = $this->service('EmployeeService');

            try {
                $employee = $employeeService->get($path['id']);

                if (!is_null($employee)) :
                    $employees = Utils::omitPasswords($employee);

                    Utils::jsonResponse(200, $employees[0]);
                else :
                    $errors = ['Funcionário não encontrado'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }

    private function updateModelValues(Employee &$model, $data)
    {
        $model->setNome($data['name']);
        $model->setCPF($data['cpf']);
        $model->setPapel($data['role']);
    }

    public function create()
    {
        if (Utils::hasPermission(Role::Administrador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            if (Utils::validateInputs($_POST, $this->filters, $this->rules) == false) {
                exit();
            }

            try {
                $employee = new Employee();
                $this->updateModelValues($employee, $_POST);

                $employeeService = $this->service('EmployeeService');
                $employeeService->create($employee);

                Utils::jsonResponse(201);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::redirect();
        endif;
    }

    public function update($path)
    {
        if (Utils::hasPermission(Role::Administrador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') :
            Utils::loadPutValues($_PUT);
            if (Utils::validateInputs($_PUT, $this->filters, $this->rules) == false) {
                exit();
            }

            try {
                $employee = new Employee();
                $this->updateModelValues($employee, $_PUT);
                $employee->setId($path['id']);

                $employeeService = $this->service('EmployeeService');

                $oldEmployee = $employeeService->get($employee->getId());
                if (is_null($oldEmployee)) :
                    throw new Exception('Funcionário não encontrado');
                endif;

                $employee->setSenha($oldEmployee->getSenha());

                $employeeService->update($employee);

                Utils::jsonResponse();
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }
        else :
            Utils::jsonResponse(405);
        endif;
    }

    public function remove($data)
    {
        if (Utils::hasPermission(Role::Administrador) == false) :
            exit();
        endif;

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') :
            try {
                $id = $data['id'];
                $employeeService = $this->service('EmployeeService');
                $employeeService->remove($id);

                Utils::jsonResponse(204);
            } catch (Exception $e) {
                Utils::returnJsonError(500, $e->getMessage());
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }
}