<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div>
    <h1>Commande confirmée ! </h1>
    <p>Merci pour votre commande, elle a bien été enregistrée.</p>

    <ul>
        <li>Numéro de commande :    <strong><?= htmlspecialchars($order['id']) ?></strong></li>
        <li>Total :                 <strong><?= htmlspecialchars($order['total']) ?> Crédits</strong></li>
        <li>Identifiant Rocket League : <strong><?= htmlspecialchars($order['game_id']) ?></strong></li>
        <li>Statut :                <strong><?= htmlspecialchars($order['status']) ?></strong></li>
    </ul>

    <a href="/boutique-en-ligne/public/item/index">Retourner à la boutique</a>
    <a href="/boutique-en-ligne/public/order/index">Voir mes commandes</a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>