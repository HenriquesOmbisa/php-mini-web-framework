<?php

namespace App\Controllers;
use App\Http\Request;
use App\Http\Response;
use App\Orm\Models\Users;
use App\Utils\Data;

use App\Utils\RenderView;

class HomeController
{
    public function index(Request $request, Response $response)
    {
        return $response::json(Users::getAll(select:["name"]));

    }
    public function show(Request $request, Response $response, array $params)
    {
        try {
            $users = Users::getAll();
            RenderView::render("home", [
                "title"=> "Home page",
                "protocol" =>  PATH,
                "users"=> $users,
            ]);
        } catch (\Throwable $err) {
            return $response::json([
                "error"=> $err->getMessage(),
            ], 400);
        }
    }
    public function create(Request $request, Response $response)
    {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            $name = Data::filterString($data["name"]);
            $email = Data::filterEmail($data["email"]);
            $password = Data::passwordHash(Data::filterString($data["password"]));

            $existEmail = Users::findUnique(where:["email"=>(string)$email]);
            if(!$existEmail)
            {
                $user = Users::create([
                    "name" => $name,
                    "email"=> $email,
                    "password" => $password,
                ]);
                if(!$user)
                {
                    return $response::json([
                    "error"=> "erro ao criar o usuario"
                 ], 400);
                }
                return $response::json([
                    "user"=> $user,
                 ], 400);
                
            }else{
                 return $response::json([
                    "error"=> "email já existe"
                 ], 403);
            }
        
        
    }
    public function update(Request $request, Response $response)
    {
        echo"Update";
    }
    public function delete(Request $request, Response $response)
    {
        echo "Delete";
    }
}