<?php
require_once '../includes/config.php';

// Si déjà admin connecté, rediriger
if (isAdmin()) {
    redirect('index.php');
}

// Si utilisateur normal connecté mais pas admin
if (isLoggedIn() && !isAdmin()) {
    redirect('../index.php', 'Accès réservé aux administrateurs.', 'danger');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (loginUser($email, $password)) {
        if (isAdmin()) {
            redirect('index.php', 'Bienvenue dans l\'administration !', 'success');
        } else {
            logoutUser();
            $error = 'Accès réservé aux administrateurs.';
        }
    } else {
        $error = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-shield-halved fa-3x text-primary mb-3"></i>
                            <h3 class="fw-bold">Administration</h3>
                            <p class="text-muted">Connectez-vous pour accéder au back-office</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       placeholder="admin@boutique.fr" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="••••••••" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Se connecter
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <a href="../index.php" class="text-muted text-decoration-none">
                                <i class="fa-solid fa-arrow-left me-1"></i> Retour au site
                            </a>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded text-center">
                            <small class="text-muted">
                                <strong>Identifiants de test :</strong><br>
                                admin@boutique.fr / password
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
