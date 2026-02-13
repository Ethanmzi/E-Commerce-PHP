<?php
require_admin();
require_once __DIR__ . '/../../models/User.php';

$pdo = db($config);
$id = (int)($_GET['id'] ?? 0);

$target = $id ? user_find($pdo, $id) : null;
if (!$target) {
  flash_set('warning', 'Utilisateur introuvable.');
  redirect(url('/index.php?page=admin_users'));
}

if (($target['role'] ?? '') === 'admin') {
  flash_set('danger', 'Suppression d\'admin interdite.');
  redirect(url('/index.php?page=admin_users'));
}

if ((int)auth_user()['id'] === $id) {
  flash_set('danger', 'Tu ne peux pas te supprimer toi-même.');
  redirect(url('/index.php?page=admin_users'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        flash_set('danger', 'Token CSRF invalide.');
        redirect(url('/index.php?page=admin_users'));
    }

    user_delete($pdo, $id);
    flash_set('success', 'Utilisateur supprimé.');
    redirect(url('/index.php?page=admin_users'));
}

require __DIR__ . '/../../views/partials/header.php';
?>
<h1 class="h3 mt-3">Supprimer utilisateur</h1>
<div class="alert alert-warning">Confirmer la suppression de: <strong><?= e($target['email']) ?></strong> ?</div>
<form method="post">
  <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
  <button class="btn btn-danger" type="submit">Oui, supprimer</button>
  <a class="btn btn-outline-secondary" href="<?= e(url('/index.php?page=admin_users')) ?>">Annuler</a>
</form>
<?php require __DIR__ . '/../../views/partials/footer.php';
