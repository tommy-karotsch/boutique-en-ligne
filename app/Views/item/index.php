<?php 

require_once __DIR__ . '/../layout/header.php';
?>

<h1>Boutique Rocket League</h1>

<form method="GET" action="/boutique-en-ligne/public/item/index" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">

    <select name="category_id">
        <option value="">Toutes les catégories</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category['id']) ?>"
                <?= ($_GET['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="rarity_id">
        <option value="">Toutes les raretés</option>
        <?php foreach ($rarities as $rarity): ?>
            <option value="<?= htmlspecialchars($rarity['id']) ?>"
                <?= ($_GET['rarity_id'] ?? '') == $rarity['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($rarity['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>


    <select name="color_id">
        <option value="">Toutes les couleurs</option>
        <?php foreach ($colors as $color): ?>
            <option value="<?= htmlspecialchars($color['id']) ?>"
                <?= ($_GET['color_id'] ?? '') == $color['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($color['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>


    <select name="sort">
        <option value="">Trier par</option>
        <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
        <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
        <option value="rarity" <?= ($_GET['sort'] ?? '') === 'rarity' ? 'selected' : '' ?>>Rareté</option>
    </select>

    <button type="submit">Filtrer</button>
    <a href="/boutique-en-ligne/public/item/index">Réinitialiser</a>
</form>


<div class="catalogue">
    <?php foreach ($items as $item): ?>
        <div class="item-card" style="border-color: <?= htmlspecialchars($item['rarity_color'] ?? '#000') ?>;">
            <h2 class="item-card__name"><?= htmlspecialchars($item['name']) ?></h2>
            <p class="item-card__info">Catégorie : <?= htmlspecialchars($item['category']) ?></p>
            <p class="item-card__info">Couleur : <?= htmlspecialchars($item['color'] ?? 'Standard') ?></p>
            <p class="item-card__price"><?= htmlspecialchars($item['price']) ?> Crédits</p>
            <a href="/boutique-en-ligne/public/item/show?id=<?= $item['id'] ?>" class="item-card__link">Voir les détails</a>
        </div>
    <?php endforeach; ?>
</div>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>
