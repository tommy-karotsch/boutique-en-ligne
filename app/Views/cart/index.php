<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1>Mon panier</h1>

    <?php if (empty($cartList)): ?>
        <div class="cart-empty">
            <p class="cart-empty__text">Votre panier est vide pour le moment.</p>
            <a href="/boutique-en-ligne/public/item/index" class="btn btn--primary">Découvrir le catalogue</a>
        </div>
    <?php else: ?>
        <div class="cart">
            <table class="table cart__table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartList as $item): ?>
                        <tr>
                            <td>
                                <div class="cart__item">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart__thumb">
                                    <?php endif; ?>
                                    <a href="/boutique-en-ligne/public/item/show?id=<?= $item['id'] ?>" class="cart__name">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </a>
                                </div>
                            </td>

                            <td><?= htmlspecialchars($item['price']) ?> Crédits</td>

                            <td>
                                <form method="POST" action="/boutique-en-ligne/public/cart/updateQuantity" class="cart__qty-form">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="0" class="cart__qty-input">
                                    <button type="submit" class="btn btn--small">OK</button>
                                </form>
                            </td>

                            <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?> Crédits</td>

                            <td>
                                <a href="/boutique-en-ligne/public/cart/remove?id=<?= $item['id'] ?>" class="btn btn--danger btn--small">Retirer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart__summary">
                <h2 class="cart__total">Total : <?= htmlspecialchars($totalPrice) ?> Crédits</h2>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/boutique-en-ligne/public/order/checkout" class="btn btn--primary">Passer la commande</a>
                <?php else: ?>
                    <p class="cart__notice">Veuillez <a href="/boutique-en-ligne/public/user/login">vous connecter</a> pour passer commande.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
