<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>➕ Demander un Congé</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=demander_conge">
                        <!-- Si RH ou Admin : liste déroulante des employés -->
                        <?php if (in_array($user['role'], ['rh', 'admin'])): ?>
                            <div class="mb-3">
                                <label>Employé concerné <span class="text-danger">*</span></label>
                                <select name="id_employe" class="form-select" required>
                                    <option value="">-- Choisir un employé --</option>
                                    <?php foreach ($employes as $emp): ?>
                                        <option value="<?= $emp['id_employe'] ?>">
                                            <?= htmlspecialchars($emp['matricule'] . ' - ' . $emp['prenom'] . ' ' . $emp['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Sélectionnez l'employé pour qui vous faites la demande.</small>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label>Date de début <span class="text-danger">*</span></label>
                            <input type="date" name="date_debut" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Date de fin <span class="text-danger">*</span></label>
                            <input type="date" name="date_fin" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Motif</label>
                            <textarea name="motif" class="form-control" rows="3" placeholder="Raison de la demande..."></textarea>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="index.php?action=conges" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">📤 Envoyer la demande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>