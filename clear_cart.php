<?php
require_once 'includes/config.php';

$pdo = getDB();
$userId = getUserId();
$sessionId = session_id();

if ($userId) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
} else {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->execute([$sessionId]);
}

redirect('panier.php', 'Panier vidé.', 'info');
