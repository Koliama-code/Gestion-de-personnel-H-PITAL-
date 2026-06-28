<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier la Catégorie</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=categorie_edit&id=<?= $categorie['id_categorie'] ?>">
                        <div class="mb-3">
                            <label>Nom de la Catégorie <span class="text-danger">*</span></label>
                            <input type="text" name="nom_categorie" class="form-control" value="<?= htmlspecialchars($categorie['nom_categorie']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($categorie['description'] ?? '') ?></textarea>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=categories" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>