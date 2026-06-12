<?php

namespace App\Models;

class CartModel extends Model
{
    protected string $table = 'carts';

    public function getOrCreateCartId(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT id FROM carts WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch();

        if ($row) {
            return (int)$row['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
        $stmt->execute([':user_id' => $userId]);
        return (int)$this->db->lastInsertId();
    }

    public function getQuantities(int $cartId): array
    {
        $stmt = $this->db->prepare("SELECT item_id, quantity FROM cart_items WHERE cart_id = :cart_id");
        $stmt->execute([':cart_id' => $cartId]);

        $cart = [];
        foreach ($stmt->fetchAll() as $row) {
            $cart[(int)$row['item_id']] = (int)$row['quantity'];
        }
        return $cart;
    }

    public function save(int $cartId, array $cart): void
    {
        $this->db->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id")
                 ->execute([':cart_id' => $cartId]);

        if (empty($cart)) {
            return;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO cart_items (cart_id, item_id, quantity) VALUES (:cart_id, :item_id, :quantity)"
        );
        foreach ($cart as $itemId => $quantity) {
            $stmt->execute([
                ':cart_id'  => $cartId,
                ':item_id'  => $itemId,
                ':quantity' => $quantity
            ]);
        }
    }

    public function clear(int $cartId): void
    {
        $this->db->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id")
                 ->execute([':cart_id' => $cartId]);
    }
}