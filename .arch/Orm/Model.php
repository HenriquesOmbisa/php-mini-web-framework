<?php

namespace App\Orm;
use App\Database\Database;
use PDO;

class Model extends Database
{
    protected static string $table;
    protected static $primaryKey ='id';
    public static function getAll(array $select = [], $orderBy = null): array
    {
        $db = self::connection();
        if(empty($select))
        {
            $query = "SELECT * FROM " . static::$table ."";
        }else
        {
            $temp = "";
           foreach($select as $key => $value)
           {
            $temp .= $value .", ";
           }
           $temp = rtrim($temp,", ");
           $query = "SELECT $temp FROM ". static::$table ."";
        }
        if ($orderBy) {
            $query .= " ORDER BY " . $orderBy;
        } else {
            $query .= " ORDER BY " . static::$primaryKey . " ASC";
        }
        $stm = $db->query($query);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findMany(array $select = [],  array $where = [], int $limit = PHP_INT_MAX, $orderBy = null)
    {
        $selectScript ="";
        (string) $query = "";
        foreach ($select as  $value)
        {
            $selectScript .= "$value, ";
        }
        $whereScript = "";
        $params = [];

        foreach ($where as $key => $value)
        {
            $whereScript .= "$key = :$key AND ";
            $params[":$key"] = $value;
        }

        $whereScript = rtrim($whereScript, " AND ");

        $db = self::connection();
        if(empty($select))
        { $query = "SELECT * FROM " . static::$table; }
        else
        { $query = "SELECT $selectScript FROM " . static::$table; }

        if (!empty($whereScript)) {
            $query .= " WHERE " . $whereScript;
        }


        if ($orderBy) {
            $query .= " ORDER BY " . $orderBy;
        } else {
            $query .= " ORDER BY " . static::$primaryKey . " ASC";
        }

        if ($limit) {
            $query .= " LIMIT " . (int) $limit;
        }

        $stm = $db->prepare($query);

        foreach ($params as $key => $value) {
            $stm->bindValue($key, $value);
        }

        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findUnique(array $select = [], array $where = [])
    {
        $selectScript ="";
        (string) $query = "";
        foreach ($select as  $value)
        {
            $selectScript .= "$value, ";
        }
        $selectScript = rtrim($selectScript, ", ");
        $whereScript = "";
        foreach ($where as $key => $value) {
            $whereScript .= "$key = :$key AND ";
        }
        $whereScript = rtrim($whereScript, " AND ");
        
        if (empty($selectScript))
        {$query .= "SELECT * FROM " . static::$table . " WHERE " . $whereScript . " LIMIT 1";}
        else{$query .= "SELECT $selectScript FROM " . static::$table . " WHERE " . $whereScript . " LIMIT 1";}
        
        $db = self::connection();
        $stm = $db->prepare($query);
        
        foreach ($where as $key => $value) {
            $stm->bindValue(":$key", $value);
        }

        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(array $data, array $select = [])
{
    $db = self::connection();
    $columns = implode(", ", array_keys($data));
    $values = ":" . implode(", :", array_keys($data));
    $query = "INSERT INTO " . static::$table . " ($columns) VALUES ($values)";
    $stm = $db->prepare($query);

    if ($stm->execute($data)) {
        $lastId = $db->lastInsertId();
        return self::findUnique($select, [static::$primaryKey => $lastId]);
    }

    return false;
}


public static function createMany(array $datas, array $select = [])
{
    $db = self::connection();
    $createdRecords = [];

    foreach ($datas as $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        $stm = $db->prepare($query);

        foreach ($data as $key => $value) {
            $stm->bindValue(":$key", $value);
        }

        if ($stm->execute()) {
            $lastId = $db->lastInsertId();
            $selectFields = empty($select) ? array_merge([static::$primaryKey], array_keys($data)) : $select;
            $createdRecord = self::findUnique($selectFields, [static::$primaryKey => $lastId]);
            $createdRecords[] = $createdRecord;
        } else {
            return false;
        }
    }

    return $createdRecords; 
}




    public static function update(array $data, $id): bool
    {
        $db = self::connection();
        $columns = "";
        foreach ($data as $key => $value) {
            $columns .= "$key = :$key, ";
        }
        $columns = rtrim($columns, ", ");
        $query = "UPDATE " . static::$table . " SET $columns WHERE " . static::$primaryKey . " = :id";
        $stm = $db->prepare($query);
        $data['id'] = $id;
        return $stm->execute($data);
    }
    public static function delete($table, $id): bool
    {
        $db = self::connection();
        $query = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id";
        $stm = $db->prepare($query);
        $stm->bindParam(':id', $id);
        return $stm->execute();
    }
}
