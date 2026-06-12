<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\OrderModel;

class OrderController
{
    public function index()
    {
        $this->checkLogin();

        $orderModel = new OrderModel();
        $orders = $orderModel->findByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../Views/order/index.php';
    }

    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])){
            header('Location: /boutique-en-ligne/public/user/login');
            exit;
        }
    }

    public function checkout()
    {
        $this->checkLogin();

        if(empty($_SESSION['cart'])){
            header('Location: /boutique-en-ligne/public/item/index');
            exit;
        }

        $itemModel  = new ItemModel();
        $orderModel = new OrderModel();

        $cartItemsForModel = [];
        $totalPrice        = 0;
        $cartPreview       = [];
        $errors            = [];
        $address           = '';

        foreach ($_SESSION['cart'] as $itemId => $quantity) {

            $itemData = $itemModel->findById((int)$itemId);

            if ($itemData) {
                $cartItemsForModel[] = [
                    'item_id'    => $itemId,
                    'quantity'   => $quantity,
                    'unit_price' => $itemData['price']
                ];

                $totalPrice += ($itemData['price'] * $quantity);
                $cartPreview[] = [
                    $itemData['name'],
                    $quantity,
                    $itemData['price'],
                    ($itemData['price'] * $quantity)
                ];
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $address = trim($_POST['delivery_address'] ?? '');

            if(empty($address)){
                $errors[] = "L'adresse de livraison est requise pour valider votre commande.";
            }

            if(empty($errors)){

                if(!empty($cartItemsForModel)){
                 $orderId = $orderModel->createOrder(
                        $_SESSION['user_id'],
                        $totalPrice,
                        $address,
                        $cartItemsForModel
                    );

                    if($orderId){
                        $cartModel = new CartModel();
                        $cartId = $cartModel->getOrCreateCartId($_SESSION['user_id']);
                        $cartModel->clear($cartId);

                        unset($_SESSION['cart']);
                        unset($_SESSION['cart_total']);

                        header('Location: /boutique-en-ligne/public/order/confirm?id=' . $orderId);
                        exit;
                    } else {
                        $errors[] = "Une erreur est survenue lors de la création de la commande. Veuillez réessayer.";
                    }
                } else {
                    $errors[] = "Votre panier ne contient aucun article valide.";
                }
            }
        }

        require_once __DIR__ . '/../Views/order/checkout.php';
    }

    public function confirm()
    {
        $this->checkLogin();

        $orderId = $_GET['id'] ?? null;
        if(!$orderId){
            header('Location: /boutique-en-ligne/public/item/index');
            exit;
        }

        $orderModel = new OrderModel();
        $order = $orderModel->findById((int)$orderId);

        if(!$order || (int)$order['user_id'] !== (int)$_SESSION['user_id']){
            header('Location: /boutique-en-ligne/public/item/index');
            exit;
        }

        require_once __DIR__ . '/../Views/order/confirm.php';
    }

}