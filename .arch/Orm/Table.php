<?php

namespace App\Orm;
use App\Migrate\MigrationManager;
use App\Database\Database;

use PDO;

class Table extends MigrationManager
{
    public static array $message;
    // Criar tabelas com suporte a chaves estrangeiras
    public static function create(string $table, array $columns, array $foreignKeys = [])
    {
        $db = self::connection();
        self::createMigrationTable();
        // Construir a definição das colunas
        $columnDefs = self::generateColumn($columns);

        // Adicionar definições de chaves estrangeiras
        $foreignKeyDefs = self::generateForeignKey($foreignKeys);

        // Montar a query
        $query = "CREATE TABLE IF NOT EXISTS $table ($columnDefs";
        if (!empty($foreignKeyString)) {
            $query .= ", $foreignKeyDefs";
        }
        $query .= ")";

        // Executar a query
        if(!self::existTable($table))
        {
            $stm = $db->prepare($query);
            $stm->execute();
            self::addMigration($table, $columnDefs);
            echo"Created table $table sucessfull.\n";
            self::createClass($table);
        } else if(self::hasChanges($table, $columnDefs))
        {
            $drop = "DROP TABLE $table;";
            $stm = $db->prepare($drop);
            $stm->execute();
            $stm = $db->prepare($query);
            $stm->execute();
            self::recordMigration($table, $columnDefs);
            echo"Table $table changed sucessfull.\n";
        } else {
            echo"Table $table exits and don't have changes. \n";
        }
    }
    public static function generateColumn(array $columns)
    {
        foreach ($columns as $name => $types) {
            $cTypes = "";
            foreach ($types as $type)
            {
                $cTypes .= "$type ";
            }
            $columnDefs[] = "$name $cTypes";
        }
        return implode(", ", $columnDefs);
    }
    public static function generateForeignKey(array $foreignKeys)
    {
       $foreignKeyDefs = [];
        foreach ($foreignKeys as $fk) {
            $foreignKeyDefs[] = "FOREIGN KEY ({$fk['column']}) REFERENCES {$fk['references']}({$fk['on']}) ON DELETE {$fk['onDelete']}";
        }
        return implode(", ", $foreignKeyDefs);
    }
    public static function dropTable(string $table): bool
    {
        $db = Database::connection();
        $query = "DROP TABLE IF EXISTS $table";
        $stm = $db->prepare($query);
        return $stm->execute();
    }
}