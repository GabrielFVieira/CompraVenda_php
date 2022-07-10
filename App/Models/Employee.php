<?php

namespace App\models;

class Employee
{
    private $id, $nome, $cpf, $senha, $papel;

    public function __construct()
    {
        $this->id = 0;
        $this->nome = "";
        $this->cpf = "";
        $this->senha = "";
        $this->papel = "";
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    /******************* */
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /******************* */
    public function getCPF()
    {
        return $this->cpf;
    }

    public function setCPF($cpf)
    {
        $this->cpf = $cpf;
    }

    /******************* */
    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /******************* */
    public function getPapel()
    {
        return $this->papel;
    }

    public function setPapel($papel)
    {
        $this->papel = $papel;
    }

    public function getPapelString()
    {
        return Role::toString($this->papel);
    }
}