<?php

namespace App\Models;

class CartModel extends Model
{
    protected string $table = 'carts';

    public function createCart(int $user_id, $created_at)
    {
        try{
            $stmt = $this->db->prepare("
            INSERT INTO carts (user_id, created_at) VALUES (:user_id, :created_at)
            ");
            $stmt->execute([
                ':user_id'    => $user_id,
                ':created_at' => $created_at
            ]);
            return (int)$this->db->lastInsertId();
        } catch (\Exception $e){
            return false;
        }
    }
}