<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 600px; margin: 20px auto;">
    <h1>Mon Profil</h1>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" action="/boutique-en-ligne/public/user/profile">

        <div style="margin-bottom: 10px;">
            <label>Nom d'utilisateur :</label><br>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Email :</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Adresse :</label><br>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="password">Nouveau mot de passe :</label><br>
            <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="password_confirm">Confirmer le nouveau mot de passe :</label><br>
            <input type="password" name="password_confirm" id="password_confirm" placeholder="Retaper le mot de passe" style="width: 100%;">
        </div>

        <button type="submit" style="padding: 10px 15px; background: #007bff; color: white; border: none;">Enregistrer les modifications</button>
    </form>

    <hr style="margin: 30px 0;">

    <h2 style="color: red;">Supprimer mon compte</h2>
    <form method="POST" action="/boutique-en-ligne/public/user/delete" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible. Et supprimera toutes vos données.');">
        <button type="submit" style="padding: 10px 15px; background: red; color: white; border: none;">Supprimer mon compte</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>