<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier le compte</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=webmaster_edit&id=<?= $editUser['id_utilisateur'] ?>">
                        <div class="mb-3">
                            <label>Nom d'utilisateur <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($editUser['username']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Nouveau mot de passe (optionnel)</label>
                            <input type="text" name="new_password" class="form-control" placeholder="Laisser vide pour conserver l'actuel">
                            <small class="text-muted">Si vous saisissez un mot de passe, il remplacera l'ancien.</small>
                        </div>
                        <div class="mb-3">
                            <label>Rôle <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="admin" <?= ($editUser['role'] == 'admin') ? 'selected' : '' ?>>Administrateur</option>
                                <option value="rh" <?= ($editUser['role'] == 'rh') ? 'selected' : '' ?>>Responsable RH</option>
                            </select>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=webmaster_dashboard" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>