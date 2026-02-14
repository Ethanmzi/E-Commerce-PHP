<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($productId <= 0) {
        redirect('articles.php', 'Produit invalide.', 'danger');
    }
    
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    if (addToCart($productId, $quantity)) {
        redirect('panier.php', 'Produit ajouté au panier !', 'success');
    } else {
        redirect('articles.php', 'Impossible d\'ajouter ce produit (stock insuffisant ou produit inexistant).', 'warning');
    }
} else {
    redirect('articles.php');
}
