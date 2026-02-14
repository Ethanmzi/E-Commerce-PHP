<?php 
$pageTitle = 'Panier';
include 'includes/header.php'; 

// Récupérer le panier
$cart = getCart();
$cartTotal = getCartTotal();
?>

<div class="text-center mb-5">
    <h1 class="section-title mb-2"><i class="fa-solid fa-cart-shopping me-3"></i>Votre Panier</h1>
</div>

<?php if (empty($cart)): ?>
    <div class="text-center py-5">
        <i class="fa-solid fa-cart-shopping fa-4x text-muted mb-4"></i>
        <h3>Votre panier est vide</h3>
        <p class="text-muted">Découvrez nos produits et ajoutez vos articles favoris !</p>
        <a href="articles.php" class="btn btn-primary btn-lg mt-3">
            <i class="fa-solid fa-shopping-bag me-2"></i>Voir le catalogue
        </a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="border-bottom">
                                <th class="text-muted fw-normal small text-uppercase">Produit</th>
                                <th class="text-muted fw-normal small text-uppercase">Prix</th>
                                <th class="text-muted fw-normal small text-uppercase">Quantité</th>
                                <th class="text-muted fw-normal small text-uppercase">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['image']): ?>
                                                <img src="assets/img/uploads/<?= htmlspecialchars($item['image']) ?>" 
                                                     class="rounded-3 me-3" width="80" height="80" style="object-fit: cover;" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>">
                                            <?php else: ?>
                                                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 80px; height: 80px; background: var(--bg-elevated, #2c2c2e);">
                                                    <i class="fa-solid fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                                <small class="text-muted">Réf: #<?= $item['product_id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= formatPrice($item['price']) ?></td>
                                    <td style="width: 140px;">
                                        <form action="update_cart.php" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                            <input type="number" name="quantity" class="form-control form-control-sm me-2" 
                                                   value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mettre à jour">
                                                <i class="fa-solid fa-sync"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="fw-bold"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                                    <td class="text-end">
                                        <a href="remove_from_cart.php?id=<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Retirer cet article du panier ?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="articles.php" class="btn btn-link text-decoration-none" style="color: var(--accent-color, #2997ff);">
                            <i class="fa-solid fa-arrow-left me-2"></i>Continuer mes achats
                        </a>
                        <a href="clear_cart.php" class="btn btn-outline-danger"
                           onclick="return confirm('Vider complètement le panier ?')">
                            <i class="fa-solid fa-trash me-1"></i>Vider le panier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4" style="font-weight: 600;">Résumé de la commande</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Sous-total (<?= count($cart) ?> article<?= count($cart) > 1 ? 's' : '' ?>)</span>
                        <span><?= formatPrice($cartTotal) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Livraison</span>
                        <span style="color: #34c759;">Gratuite</span>
                    </div>
                    <hr style="border-color: var(--border-color, #38383a);">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total (TTC)</span>
                        <span class="price-tag" style="font-size: 1.5rem;"><?= formatPrice($cartTotal) ?></span>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <a href="checkout.php" class="btn btn-primary w-100 btn-lg">
                            <i class="fa-solid fa-credit-card me-2"></i>Passer à la caisse
                        </a>
                    <?php else: ?>
                        <a href="connexion.php" class="btn btn-primary w-100 btn-lg">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Se connecter pour commander
                        </a>
                        <p class="text-center mt-3 small text-muted">
                            Pas encore de compte ? <a href="inscription.php" style="color: var(--accent-color, #2997ff);">Inscrivez-vous</a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-3 text-center">
                <p class="small text-muted"><i class="fa-solid fa-lock me-2"></i>Paiement 100% sécurisé</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>