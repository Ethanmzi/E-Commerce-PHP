<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Checkout.php';

require_login();

$pdo = db($config);
$cart = cart_get();

if (!$cart) {
    flash_set('info', 'Panier vide.');
    redirect(url('/index.php?page=catalog'));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        $errors[] = 'Token CSRF invalide.';
    }

    $adresse = input_string($_POST['adresse_facturation'] ?? '');
    $ville = input_string($_POST['ville'] ?? '');
    $cp = input_string($_POST['code_postal'] ?? '');

    if ($adresse === '' || mb_strlen($adresse) < 5) {
        $errors[] = 'Adresse invalide.';
    }
    if ($ville === '') {
        $errors[] = 'Ville requise.';
    }
    if ($cp === '') {
        $errors[] = 'Code postal requis.';
    }

    if (!$errors) {
        try {
            $invoiceId = checkout_create($pdo, (int)auth_user()['id'], $cart, [
                'adresse_facturation' => $adresse,
                'ville' => $ville,
                'code_postal' => $cp,
            ]);
            cart_clear();
            flash_set('success', 'Commande validée. Facture #' . $invoiceId);
            redirect(url('/index.php?page=home'));
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }
}

require __DIR__ . '/../views/partials/header.php';
?>
<h1 class="h3 mt-3">Validation commande</h1>
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
    <label class="form-label">Adresse de facturation</label>
    <input class="form-control" name="adresse_facturation" required minlength="5" value="<?= e($_POST['adresse_facturation'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Ville</label>
    <input class="form-control" name="ville" required value="<?= e($_POST['ville'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Code postal</label>
    <input class="form-control" name="code_postal" required value="<?= e($_POST['code_postal'] ?? '') ?>">
  </div>
  <button class="btn btn-success" type="submit">Confirmer et payer (démo)</button>
</form>
<?php require __DIR__ . '/../views/partials/footer.php';
