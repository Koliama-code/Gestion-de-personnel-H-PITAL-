<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>➕ Ajouter une nouvelle Catégorie</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=categorie_add">
                        <div class="mb-3">
                            <label>Nom de la Catégorie <span class="text-danger">*</span></label>
                            <input type="text" name="nom_categorie" class="form-control" placeholder="Ex: Médical" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Description de la catégorie"></textarea>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=categories" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>