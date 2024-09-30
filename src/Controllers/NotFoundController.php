<?php

namespace App\Controllers;
use App\Http\Request;
use App\Http\Response;
use App\Utils\RenderView;

class NotFoundController
{
    public function index(Request $request, Response $response)
    {
        return $response::json(["message"=> "Page not found."],404);
    }
}