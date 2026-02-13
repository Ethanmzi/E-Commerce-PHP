<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaBoutique | E-commerce Dynamique</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Petite touche de style pour la nav */
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
        .nav-link:hover { color: #ffc107 !important; transition: 0.3s; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fa-solid fa-bag-shopping me-2"></i>MaBoutique
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
                        <span class="badge bg-danger rounded-pill">0</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user"></i> Compte
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="connexion.php">Connexion</a></li>
                        <li><a class="dropdown-item" href="inscription.php">Inscription</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="admin/index.php border">Admin (Back-office)</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5 py-3">