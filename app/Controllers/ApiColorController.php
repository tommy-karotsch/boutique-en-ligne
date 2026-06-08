<?php

namespace App\Controllers;

use App\Models\ColorModel;

class ApiColorController
{
    public function index(): void
    {
        header('Content-Type: application/json');

        $model  = new ColorModel();
        $method = $_SERVER['REQUEST_METHOD'];
        $id     = $_GET['id'] ?? null;

        switch($method){

            case'GET' :
                if ($id){
                    echo json_encode($model->findById((int)$id));
                } else{
                    echo json_encode($model->findAll());
                }
                break;

            case 'POST' :
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode(['success' => $model->create($data)]);
                break;

            case 'PUT' :
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode(['success' => $model->update((int)$id, $data)]);
                break;

            case 'DELETE' :
                echo json_encode(['success' => $model->delete((int)$id)]);
                break;

            default: 
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non supportée']);
            break;
        }
    }
}