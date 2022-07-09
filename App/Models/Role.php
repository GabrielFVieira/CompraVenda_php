<?php

namespace App\models;

use App\Core\BaseEnum;

class Role extends BaseEnum
{
    const Administrador = "0";
    const Vendedor      = "1";
    const Comprador     = "2";
}