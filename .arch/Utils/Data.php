<?php

namespace App\Utils;

class Data
{
    public static function filterEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function filterString($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }
    public static function filterInt($int)
    {
        return filter_var($int, FILTER_VALIDATE_INT);
    }
    public static function filterFloat($float)
    {
        return filter_var($float, FILTER_VALIDATE_FLOAT);
    }
    public static function filterBool($bool)
    {
        return filter_var($bool, FILTER_VALIDATE_BOOLEAN);
    }
    public static function htmlSpecial($date)
    {
        return htmlspecialchars($date, ENT_QUOTES,"UTF-8");
    }
    public static function passwordHash($password, $algo = PASSWORD_DEFAULT)
    {
        return password_hash($password, $algo);
    }
    public static function passwordVerify($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
}