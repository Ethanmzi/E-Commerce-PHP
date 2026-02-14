<?php
require_once 'includes/config.php';

// Si déjà connecté, rediriger vers l'accueil
if (isLoggedIn()) {
    redirect('index.php');
}

$errors = [];
$old = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $old = [
        'lastname' => sanitize($_POST['lastname'] ?? ''),
        'firstname' => sanitize($_POST['firstname'] ?? ''),
        'email' => sanitize($_POST['email'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? '')
    ];
    
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validations
    if (empty($old['lastname'])) {
        $errors[] = 'Le nom est requis.';
    }
    if (empty($old['firstname'])) {
        $errors[] = 'Le prénom est requis.';
    }
    if (empty($old['email']) || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }
    
    // Si pas d'erreurs, on inscrit
    if (empty($errors)) {
        $result = registerUser([
            'email' => $old['email'],
            'password' => $password,
            'firstname' => $old['firstname'],
            'lastname' => $old['lastname'],
            'phone' => $old['phone']
        ]);
        
        if ($result['success']) {
            // Connexion automatique après inscription
            loginUser($old['email'], $password);
            redirect('index.php', 'Bienvenue ! Votre compte a été créé avec succès.', 'success');
        } else {
            $errors[] = $result['message'];
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0">
            <div class="card-body p-5">
                <h2 class="text-center mb-4" style="font-weight: 700;">Créer un compte</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="lastname" class="form-control" 
                                   value="<?= htmlspecialchars($old['lastname'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="firstname" class="form-control" 
                                   value="<?= htmlspecialchars($old['firstname'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" 
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        <div class="form-text text-muted">Nous ne partagerons jamais votre email.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Téléphone (optionnel)</label>
                        <input type="tel" name="phone" class="form-control" placeholder="06 12 34 56 78"
                               value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
                            <div class="form-text text-muted">Minimum 8 caractères</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label small text-muted" for="terms">J'accepte les conditions générales d'utilisation</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">Créer mon compte</button>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Déjà un compte ? <a href="connexion.php" style="color: var(--accent-color, #2997ff); font-weight: 500; text-decoration: none;">Connectez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>