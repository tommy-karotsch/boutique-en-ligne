<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RL Shop</title>
</head>
<body>
    <header style="background: #333; padding: 10px; color: white;">
        <nav style="display: flex; gap: 15px; align-items: center;">
            <a href="/boutique-en-ligne/public/" style="color: white; text-decoration: none;">Accueil</a>
            <a href="/boutique-en-ligne/public/item/index" style="color: white; text-decoration: none;">Tous les Items</a>
            
            <div style="flex-grow: 1;"></div> <!-- Espace blanc -->
            
            <a href="/boutique-en-ligne/public/cart/index" style="color: #44C864; text-decoration: none; border: 1px solid #44C864; padding: 5px 10px; border-radius: 5px;">
                Panier (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Bonjour, <?= htmlspecialchars($_SESSION['username']) ?> !</span>
                <a href="/boutique-en-ligne/public/user/logout" style="color: #ffcccc; text-decoration: none;">Se déconnecter</a>
            <?php else: ?>
                <a href="/boutique-en-ligne/public/user/login" style="color: white; text-decoration: none;">Se connecter</a>
                <a href="/boutique-en-ligne/public/user/register" style="color: white; text-decoration: none;">S'inscrire</a>
            <?php endif; ?>
        </nav>
    </header>
    <main style="padding: 20px;">
