<?php

namespace App\Models;

class ColorModel extends Model
{
    protected string $table = 'colors';

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO colors (name, hex_code) VALUES (:name, :hex_code)");
        return $stmt->execute([
            ':name'       => $data['name'],
            ':hex_code' => $data['hex_code']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE colors SET name = :name, hex_code = :hex_code WHERE id = :id");
        return $stmt->execute([
            ':name'       => $data['name'],
            ':hex_code' => $data['hex_code'],
            ':id'         => $id
        ]);

    }
}