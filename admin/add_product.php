<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Ajouter un produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php text-warning">Administration</a>
        <a href="../index.php" class="btn btn-outline-light btn-sm">Retour au site</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fa-solid fa-plus-circle me-2 text-primary"></i>Ajouter un nouvel article</h5>
                </div>
                <div class="card-body p-4">
                    <form action="traitement_ajout.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nom de l'article</label>
                                <input type="text" name="nom" class="form-control" placeholder="ex: T-shirt coton bio" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Prix (€)</label>
                                <input type="number" name="prix" step="0.01" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description courte</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock initial</label>
                                <input type="number" name="stock" class="form-control" value="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Image du produit</label>
                                <input type="file" name="image" id="productImageInput" class="form-control" accept="image/*" required>
                            </div>
                        </div>

                        <div class="mb-4 text-center">
                            <img id="productImagePreview" src="#" alt="Aperçu" class="img-thumbnail d-none" style="max-height: 200px;">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5">Enregistrer le produit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>