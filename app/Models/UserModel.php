<?php

namespace App\Models;

class UserModel extends Model
{
    protected string $table = "users";

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

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
        foreach(['pseudo', 'email', 'address'] as $field){
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
}

