<?php

namespace App\Controllers;

use \Exception;
use App\Core\BaseController;
use App\Utils\Utils;
use App\models\Employee;
use App\models\Role;

class EmployeeController extends BaseController
{
    const DefaultPassword = 'Suporte@22';

    protected $filters = [
        'name' => 'trim|sanitize_string',
        'cpf' => 'trim|sanitize_string',
        // 'password' => 'trim|sanitize_string',
        'role' => 'trim|sanitize_string',
    ];

    protected $rules = [
        'name' => 'required|max_len,50',
        'cpf' => 'required|max_len,14',
        // 'password' => 'required|max_len,10',
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
        $employeeModel = $this->model('EmployeeModel');
        $employees = $employeeModel->list();

        $data = [
            'employees' => $employees
        ];

        $this->view('employee/index', $data);
    }

    public function find($path)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $employeeModel = $this->model('EmployeeModel');

            try {
                $employee = $employeeModel->get($path['id']);

                if (!is_null($employee)) :
                    $employees = Utils::omitPasswords($employee);

                    Utils::jsonResponse(200, $employees[0]);
                else :
                    $errors = ['Funcionário não encontrado'];
                    $data = ['errors' => $errors];
                    Utils::jsonResponse(404, $data);
                endif;
            } catch (Exception $e) {
                $errors = ['Erro ao buscar funcionário'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
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

        // if (isset($data['password'])) :
        //     $model->setSenha($data['password']);
        // endif;
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

            $employee = new Employee();
            $this->updateModelValues($employee, $_POST);
            $employee->setSenha(EmployeeController::DefaultPassword);

            try {
                $employeeModel = $this->model('EmployeeModel');
                $employeeModel->create($employee);
                Utils::jsonResponse(201);
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
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

            $employeeModel = $this->model('EmployeeModel');
            $oldEmployee = $employeeModel->get($path['id']);

            if (is_null($oldEmployee)) :
                $errors = ['Funcionário não encontrado'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(404, $data);
                exit();
            endif;

            $this->updateModelValues($oldEmployee, $_PUT);

            try {
                $employeeModel->update($oldEmployee);
                Utils::jsonResponse();
            } catch (Exception $e) {
                $errors = [$e->getMessage()];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
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
                $employeeModel = $this->model('EmployeeModel');
                $employeeModel->remove($id);
                Utils::jsonResponse(204);
            } catch (Exception $e) {
                $errors = ['Erro ao remover funcionário'];
                $data = ['errors' => $errors];
                Utils::jsonResponse(500, $data);
            }

            exit();
        else :
            Utils::jsonResponse(405);
        endif;
    }
}
