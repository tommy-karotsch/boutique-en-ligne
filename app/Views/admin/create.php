<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="form">
<h1>Ajouter un nouvel item</h1>

<?php if(isset($error)): ?>
    <p class="form__error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">

    <div class="form__group">
        <label class="form__label" for="name">Nom :</label>
        <input type="text" id="name" name="name" class="form__input" required>
    </div>

    <div class="form__group">
        <label class="form__label" for="description">Description :</label>
        <textarea id="description" name="description" rows="4" class="form__input"></textarea>
    </div>

    <div class="form__group">
        <label class="form__label" for="price">Prix (en Crédits) :</label>
        <input type="number" id="price" name="price" class="form__input" min="100" max="2500" step="1" required>
    </div>

    <div class="form__group">
        <label class="form__label" for="stock">Stock :</label>
        <input type="number" id="stock" name="stock" class="form__input" min="0" required>
    </div>

    <div class="form__group">
        <label class="form__label" for="image">URL de l'image :</label>
        <input type="text" id="image" name="image" class="form__input">
    </div>

    <div class="form__group">
        <label class="form__label">Catégorie : </label>
        <select class="form__input" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
            <?php endforeach; ?>    
        </select>
    </div>

    <div class="form__group">
        <label class="form__label">Rareté : </label>
        <select class="form__input" name="rarity_id" required>
            <?php foreach ($rarities as $rarity): ?>
                <option value="<?= htmlspecialchars($rarity['id']) ?>"><?= htmlspecialchars($rarity['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form__group">
        <label class="form__label">Couleur : </label>
        <select class="form__input" name="color_id" required>
            <?php foreach ($colors as $color): ?>
                <option value="<?= htmlspecialchars($color['id']) ?>"><?= htmlspecialchars($color['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form__group">
        <button type="submit" class="btn btn--primary">Ajouter l'item</button>
    </div>

</form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>