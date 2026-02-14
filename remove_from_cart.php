<?php
require_once 'includes/config.php';

$cartId = intval($_GET['id'] ?? 0);

if ($cartId <= 0) {
    redirect('panier.php', 'Article invalide.', 'danger');
}

$pdo = getDB();
$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
$stmt->execute([$cartId]);

redirect('panier.php', 'Article retiré du panier.', 'info');
