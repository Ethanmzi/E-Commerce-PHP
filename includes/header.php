<?php
require_once __DIR__ . '/config.php';
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'MaBoutique' ?> | E-commerce</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fa-solid fa-droplet me-2"></i>GlowSerum
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="articles.php">Catalogue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Qui sommes-nous ?</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">
                        <i class="fa-solid fa-cart-shopping"></i> 
                        <span class="badge bg-danger rounded-pill"><?= $cartCount ?></span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <?php if (isLoggedIn()): ?>
                        <a class="nav-link dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user-check"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Mon compte') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="admin/index.php"><i class="fa-solid fa-gauge me-2"></i>Administration</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Déconnexion</a></li>
                        </ul>
                    <?php else: ?>
                        <a class="nav-link dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i> Compte
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="connexion.php"><i class="fa-solid fa-right-to-bracket me-2"></i>Connexion</a></li>
                            <li><a class="dropdown-item" href="inscription.php"><i class="fa-solid fa-user-plus me-2"></i>Inscription</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-muted small" href="admin/login.php"><i class="fa-solid fa-shield me-2"></i>Admin</a></li>
                        </ul>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5 py-3">
<?= displayFlashMessage() ?>