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
        $orderModel = new OrderModel();

        $nbOrders = $orderModel->countAll();
        $recentItems = $itemModel->findRecent(5);

        $items = $itemModel->findAllWithDetails();

        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);

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

        $id = (int)($_GET['id'] ?? 0);

        $itemModel = new ItemModel();

        $item = $itemModel->findByIdWithRelations($id);

        if(!$item){
            header('Location: /boutique-en-ligne/public/admin/index');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $data = [
                ':name'          => $_POST['name'] ?? '',
                ':description'   => $_POST['description'] ?? '',
                ':price'         => $_POST['price'] ?? 0,
                ':stock'         => $_POST['stock'] ?? 0,
                ':image'         => $_POST['image'] ?? '',
                ':category_id'   => $_POST['category_id'] ?? 1,
                ':rarity_id'     => $_POST['rarity_id'] ?? 1,
                ':color_id'      => $_POST['color_id'] ?? 1
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
            try {
                $itemModel = new ItemModel();
                $itemModel->delete($id);
            } catch (\PDOException $e) {
                $_SESSION['admin_error'] = "Impossible de supprimer cet item : il fait partie d'une commande existante.";
            }
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

    public function catalog()
    {
        $this->checkAdmin();

        $categories = (new CategoryModel())->findAllWithItemCount('category_id');
        $rarities = (new RarityModel())->findAllWithItemCount('rarity_id');
        $colors = (new ColorModel())->findAllWithItemCount('color_id');

        $error = $_SESSION['catalog_error'] ?? null;
        unset($_SESSION['catalog_error']);

        require_once __DIR__ . '/../Views/admin/catalog.php';
    }

    public function catalogAdd()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $type = $_POST['type'] ?? '';
            $name = $_POST['name'] ?? '';

            if ($name !== ''){
                switch ($type){
                    case 'category':
                        (new CategoryModel())->create(['name' => $name]);
                        break;
                    case 'rarity':
                        (new RarityModel())->create([
                            'name' => $name,
                            'color_code' => $_POST['color_code'] ?? '#000000'
                        ]);
                        break;
                    case 'color':
                        (new ColorModel())->create([
                            'name' => $name,
                            'hex_code' => $_POST['hex_code'] ?? '#000000'
                        ]);
                        break;
                }
            }
        }

        header('Location: /boutique-en-ligne/public/admin/catalog');
        exit;
    }

    public function catalogDelete()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $type = $_POST['type'] ?? '';
            $id = (int)($_POST['id'] ?? 0);

            if ($id > 0){
                try {
                    switch ($type){
                        case 'category':
                            (new CategoryModel())->delete($id);
                            break;
                        case 'rarity':
                            (new RarityModel())->delete($id);
                            break;
                        case 'color':
                            (new ColorModel())->delete($id);
                            break;
                    }
                } catch (\PDOException $e) {
                    $_SESSION['catalog_error'] = "Impossible de supprimer : cet élément est encore utilisé par des items.";
                }
            }
        }
        header('Location: /boutique-en-ligne/public/admin/catalog');
        exit;
    }

    public function catalogUpdate()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $type = $_POST['type'] ?? '';
            $id   = (int)($_POST['id'] ?? 0);
            $name = $_POST['name'] ?? '';

            if ($id > 0){
                switch ($type){
                    case 'category':
                        (new CategoryModel())->update($id, ['name' => $name]);
                        break;
                    case 'rarity':
                        (new RarityModel())->update($id, [
                            'name'       => $name,
                            'color_code' => $_POST['color_code'] ?? '#000000'
                        ]);
                        break;
                    case 'color':
                        (new ColorModel())->update($id, [
                            'name'     => $name,
                            'hex_code' => $_POST['hex_code'] ?? '#000000'
                        ]);
                        break;
                }
            }
        }
        header('Location: /boutique-en-ligne/public/admin/catalog');
        exit;
    }
}