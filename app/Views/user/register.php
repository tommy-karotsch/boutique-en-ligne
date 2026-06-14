<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="form">
    <h1>Inscription</h1>

    <?php if (isset($error)): ?>
        <p class="form__error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form__group">
            <label class="form__label" for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="password_confirm">Confirmer le mot de passe :</label>
            <input type="password" id="password_confirm" name="password_confirm" required class="form__input">
        </div>

        <button type="submit" class="btn">S'inscrire</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
