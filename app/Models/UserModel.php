<?php

namespace App\Models;

class UserModel extends Model
{
    protected string $table = "users";

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (username, email, password)
            VALUES (:username, :email, :password)
        ");
        
        return $stmt->execute([
            ':username' => $data['username'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $updates = [];
        $bindings = [':id' => $id];
        foreach(['username', 'email', 'address'] as $field){
            if(isset($data[$field])){
                $updates[] = "$field = :$field";
                $bindings[":$field"] = $data[$field];
            }
        }
        if(empty($updates)) return true;
        $stmt = $this->db->prepare("UPDATE users SET " . implode(',',
        $updates). " WHERE id = :id");
        return $stmt->execute($bindings);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public function deleteAccount(int $id): bool
    {
        try{
            $this->db->beginTransaction();

            $this->db->prepare("DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM carts WHERE user_id = :id)")
            ->execute([':id' => $id]);

            $this->db->prepare("DELETE FROM carts WHERE user_id = :id")
            ->execute([':id' => $id]);

            $this->db->prepare("DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = :id)")
            ->execute([':id' => $id]);

            $this->db->prepare("DELETE FROM orders WHERE user_id = :id")
            ->execute([':id' => $id]);

            $this->db->prepare("DELETE FROM users WHERE id = :id")
            ->execute([':id' => $id]);

            $this->db->commit();
            return true;
            
        } catch (\Exception $e){
            $this->db->rollBack();
            return false;
        }
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }
}

