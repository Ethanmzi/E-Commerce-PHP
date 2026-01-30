<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col">
        <h1 class="border-bottom pb-2">Notre Catalogue</h1>
        <p class="text-muted">Découvrez notre sélection exclusive.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Nouveau</div>
            
            <img src="assets/img/uploads/img_principal.png" class="card-img-top" alt="Nom du produit">
            
            <div class="card-body text-center">
                <h5 class="card-title">Nom de l'article</h5>
                <p class="text-muted small">Une courte description accrocheuse pour le catalogue.</p>
                <div class="text-primary fw-bold fs-5 mb-3">29.99 €</div>
                
                <div class="d-grid gap-2">
                    <a href="produit.php" class="btn btn-outline-dark">Voir les détails</a>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-cart-plus me-2"></i>Ajouter
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>

<?php include 'includes/footer.php'; ?>