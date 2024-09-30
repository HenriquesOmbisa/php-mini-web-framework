<?php

namespace App\Orm;

class Def
{
    public static function int(): string
    {
        return 'INT';
    }

    public static function string(int $length = 255): string
    {
        return "VARCHAR($length)";
    }

    public static function text(): string
    {
        return "TEXT";
    }

    public static function primaryKey(): string
    {
        return 'PRIMARY KEY';
    }

    public static function autoIncrement(): string
    {
        return 'AUTO_INCREMENT';
    }
    public static function unique(): string
    {
        return 'UNIQUE';
    }
    public static function currentTimestemp(): string
    {
        return 'CURRENT_TIMESTAMP';
    }
    public static function deafult(): string
    {
        return 'DEFAULT';
    }
    public static function timestamp(): string
    {
        return 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
    }
    public static function foreignKey(string $references, string $on): array
    {
        return [
            'column' => $references,
            'references' => $on,
            'onDelete' => 'CASCADE'
        ];
    }
}
