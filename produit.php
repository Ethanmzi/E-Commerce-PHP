<?php include 'includes/header.php'; ?>

<?php
// Récupération dynamique de toutes les images du dossier uploads
$dossier_images = 'assets/img/uploads/';
$images = [];
$extensions_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (is_dir($dossier_images)) {
    $fichiers = scandir($dossier_images);
    foreach ($fichiers as $fichier) {
        $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
        if (in_array($extension, $extensions_valides)) {
            $images[] = $fichier;
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 mb-4">
        <!-- Carrousel principal -->
        <div id="carouselProduit" class="carousel slide carousel-fade" data-bs-ride="false">
            <div class="carousel-inner rounded shadow">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo $dossier_images . $image; ?>" 
                                 class="d-block w-100 product-main-image" 
                                 alt="Image produit <?php echo $index + 1; ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/600x600" class="d-block w-100" alt="Pas d'image">
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($images) > 1): ?>
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
        <?php if (count($images) > 1): ?>
            <div class="product-thumbnails d-flex justify-content-center gap-2 mt-3 flex-wrap">
                <?php foreach ($images as $index => $image): ?>
                    <img src="<?php echo $dossier_images . $image; ?>" 
                         class="thumbnail-img <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-bs-target="#carouselProduit" 
                         data-bs-slide-to="<?php echo $index; ?>"
                         alt="Miniature <?php echo $index + 1; ?>">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Indicateur du nombre d'images -->
        <?php if (count($images) > 1): ?>
            <p class="text-center text-muted mt-2">
                <small><i class="fa-solid fa-images me-1"></i><?php echo count($images); ?> photos disponibles</small>
            </p>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="articles.php">Catalogue</a></li>
                <li class="breadcrumb-item active">Nom du produit</li>
            </ol>
        </nav>

        <h2 class="display-6 fw-bold">Nom de l'article détaillé</h2>
        <p class="fs-4 text-primary fw-bold">49.00 €</p>
        
        <p class="text-muted">
            Voici une description longue et détaillée. On explique ici la matière, 
            la provenance, et pourquoi ce produit est indispensable pour le client.
        </p>

        <hr>

        <form action="ajouter_au_panier.php" method="POST">
            <div class="mb-4">
                <label class="form-label fw-bold">Quantité :</label>
                <input type="number" class="form-control" value="1" min="1" style="width: 100px;">
            </div>
            
            <div class="mb-2">
                <span class="badge bg-success">En stock</span>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mt-3">
                <i class="fa-solid fa-cart-shopping me-2"></i>Ajouter au panier
            </button>
        </form>

        <div class="mt-4 p-3 bg-light rounded border">
            <small><i class="fa-solid fa-truck me-2"></i> Livraison gratuite sous 3 à 5 jours.</small>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>