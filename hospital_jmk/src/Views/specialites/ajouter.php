<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>➕ Ajouter une nouvelle Spécialité</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=specialite_add">
                        <div class="mb-3">
                            <label>Nom de la Spécialité <span class="text-danger">*</span></label>
                            <input type="text" name="nom_specialite" class="form-control" placeholder="Ex: Cardiologie" required>
                        </div>
                        <div class="mb-3">
                            <label>Catégorie associée</label>
                            <select name="id_categorie" class="form-select">
                                <option value="">-- Aucune --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=specialites" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>