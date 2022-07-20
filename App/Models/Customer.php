<?php

namespace App\models;

class Customer implements \JsonSerializable
{
    private $id, $nome, $cpf, $endereco, $bairro, $cidade, $uf, $cep, $telefone, $email;

    public function __construct()
    {
        $this->id = 0;
        $this->nome = "";
        $this->cpf = "";
        $this->endereco = "";
        $this->bairro = "";
        $this->cidade = "";
        $this->uf = "";
        $this->cep = "";
        $this->telefone = "";
        $this->email = "";
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCPF()
    {
        return $this->cpf;
    }

    public function setCPF($cpf)
    {
        $this->cpf = $cpf;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getUF()
    {
        return $this->uf;
    }

    public function setUF($uf)
    {
        $this->uf = $uf;
    }

    public function getCEP()
    {
        return $this->cep;
    }

    public function setCEP($cep)
    {
        $this->cep = $cep;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}