<?php 
$pageTitle = 'Accueil';
include 'includes/header.php'; 

// Récupérer les produits vedettes
$featuredProducts = getProducts(6, true);
?>

<div class="hero-section rounded-4 mb-5">
    <div class="container py-5">
        <h1>Transformez-vous.</h1>
        <p>Sérums de transformation visage. Résultats visibles en 1, 3 ou 6 mois.</p>
        <a href="articles.php" class="btn btn-primary btn-lg">Découvrir nos sérums</a>
    </div>
</div>

<h2 class="section-title">Nos best-sellers</h2>
<div class="row g-4">
    <?php if (empty($featuredProducts)): ?>
        <div class="col-12 text-center py-5">
            <i class="fa-solid fa-droplet fa-3x mb-3 text-muted"></i>
            <p class="text-muted">Aucun sérum best-seller pour le moment.</p>
            <a href="articles.php" class="btn btn-outline-primary">Voir tous les sérums</a>
        </div>
    <?php else: ?>
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
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
                        <a href="produit.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark">Découvrir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>