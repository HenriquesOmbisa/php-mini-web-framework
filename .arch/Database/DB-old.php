<?php

namespace App\Models;
use PDO;

class Database
{
    private static function connection(): PDO
    {
        try {
            $string = DB_TYPE .":host=". DB_HOST .";dbname=". DB_NAME .";";
            $db = new PDO($string , DB_USER, DB_PASS);

            return $db;
        } catch (\PDOException $err) {
            die($err->getMessage());
        }
    }
    public static function read(string $query, $data = [])
    {
        $db = self::connection();
        $stm = $db->prepare($query);

        if(count($data) == 0)
        {
            $stm = $db->query($query);
            $check = 0;
            if($stm->rowCount() > 0) $check = 1;

        } else $check = $stm->execute($data);

        $check ? $data = $stm->fetchAll(PDO::FETCH_ASSOC) : $data = [];
        return $data;
    }
    public static function write(string $query, $data = []): bool
    {
        $db = self::connection();
        $stm = $db->prepare($query);

        if(count($data) == 0)
        {
            $stm = $db->query($query);
            $check = 0;
            if($stm->rowCount() > 0) $check = 1;

        } else $check = $stm->execute($data);

        $check ? $state = true : $state = false;
        return $state;
    }
}