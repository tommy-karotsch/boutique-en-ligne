<?php

require_once __DIR__ . '/../layout/header.php';

?>

<div>
    <h1>Admin Dashboard</h1>
    <a href="/boutique-en-ligne/public/admin/create">Ajouter un item</a>
</div>

<table>
    
    <thead>
        <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Rareté</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Actions</th>    
        </tr>
    </thead>

    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['category']) ?></td>
                <td><?= htmlspecialchars($item['rarity']) ?></td>
                <td><?= htmlspecialchars($item['price']) ?></td>
                <td><?= htmlspecialchars($item['stock']) ?></td>
                <td>
                    <a href="/boutique-en-ligne/public/admin/edit?id=<?= $item['id'] ?>">Modifier</a>
                    <a href="/boutique-en-ligne/public/admin/delete?id=<?= $item['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
    </tbody>

</table>