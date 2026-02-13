<?php
require_once __DIR__ . '/../models/User.php';

$pdo = db($config);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        $errors[] = 'Token CSRF invalide.';
    }

    $email = strtolower(input_string($_POST['email'] ?? ''));
    $pwd = (string)($_POST['password'] ?? '');

    if (!validate_email($email)) {
        $errors[] = 'Email invalide.';
    }

    if (!$errors) {
        $user = user_find_by_email($pdo, $email);
        if (!$user || !password_verify($pwd, (string)$user['mot_de_passe'])) {
            $errors[] = 'Identifiants incorrects.';
        } else {
            $_SESSION['user'] = [
                'id' => (int)$user['id'],
                'nom' => $user['nom'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            flash_set('success', 'Connexion réussie.');
            redirect(url('/index.php?page=home'));
        }
    }
}

require __DIR__ . '/../views/partials/header.php';
?>
<h1 class="h3 mt-3">Connexion</h1>
<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $err): ?>
        <li><?= e($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
  <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Mot de passe</label>
    <input class="form-control" type="password" name="password" required minlength="8">
  </div>
  <button class="btn btn-primary" type="submit">Se connecter</button>
</form>
<?php require __DIR__ . '/../views/partials/footer.php';
