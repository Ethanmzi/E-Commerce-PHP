<?php
$pageTitle = 'Ajouter un produit';
require_once 'includes/header.php';

$pdo = getDB();
$categories = getCategories();
$errors = [];
$old = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'name' => sanitize($_POST['name'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'price' => floatval($_POST['price'] ?? 0),
        'stock' => intval($_POST['stock'] ?? 0),
        'category_id' => intval($_POST['category_id'] ?? 0),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];
    
    // Validations
    if (empty($old['name'])) {
        $errors[] = 'Le nom du produit est requis.';
    }
    if ($old['price'] <= 0) {
        $errors[] = 'Le prix doit être supérieur à 0.';
    }
    
    // Gestion de l'image
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['image']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = 'Format d\'image non autorisé (JPG, PNG, GIF, WEBP uniquement).';
        } else {
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('prod_') . '.' . $extension;
            $uploadPath = UPLOAD_PATH . $imageName;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $errors[] = 'Erreur lors de l\'upload de l\'image.';
                $imageName = null;
            }
        }
    }
    
    // Si pas d'erreurs, insertion
    if (empty($errors)) {
        $slug = generateSlug($old['name']);
        
        // Vérifier unicité du slug
        $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . uniqid();
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO products (name, slug, description, price, stock, category_id, image, is_featured, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        try {
            $stmt->execute([
                $old['name'],
                $slug,
                $old['description'],
                $old['price'],
                $old['stock'],
                $old['category_id'] ?: null,
                $imageName,
                $old['is_featured'],
                $old['is_active']
            ]);
            
            redirect('index.php', 'Produit ajouté avec succès !', 'success');
        } catch (PDOException $e) {
            $errors[] = 'Erreur lors de l\'ajout du produit.';
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fa-solid fa-plus-circle me-2 text-primary"></i>Ajouter un nouveau produit</h4>
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
                
                <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Nom du produit <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                   placeholder="ex: T-shirt coton bio" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Prix (€) <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-control" 
                                   value="<?= $old['price'] ?? '' ?>" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4" 
                                  placeholder="Description détaillée du produit..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" 
                                        <?= ($old['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Stock initial</label>
                            <input type="number" name="stock" class="form-control" 
                                   value="<?= $old['stock'] ?? 0 ?>" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Image du produit</label>
                            <input type="file" name="image" id="productImageInput" 
                                   class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <img id="productImagePreview" src="#" alt="Aperçu" 
                             class="img-thumbnail d-none" style="max-height: 200px;">
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" 
                                       id="is_featured" <?= ($old['is_featured'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fa-solid fa-star text-warning me-1"></i> Produit vedette (mis en avant)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" 
                                       id="is_active" checked <?= isset($old['is_active']) && !$old['is_active'] ? '' : 'checked' ?>>
                                <label class="form-check-label" for="is_active">
                                    <i class="fa-solid fa-eye text-success me-1"></i> Produit actif (visible sur le site)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa-solid fa-save me-1"></i> Enregistrer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Conseils</h6>
            </div>
            <div class="card-body">
                <ul class="small text-muted mb-0">
                    <li class="mb-2">Utilisez un nom clair et descriptif</li>
                    <li class="mb-2">Les images doivent être en JPG, PNG ou WEBP</li>
                    <li class="mb-2">Taille recommandée : 800x800 pixels</li>
                    <li>Une bonne description améliore les ventes</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>