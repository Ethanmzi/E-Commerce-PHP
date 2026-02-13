<?php
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Cart.php';

$pdo = db($config);
$id = (int)($_GET['id'] ?? 0);
$item = $id ? item_find($pdo, $id) : null;

if (!$item) {
    flash_set('warning', 'Produit introuvable.');
    redirect(url('/index.php?page=catalog'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        flash_set('danger', 'Token CSRF invalide.');
        redirect(url('/index.php?page=product&id=' . $id));
    }

    $qty = (int)($_POST['qty'] ?? 1);
    if ($qty > 0) {
        cart_add($id, $qty);
        flash_set('success', 'Ajouté au panier.');
    }
    redirect(url('/index.php?page=cart'));
}

require __DIR__ . '/../views/partials/header.php';
?>
<div class="row mt-3 g-3">
  <div class="col-md-5">
    <?php if (!empty($item['image'])): ?>
      <img class="product-img" src="<?= e(url('/' . ltrim($item['image'], '/'))) ?>" alt="<?= e($item['nom']) ?>">
    <?php else: ?>
      <div class="bg-light" style="height:420px"></div>
    <?php endif; ?>
  </div>
  <div class="col-md-7">
    <h1 class="h3"><?= e($item['nom']) ?></h1>
    <p class="text-muted"><?= e($item['description']) ?></p>
    <div class="d-flex justify-content-between align-items-center">
      <div class="h4 mb-0"><?= e(number_format((float)$item['prix'], 2)) ?> €</div>
      <div class="text-muted">Stock: <?= (int)($item['stock_reel'] ?? $item['stock'] ?? 0) ?></div>
    </div>
    <hr>
    <form method="post" class="needs-validation" novalidate>
      <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
      <div class="row g-2 align-items-end">
        <div class="col-4">
          <label class="form-label">Quantité</label>
          <input type="number" name="qty" class="form-control" value="1" min="1" max="99" required>
        </div>
        <div class="col-8">
          <button class="btn btn-primary w-100" type="submit">Ajouter au panier</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../views/partials/footer.php';
