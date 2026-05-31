<?php 

require_once __DIR__ . '/../layout/header.php';
?>

<h1>Boutique Rocket League</h1>

<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    <?php foreach ($items as $item): ?>
        <div style="border: 2px solid <?= htmlspecialchars($item['rarity_color'] ?? '#000'); ?>; padding: 10px; width: 250px;">
            <h2><?= htmlspecialchars($item['name']) ?></h2>
            <p>Catégorie : <?= htmlspecialchars($item['category'] ?? 'N/A') ?></p>
            <p>Couleur : <?= htmlspecialchars($item['color'] ?? 'Standard') ?></p>
            <p><strong>Prix : <?= htmlspecialchars($item['price']) ?> Crédits</strong></p>
            <a href="/boutique-rl/public/item/show?id=<?= $item['id'] ?>">Voir les détails</a>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
