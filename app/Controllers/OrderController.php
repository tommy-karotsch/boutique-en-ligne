<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\OrderModel;

class OrderController
{
    private function checkLogin()
    {
        if(!isset($_SESSION['user_id'])){
            header('Location: /boutique-en-ligne/public/user/login');
            exit;
        }
    }

    /**
     * Gestion du tunnel d'achat
     */
    public function checkout()
    {
        $this->checkLogin();

        if(empty($_SESSION['cart'])){
            header('Location: /boutique-en-ligne/public/item/index');
            exit;
        }

        $errors = [];
        $address = '';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $address = trim($_POST['delivery_address'] ?? '');

            if(empty($address)){
                $errors[] = "L'adresse de livraison est requise pour valider votre commande.";
            }

            if(empty($errors)){
                $itemModel = new ItemModel();
                $orderModel = new OrderModel();
                
                $cartItemsForModel = [];
                $totalPrice = 0;

                foreach ($_SESSION['cart'] as $itemId => $quantity) {
                    $itemData = $itemModel->findById((int)$itemId);
                    if ($itemData) {
                        $cartItemsForModel[] = [
                            'item_id'    => $itemId,
                            'quantity'   => $quantity,
                            'unit_price' => $itemData['price']
                        ];
                        $totalPrice += ($itemData['price'] * $quantity);
                    }
                }

                if (!empty($cartItemsForModel)) {
                    // CORRECTION : Utilisation de ta méthode native createOrder()
                    $orderId = $orderModel->createOrder(
                        $_SESSION['user_id'],
                        $totalPrice,
                        $address,
                        $cartItemsForModel
                    );

                    if ($orderId){
                        // Nettoyage de la session après l'achat réussi
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
            header('Location: /boutique-en-ligne/public/');
            exit;
        }

        $orderModel = new OrderModel();
        $order = $orderModel->findById((int)$orderId); 

        if(!$order || $order['user_id'] !== $_SESSION['user_id']){
            header('Location: /boutique-en-ligne/public/');
            exit;
        }

        require_once __DIR__ . '/../Views/order/confirm.php';
    }

    public function index()
    {
        $this->checkLogin();

        $orderModel = new OrderModel();
        $orders = $orderModel->findByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../Views/order/index.php';
    }
}