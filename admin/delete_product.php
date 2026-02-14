<?php
require_once '../includes/config.php';
requireAdmin();

$productId = intval($_GET['id'] ?? 0);
if (!$productId) {
    redirect('index.php', 'Produit introuvable.', 'danger');
}

$pdo = getDB();

// Récupérer le produit pour supprimer l'image
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    redirect('index.php', 'Produit introuvable.', 'danger');
}

// Supprimer l'image si elle existe
if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])) {
    unlink(UPLOAD_PATH . $product['image']);
}

// Supprimer les images additionnelles
$stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$stmt->execute([$productId]);
$images = $stmt->fetchAll();

foreach ($images as $img) {
    if (file_exists(UPLOAD_PATH . $img['image_path'])) {
        unlink(UPLOAD_PATH . $img['image_path']);
    }
}

// Supprimer le produit (les images seront supprimées par CASCADE)
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$productId]);

redirect('index.php', 'Produit supprimé avec succès.', 'success');
