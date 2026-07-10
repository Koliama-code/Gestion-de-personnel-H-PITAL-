<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>➕ Ajouter un planning</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=planning_add">
                        <div class="mb-3">
                            <label>Service <span class="text-danger">*</span></label>
                            <select name="id_service" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($services as $s): ?>
                                    <option value="<?= $s['id_service'] ?>"><?= htmlspecialchars($s['nom_service']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="date_planning" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Type de garde <span class="text-danger">*</span></label>
                            <select name="type_garde" class="form-select" required>
                                <option value="Jour">Jour</option>
                                <option value="Nuit">Nuit</option>
                                <option value="Garde">Garde</option>
                                <option value="Repos">Repos</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Heure début</label>
                                <input type="time" name="heure_debut" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Heure fin</label>
                                <input type="time" name="heure_fin" class="form-control">
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=plannings" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>