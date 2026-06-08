<?php

namespace App\Models;

class CartModel extends Model
{
    protected string $table = 'carts';

    public function createCart(int $user_id): int|false
    {
        try{
            $stmt = $this->db->prepare("
            INSERT INTO carts (user_id) VALUES (:user_id)
            ");
            $stmt->execute([
                ':user_id'    => $user_id,
            ]);
            return (int)$this->db->lastInsertId();
        } catch (\Exception $e){
            return false;
        }
    }
}