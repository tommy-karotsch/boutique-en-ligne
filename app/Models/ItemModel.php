<?php

namespace App\Models;

class ItemModel extends Model
{
    protected string $table = "items";

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

    public function findByIdWithRelations(int $id): ?array
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

    public function filter(array $filters, ?string $sort = null): array
    {
        $sql = " SELECT items.*,
        categories.name     AS category,
        rarities.name       AS rarity,
        rarities.color_code AS rarity_color,
        colors.name         AS color
        FROM items
        JOIN categories ON categories.id = items.category_id
        JOIN rarities   ON rarities.id   = items.rarity_id
        JOIN colors     ON colors.id     = items.color_id 
        ";

        $conditions = [];
        $bindings = [];

        if (!empty($filters['category_id'])){
            $conditions[] = "items.category_id = :category_id";
            $bindings[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['rarity_id'])){
            $conditions[] = 'items.rarity_id = :rarity_id';
            $bindings[':rarity_id'] = $filters['rarity_id'];
        }

        if (!empty($filters['color_id'])){
            $conditions[] = 'items.color_id = :color_id';
            $bindings[':color_id'] = $filters['color_id'];
        }


        if (!empty($conditions)){
            $sql .= " WHERE " . implode(' AND ', $conditions); 
        }


        if ($sort === 'price_asc'){
            $sql .= " ORDER BY items.price ASC ";
        } elseif ($sort === 'price_desc'){
            $sql .= " ORDER BY items.price DESC";
        } elseif ($sort === 'rarity'){
            $sql .= " ORDER BY items.rarity_id DESC";
        }



        $stmt = $this->db->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    public function findRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT items.*, categories.name AS category
            FROM items
            JOIN categories ON categories.id = items.category_id
            ORDER BY items.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}









