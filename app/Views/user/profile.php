<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="form">
    <h1>MON PROFIL</h1>

    <?php if (isset($success)): ?>
        <p class="form__success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="form__error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/boutique-en-ligne/public/user/profile">

        <div class="form__group">
            <label class="form__label" for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="address">Adresse :</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="password">Nouveau mot de passe :</label>
            <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer" class="form__input">
        </div>

        <div class="form__group">
            <label class="form__label" for="password_confirm">Confirmer le nouveau mot de passe :</label>
            <input type="password" name="password_confirm" id="password_confirm" placeholder="Retaper le mot de passe" class="form__input">
        </div>

        <button type="submit" class="btn">Enregistrer les modifications</button>
    </form>

    <hr style="margin: 20px 0;">

    <h2>Supprimer mon compte</h2>
    <form method="POST" action="/boutique-en-ligne/public/user/delete" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible. Et supprimera toutes vos données.');">
        <button type="submit" class="btn btn--danger">Supprimer mon compte</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>