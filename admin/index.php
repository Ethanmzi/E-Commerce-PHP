<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Gestion</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Back-office : Liste des produits</h1>
        <a href="add_product.php" class="btn btn-primary my-3"> + Ajouter un article</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><img src="../assets/img/prod1.jpg" width="50"></td>
                    <td>Exemple Article</td>
                    <td>29.99 €</td>
                    <td>15</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Modifier</button>
                        <button class="btn btn-danger btn-sm">Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>