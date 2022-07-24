<?php

namespace App\Core;

use \PDO;
use \PDOException;

class BaseRepository
{
    private const options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8"
    ];

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