<?php

namespace App\models;

class Sale implements \JsonSerializable
{
    private $id, $amount, $date, $value, $clientId, $clientName,
        $productId, $productName, $employeeId, $employeeName;

    public function __construct()
    {
        $this->id = 0;
        $this->amount = 0;
        $this->date = "";
        $this->value = 0;
        $this->clientId = 0;
        $this->clientName = "";
        $this->productId = 0;
        $this->productName = "";
        $this->employeeId = 0;
        $this->employeeName = "";
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue(float $value)
    {
        $this->value = $value;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId)
    {
        $this->clientId = $clientId;
    }

    public function getClientName()
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName)
    {
        $this->clientName = $clientName;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function setProductName(string $productName)
    {
        $this->productName = $productName;
    }

    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    public function setEmployeeId(int $employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function getEmployeeName()
    {
        return $this->employeeName;
    }

    public function setEmployeeName(string $employeeName)
    {
        $this->employeeName = $employeeName;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}