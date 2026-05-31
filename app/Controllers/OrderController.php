<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\OrderModel;

class OrderController
{
    public function checkout()
    {
        if (!isset($_SESSION['user_id']))
            {
                header('Location: /boutique-rl/public/user/login');
                exit;
            }

        if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
            header('Location: /boutique-rl/public/cart/index');
            exit;
        }

        $totalPrice = 0;
        $itemModel = new ItemModel();
        foreach ($_SESSION['cart'] as $id => $quantity){
            $item = $itemModel->findById($id);
            if($item){
                $totalPrice += ($item['price'] * $quantity);
            }
        }

        require_once __DIR__ . '/../Views/order/checkout.php';
    }

    public function confirm()
    {
        if(!isset($_SESSION['user_id']) || empty($_SESSION['cart'])){
            header('Location: /boutique-rl/public/');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $address = $_POST['address'] ?? '';
        $itemModel = new ItemModel();
        $totalPrice = 0;
        $cartItemsDetails = [];

        foreach ($_SESSION['cart'] as $id => $quantity){
            $item = $itemModel->findById($id);
            if($item){
                $totalPrice += ($item['price'] * $quantity);
                $cartItemsDetails[] = [
                    'item_id' => $id,
                    'quantity' => $quantity,
                    'unit_price' => $item['price']
                ];
            }
        }

        $orderModel = new OrderModel();
        $orderId = $orderModel->createOrder($userId, $totalPrice, $address, $cartItemsDetails);

        if($orderId){
            unset($_SESSION['cart']);

            require_once __DIR__ . '/../Views/layout/header.php';
            echo "<div style='text-align: center; margin-top: 50px;'>";
            echo "<h1 style='color: green;'>Commande validée avec succès !</h1>";
            echo "<p>Merci pour votre achat. Voici votre numéro de commande : <strong>#$orderId</strong></p>";
            echo "</div>";
            require_once __DIR__ . '/../Views/layout/footer.php';
        } else{
            echo "Erreur lors de la création de la commande.";
        }
    }
}
