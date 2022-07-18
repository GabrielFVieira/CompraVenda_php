<?php

namespace App\models;

class Category implements \JsonSerializable
{
    private $id, $nome;

    public function __construct()
    {
        $this->id = 0;
        $this->nome = "";
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

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
