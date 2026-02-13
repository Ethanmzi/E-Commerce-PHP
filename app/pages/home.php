<?php
require_once __DIR__ . '/../models/Item.php';
$pdo = db($config);
$items = array_slice(item_all($pdo), 0, 3);
require __DIR__ . '/../views/partials/header.php';
?>
<div class="p-4 p-md-5 mb-4 rounded text-bg-dark mt-3">
  <div class="col-lg-7 px-0">
    <h1 class="display-6">Bienvenue sur <?= e($config['app']['name'] ?? 'E-commerce') ?></h1>
    <p class="lead my-3">Découvre nos produits, ajoute-les au panier, puis passe commande.</p>
    <p class="lead mb-0"><a class="text-white fw-bold" href="<?= e(url('/index.php?page=catalog')) ?>">Voir le catalogue →</a></p>
  </div>
</div>

<h2 class="h4">Produits en avant</h2>
<div class="row g-3">
  <?php foreach ($items as $it): ?>
    <div class="col-md-4">
      <div class="card h-100">
        <?php if (!empty($it['image'])): ?>
          <img class="card-img-top" src="<?= e(url('/' . ltrim($it['image'], '/'))) ?>" alt="<?= e($it['nom']) ?>">
        <?php else: ?>
          <div class="card-img-top bg-light"></div>
        <?php endif; ?>
        <div class="card-body">
          <h5 class="card-title"><?= e($it['nom']) ?></h5>
          <p class="card-text text-muted"><?= e(mb_strimwidth($it['description'], 0, 90, '…')) ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold"><?= e(number_format((float)$it['prix'], 2)) ?> €</span>
            <a class="btn btn-sm btn-primary" href="<?= e(url('/index.php?page=product&id=' . (int)$it['id'])) ?>">Détails</a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../views/partials/footer.php';
