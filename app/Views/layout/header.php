<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/boutique-en-ligne/public/css/style.css">
    <title>RL Shop</title>
</head>
<body>
    <header class="header">
        <button class="nav-toggle" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
        
        <nav class="nav" id="nav">
            <a href="/boutique-en-ligne/public/" class="nav-link">Accueil</a>
            <a href="/boutique-en-ligne/public/item/index" class="nav-link">Catalogue</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                
                <span class="nav-user">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?> !</span>

            <div class="nav-spacer"></div>

            <?php if ($_SESSION['user_role'] === 'admin'): ?>

                    <a href="/boutique-en-ligne/public/admin/index" class="nav-link">Admin</a> 

            <?php endif; ?>
            
                    <a href="/boutique-en-ligne/public/user/profile" class="nav-link">Mon profil</a>
                    <a href="/boutique-en-ligne/public/user/logout" class="nav-link">Se déconnecter</a>

            <?php else: ?>

            <div class="nav-spacer"></div>

                    <a href="/boutique-en-ligne/public/user/login" class="nav-link">Se connecter</a>
                    <a href="/boutique-en-ligne/public/user/register" class="nav-link">S'inscrire</a>

            <?php endif; ?>

            <a href="/boutique-en-ligne/public/cart/index" class="nav-link">
                Panier (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)
            </a>

        </nav>
    </header>
    <main class="main">
        <div class="container">
