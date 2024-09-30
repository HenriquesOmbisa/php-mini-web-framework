<?php

namespace App\Core;
use App\Http\Request;
use App\Http\Response;


class Core
{
    public static function dispatch(array $routes)
    {
        $url = '/';
        isset($_GET['url']) && $url .= $_GET['url'];

        $prefix = 'App\\Controllers\\';
        $url !== '/' && $url = rtrim($url, '/');
        $explode = explode('/', $url);
        $routeFound = false;


        foreach ($routes as $route)
        {
            $pattern = '#^'. preg_replace('/{id}/','([\w-]+)', $route['path']) .'$#';
            if (preg_match($pattern, $url, $params))
            {
                array_shift($params);
                
                [$controller, $method] = explode('@', $route['action']);
                if(Request::getMethod() !== $route['method'])
                {
                    Response::json([
                        'error' => 'Method not allowed',
                    ],405);
                    return;
                }
                $controller = $prefix . $controller;
                $req = str_replace('App', 'src', $controller) . '.php';
                require $req;
                $routeFound = true;

                $extendController = new $controller;
                $extendController->$method(new Request, new Response, $params);
            }
        }

        if(!$routeFound)
        {
            $controller = $prefix . 'NotFoundController';
            $req = str_replace('App', 'src', $controller) . '.php';
            require $req;

            $extendController = new $controller;
            $extendController->index(new Request, new Response, $params);
        }
    }
}