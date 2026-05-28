<?php

namespace App\Models;

class ItemModel extends Model
{
    protected string $table = "items";

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
        INSERT INTO items(name, description, price, stock, image, category_id, rarity_id, color_id)
        VALUES (:name, :description, :price, :stock, :image, :category_id, :rarity_id, :color_id
        ");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data[':id'] = $id;
        $stmt = $this->db->prepare("
        UPDATE items
        SET name            = :name,
            description     = :description,
            price           = :price,
            stock           = :stock,
            image           = :image,
            category_id     = :category_id,
            rarity_id       = :rarity_id,
            color_id        = :color_id
        WHERE id = :id
    ");
    return $stmt->execute($data);
    }

    public function findAllWithDetails(): array
    {
        $stmt = $this->db->query("
        SELECT items.*,
        categories.name     AS category,
        rarities.name       AS rarity,
        rarities.color_code AS rarity_color,
        colors.name         AS color
        FROM items
        JOIN categories ON categories.id = items.category_id
        JOIN rarities   ON rarities.id   = items.rarity_id
        JOIN colors     ON colors.id     = items.color_id
        ");
        return $stmt->fetchAll();
    }
}