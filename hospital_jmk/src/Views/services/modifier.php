<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier le Service</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=service_edit&id=<?= $service['id_service'] ?>">
                        <div class="mb-3">
                            <label>Nom du Service <span class="text-danger">*</span></label>
                            <input type="text" name="nom_service" class="form-control" value="<?= htmlspecialchars($service['nom_service']) ?>" required>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=services" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>