<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <h2 class="fw-bold text-center mb-4">Créer un compte</h2>
                
                <form action="traitement_inscription.php" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
                        <div class="form-text">Nous ne partagerons jamais votre email.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label small" for="terms">J'accepte les conditions générales d'utilisation</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 btn-lg">Créer mon compte</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>