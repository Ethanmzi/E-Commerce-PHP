<?php
// Récupérer l'ID du produit
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Inclure le header (qui inclut config.php)
include 'includes/header.php';

// Récupérer le produit
$product = getProduct($productId);

if (!$product) {
    redirect('articles.php', 'Produit introuvable.', 'warning');
}

$pageTitle = $product['name'];

// Récupérer les images du produit depuis la DB
$productImages = getProductImages($productId);

// Si pas d'images en DB, utiliser l'image principale du produit
if (empty($productImages) && $product['image']) {
    $productImages = [['image_path' => $product['image'], 'is_primary' => 1]];
}

// Si toujours pas d'images, on peut aussi scanner le dossier uploads (optionnel)
$dossier_images = 'assets/img/uploads/';
if (empty($productImages)) {
    $extensions_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (is_dir($dossier_images)) {
        $fichiers = scandir($dossier_images);
        foreach ($fichiers as $fichier) {
            $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
            if (in_array($extension, $extensions_valides)) {
                $productImages[] = ['image_path' => $fichier, 'is_primary' => 0];
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 mb-4">
        <!-- Carrousel principal -->
        <div id="carouselProduit" class="carousel slide carousel-fade" data-bs-ride="false">
            <div class="carousel-inner rounded shadow">
                <?php if (!empty($productImages)): ?>
                    <?php foreach ($productImages as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= $dossier_images . htmlspecialchars($img['image_path']) ?>" 
                                 class="d-block w-100 product-main-image" 
                                 alt="<?= htmlspecialchars($product['name']) ?> - Image <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/600x600?text=Pas+d%27image" class="d-block w-100" alt="Pas d'image">
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($productImages) > 1): ?>
                <!-- Boutons précédent/suivant -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduit" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselProduit" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            <?php endif; ?>
        </div>

        <!-- Miniatures des images -->
        <?php if (count($productImages) > 1): ?>
            <div class="product-thumbnails d-flex justify-content-center gap-2 mt-3 flex-wrap">
                <?php foreach ($productImages as $index => $img): ?>
                    <img src="<?= $dossier_images . htmlspecialchars($img['image_path']) ?>" 
                         class="thumbnail-img <?= $index === 0 ? 'active' : '' ?>" 
                         data-bs-target="#carouselProduit" 
                         data-bs-slide-to="<?= $index ?>"
                         alt="Miniature <?= $index + 1 ?>">
                <?php endforeach; ?>
            </div>
            <p class="text-center text-muted mt-2">
                <small><i class="fa-solid fa-images me-1"></i><?= count($productImages) ?> photos disponibles</small>
            </p>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="articles.php">Catalogue</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>

        <?php if ($product['category_name']): ?>
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($product['category_name']) ?></span>
        <?php endif; ?>

        <h2 class="mb-3" style="font-weight: 700;"><?= htmlspecialchars($product['name']) ?></h2>
        <p class="price-tag" style="font-size: 1.75rem;"><?= formatPrice($product['price']) ?></p>
        
        <p class="text-muted">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'Aucune description disponible.')) ?>
        </p>

        <hr>

        <form action="ajouter_au_panier.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            
            <div class="mb-4">
                <label class="form-label fw-bold">Quantité :</label>
                <input type="number" name="quantity" class="form-control" value="1" min="1" 
                       max="<?= $product['stock'] ?>" style="width: 100px;">
            </div>
            
            <div class="mb-2">
                <?php if ($product['stock'] > 10): ?>
                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>En stock (<?= $product['stock'] ?> disponibles)</span>
                <?php elseif ($product['stock'] > 0): ?>
                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-exclamation-triangle me-1"></i>Stock limité (<?= $product['stock'] ?> restants)</span>
                <?php else: ?>
                    <span class="badge bg-danger"><i class="fa-solid fa-times me-1"></i>Rupture de stock</span>
                <?php endif; ?>
            </div>

            <?php if ($product['stock'] > 0): ?>
                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mt-3">
                    <i class="fa-solid fa-cart-shopping me-2"></i>Ajouter au panier
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-secondary btn-lg w-100 py-3 mt-3" disabled>
                    <i class="fa-solid fa-ban me-2"></i>Indisponible
                </button>
            <?php endif; ?>
        </form>

        <div class="mt-4 p-3 rounded-3 info-box">
            <small class="text-muted"><i class="fa-solid fa-truck me-2"></i> Livraison gratuite sous 3 à 5 jours.</small>
        </div>
        
        <div class="mt-2 p-3 rounded-3 info-box">
            <small class="text-muted"><i class="fa-solid fa-rotate-left me-2"></i> Retour gratuit sous 30 jours.</small>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>