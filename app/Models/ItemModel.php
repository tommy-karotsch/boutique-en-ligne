<?php

namespace App\Models;

class ItemModel extends Model
{
    protected string $table = "items";

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
        INSERT INTO items(name, description, price, stock, image, category_id, rarity_id, color_id)
        VALUES (:name, :description, :price, :stock, :image, :category_id, :rarity_id, :color_id)
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

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
        SELECT items.*,
        categories.name     AS category,
        rarities.name       AS rarity,
        rarities.color_code AS rarity_color,
        colors.name         AS color
        FROM items
        JOIN categories ON categories.id = items.category_id
        JOIN rarities   ON rarities.id   = items.rarity_id
        JOIN colors     ON colors.id     = items.color_id
        WHERE items.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public function findByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare("
        SELECT items.*, categories.name AS category_name, rarities.name AS rarity_name, colors.name AS color_name 
        FROM {$this->table} 
        JOIN categories ON categories.id = items.category_id
        JOIN rarities ON rarities.id = items.rarity_id
        JOIN colors ON colors.id = items.color_id
        WHERE category_id = :category_id");
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function findByRarity(int $rarityId): array
    {
        $stmt = $this->db->prepare("
        SELECT items.*, categories.name AS category_name, rarities.name AS rarity_name, colors.name AS color_name 
        FROM {$this->table} 
        JOIN categories ON categories.id = items.category_id
        JOIN rarities ON rarities.id = items.rarity_id
        JOIN colors ON colors.id = items.color_id
        WHERE rarity_id = :rarity_id");
        $stmt->execute([':rarity_id' => $rarityId]);
        return $stmt->fetchAll();
    }

    public function findByColor(int $colorId): array
    {
        $stmt = $this->db->prepare("
        SELECT items.*, categories.name AS category_name, rarities.name AS rarity_name, colors.name AS color_name 
        FROM {$this->table} 
        JOIN categories ON categories.id = items.category_id
        JOIN rarities ON rarities.id = items.rarity_id
        JOIN colors ON colors.id = items.color_id
        WHERE color_id = :color_id");
        $stmt->execute([':color_id' => $colorId]);
        return $stmt->fetchAll();
    }
}
