<?php

namespace App\Controllers;

use App\Models\ItemModel;

class ItemController
{
    public function index()
    {
        $itemModel = new ItemModel();
        
        $items = $itemModel->findAllWithDetails();

        require_once __DIR__ . '/../Views/item/index.php';
    }

    public function show()
    {
        if (!isset($_GET['id'])) {
            header('Location: /boutique-en-ligne/public/');
            exit;
        }

        $id = (int)$_GET['id'];
        $itemModel = new ItemModel();
        $item = $itemModel->findByIdWithRelations($id);

        if (!$item) {
            echo "Item introuvable";
            return;
        }

        require_once __DIR__ . '/../Views/item/show.php';
    }
}
