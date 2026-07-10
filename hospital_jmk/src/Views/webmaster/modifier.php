<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier le compte de <?= htmlspecialchars($editUser['username']) ?></h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=webmaster_edit&id=<?= $editUser['id_utilisateur'] ?>">
                        <div class="mb-3">
                            <label>Nom d'utilisateur</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($editUser['username']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                            <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe...">
                        </div>
                        <div class="mb-3">
                            <label>Rôle</label>
                            <select name="role" class="form-select">
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