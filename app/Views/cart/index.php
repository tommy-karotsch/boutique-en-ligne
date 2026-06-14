<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 800px; margin: 20px auto; padding: 20px;">
    <h1>Mon Panier</h1>

    <?php if (empty($cartList)): ?>
        <p>Votre panier est vide.</p>
        <a href="/boutique-en-ligne/public/">Retourner à la boutique</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartList as $item): ?>
                    <tr>
                        
                        <td>
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                        </td>

                        <td>
                            <?= htmlspecialchars($item['price']) ?> CR
                        </td>

                        <td>
                            <form method="POST" action="/boutique-en-ligne/public/cart/updateQuantity">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="0">
                                <button type="submit" class="btn">OK</button>
                            </form>
                        </td>

                        <td>
                            <?= htmlspecialchars($item['price'] * $item['quantity']) ?> CR
                        </td>

                        <td>
                            <a href="/boutique-en-ligne/public/cart/remove?id=<?= $item['id'] ?>" class="btn btn--danger">Retirer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div>
            <h2>Total : <?= htmlspecialchars($totalPrice) ?> Crédits</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/boutique-en-ligne/public/order/checkout" class="btn">Passer la commande</a>
            <?php else: ?>
                <p><em>Veuillez <a href="/boutique-en-ligne/public/user/login">vous connecter</a> pour passer commande.</em></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
