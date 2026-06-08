<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 800px; margin: 20px auto; padding: 20px;">
    <h1>Mon Panier</h1>

    <?php if (empty($cartList)): ?>
        <p>Votre panier est vide.</p>
        <a href="/boutique-en-ligne/public/">Retourner à la boutique</a>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #333;">
                    <th style="padding: 10px;">Article</th>
                    <th style="padding: 10px;">Prix Unitaire</th>
                    <th style="padding: 10px;">Quantité</th>
                    <th style="padding: 10px;">Total</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartList as $item): ?>
                    <tr style="border-bottom: 1px solid #ccc;">
                        
                        <td style="padding: 10px;">
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                        </td>

                        <td style="padding: 10px;">
                            <?= htmlspecialchars($item['price']) ?> CR
                        </td>

                        <td style="padding: 10px;">
                            <form method="POST" action="/boutique-en-ligne/public/cart/updateQuantity" style="display: flex; gap: 5px;">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="0" style="width: 60px;">
                                <button type="submit">OK</button>
                            </form>
                        </td>

                        <td style="padding: 10px;">
                            <?= htmlspecialchars($item['price'] * $item['quantity']) ?> CR
                        </td>

                        <td style="padding: 10px;">
                            <a href="/boutique-en-ligne/public/cart/remove?id=<?= $item['id'] ?>" style="color: red; text-decoration: none;">Retirer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <h2>Total : <?= htmlspecialchars($totalPrice) ?> Crédits</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/boutique-en-ligne/public/order/checkout" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">Passer la commande</a>
            <?php else: ?>
                <p style="color: #666;"><em>Veuillez <a href="/boutique-en-ligne/public/user/login">vous connecter</a> pour passer commande.</em></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
