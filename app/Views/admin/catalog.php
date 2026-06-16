<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="catalog-admin__header">
    <a href="/boutique-en-ligne/public/admin/index" class="btn">← Retour admin</a>
    <h1>Gestion du catalogue</h1>
</div>

<?php if (isset($error)): ?>
    <p class="form__error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="catalog-admin">

    <section class="catalog-admin__section">
        <h2>Catégories</h2>
        <ul class="catalog-admin__list">
            <?php foreach ($categories as $cat): ?>
                <li>
                    <form method="POST" action="/boutique-en-ligne/public/admin/catalogUpdate" class="catalog-admin__edit">
                        <input type="hidden" name="type" value="category">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" onchange="this.form.submit()" class="catalog-admin__name">
                        <span class="catalog-admin__count"><?= htmlspecialchars($cat['item_count']) ?> item(s)</span>
                    </form>
                    <form method="POST" action="/boutique-en-ligne/public/admin/catalogDelete" onsubmit="return confirm('Supprimer ?');">
                        <input type="hidden" name="type" value="category">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <button type="submit" class="btn btn--danger btn--small">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="POST" action="/boutique-en-ligne/public/admin/catalogAdd" class="catalog-admin__add">
            <input type="hidden" name="type" value="category">
            <input type="text" name="name" placeholder="Nouvelle catégorie" required class="form__input">
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </section>

    <section class="catalog-admin__section">
        <h2>Raretés</h2>
        <ul class="catalog-admin__list">
            <?php foreach ($rarities as $rarity): ?>
                <li>
                    <form method="POST" action="/boutique-en-ligne/public/admin/catalogUpdate" class="catalog-admin__edit">
                        <input type="hidden" name="type" value="rarity">
                        <input type="hidden" name="id" value="<?= $rarity['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($rarity['name']) ?>" onchange="this.form.submit()" class="catalog-admin__name">
                        <span class="catalog-admin__count"><?= htmlspecialchars($rarity['item_count']) ?> item(s)</span>
                        <input type="color" name="color_code" value="<?= htmlspecialchars($rarity['color_code']) ?>" onchange="this.form.submit()">
                    </form>
                    <form method="POST" action="/boutique-en-ligne/public/admin/catalogDelete" onsubmit="return confirm('Supprimer ?');">
                        <input type="hidden" name="type" value="rarity">
                        <input type="hidden" name="id" value="<?= $rarity['id'] ?>">
                        <button type="submit" class="btn btn--danger btn--small">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="POST" action="/boutique-en-ligne/public/admin/catalogAdd" class="catalog-admin__add">
            <input type="hidden" name="type" value="rarity">
            <input type="text" name="name" placeholder="Nouvelle rareté" required class="form__input">
            <input type="color" name="color_code">
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </section>

    <section class="catalog-admin__section">
        <h2>Couleurs</h2>
        <ul class="catalog-admin__list">
            <?php foreach ($colors as $color): ?>
                <li>
                    <form method="POST" action="/boutique-en-ligne/public/admin/catalogUpdate" class="catalog-admin__edit">
                        <input type="hidden" name="type" value="color">
                        <input type="hidden" name="id" value="<?= $color['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($color['name']) ?>" onchange="this.form.submit()" class="catalog-admin__name">
                        <span class="catalog-admin__count"><?= htmlspecialchars($color['item_count']) ?> item(s)</span>
                        <input type="color" name="hex_code" value="<?= htmlspecialchars($color['hex_code']) ?>" onchange="this.form.submit()">
                    </form>
                    <form method="POST" action="/boutique-en-ligne/public/admin/catalogDelete" onsubmit="return confirm('Supprimer ?');">
                        <input type="hidden" name="type" value="color">
                        <input type="hidden" name="id" value="<?= $color['id'] ?>">
                        <button type="submit" class="btn btn--danger btn--small">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="POST" action="/boutique-en-ligne/public/admin/catalogAdd" class="catalog-admin__add">
            <input type="hidden" name="type" value="color">
            <input type="text" name="name" placeholder="Nouvelle couleur" required class="form__input">
            <input type="color" name="hex_code">
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </section>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
