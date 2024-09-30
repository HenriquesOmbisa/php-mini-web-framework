<?php

namespace App\Database;
use PDO;

class Database
{
    protected static function connection(): PDO
    {
        try {
            $string = DB_TYPE .":host=". DB_HOST .";dbname=". DB_NAME .";";
            $db = new PDO($string , DB_USER, DB_PASS);

            return $db;
        } catch (\PDOException $err) {
            die($err->getMessage());
        }
    }
}