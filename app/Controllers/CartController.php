<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\CartModel;

class CartController
{

    private function persistCart(): void
    {
        if(!isset($_SESSION['user_id']))
        {
            return;
        }

        $cartModel = new CartModel();
        $cartId = $cartModel->getOrCreateCartId($_SESSION['user_id']);
        $cartModel->save($cartId, $_SESSION['cart'] ?? []);
    }

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

        if ($id > 0){
            $itemModel = new ItemModel();
            $item = $itemModel->findById($id);

            if($item){
                $currentQuantity = $_SESSION['cart'][$id] ?? 0;

                if ($currentQuantity < $item['stock']){
                    $_SESSION['cart'][$id] = $currentQuantity + 1;
                }
            }
        }

        $this->persistCart();
        header('Location: /boutique-en-ligne/public/cart/index');
        exit;
    }

    public function remove()
    {
        $id = (int)($_GET['id'] ?? 0);

        if (isset($_SESSION['cart'][$id])){
            unset($_SESSION['cart'][$id]);
        }

        $this->persistCart();
        header('Location: /boutique-en-ligne/public/cart/index');
        exit;
    }

    public function updateQuantity()
    {
        $id = (int)($_POST['id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($id > 0){
            if ($quantity <= 0){
                unset($_SESSION['cart'][$id]);
            } else{
                $itemModel = new ItemModel();
                $item = $itemModel->findById($id);

                if ($item && $quantity <= $item['stock']){
                    $_SESSION['cart'][$id] = $quantity;
                }
            }
        }
        $this->persistCart();
        header('Location: /boutique-en-ligne/public/cart/index');
        exit;
    }
}
