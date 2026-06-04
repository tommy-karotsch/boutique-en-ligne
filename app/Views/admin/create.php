<?php 

require_once __DIR__ . '/../layout/header.php';

?>

<h1>Ajouter un nouvel item</h1>

<?php if(isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" style="max-width: 600px; margin-top: 20px">

    <div style="margin-bottom: 10px;">
        <label>Nom :</label><br>
        <input type="text" name="name" required style="width: 100%;" required>
    </div>

    <div style="margin-botttom: 10px;">
        <label>Description :</label><br>
        <textarea name="description" rows="4" style="width: 100%;"></textarea>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Prix (en Crédits) :</label><br>
        <input type="number" name="price" required style="width: 100%;" required>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Stock :</label><br>
        <input type="number" name="stock" required style="width: 100%;" required>
    </div>

    <div style="margin-bottom: 10px;">
        <label>URL de l'image :</label><br>
        <input type="text" name="image" style="width: 100%;" required>
    </div>

    <div>
        <label>Catégorie : </label><br>
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach; ?>    
        </select>
    </div>

    <div>
        <label>Rareté : </label><br>
        <select name="rarity_id" required>
            <?php foreach ($rarities as $rarity): ?>
                <option value="<?= $rarity['id'] ?>"><?= $rarity['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="colors_id"></label><br>
        <select name="color_id" required>
            <?php foreach ($colors as $color): ?>
                <option value="<?= $color['id'] ?>"><?= $color['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <button type="submit" style="margin-top: 20px; padding: 10px 20px;">Ajouter l'item</button>
    </div>

</form>