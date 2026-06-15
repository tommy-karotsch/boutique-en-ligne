<?php

require_once __DIR__ . '/../layout/header.php';

?>

<div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
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

    <h2 style="color: #007bff;">Total à payer : <?= htmlspecialchars($totalPrice) ?> Crédits</h2>
    <?php if(!empty($errors)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="POST" action="/boutique-en-ligne/public/order/checkout">
        
        <div style="margin-top: 15px;">
            <label for="delivery_address"><strong>Veuillez indiquer votre adresse de livraison :</strong></label><br>
            <textarea name="delivery_address" id="delivery_address" rows="5" required style="width: 100%; margin-top: 5px; padding: 10px;"><?= htmlspecialchars($address) ?></textarea>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="/boutique-en-ligne/public/cart/index" style="margin-right: 15px; color: #555; text-decoration: none;">Retour au panier</a>
            
            <button type="submit" class="btn">Confirmer et Payer</button>
        </div>
    </form>
</div>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>
