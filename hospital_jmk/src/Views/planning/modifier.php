<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier un planning</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=planning_edit&id=<?= $planning['id_planning'] ?>">
                        <div class="mb-3">
                            <label>Service <span class="text-danger">*</span></label>
                            <select name="id_service" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($services as $s): ?>
                                    <option value="<?= $s['id_service'] ?>" <?= ($s['id_service'] == $planning['id_service']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['nom_service']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="date_planning" class="form-control" value="<?= htmlspecialchars($planning['date_planning']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Type de garde <span class="text-danger">*</span></label>
                            <select name="type_garde" class="form-select" required>
                                <?php foreach (['Jour', 'Nuit', 'Garde', 'Repos'] as $type): ?>
                                    <option value="<?= $type ?>" <?= ($type == $planning['type_garde']) ? 'selected' : '' ?>><?= $type ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Heure début</label>
                                <input type="time" name="heure_debut" class="form-control" value="<?= htmlspecialchars($planning['heure_debut'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Heure fin</label>
                                <input type="time" name="heure_fin" class="form-control" value="<?= htmlspecialchars($planning['heure_fin'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=plannings" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>