<?php include 'includes/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5 text-center">
        <h1 class="display-5 fw-bold">Bienvenue sur notre boutique</h1>
        <p class="col-md-8 fs-4 mx-auto">Découvrez nos meilleurs produits aux meilleurs prix.</p>
        <a href="articles.php" class="btn btn-primary btn-lg">Voir le catalogue</a>
    </div>
</div>

<h2 class="text-center mb-4">Produits à la une</h2>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <img src="assets/img/uploads/img_principal.png" class="card-img-top" alt="Produit">
            <div class="card-body">
                <h5 class="card-title">Nom du Produit</h5>
                <p class="card-text">Une description rapide pour donner envie.</p>
                <a href="produit.php" class="btn btn-outline-dark">Détails</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>