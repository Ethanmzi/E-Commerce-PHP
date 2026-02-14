<?php
$pageTitle = 'Modifier un produit';
require_once 'includes/header.php';

$pdo = getDB();
$categories = getCategories();
$errors = [];

// Récupérer l'ID du produit
$productId = intval($_GET['id'] ?? 0);
if (!$productId) {
    redirect('index.php', 'Produit introuvable.', 'danger');
}

// Récupérer le produit
$product = getProduct($productId);
if (!$product) {
    redirect('index.php', 'Produit introuvable.', 'danger');
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => sanitize($_POST['name'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'price' => floatval($_POST['price'] ?? 0),
        'stock' => intval($_POST['stock'] ?? 0),
        'category_id' => intval($_POST['category_id'] ?? 0),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];
    
    // Validations
    if (empty($data['name'])) {
        $errors[] = 'Le nom du produit est requis.';
    }
    if ($data['price'] <= 0) {
        $errors[] = 'Le prix doit être supérieur à 0.';
    }
    
    // Gestion de l'image
    $imageName = $product['image']; // Garder l'ancienne image par défaut
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['image']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = 'Format d\'image non autorisé.';
        } else {
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('prod_') . '.' . $extension;
            $uploadPath = UPLOAD_PATH . $imageName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Supprimer l'ancienne image si elle existe
                if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])) {
                    unlink(UPLOAD_PATH . $product['image']);
                }
            } else {
                $errors[] = 'Erreur lors de l\'upload de l\'image.';
                $imageName = $product['image'];
            }
        }
    }
    
    // Si pas d'erreurs, mise à jour
    if (empty($errors)) {
        $slug = generateSlug($data['name']);
        
        // Vérifier unicité du slug (sauf pour ce produit)
        $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $productId]);
        if ($stmt->fetch()) {
            $slug .= '-' . uniqid();
        }
        
        $stmt = $pdo->prepare("
            UPDATE products SET 
                name = ?, slug = ?, description = ?, price = ?, 
                stock = ?, category_id = ?, image = ?, is_featured = ?, is_active = ?
            WHERE id = ?
        ");
        
        try {
            $stmt->execute([
                $data['name'],
                $slug,
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['category_id'] ?: null,
                $imageName,
                $data['is_featured'],
                $data['is_active'],
                $productId
            ]);
            
            redirect('index.php', 'Produit modifié avec succès !', 'success');
        } catch (PDOException $e) {
            $errors[] = 'Erreur lors de la modification.';
        }
    }
    
    // Mettre à jour les données pour l'affichage
    $product = array_merge($product, $data);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fa-solid fa-pen me-2 text-warning"></i>Modifier: <?= htmlspecialchars($product['name']) ?></h4>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Nom du produit <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Prix (€) <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-control" 
                                   value="<?= $product['price'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" 
                                        <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Stock</label>
                            <input type="number" name="stock" class="form-control" 
                                   value="<?= $product['stock'] ?>" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nouvelle image</label>
                            <input type="file" name="image" id="productImageInput" 
                                   class="form-control" accept="image/*">
                        </div>
                    </div>

                    <?php if ($product['image']): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Image actuelle</label>
                            <div>
                                <img src="../assets/img/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                     class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4 text-center">
                        <img id="productImagePreview" src="#" alt="Aperçu" 
                             class="img-thumbnail d-none" style="max-height: 200px;">
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" 
                                       id="is_featured" <?= $product['is_featured'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fa-solid fa-star text-warning me-1"></i> Produit vedette
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" 
                                       id="is_active" <?= $product['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">
                                    <i class="fa-solid fa-eye text-success me-1"></i> Produit actif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-warning px-5">
                            <i class="fa-solid fa-save me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Informations</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2"><strong>ID :</strong> #<?= $product['id'] ?></li>
                    <li class="mb-2"><strong>Créé le :</strong> <?= date('d/m/Y H:i', strtotime($product['created_at'])) ?></li>
                    <li><strong>Modifié le :</strong> <?= date('d/m/Y H:i', strtotime($product['updated_at'])) ?></li>
                </ul>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fa-solid fa-triangle-exclamation me-2"></i>Zone de danger</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Cette action est irréversible.</p>
                <a href="delete_product.php?id=<?= $product['id'] ?>" 
                   class="btn btn-outline-danger btn-sm w-100"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                    <i class="fa-solid fa-trash me-1"></i> Supprimer ce produit
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>