<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>👥 Affectations du planning du <?= htmlspecialchars($planning['date_planning']) ?></h2>
                <a href="index.php?action=plannings" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <!-- Formulaire d'affectation -->
            <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Ajouter une affectation</h5>
                        <form method="POST" action="index.php?action=planning_affecter&id=<?= $id_planning ?>" class="row g-3">
                            <div class="col-md-8">
                                <select name="id_employe" class="form-select" required>
                                    <option value="">-- Choisir un employé --</option>
                                    <?php foreach ($employes as $e): ?>
                                        <option value="<?= $e['id_employe'] ?>">
                                            <?= htmlspecialchars($e['matricule'] . ' - ' . $e['prenom'] . ' ' . $e['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Affecter</button>
                            </div>
                        </form>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger mt-2"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Liste des affectations -->
            <div class="card shadow p-3">
                <h5>Employés affectés</h5>
                <?php if (empty($affectations)): ?>
                    <p class="text-muted">Aucun employé affecté à ce planning.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom & Prénom</th>
                                <th>Date affectation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($affectations as $a): ?>
                                <tr>
                                    <td><?= htmlspecialchars($a['matricule']) ?></td>
                                    <td><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></td>
                                    <td><?= htmlspecialchars($a['created_at'] ?? '-') ?></td>
                                    <td>
                                        <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
                                            <a href="index.php?action=planning_desaffecter&id_planning=<?= $id_planning ?>&id_employe=<?= $a['id_employe'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Retirer cet employé ?')">
                                                <i class="bi bi-person-dash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>