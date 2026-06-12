<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="border: 2px solid <?= htmlspecialchars($item['rarity_color'] ?? '#000'); ?>; padding: 20px; max-width: 600px; margin: 20px auto;">
    <h1><?= htmlspecialchars($item['name']) ?></h1>
    
    <?php if (!empty($item['image'])): ?>
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="max-width: 100%; height: auto;">
    <?php endif; ?>

    <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($item['description'] ?? 'Aucune description disponible.')) ?></p>
    
    <ul>
        <li><strong>Catégorie :</strong> <?= htmlspecialchars($item['category'] ?? 'N/A') ?></li>
        <li><strong>Rareté :</strong> <?= htmlspecialchars($item['rarity'] ?? 'N/A') ?></li>
        <li><strong>Couleur :</strong> <?= htmlspecialchars($item['color'] ?? 'Standard') ?></li>
        <li><strong>Stock :</strong> <?= htmlspecialchars($item['stock']) ?> unités</li>
    </ul>

    <h2>Prix : <?= htmlspecialchars($item['price']) ?> Crédits</h2>

    <div style="margin-top: 20px;">
        <a href="/boutique-en-ligne/public/item/index" style="padding: 10px; background: #eee; text-decoration: none; color: black; border-radius: 5px;">Retour à la boutique</a>
        
<?php if ($item['stock'] > 0): ?>
            <a href="/boutique-en-ligne/public/cart/add?id=<?= $item['id'] ?>" style="padding: 10px; background: #28a745; text-decoration: none; color: white; border-radius: 5px; margin-left: 10px;">Ajouter au panier</a>
        <?php else: ?>
            <span style="padding: 10px; color: red; margin-left: 10px;">Rupture de stock</span>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
