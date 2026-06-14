<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="form">
    <h1>Connexion</h1>

    <?php if (isset($error)): ?>
        <p class="form__error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form__group">
            <label class="form__label" for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form__input">
        </div>

        <button type="submit" class="btn">Se connecter</button>
    </form>
    
    <p class="form__group" style="margin-top: 15px;">Pas encore de compte ? <a href="/boutique-en-ligne/public/user/register">S'inscrire</a></p>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
