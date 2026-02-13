<?php
require_admin();
require_once __DIR__ . '/../../models/Item.php';

$pdo = db($config);
$id = (int)($_GET['id'] ?? 0);
$item = $id ? item_find($pdo, $id) : null;
if (!$item) {
    flash_set('warning', 'Produit introuvable.');
    redirect(url('/index.php?page=admin_products'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        flash_set('danger', 'Token CSRF invalide.');
        redirect(url('/index.php?page=admin_products'));
    }

    item_delete($pdo, $id);
    flash_set('success', 'Produit supprimé.');
    redirect(url('/index.php?page=admin_products'));
}

require __DIR__ . '/../../views/partials/header.php';
?>
<h1 class="h3 mt-3">Supprimer produit</h1>
<div class="alert alert-warning">Confirmer la suppression de: <strong><?= e($item['nom']) ?></strong> ?</div>
<form method="post">
  <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
  <button class="btn btn-danger" type="submit">Oui, supprimer</button>
  <a class="btn btn-outline-secondary" href="<?= e(url('/index.php?page=admin_products')) ?>">Annuler</a>
</form>
<?php require __DIR__ . '/../../views/partials/footer.php';
