<?php

namespace App\Models;

class CategoryModel extends Model
{
    protected string $table = 'categories';

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
        return $stmt->execute([':name' => $data['name']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE categories SET name = :name WHERE id = :id");
        return $stmt->execute([
            ':name' => $data['name'],
            ':id'   => $id
        ]);
    }
}