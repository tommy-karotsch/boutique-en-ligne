<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 400px; margin: 20px auto; padding: 20px; border: 1px solid #ccc;">
    <h1>Connexion</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 10px;">
            <label>Email :</label><br>
            <input type="email" name="email" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Mot de passe :</label><br>
            <input type="password" name="password" required style="width: 100%;">
        </div>

        <button type="submit" style="padding: 10px 15px; background: #28a745; color: white; border: none;">Se connecter</button>
    </form>
    
    <p style="margin-top: 15px;">Pas encore de compte ? <a href="/boutique-en-ligne/public/user/register">S'inscrire</a></p>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
