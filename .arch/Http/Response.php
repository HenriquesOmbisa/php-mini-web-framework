<?php

namespace App\Http;

class Response
{
    public static function json(array $data = [], int $statusCode = 200, array $headers = ['Content-Type' => 'application/json'])
    {
        http_response_code($statusCode);
        foreach ($headers as $key => $value) {
            header($key .':'. $value);
        }

        echo json_encode($data);
    }

    public static function setHeaders(array $headers = [''=> 'application/json'])
    {
        foreach ($headers as $key => $value) {
            header($key .':'. $value);
        }
    }

    public static function setStatusCode(int $statusCode = 200)
    {
        http_response_code($statusCode);
    }

    public static function redirect(string $url, array $params = [])
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        header('Location: '. $url);
        exit;
    }

}
