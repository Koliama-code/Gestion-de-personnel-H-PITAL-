<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>➕ Ajouter un compte (Admin ou RH)</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=webmaster_add">
                        <div class="mb-3">
                            <label>Nom d'utilisateur <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Ex: admin2" required>
                        </div>
                        <div class="mb-3">
                            <label>Mot de passe <span class="text-danger">*</span></label>
                            <input type="text" name="password" class="form-control" placeholder="Ex: MonMotDePasse123!" required>
                            <small class="text-muted">Le mot de passe sera stocké de manière sécurisée (hashé).</small>
                        </div>
                        <div class="mb-3">
                            <label>Rôle <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="admin">Administrateur</option>
                                <option value="rh">Responsable RH</option>
                            </select>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=webmaster_dashboard" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Créer le compte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>