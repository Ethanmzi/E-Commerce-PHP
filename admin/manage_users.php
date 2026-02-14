<?php
$pageTitle = 'Gestion des utilisateurs';
require_once 'includes/header.php';

$pdo = getDB();

// Récupérer tous les utilisateurs
$users = $pdo->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as orders_count,
           (SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE user_id = u.id AND payment_status = 'paid') as total_spent
    FROM users u 
    ORDER BY u.created_at DESC
")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Gestion des Utilisateurs</h4>
    <span class="badge bg-primary fs-6"><?= count($users) ?> utilisateur(s)</span>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Commandes</th>
                        <th>Total dépensé</th>
                        <th>Inscrit le</th>
                        <th>Statut</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users fa-3x mb-3"></i>
                                <p>Aucun utilisateur</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="ps-4"><strong>#<?= $user['id'] ?></strong></td>
                                <td>
                                    <strong><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></strong>
                                </td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                                <td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <span class="badge bg-danger"><i class="fa-solid fa-shield me-1"></i>Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Client</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= $user['orders_count'] ?></span>
                                </td>
                                <td><strong><?= formatPrice($user['total_spent']) ?></strong></td>
                                <td>
                                    <small><?= date('d/m/Y', strtotime($user['created_at'])) ?></small>
                                </td>
                                <td>
                                    <?php if ($user['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <a href="toggle_user.php?id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="<?= $user['is_active'] ? 'Désactiver' : 'Activer' ?>">
                                            <i class="fa-solid fa-<?= $user['is_active'] ? 'ban' : 'check' ?>"></i>
                                        </a>
                                        <a href="delete_user.php?id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Supprimer cet utilisateur ? Toutes ses données seront perdues.')"
                                           title="Supprimer">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Stats rapides -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fa-solid fa-user-check fa-2x text-success mb-2"></i>
                <h4 class="mb-0"><?= count(array_filter($users, fn($u) => $u['is_active'])) ?></h4>
                <small class="text-muted">Utilisateurs actifs</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fa-solid fa-shield fa-2x text-danger mb-2"></i>
                <h4 class="mb-0"><?= count(array_filter($users, fn($u) => $u['role'] === 'admin')) ?></h4>
                <small class="text-muted">Administrateurs</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fa-solid fa-shopping-bag fa-2x text-info mb-2"></i>
                <h4 class="mb-0"><?= count(array_filter($users, fn($u) => $u['orders_count'] > 0)) ?></h4>
                <small class="text-muted">Clients ayant commandé</small>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>