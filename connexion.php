<?php
require_once 'includes/config.php';

// Si déjà connecté, rediriger
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        if (loginUser($email, $password)) {
            // Rediriger vers la page admin si admin, sinon accueil
            if (isAdmin()) {
                redirect('admin/index.php', 'Bienvenue dans l\'administration !', 'success');
            } else {
                redirect('index.php', 'Connexion réussie. Bienvenue ' . $_SESSION['user_name'] . ' !', 'success');
            }
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 mt-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-circle-user fa-4x" style="color: var(--accent-color, #2997ff);"></i>
                    <h2 class="mt-3" style="font-weight: 700;">Connexion</h2>
                    <p class="text-muted">Accédez à votre espace client</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nom@exemple.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">Se connecter</button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Pas encore de compte ? <a href="inscription.php" style="color: var(--accent-color, #2997ff); font-weight: 500; text-decoration: none;">Inscrivez-vous</a></p>
                </div>
                
                <hr class="my-4" style="border-color: var(--border-color, #38383a);">
                
                <div class="text-center">
                    <small class="text-muted">
                        <strong>Compte admin de test :</strong><br>
                        Email: admin@boutique.fr<br>
                        Mot de passe: password
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>