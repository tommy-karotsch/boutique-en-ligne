<?php

namespace App\Controllers;

use App\Models\ItemModel;

class CartController
{
    public function index()
    {
        if(!isset($_SESSION['cart']) || empty ($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        $cartList = [];
        $totalPrice = 0;
        $itemModel = new ItemModel();

        foreach ($_SESSION['cart'] as $itemId => $quantity){
            $item = $itemModel->findByIdWithRelations($itemId);
            if($item){
                $item['quantity'] = $quantity;
                $cartList[] = $item;

                $totalPrice += ($item['price'] * $quantity);
            }
        }

        require_once __DIR__ . '/../Views/cart/index.php';
    }

    public function add()
    {
        $id = (int)($_GET['id'] ?? 0);

        if($id > 0){
            if(!isset($_SESSION['cart'])){
                $_SESSION['cart'] = [];
            }

            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id] += 1;
            } else{
                $_SESSION['cart'][$id] = 1;
            }
        }

        header('Location: /boutique-en-ligne/public/cart/index');
        exit;
    }

    public function remove()
    {
        $id = (int)($_GET['id'] ?? 0);

        if (isset($_SESSION['cart'][$id])){
            unset($_SESSION['cart'][$id]);
        }

        header('Location: /boutique-en-ligne/public/cart/index');
        exit;
    }
}
