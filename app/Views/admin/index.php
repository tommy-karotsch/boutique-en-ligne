<?php

require_once __DIR__ . '/../layout/header.php';

?>

<div class="admin-actions">
    <h1>Admin Dashboard</h1>
    <a href="/boutique-en-ligne/public/admin/create">Ajouter un item</a>
    <a href="/boutique-en-ligne/public/admin/orders">Suivi des commandes</a>
    <a href="/boutique-en-ligne/public/admin/catalog">Gérer le catalogue</a>
</div>

<?php if (isset($error)): ?>
    <p class="form__error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>


<section class="dashboard">
    <div class="dashboard__card">
        <span class="dashboard__number"><?= htmlspecialchars($nbOrders) ?></span>
        <span class="dashboard__label">Commandes</span>
    </div>
    <div class="dashboard__card">
        <span class="dashboard__number"><?= htmlspecialchars(count($items)) ?></span>
        <span class="dashboard__label">Items au catalogue</span>
    </div>
</section>

<h2>Produits récemment ajoutés</h2>
<ul class="recent-list">
    <?php foreach ($recentItems as $recent): ?>
        <li>
            <strong><?= htmlspecialchars($recent['name']) ?></strong>
            - <?= htmlspecialchars($recent['category']) ?>
            (<?= htmlspecialchars($recent['price']) ?> Crédits)
        </li>
    <?php endforeach; ?>
</ul>


<table class="table">
    
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
                    <a href="/boutique-en-ligne/public/admin/edit?id=<?= $item['id'] ?>" class="btn">Modifier</a>
                    <form method="POST" action="/boutique-en-ligne/public/admin/delete">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn--danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>