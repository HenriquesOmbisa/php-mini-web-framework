<?php

namespace App\Http;

class Request
{
    public static function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function body()
    {
        $json = json_decode(file_get_contents("php://input"));
        $data = match (self::getMethod())
        {
            'GET'    => $_GET,
            'POST'   => $json,
            'PUT'    => $json,
            'DELETE' => $json,
            default  => [],
        };

        return $data;
    }
}