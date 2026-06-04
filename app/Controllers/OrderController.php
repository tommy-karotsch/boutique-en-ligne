<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class OrderController
{
    public function checkLogin()
    {
        if(!isset($_SESSION['user_id'])){
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

        $errors = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $address = trim($_POST['delivery_address'] ?? '');
        }

        if(empty($errors)){
            $orderModel = new OrderModel();
            $orderItemModel = new OrderItemModel();
            $itemModel = new ItemModel();

            $orderId = $orderModel->creare([
                'user_id'           => $_SESSION['user_id'],
                'delivery_address'  => $address,
                'total_price'       => $_SESSION['cart_total'] ?? 0,
                'status'            => 'en attente'
            ]);

            if ($orderId){
                foreach($_SESSION['cart'] as $itemId => $product){
                    $orderItemModel->create([
                        'order_id'      => $orderId,
                        'item_id'       => $itemId,
                        'quantity'      => $product['quantity'],
                        'price'         => $product['price']
                    ]);

                    $itemModel->updateStock($itemId, $product['quantity']);
                }

                unset($_SESSION['cart']);
                unset($_SESSION['cart_total']);

                header('Location: /boutique-en-ligne/public/order/confirm?id=' . $orderId);
                exit;
            } else{
                $errors[] = "Une erreur est survenue lors de la création de la commande. Veuillez réessayer.";
            }
        }

        require_once __DIR__ . '/../Views/order/checkout.php';
    }

    public function confirm()
    {
        $this->checkLogin();

        $orderId = $_GET['id'] ?? null;
        if(!$orderId){
            header('Location: /boutique-en-ligne/public');
            exit;
        }

        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if(!$order || $order['user_id'] !== $_SESSION['user_id']){
          header('Location: /boutique-en-lign/public');
          exit;  
        }

        require_once __DIr__ . '/../Views/order/confirm.php';
    }

    public function index()
    {
        $this->checkLogin();

        $orderModel = new OrderModel();

        $order = $orderModel->findByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../Views/order/index.php';
    }
}