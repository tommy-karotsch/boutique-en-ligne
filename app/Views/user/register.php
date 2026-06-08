<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 400px; margin: 20px auto; padding: 20px; border: 1px solid #ccc;">
    <h1>Inscription</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 10px;">
            <label>Nom d'utilisateur :</label><br>
            <input type="text" name="username" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Email :</label><br>
            <input type="email" name="email" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Mot de passe :</label><br>
            <input type="password" name="password" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Confirmer le mot de passe :</label><br>
            <input type="password" name="password_confirm" required style="width: 100%;">
        </div>

        <button type="submit" style="padding: 10px 15px; background: #007bff; color: white; border: none;">S'inscrire</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
