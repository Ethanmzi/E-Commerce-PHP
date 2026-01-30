<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-circle-user fa-4x text-primary"></i>
                    <h2 class="fw-bold mt-3">Connexion</h2>
                    <p class="text-muted">Accédez à votre espace client</p>
                </div>

                <form action="traitement_connexion.php" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nom@exemple.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm">Se connecter</button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Pas encore de compte ? <a href="inscription.php" class="text-primary fw-bold text-decoration-none">Inscrivez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>