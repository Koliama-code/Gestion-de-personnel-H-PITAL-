<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>🕐 Pointer un employé</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=presence_pointer">
                        <div class="mb-3">
                            <label>Employé <span class="text-danger">*</span></label>
                            <select name="id_employe" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                <?php foreach ($employes as $emp): ?>
                                    <option value="<?= $emp['id_employe'] ?>">
                                        <?= htmlspecialchars($emp['matricule'] . ' - ' . $emp['prenom'] . ' ' . $emp['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="arrivee">Arrivée</option>
                                <option value="depart">Départ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Commentaire</label>
                            <textarea name="commentaire" class="form-control" rows="2" placeholder="Optionnel..."></textarea>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=presence_dashboard" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Pointer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>