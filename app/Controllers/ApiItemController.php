<?php

namespace App\Controllers;

use App\Models\ItemModel;

class ApiItemController
{
    public function index(): void
    {
        header('Content-Type: application/json');

        $itemModel = new ItemModel();
        $method = $_SERVER['REQUEST_METHOD'];
        if (in_array($method, ['POST', 'PUT', 'DELETE'])){
            if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin'){
                http_response_code(403);
                echo json_encode(['error' => 'Accès interdit']);
                return;
            }
        }
        $id = $_GET['id'] ?? null;

        switch($method){
            case 'GET':
                if ($id){
                    $item = $itemModel->findByIdWithRelations((int)$id);
                    echo json_encode($item);
                } elseif (isset($_GET['top'])) {
                    $items = $itemModel->findTopByRarity(4);
                    echo json_encode($items);
                } else {
                    $items = $itemModel->findAllWithDetails();
                    echo json_encode($items);
                }
                break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $success = $itemModel->create($data);
                echo json_encode(['success' => $success]);
                break;

            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                $success = $itemModel->update((int)$id, $data);
                echo json_encode(['success' => $success]);
                break;

            case 'DELETE':
                $success = $itemModel->delete((int)$id);
                echo json_encode(['success' => $success]);
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Méthode non supportée']);
                break;
        }
    }
}