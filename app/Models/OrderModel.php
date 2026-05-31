<?php

namespace App\Models;

class OrderModel extends Model
{
    protected string $table = "orders";

    public function createOrder(int $userId, float $total, string $address, array $cartItems): int|false
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total, delivery_address) 
                VALUES (:user_id, :total, :delivery_address)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':total'   => $total,
                ':delivery_address' => $address
            ]);

            $orderId = $this->db->lastInsertId();

            $stmtItems = $this->db->prepare("
                INSERT INTO order_items (order_id, item_id, quantity, unit_price) 
                VALUES (:order_id, :item_id, :quantity, :unit_price)
            ");

            foreach ($cartItems as $item) {
                $stmtItems->execute([
                    ':order_id'   => $orderId,
                    ':item_id'    => $item['item_id'],
                    ':quantity'   => $item['quantity'],
                    ':unit_price' => $item['unit_price']
                ]);
            }

            $this->db->commit();
            return (int)$orderId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}