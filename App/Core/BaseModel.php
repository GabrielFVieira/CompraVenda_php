<?php

namespace App\Core;

use \PDO;
use \PDOException;

class BaseModel
{
    private const options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

    public static function getConexao()
    {
        $db = "mysql:host=" . HOST . ";dbname=" . DB;
        try {
            return new PDO($db, USER, PASSWORD, self::options);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
