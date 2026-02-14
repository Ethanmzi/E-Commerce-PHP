<?php 
$pageTitle = 'Catalogue';
include 'includes/header.php'; 

// Récupérer tous les produits (pas de filtre par catégorie)
$products = getProducts();
?>

<div class="text-center mb-5">
    <h1 class="section-title mb-2">Nos Sérums</h1>
    <p class="text-muted">Découvrez nos <?= count($products) ?> sérum<?= count($products) > 1 ? 's' : '' ?> de transformation.</p>
</div>

<div class="row g-4">
    <?php if (empty($products)): ?>
        <div class="col-12 text-center py-5">
            <i class="fa-solid fa-search fa-3x mb-3 text-muted"></i>
            <p class="text-muted">Aucun produit trouvé.</p>
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <?php if ($product['is_featured']): ?>
                        <div class="badge bg-warning position-absolute" style="top: 1rem; right: 1rem; z-index: 1;">
                            <i class="fa-solid fa-star me-1"></i>Best-seller
                        </div>
                    <?php endif; ?>
                    
                    <div style="overflow: hidden;">
                        <?php if ($product['image']): ?>
                            <img src="assets/img/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x300?text=S%C3%A9rum" 
                                 class="card-img-top" alt="Sérum">
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text">
                            <?= htmlspecialchars(substr($product['description'] ?? '', 0, 80)) ?>...
                        </p>
                        <p class="price-tag mb-3"><?= formatPrice($product['price']) ?></p>
                        
                        <div class="d-grid gap-2">
                            <a href="produit.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark">Voir les détails</a>
                            <?php if ($product['stock'] > 0): ?>
                                <form action="ajouter_au_panier.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa-solid fa-cart-plus me-2"></i>Ajouter au panier
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Rupture de stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>