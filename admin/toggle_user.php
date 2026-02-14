<?php
require_once '../includes/config.php';
requireAdmin();

$userId = intval($_GET['id'] ?? 0);
if (!$userId) {
    redirect('manage_users.php', 'Utilisateur introuvable.', 'danger');
}

$pdo = getDB();

// Vérifier que ce n'est pas un admin
$stmt = $pdo->prepare("SELECT role, is_active FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    redirect('manage_users.php', 'Utilisateur introuvable.', 'danger');
}

if ($user['role'] === 'admin') {
    redirect('manage_users.php', 'Impossible de modifier un administrateur.', 'danger');
}

// Inverser le statut
$newStatus = $user['is_active'] ? 0 : 1;
$stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
$stmt->execute([$newStatus, $userId]);

$message = $newStatus ? 'Utilisateur activé.' : 'Utilisateur désactivé.';
redirect('manage_users.php', $message, 'success');
