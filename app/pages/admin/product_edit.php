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

$errors = [];

function admin_handle_upload_optional(string $existing): ?string
{
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        return $existing !== '' ? $existing : null;
    }

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Erreur upload image.');
    }

    $tmp = $_FILES['image']['tmp_name'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format image non autorisé (jpg/png/webp).');
    }

    $name = uniqid('img_', true) . '.' . $allowed[$mime];
    $destDir = __DIR__ . '/../../../assets/img/uploads';
    if (!is_dir($destDir)) {
        mkdir($destDir, 0775, true);
    }
    $destPath = $destDir . '/' . $name;

    if (!move_uploaded_file($tmp, $destPath)) {
        throw new RuntimeException('Impossible de déplacer le fichier.');
    }

    return 'assets/img/uploads/' . $name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($config, $_POST['_csrf'] ?? null)) {
        $errors[] = 'Token CSRF invalide.';
    }

    $nom = input_string($_POST['nom'] ?? '');
    $description = input_string($_POST['description'] ?? '');
    $prix = $_POST['prix'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if ($nom === '') $errors[] = 'Nom requis.';
    if ($description === '') $errors[] = 'Description requise.';
    if (!validate_price($prix)) $errors[] = 'Prix invalide.';
    if (!validate_int_non_negative($stock)) $errors[] = 'Stock invalide.';

    $imagePath = $item['image'] ?? null;
    if (!$errors) {
        try {
            $imagePath = admin_handle_upload_optional((string)($item['image'] ?? ''));
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!$errors) {
        item_update($pdo, $id, [
            'nom' => $nom,
            'description' => $description,
            'prix' => (float)$prix,
            'stock' => (int)$stock,
            'image' => $imagePath,
        ]);
        flash_set('success', 'Produit modifié.');
        redirect(url('/index.php?page=admin_products'));
    }
}

require __DIR__ . '/../../views/partials/header.php';
?>
<h1 class="h3 mt-3">Modifier produit #<?= (int)$id ?></h1>
<?php if ($errors): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e1): ?><li><?= e($e1) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
  <input type="hidden" name="_csrf" value="<?= e(csrf_token($config)) ?>">
  <div class="mb-3">
    <label class="form-label">Nom</label>
    <input class="form-control" name="nom" required value="<?= e($_POST['nom'] ?? $item['nom']) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" name="description" rows="5" required><?= e($_POST['description'] ?? $item['description']) ?></textarea>
  </div>
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Prix (€)</label>
      <input class="form-control" name="prix" type="number" step="0.01" min="0" required value="<?= e($_POST['prix'] ?? $item['prix']) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Stock</label>
      <input class="form-control" name="stock" type="number" min="0" required value="<?= e($_POST['stock'] ?? ($item['stock_reel'] ?? $item['stock'])) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Image</label>
      <input class="form-control" name="image" type="file" accept="image/*">
      <div class="form-text">Laisser vide pour conserver l'image.</div>
    </div>
  </div>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Enregistrer</button>
    <a class="btn btn-outline-secondary" href="<?= e(url('/index.php?page=admin_products')) ?>">Retour</a>
  </div>
</form>
<?php require __DIR__ . '/../../views/partials/footer.php';
