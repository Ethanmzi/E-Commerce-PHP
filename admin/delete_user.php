<?php
require_once '../includes/config.php';
requireAdmin();

$userId = intval($_GET['id'] ?? 0);
if (!$userId) {
    redirect('manage_users.php', 'Utilisateur introuvable.', 'danger');
}

$pdo = getDB();

// Vérifier que ce n'est pas un admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    redirect('manage_users.php', 'Utilisateur introuvable.', 'danger');
}

if ($user['role'] === 'admin') {
    redirect('manage_users.php', 'Impossible de supprimer un administrateur.', 'danger');
}

// Supprimer l'utilisateur (les commandes, panier seront supprimés par CASCADE)
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$userId]);

redirect('manage_users.php', 'Utilisateur supprimé avec succès.', 'success');
