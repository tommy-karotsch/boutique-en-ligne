<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="product" style="border-color: <?= htmlspecialchars($item['rarity_color'] ?? '#000') ?>;">
    <h1 class="product__name"><?= htmlspecialchars($item['name']) ?></h1>

    <?php if (!empty($item['image'])): ?>
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product__image">
    <?php endif; ?>

    <p class="product__desc"><strong>Description :</strong> <?= nl2br(htmlspecialchars($item['description'] ?? 'Aucune description disponible.')) ?></p>

    <ul class="product__details">
        <li><strong>Catégorie :</strong> <?= htmlspecialchars($item['category'] ?? 'N/A') ?></li>
        <li><strong>Rareté :</strong> <?= htmlspecialchars($item['rarity'] ?? 'N/A') ?></li>
        <li><strong>Couleur :</strong> <?= htmlspecialchars($item['color'] ?? 'Standard') ?></li>
        <li><strong>Stock :</strong> <?= htmlspecialchars($item['stock']) ?> unités</li>
    </ul>

    <h2 class="product__price"><?= htmlspecialchars($item['price']) ?> Crédits</h2>

    <div class="product__actions">
        <a href="/boutique-en-ligne/public/item/index" class="btn">Retour à la boutique</a>

        <?php if ($item['stock'] > 0): ?>
            <a href="/boutique-en-ligne/public/cart/add?id=<?= $item['id'] ?>" class="btn btn--primary">Ajouter au panier</a>
        <?php else: ?>
            <span class="product__stock-out">Rupture de stock</span>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>