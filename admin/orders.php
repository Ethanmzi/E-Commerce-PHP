<?php
$pageTitle = 'Gestion des Commandes';
require_once 'includes/header.php';

$pdo = getDB();

// Mise à jour du statut si demandé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];
    
    $allowedStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);
        setFlashMessage('success', 'Statut de la commande mis à jour.');
        header('Location: orders.php');
        exit;
    }
}

// Récupérer toutes les commandes avec infos utilisateur
$orders = $pdo->query("
    SELECT o.*, CONCAT(u.firstname, ' ', u.lastname) as user_name, u.email as user_email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
")->fetchAll();

// Fonction pour traduire les statuts
function getStatusBadge($status) {
    $badges = [
        'pending' => ['bg-warning text-dark', 'En attente'],
        'confirmed' => ['bg-info', 'Confirmée'],
        'shipped' => ['bg-primary', 'Expédiée'],
        'delivered' => ['bg-success', 'Livrée'],
        'cancelled' => ['bg-danger', 'Annulée']
    ];
    $badge = $badges[$status] ?? ['bg-secondary', $status];
    return '<span class="badge ' . $badge[0] . '">' . $badge[1] . '</span>';
}

function getPaymentBadge($status) {
    if ($status === 'paid') {
        return '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Payée</span>';
    }
    return '<span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i>En attente</span>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark"><i class="fa-solid fa-shopping-bag me-2"></i>Gestion des Commandes</h2>
    <span class="badge bg-primary fs-6"><?= count($orders) ?> commande(s)</span>
</div>

<?php if (empty($orders)): ?>
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Aucune commande pour le moment</h5>
            <p class="text-muted">Les commandes apparaîtront ici une fois passées par les clients.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">N° Commande</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Livraison</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-primary"><?= htmlspecialchars($order['order_number']) ?></strong>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($order['user_name'] ?? 'Client supprimé') ?></strong>
                                        <br><small class="text-muted"><?= htmlspecialchars($order['user_email'] ?? '-') ?></small>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-success"><?= formatPrice($order['total_amount']) ?></strong>
                                </td>
                                <td><?= getStatusBadge($order['status']) ?></td>
                                <td><?= getPaymentBadge($order['payment_status']) ?></td>
                                <td>
                                    <small>
                                        <?= htmlspecialchars($order['shipping_address']) ?><br>
                                        <?= htmlspecialchars($order['shipping_postal_code']) ?> <?= htmlspecialchars($order['shipping_city']) ?>
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($order['created_at'])) ?><br>
                                        <?= date('H:i', strtotime($order['created_at'])) ?>
                                    </small>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderModal<?= $order['id'] ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#statusModal<?= $order['id'] ?>">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- MODALS EN DEHORS DU TABLEAU -->
<?php foreach ($orders as $order): ?>
    <!-- Modal Détails -->
    <div class="modal fade" id="orderModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-shopping-bag me-2"></i>
                        Commande <?= htmlspecialchars($order['order_number']) ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Informations client</h6>
                            <p class="mb-1"><strong><?= htmlspecialchars($order['user_name'] ?? 'N/A') ?></strong></p>
                            <p class="mb-0 text-muted"><?= htmlspecialchars($order['user_email'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Adresse de livraison</h6>
                            <p class="mb-0">
                                <?= htmlspecialchars($order['shipping_address']) ?><br>
                                <?= htmlspecialchars($order['shipping_postal_code']) ?> <?= htmlspecialchars($order['shipping_city']) ?>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="text-muted mb-3">Articles commandés</h6>
                    <?php
                    $itemsStmt = $pdo->prepare("
                        SELECT oi.*, p.name as product_name, p.image 
                        FROM order_items oi 
                        LEFT JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?
                    ");
                    $itemsStmt->execute([$order['id']]);
                    $items = $itemsStmt->fetchAll();
                    ?>
                    
                    <?php if (!empty($items)): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-end">Prix unit.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="../assets/img/uploads/<?= htmlspecialchars($item['image']) ?>" 
                                                         alt="" class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php endif; ?>
                                                <?= htmlspecialchars($item['product_name'] ?? 'Produit supprimé') ?>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= formatPrice($item['price']) ?></td>
                                        <td class="text-end"><strong><?= formatPrice($item['price'] * $item['quantity']) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong class="text-success fs-5"><?= formatPrice($order['total_amount']) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Détails des articles non disponibles.</p>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Méthode de paiement</small>
                            <p class="mb-0">
                                <i class="fa-solid fa-credit-card me-1"></i>
                                <?= $order['payment_method'] === 'card' ? 'Carte bancaire' : ucfirst($order['payment_method']) ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Statut paiement</small>
                            <p class="mb-0"><?= getPaymentBadge($order['payment_status']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Statut commande</small>
                            <p class="mb-0"><?= getStatusBadge($order['status']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Changer Statut -->
    <div class="modal fade" id="statusModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-edit me-2"></i>
                            Modifier le statut
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Commande : <strong><?= htmlspecialchars($order['order_number']) ?></strong></p>
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Nouveau statut</label>
                            <select name="status" class="form-select">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                                <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Expédiée</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Livrée</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="update_status" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>
