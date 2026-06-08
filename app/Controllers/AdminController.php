<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\CategoryModel;
use App\Models\RarityModel;
use App\Models\ColorModel;

class AdminController{
    
    private function checkAdmin(){
        if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'){
            header('Location: /boutique-en-ligne/public/');
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
                header('Location: /boutique-en-ligne/public/admin/index');
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

    public function edit(){
        $this->checkAdmin();

        $id = $_GET['id'] ?? null;

        $itemModel = new ItemModel();

        $item = $itemModel->findByIdWithRelations((int)$id);

        if(!$item){
            header('Location: /boutique-en-ligne/public/admin/index');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
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

            if($itemModel->update($id, $data)){
                header('Location: /boutique-en-ligne/public/admin/index');
                exit;
            }

            $error = "Une erreur est survenue lors de la modification";
        }

        $categoryModel = new CategoryModel();
        $rarityModel   = new RarityModel();
        $colorModel    = new ColorModel();

        $categories = $categoryModel->findAll();
        $rarities = $rarityModel->findAll();
        $colors = $colorModel->findAll();

        require_once __DIR__ . '/../Views/admin/edit.php';
    }

    public function delete(){

        $this->checkAdmin();

        $id = (int)($_POST['id'] ?? 0);

        if($id > 0){
            $itemModel = new ItemModel();
            $itemModel->delete($id);
        }
        header('Location: /boutique-en-ligne/public/admin/index');
        exit;
    }

    public function orders()
    {
        $this->checkAdmin();

        $status = $_GET['status'] ?? null;

        $orderModel = new OrderModel();
        $orders = $orderModel->findAllForAdmin($status);

        require_once __DIR__ . '/../Views/admin/orders.php';
    }

    public function updateOrderStatus()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id     = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';

            if ($id > 0){
                $orderModel = new OrderModel();
                $orderModel->updateStatus($id, $status);
            }
        }



        header('Location: /boutique-en-ligne/public/admin/orders');
        exit;
    }
}