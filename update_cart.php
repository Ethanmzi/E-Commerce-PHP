<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = intval($_POST['cart_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($cartId <= 0) {
        redirect('panier.php', 'Article invalide.', 'danger');
    }
    
    if ($quantity <= 0) {
        // Si quantité 0 ou moins, supprimer l'article
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->execute([$cartId]);
        redirect('panier.php', 'Article retiré du panier.', 'info');
    }
    
    // Mettre à jour la quantité
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cartId]);
    
    redirect('panier.php', 'Panier mis à jour.', 'success');
} else {
    redirect('panier.php');
}
