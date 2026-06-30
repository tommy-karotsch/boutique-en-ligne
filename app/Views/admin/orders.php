<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container">
<h1>Suivi des commandes</h1>

<form method="GET" action="/boutique-en-ligne/public/admin/orders" class="order-filter" id="order-filter">
    <label class="order-filter__label" for="status">Filtrer par statut :</label>
    <select name="status" id="status" class="order-filter__select">
        <option value="">Tous les statuts</option>
        <option value="pending"   <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
        <option value="shipped"   <?= ($_GET['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Expédiée</option>
        <option value="delivered" <?= ($_GET['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>Livrée</option>
        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
    </select>
    <button type="submit" class="btn btn--small">Filtrer</button>
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
                <td><?= htmlspecialchars($order['total']) ?> Crédits</td>
                <td><span class="status-badge status-badge--<?= htmlspecialchars($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                <td>
                    <form method="POST" action="/boutique-en-ligne/public/admin/updateOrderStatus" class="order-status-form">
                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <select name="status">
                            <option value="pending"   <?= ($order['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="shipped"   <?= ($order['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Expédiée</option>
                            <option value="delivered" <?= ($order['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>Livrée</option>
                            <option value="cancelled" <?= ($order['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                        <button type="submit" class="btn btn--small">OK</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>