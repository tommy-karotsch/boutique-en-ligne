<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\RarityModel;
use App\Models\ColorModel;

class ItemController
{
    public function index()
    {
        $filters = [
            'category_id' => $_GET['category_id'] ?? null,
            'rarity_id'   => $_GET['rarity_id'] ?? null,
            'color_id'    => $_GET['color_id'] ?? null,
        ];
        $sort = $_GET['sort'] ?? null;

        
        $itemModel = new ItemModel();
        $items = $itemModel->filter($filters, $sort);

        $categories = (new CategoryModel())->findAll();
        $rarities = (new RarityModel())->findAll();
        $colors = (new ColorModel())->findAll();

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
