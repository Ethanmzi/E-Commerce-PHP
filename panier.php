<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="fa-solid fa-cart-shopping me-2"></i>Votre Panier</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/80" class="rounded me-3" alt="Produit">
                                    <div>
                                        <h6 class="mb-0">Nom du Produit</h6>
                                        <small class="text-muted">Réf: #12345</small>
                                    </div>
                                </div>
                            </td>
                            <td>29.99 €</td>
                            <td style="width: 120px;">
                                <input type="number" class="form-control form-control-sm" value="1" min="1">
                            </td>
                            <td class="fw-bold">29.99 €</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="articles.php" class="btn btn-link text-decoration-none text-dark">
                        <i class="fa-solid fa-arrow-left me-2"></i>Continuer mes achats
                    </a>
                    <button class="btn btn-outline-danger btn-sm">Vider le panier</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Résumé de la commande</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total</span>
                    <span>29.99 €</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Livraison</span>
                    <span class="text-success">Gratuite</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold">Total (TTC)</span>
                    <span class="fw-bold fs-4 text-primary">29.99 €</span>
                </div>
                <button class="btn btn-primary w-100 btn-lg">Passer à la caisse</button>
            </div>
        </div>
        
        <div class="mt-3 text-center">
            <p class="small text-muted"><i class="fa-solid fa-lock me-2"></i>Paiement 100% sécurisé</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>