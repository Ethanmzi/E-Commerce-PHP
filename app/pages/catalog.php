<?php
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Cart.php';

$pdo = db($config);
$items = item_all($pdo);

// Add to cart (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        flash_set('danger', 'Token CSRF invalide.');
        redirect(url('/index.php?page=catalog'));
    }

    $itemId = (int)($_POST['item_id'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 1);
    if ($itemId > 0 && $qty > 0) {
        cart_add($itemId, $qty);
        flash_set('success', 'Ajouté au panier.');
    }
    redirect(url('/index.php?page=catalog'));
}

require __DIR__ . '/../views/partials/header.php';
?>
<h1 class="h3 mt-3">Articles</h1>
<div class="row g-3">
  <?php foreach ($items as $it): ?>
    <div class="col-md-4">
      <div class="card h-100">
        <?php if (!empty($it['image'])): ?>
          <img class="card-img-top" src="<?= e(url('/' . ltrim($it['image'], '/'))) ?>" alt="<?= e($it['nom']) ?>">
        <?php else: ?>
          <div class="card-img-top bg-light"></div>
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= e($it['nom']) ?></h5>
          <p class="card-text text-muted"><?= e(mb_strimwidth($it['description'], 0, 110, '…')) ?></p>
          <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold"><?= e(number_format((float)$it['prix'], 2)) ?> €</span>
              <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('/index.php?page=product&id=' . (int)$it['id'])) ?>">Voir</a>
            </div>
            <form method="post" class="needs-validation" novalidate>
              <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
              <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
              <div class="d-flex gap-2">
                <input type="number" class="form-control form-control-sm" name="qty" value="1" min="1" max="99" required>
                <button class="btn btn-sm btn-primary" type="submit">Ajouter</button>
              </div>
            </form>
            <div class="small text-muted mt-2">Stock: <?= (int)($it['stock_reel'] ?? $it['stock'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../views/partials/footer.php';
