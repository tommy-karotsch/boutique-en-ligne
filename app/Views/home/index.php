<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="hero">
    <h1 class="hero__title">La boutique des collectors</h1>
    <p class="hero__subtitle">Items Rares · Exotiques · Marché noir</p>
    <a href="/boutique-en-ligne/public/item/index" class="btn btn--primary">Voir le catalogue</a>
</section>

<section class="catalogue">
    <h2>Items populaires</h2>
    <div id="catalogue"></div>
</section>

<script src="/boutique-en-ligne/public/js/catalogue.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>