<?php

use App\Core\BaseService;

class AccessService extends BaseService
{
    public function login(string $cpf, $password)
    {
        $model = $this->service('EmployeeService');
        $usuario = $model->getEmployeeByCPF($cpf);

        if (!empty($usuario) && $password == $usuario->getSenha()) :
            session_regenerate_id(true);

            $_SESSION['id'] = $usuario->getId();
            $_SESSION['nomeUsuario'] = $usuario->getNome();
            $_SESSION['cpfUsuario'] = $usuario->getCPF();
            $_SESSION['papelUsuario'] = $usuario->getPapelString();

            return;
        else :
            throw new Exception("Usuário e/ou Senha incorreta");
        endif;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}