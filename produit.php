<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <img src="https://via.placeholder.com/600x600" class="img-fluid rounded shadow" alt="Grand format">
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