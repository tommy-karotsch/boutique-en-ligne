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

    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT order_items.*, items.name, items.image
            FROM order_items
            JOIN items ON order_items.item_id = items.id
            WHERE order_items.order_id = :order_id
        ");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM orders
            WHERE user_id = :user_id
            ORDER BY ordered_at DESC
        ");
        $stmt->execute([
            ':user_id' => $userId
            ]);
            return $stmt->fetchAll();
    }
    
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['pending', 'shipped', 'delivered', 'cancelled'];

        if(!in_array($status, $allowed)){
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE orders
            SET status = :status
            WHERE id = :id
            ");
            
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $id
        ]);
    }

    public function findAllForAdmin(?string $status = null): array
    {
        $sql = "SELECT orders.*, users.username
                FROM orders
                JOIN users ON users.id = orders.user_id";

        $bindings = [];

        if (!empty($status)){
            $sql .= " WHERE orders.status = :status";
            $bindings[':status'] = $status;
        }

        $sql .= " ORDER BY orders.ordered_at DESC ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }
}