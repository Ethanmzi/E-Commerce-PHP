<?php
require_once __DIR__ . '/../models/User.php';

$pdo = db($config);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        $errors[] = 'Token CSRF invalide.';
    }

    $nom = input_string($_POST['nom'] ?? '');
    $email = strtolower(input_string($_POST['email'] ?? ''));
    $pwd = (string)($_POST['password'] ?? '');
    $pwd2 = (string)($_POST['password_confirm'] ?? '');

    if ($nom === '' || mb_strlen($nom) < 2) {
        $errors[] = 'Nom invalide (min 2 caractères).';
    }
    if (!validate_email($email)) {
        $errors[] = 'Email invalide.';
    }
    if ($pwd === '' || mb_strlen($pwd) < 8) {
        $errors[] = 'Mot de passe trop court (min 8).';
    }
    if ($pwd !== $pwd2) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }

    if (!$errors && user_find_by_email($pdo, $email)) {
        $errors[] = 'Cet email est déjà utilisé.';
    }

    if (!$errors) {
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        $id = user_create($pdo, $nom, $email, $hash, 'user');
        $_SESSION['user'] = ['id' => $id, 'nom' => $nom, 'email' => $email, 'role' => 'user'];
        flash_set('success', 'Compte créé, bienvenue !');
        redirect(url('/index.php?page=home'));
    }
}

require __DIR__ . '/../views/partials/header.php';
?>
<h1 class="h3 mt-3">Inscription</h1>
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
    <label class="form-label">Nom</label>
    <input class="form-control" name="nom" required minlength="2" value="<?= e($_POST['nom'] ?? '') ?>">
    <div class="invalid-feedback">Nom requis.</div>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
    <div class="invalid-feedback">Email valide requis.</div>
  </div>
  <div class="mb-3">
    <label class="form-label">Mot de passe</label>
    <input class="form-control" type="password" name="password" required minlength="8">
    <div class="invalid-feedback">Min 8 caractères.</div>
  </div>
  <div class="mb-3">
    <label class="form-label">Confirmer</label>
    <input class="form-control" type="password" name="password_confirm" required minlength="8">
  </div>
  <button class="btn btn-primary" type="submit">Créer mon compte</button>
</form>
<?php require __DIR__ . '/../views/partials/footer.php';
