<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\RarityModel;
use App\Models\ColorModel;

class AdminController{
    
    private function checkAdmin(){
        if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'){
            header('Location: /boutique-rl/public/');
            exit;
        }
    }

    public function index(){
        $this->checkAdmin();

        $itemModel = new ItemModel();
        $items = $itemModel->findAllWithDetails();

        require_once __DIR__ . '/../Views/admin/index.php';
    }

    public function create(){
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $data = [
                'name'          => $_POST['name'] ?? '',
                'description'   => $_POST['description'] ?? '',
                'price'         => $_POST['price'] ?? 0,
                'stock'         => $_POST['stock'] ?? 0,
                'image'         => $_POST['image'] ?? '',
                'category_id'   => $_POST['category_id'] ?? 1,
                'rarity_id'     => $_POST['rarity_id'] ?? 1,
                'color_id'      => $_POST['color_id'] ?? 1
            ];

            $itemModel = new ItemModel();

            if($itemModel->create($data)){
                header('Location: /boutique-rl/public/admin/index');
                exit;
            }
            $error = "Une erreur est survenue lors de l'ajout.";
        }

        $categoryModel = new CategoryModel();
        $rarityModel = new RarityModel();
        $colorModel = new ColorModel();

        $categories = $categoryModel->findAll();
        $rarities = $rarityModel->findAll();
        $colors = $colorModel->findAll();

        require_once __DIR__ . '/../Views/admin/create.php';
    }
}