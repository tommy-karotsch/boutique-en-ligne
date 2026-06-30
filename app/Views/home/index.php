<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="hero">


    <div class="hero__left">
        <h1 class="hero__title">Boutique<br>Rocket League</h1>
        <p class="hero__subtitle">Items Rares · Exotiques · Marché noir</p>
        <a href="/boutique-en-ligne/public/item/index" class="btn btn--primary">Voir le catalogue</a>
    </div>

    <div class="hero__right">
        <ul class="hero__stats">
            <li class="hero__stat">
                <span class="hero__stat-number"><?= $nbItems ?></span>
                <span class="hero__stat-label">Items</span>
            </li>
            <li class="hero__stat">
                <span class="hero__stat-number"><?= $nbCategories ?></span>
                <span class="hero__stat-label">Catégories</span>
            </li>
            <li class="hero__stat">
                <span class="hero__stat-number"><?= $nbColors ?></span>
                <span class="hero__stat-label">Couleurs</span>
            </li>
            <li class="hero__stat">
                <span class="hero__stat-number"><?= $nbRarities ?></span>
                <span class="hero__stat-label">Raretés</span>
            </li>
        </ul>
    </div>
</section>

<section class="catalogue-populaire">
    <h2>Items rares</h2>
    <div id="catalogue"></div>
</section>

<script src="/boutique-en-ligne/public/js/catalogue.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>