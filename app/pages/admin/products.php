<?php
require_admin();
require_once __DIR__ . '/../../models/Item.php';

$pdo = db($config);
$items = item_all($pdo);

require __DIR__ . '/../../views/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center mt-3">
  <h1 class="h3 mb-0">Produits</h1>
  <a class="btn btn-primary" href="<?= e(url('/index.php?page=admin_product_create')) ?>">Ajouter</a>
</div>

<div class="table-responsive mt-3">
  <table class="table align-middle">
    <thead><tr><th>ID</th><th>Nom</th><th>Prix</th><th>Stock</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?= (int)$it['id'] ?></td>
          <td><?= e($it['nom']) ?></td>
          <td><?= e(number_format((float)$it['prix'], 2)) ?> €</td>
          <td><?= (int)($it['stock_reel'] ?? $it['stock'] ?? 0) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/index.php?page=admin_product_edit&id=' . (int)$it['id'])) ?>">Modifier</a>
            <a class="btn btn-sm btn-outline-danger" href="<?= e(url('/index.php?page=admin_product_delete&id=' . (int)$it['id'])) ?>">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../../views/partials/footer.php';
