<?php

namespace App\models;

use App\Core\BaseEnum;

class Role extends BaseEnum
{
    const Administrador = "0";
    const Vendedor      = "1";
    const Comprador     = "2";

    public static function getRoles()
    {
        return [
            Role::optionAux(Role::Administrador),
            Role::optionAux(Role::Vendedor),
            Role::optionAux(Role::Comprador)
        ];
    }

    private static function optionAux(string $id)
    {
        return [
            'id' => $id,
            'name' => Role::toString($id)
        ];
    }
}
