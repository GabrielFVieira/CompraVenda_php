<?php

namespace App\models;

class Purchase
{
    private $id, $quantidadeCompra, $valorCompra, $idFornecedor, $nomeFornecedor,
        $idProduto, $nomeProduto, $idFuncionario, $nomeFuncionario, $dataCompra;

    public function __construct()
    {
        $this->id = 0;
        $this->quantidadeCompra = 0;
        $this->valorCompra = 0;
        $this->idFornecedor = 0;
        $this->nomeFornecedor = "";
        $this->idProduto = 0;
        $this->nomeProduto = "";
        $this->idFuncionario = 0;
        $this->nomeFuncionario = "";
        $this->dataCompra = "";
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getQuantidade()
    {
        return $this->quantidadeCompra;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidadeCompra = $quantidade;
    }

    public function getValor()
    {
        return $this->valorCompra;
    }

    public function setValor($valor)
    {
        $this->valorCompra = $valor;
    }

    public function getIdFornecedor()
    {
        return $this->idFornecedor;
    }

    public function setIdFornecedor($idFornecedor)
    {
        $this->idFornecedor = $idFornecedor;
    }

    public function getNomeFornecedor()
    {
        return $this->nomeFornecedor;
    }

    public function setNomeFornecedor($nomeFornecedor)
    {
        $this->nomeFornecedor = $nomeFornecedor;
    }

    public function getIdProduto()
    {
        return $this->idProduto;
    }

    public function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
    }

    public function getNomeProduto()
    {
        return $this->nomeProduto;
    }

    public function setNomeProduto($nomeProduto)
    {
        $this->nomeProduto = $nomeProduto;
    }

    public function getIdFuncionario()
    {
        return $this->idFuncionario;
    }

    public function setIdFuncionario($idFuncionario)
    {
        $this->idFuncionario = $idFuncionario;
    }

    public function getNomeFuncionario()
    {
        return $this->nomeFuncionario;
    }

    public function setNomeFuncionario($nomeFuncionario)
    {
        $this->nomeFuncionario = $nomeFuncionario;
    }

    public function getData()
    {
        return $this->dataCompra;
    }

    public function setData($dataCompra)
    {
        $this->dataCompra = $dataCompra;
    }
}