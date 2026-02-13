<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Checkout.php';

$pdo = db($config);
$cart = cart_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        flash_set('danger', 'Token CSRF invalide.');
        redirect(url('/index.php?page=cart'));
    }

    $action = (string)($_POST['action'] ?? '');

    if ($action === 'remove') {
        cart_remove((int)($_POST['item_id'] ?? 0));
    } elseif ($action === 'clear') {
        cart_clear();
    } elseif ($action === 'set_qty') {
        $id = (int)($_POST['item_id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 1);
        cart_set($id, $qty);
    }

    redirect(url('/index.php?page=cart'));
}

// Build cart lines
$lines = [];
foreach ($cart as $itemId => $qty) {
    $item = item_find($pdo, (int)$itemId);
    if (!$item) {
        continue;
    }
    $lines[] = [
        'item' => $item,
        'qty' => (int)$qty,
        'subtotal' => (float)$item['prix'] * (int)$qty,
    ];
}
$total = cart_total($pdo, $cart);

require __DIR__ . '/../views/partials/header.php';
?>
<h1 class="h3 mt-3">Panier</h1>

<?php if (!$lines): ?>
  <div class="alert alert-info">Ton panier est vide.</div>
  <a class="btn btn-primary" href="<?= e(url('/index.php?page=catalog')) ?>">Aller au catalogue</a>
<?php else: ?>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>Article</th><th>Prix</th><th style="width:140px">Quantité</th><th>Sous-total</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($lines as $l): $it = $l['item']; ?>
          <tr>
            <td>
              <a href="<?= e(url('/index.php?page=product&id=' . (int)$it['id'])) ?>"><?= e($it['nom']) ?></a>
            </td>
            <td><?= e(number_format((float)$it['prix'], 2)) ?> €</td>
            <td>
              <form method="post" class="d-flex gap-2">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
                <input type="hidden" name="action" value="set_qty">
                <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                <input type="number" class="form-control form-control-sm" name="qty" value="<?= (int)$l['qty'] ?>" min="1" max="99" required>
                <button class="btn btn-sm btn-outline-primary" type="submit">OK</button>
              </form>
            </td>
            <td><?= e(number_format((float)$l['subtotal'], 2)) ?> €</td>
            <td>
              <form method="post">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center">
    <form method="post">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
      <input type="hidden" name="action" value="clear">
      <button class="btn btn-outline-secondary" type="submit">Vider le panier</button>
    </form>
    <div class="h4 mb-0">Total: <?= e(number_format((float)$total, 2)) ?> €</div>
  </div>

  <div class="mt-3">
    <a class="btn btn-success" href="<?= e(url('/index.php?page=checkout')) ?>">Passer commande</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/../views/partials/footer.php';
