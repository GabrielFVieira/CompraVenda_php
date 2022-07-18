<?php

namespace App\models;

class Product implements \JsonSerializable
{
    private $id, $nome, $descricao, $precoCompra, $precoVenda,
        $quantidadeDisponivel, $liberadoVenda, $idCategoria, $nomeCategoria;

    const LIBERADO = 'S';
    const NAO_LIBERADO = 'N';

    public function __construct()
    {
        $this->id = 0;
        $this->nome = "";
        $this->descricao = "";
        $this->precoCompra = 0.0;
        $this->precoVenda = 0.0;
        $this->quantidadeDisponivel = 0;
        $this->liberadoVenda = self::NAO_LIBERADO;
        $this->idCategoria = 0;
        $this->nomeCategoria = "";
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome(string $nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao)
    {
        $this->descricao = $descricao;
    }

    public function getPrecoCompra()
    {
        return $this->precoCompra;
    }

    public function setPrecoCompra(float $preco)
    {
        $this->precoCompra = $preco;
    }

    public function getPrecoVenda()
    {
        return $this->precoVenda;
    }

    public function setPrecoVenda(float $preco)
    {
        $this->precoVenda = $preco;
    }

    public function getQuantidadeDisponivel()
    {
        return $this->quantidadeDisponivel;
    }

    public function setQuantidadeDisponivel(int $qtd)
    {
        $this->quantidadeDisponivel = $qtd;
    }

    public function getLiberadoVenda()
    {
        return $this->liberadoVenda;
    }

    public function isLiberadoVenda()
    {
        return $this->liberadoVenda == self::LIBERADO ? true : false;
    }

    public function setLiberadoVendaBool(bool $liberado)
    {
        $this->liberadoVenda = $liberado ? self::LIBERADO : self::NAO_LIBERADO;
    }

    public function setLiberadoVenda(string $liberado)
    {
        $this->liberadoVenda = $liberado;
    }

    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    public function setIdCategoria(int $idCategoria)
    {
        $this->idCategoria = $idCategoria;
    }

    public function getNomeCategoria()
    {
        return $this->nomeCategoria;
    }

    public function setNomeCategoria(string $nomeCategoria)
    {
        $this->nomeCategoria = $nomeCategoria;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}