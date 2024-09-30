<?php

namespace App\Schema;

use App\Orm\Table;
use App\Orm\Def;

class Schema
{
    public static function up()
    {
        Table::create('users', [
            'id' => [Def::int(), Def::primaryKey(), Def::autoIncrement(), Def::unique()],
            'name' => [Def::string(100)],
            'email' => [Def::string(255), Def::unique()],
            'password' => [Def::string(255)],
            'created_at' => [Def::timestamp(), Def::deafult(), Def::currentTimestemp()]
        ]);

        Table::create('posts', [
            'id' => [Def::int(), Def::primaryKey()],
            'text' => [Def::string(100)],
            'author' => [Def::string(255)],
            'categorie' => [Def::string(255)],
            'created_at' => [Def::timestamp()]
        ]);

        Table::create('categories', [
            'id' => [Def::int(), Def::primaryKey()],
            'name' => [Def::string(100)],
        ]);

        Table::create('images', [
            'id' => [Def::int(), Def::primaryKey()],
            'title' => [Def::string(100)],
        ]);
    }
}
