<?php

require_once __DIR__ . '/../layout/header.php';

?>

<div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
    <h1>Validation de commande</h1>

    <h2 style="color: #007bff;">Total à payer : <?= htmlspecialchars($totalPrice) ?> Crédits</h2>

    <!-- Le formulaire renvoie les données vers order/confirm -->
    <form method="POST" action="/boutique-en-ligne/public/order/confirm">
        
        <div style="margin-top: 15px;">
            <label for="delivery_address"><strong>Veuillez indiquer votre adresse de livraison :</strong></label><br>
            <textarea name="delivery_address" id="delivery_address" rows="5" required style="width: 100%; margin-top: 5px; padding: 10px;"></textarea>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="/boutique-en-ligne/public/cart/index" style="margin-right: 15px; color: #555; text-decoration: none;">Retour au panier</a>
            
            <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                Confirmer et Payer
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
