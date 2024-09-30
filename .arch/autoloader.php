<?php

require_once("config.php");


spl_autoload_register(function(string $class)
{

    $base_dir = DIR_APP . DS;
    $class = $base_dir . str_replace('App', 'src', $class);
    if (
        strpos($class,'Http') !== false ||
        strpos($class,'Migrate') !== false ||
        strpos($class,'Utils') !== false ||
        strpos($class,'Orm') !== false ||
        strpos($class,'Database') !== false ||
        strpos($class,'Core') !== false
        )
    { $class = str_replace('src', '.arch', $class); }
    $class = str_replace('\\', DS, $class) .'.php';
    if (file_exists($class) && !is_dir($class)) {
        require_once($class);
        return true;
    } else {
        return false;
    }
});