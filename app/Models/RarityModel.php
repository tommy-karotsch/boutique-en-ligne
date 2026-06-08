<?php

namespace App\Models;

class RarityModel extends Model
{
    protected string $table = 'rarities';

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO rarities (name, color_code) VALUES (:name, :color_code)");
        return $stmt->execute([
            ':name'       => $data['name'],
            ':color_code' => $data['color_code']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE rarities SET name = :name, color_code = :color_code WHERE id = :id");
        return $stmt->execute([
            ':name'       => $data['name'],
            ':color_code' => $data['color_code'],
            ':id'         => $id
        ]);

    }
}