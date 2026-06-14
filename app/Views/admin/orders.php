<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1>Suivi des commandes</h1>

<form method="GET" action="/boutique-en-ligne/public/admin/orders">
    <select name="status">
        <option value="">Tous les status</option>
        <option value="pending"   <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
        <option value="shipped"   <?= ($_GET['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Expédiée</option>
        <option value="delivered" <?= ($_GET['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>Livrée</option>
        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
    </select>
    <button type="submit">Filtrer</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th>N°</th>
            <th>Client</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Changer le statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['username']) ?></td>
                <td><?= htmlspecialchars($order['ordered_at']) ?></td>
                <td><?= htmlspecialchars($order['total']) ?></td>
                <td><?= htmlspecialchars($order['status']) ?></td>
                <td>
                    <form method="POST" action="/boutique-en-ligne/public/admin/updateOrderStatus">
                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <select name="status">
                            <option value="pending"   <?= ($order['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="shipped"   <?= ($order['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Expédiée</option>
                            <option value="delivered" <?= ($order['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>Livrée</option>
                            <option value="cancelled" <?= ($order['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                        <button type="submit">OK</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>