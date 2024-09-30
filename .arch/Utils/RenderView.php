<?php

namespace App\Utils;

class RenderView
{
    public static function render(string $view, array $args = [])
    {
        extract($args);
        $dir = str_replace(".arch". DS . "Utils","src", __DIR__ . DS ."Views" . DS . "{$view}.php");
        if(file_exists($dir))
        {
            require_once $dir;
        } else {
            throw new \Exception("View {$view} not found.");
        }
    }
}