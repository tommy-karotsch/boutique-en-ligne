<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1>Modifier un item</h1>

<?php if(isset($error)): ?>
    <p class="form__error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" style="max-width: 600px; margin-top: 20px">

    <div class="form__group">
        <label class="form__label">Nom :</label>
        <input type="text" name="name" class="form__input" value="<?= htmlspecialchars($item['name']) ?>" required>
    </div>

    <div class="form__group">
        <label class="form__label">Description :</label>
        <textarea name="description" rows="4" class="form__input"><?= htmlspecialchars($item['description']) ?></textarea>
    </div>

    <div class="form__group">
        <label class="form__label">Prix (en Crédits) :</label>
        <input type="number" name="price" class="form__input" value="<?= htmlspecialchars($item['price']) ?>" required>
    </div>

    <div class="form__group">
        <label class="form__label">Stock :</label>
        <input type="number" name="stock" class="form__input" value="<?= htmlspecialchars($item['stock']) ?>" required>
    </div>

    <div class="form__group">
        <label class="form__label">URL de l'image :</label>
        <input type="text" name="image" class="form__input" value="<?= htmlspecialchars($item['image']) ?>">
    </div>

    <div class="form__group">
        <label class="form__label">Catégorie : </label>
        <select name="category_id" class="form__input" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['id']) ?>" 
                    <?= $item['category_id'] == $category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>    
        </select>
    </div>

    <div class="form__group">
        <label class="form__label">Rareté : </label>
        <select name="rarity_id" class="form__input" required>
            <?php foreach ($rarities as $rarity): ?>
                <option value="<?= htmlspecialchars($rarity['id']) ?>"
                    <?= $item['rarity_id'] == $rarity['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($rarity['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form__group">
        <label class="form__label">Couleur : </label>
        <select name="color_id" class="form__input" required>
            <?php foreach ($colors as $color): ?>
                <option value="<?= htmlspecialchars($color['id']) ?>"
                    <?= $item['color_id'] == $color['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($color['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <button type="submit" class="btn">Modifier l'item</button>
    </div>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>