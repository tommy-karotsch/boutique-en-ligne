<?php

require_once __DIR__ . '/../layout/header.php';

?>

<div class="container checkout">
    <h1>Validation de commande</h1>

<h2>Récapitulatif de votre commande</h2>
<table class="table">
    <thead>
        <tr>
            <th>Article</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cartPreview as $ligne): ?>
            <tr>
                <td><?= htmlspecialchars($ligne[0]) ?></td>
                <td><?= htmlspecialchars($ligne[1]) ?></td>
                <td><?= htmlspecialchars($ligne[2]) ?> CR</td>
                <td><?= htmlspecialchars($ligne[3]) ?> CR</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <h2 class="checkout__total">Total à payer : <?= htmlspecialchars($totalPrice) ?> Crédits</h2>
    <?php if(!empty($errors)): ?>
        <div class="checkout__errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="POST" action="/boutique-en-ligne/public/order/checkout">
        
        <div class="form__group">
            <label class="form__label" for="game_id"><strong>Identifiant Rocket League (Epic Games) :</strong></label>
            <input type="text" name="game_id" id="game_id" required class="form__input" placeholder="Ex : TommyK#1234" value="<?= htmlspecialchars($gameId) ?>">
            <small class="checkout__hint">Vos items seront livrés directement sur ce compte.</small>
        </div>

        <div class="checkout__actions">
            <a href="/boutique-en-ligne/public/cart/index" class="btn">Retour au panier</a>
            <button type="submit" class="btn btn--primary">Confirmer et Payer</button>
        </div>
    </form>
</div>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>
