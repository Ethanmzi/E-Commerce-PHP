<?php
require_once __DIR__ . '/../../includes/config.php';

// Protection : accès admin uniquement
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?= $pageTitle ?? 'Back-office' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #212529 0%, #343a40 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link i {
            width: 24px;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
        .main-content .card {
            width: 100% !important;
            max-width: none !important;
        }
        .main-content > .py-4 {
            max-width: 100% !important;
        }
        .stat-card {
            border-radius: 15px;
            border: none;
        }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Fix pour les modals Bootstrap dans l'admin */
        .modal-content {
            background: #fff !important;
            color: #212529 !important;
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5) !important;
        }
        .modal-dialog {
            max-width: 600px;
        }
        .modal-dialog.modal-lg {
            max-width: 900px;
        }
        /* Titres admin en noir */
        .main-content h1, .main-content h2, .main-content h3, .main-content h4, .main-content h5, .main-content h6 {
            color: #212529 !important;
        }
        .modal-header {
            background: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem 2rem !important;
        }
        .modal-header .modal-title {
            color: #212529 !important;
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }
        .modal-body {
            color: #212529 !important;
            padding: 2rem !important;
            font-size: 1.1rem !important;
        }
        .modal-body p, .modal-body small, .modal-body h6 {
            color: #212529 !important;
        }
        .modal-body h6 {
            font-size: 1rem !important;
            font-weight: 600 !important;
        }
        .modal-body .text-muted {
            color: #6c757d !important;
        }
        .modal-footer {
            border-top: 1px solid #dee2e6 !important;
            padding: 1.25rem 2rem !important;
        }
        .modal .form-control {
            background: #fff !important;
            color: #212529 !important;
            border: 1px solid #ced4da !important;
            padding: 1rem 1.25rem !important;
            font-size: 1.1rem !important;
        }
        .modal .form-select {
            background-color: #fff !important;
            color: #212529 !important;
            border: 1px solid #ced4da !important;
            padding: 1rem 1.25rem !important;
            font-size: 1.1rem !important;
            min-height: 60px;
        }
        .modal .form-label {
            color: #212529 !important;
            font-size: 1.1rem !important;
            font-weight: 500 !important;
            margin-bottom: 0.75rem !important;
        }
        .modal .table {
            color: #212529 !important;
            font-size: 1rem !important;
        }
        .modal .btn-close {
            filter: none !important;
            font-size: 1rem !important;
        }
        .modal .btn {
            padding: 0.75rem 1.5rem !important;
            font-size: 1rem !important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse py-3">
            <div class="position-sticky">
                <div class="text-center mb-4">
                    <a href="../index.php" class="text-white text-decoration-none">
                        <i class="fa-solid fa-bag-shopping fa-2x mb-2"></i>
                        <h5 class="mb-0">MaBoutique</h5>
                        <small class="text-muted">Administration</small>
                    </a>
                </div>
                
                <hr class="text-white-50">
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                            <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : '' ?>" href="add_product.php">
                            <i class="fa-solid fa-plus-circle me-2"></i> Ajouter Produit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>" href="manage_users.php">
                            <i class="fa-solid fa-users me-2"></i> Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>" href="orders.php">
                            <i class="fa-solid fa-shopping-bag me-2"></i> Commandes
                        </a>
                    </li>
                </ul>
                
                <hr class="text-white-50 mt-4">
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fa-solid fa-eye me-2"></i> Voir le site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Déconnexion
                        </a>
                    </li>
                </ul>
                
                <div class="mt-4 mx-3 p-3 bg-dark rounded text-center">
                    <small class="text-white-50">Connecté en tant que</small>
                    <p class="text-white mb-0 small"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="py-4">
                <?= displayFlashMessage() ?>
