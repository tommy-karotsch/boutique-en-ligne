<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section style="text-align: center; padding: 40px 20px;">
    <h1>La boutique des collectors</h1>
    <p>Items Rares · Exotiques · Marché noir</p>
    <a href="/boutique-en-ligne/public/item/index"
       style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #F39C12; color: white; text-decoration: none; border-radius: 5px;">
       Voir le catalogue
    </a>
</section>

<section style="padding: 20px;">
    <h2>Items populaires</h2>
    <div id="catalogue" style="display: flex; flex-wrap: wrap; gap: 20px;"></div>
</section>

<script src="/boutique-en-ligne/public/js/catalogue.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>