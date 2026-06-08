<?php

namespace App\Controllers;

use App\Models\OrderModel;

class ApiOrderController
{
    public function index(): void
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])){
            http_response_code(401);
            echo json_encode(['error' => 'Authentification requise']);
            return;
        }

        $model = new OrderModel();
        $method = $_SERVER['REQUEST_METHOD'];
        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';

        switch($method){

            case 'GET' :
                if ($isAdmin){
                    echo json_encode($model->findAllForAdmin());
                } else{
                    echo json_encode($model->findByUser($_SESSION['user_id']));
                }
                break;

            case 'PUT' :
                if (!$isAdmin){
                    http_response_code(403);
                    echo json_encode(['error' => 'Accès reservé à l\'administrateur']);
                    return;
                }

                $id = $_GET['id'] ?? null;
                $data = json_decode(file_get_contents('php://input'), true);
                $success = $model->updateStatus((int)$id, $data['status'] ?? '');
                echo json_encode(['success' => $success]);
                break;

            default : 
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non supportée']);
            break;
        }
    }
}