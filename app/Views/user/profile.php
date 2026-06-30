<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1>Mon profil</h1>

    <div class="profile-card">
        <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Commandes passées :</strong> <?= $nbOrders ?></p>
    </div>

    <div class="profile-actions">
        <a href="/boutique-en-ligne/public/user/editProfile" class="btn btn--primary">Modifier mes informations</a>
        <a href="/boutique-en-ligne/public/order/index" class="btn">Mes commandes</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>