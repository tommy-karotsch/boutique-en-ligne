<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div>
    <h1>Mes commandes</h1>

    <?php if (empty($orders)): ?> 
        <p>Vous n'avez pas encore de commande.</p>
        <a href="/boutique-en-ligne/public/item/index">Retourner à la boutique</a>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['ordered_at']) ?></td>
                        <td><?= htmlspecialchars($order['total']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>