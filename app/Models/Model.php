<?php

namespace App\Models;

use Config\Database;
use PDO;

abstract class Model{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $database =  new Database();
        $this->db = $database->getConnection();
    }

    public function findAll(): array
    {    
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByName(string $name): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = :name");
        $stmt->execute([':name' => $name]);
        return $stmt->fetch();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id'=> $id]);
    }
}
