<?php
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

$pdo = getDB();

// Statistiques
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'revenue' => $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE payment_status = 'paid'")->fetchColumn(),
];

// Liste des produits
$products = $pdo->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
")->fetchAll();
?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fa-solid fa-box fa-lg"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?= $stats['products'] ?></h3>
                    <small class="text-muted">Produits</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-success bg-opacity-10 text-success me-3">
                    <i class="fa-solid fa-users fa-lg"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?= $stats['users'] ?></h3>
                    <small class="text-muted">Utilisateurs</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fa-solid fa-shopping-bag fa-lg"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?= $stats['orders'] ?></h3>
                    <small class="text-muted">Commandes</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="icon bg-info bg-opacity-10 text-info me-3">
                    <i class="fa-solid fa-euro-sign fa-lg"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?= formatPrice($stats['revenue']) ?></h3>
                    <small class="text-muted">Chiffre d'affaires</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i>Gestion des Produits</h5>
        <a href="add_product.php" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Ajouter un produit
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fa-3x mb-3"></i>
                                <p>Aucun produit pour le moment</p>
                                <a href="add_product.php" class="btn btn-primary btn-sm">Ajouter votre premier produit</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="ps-4"><strong>#<?= $product['id'] ?></strong></td>
                                <td>
                                    <?php if ($product['image']): ?>
                                        <img src="../assets/img/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                             class="rounded" width="50" height="50" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fa-solid fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($product['name']) ?></strong>
                                    <?php if ($product['is_featured']): ?>
                                        <span class="badge bg-warning ms-1">Vedette</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['category_name'] ?? 'Non catégorisé') ?></td>
                                <td><strong><?= formatPrice($product['price']) ?></strong></td>
                                <td>
                                    <?php if ($product['stock'] > 10): ?>
                                        <span class="badge bg-success"><?= $product['stock'] ?></span>
                                    <?php elseif ($product['stock'] > 0): ?>
                                        <span class="badge bg-warning"><?= $product['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rupture</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($product['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="edit_product.php?id=<?= $product['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="delete_product.php?id=<?= $product['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Supprimer ce produit ?')" title="Supprimer">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>