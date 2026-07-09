<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📜 Historique des Congés</h2>
                <a href="index.php?action=conges" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Retour de congé effectué avec succès !</div>
            <?php endif; ?>

            <!-- Filtre par employé -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="historique_conges">
                        <div class="col-md-8">
                            <label>Filtrer par employé</label>
                            <select name="id_employe" class="form-select">
                                <option value="">-- Tous les employés --</option>
                                <?php foreach ($employes as $emp): ?>
                                    <option value="<?= $emp['id_employe'] ?>" <?= (isset($_GET['id_employe']) && $_GET['id_employe'] == $emp['id_employe']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['matricule'] . ' - ' . $emp['prenom'] . ' ' . $emp['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau historique -->
            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Employé</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historique)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucun historique disponible.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($historique as $h): ?>
                            <tr>
                                <td><?= htmlspecialchars($h['id_historique']) ?></td>
                                <td><?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?></td>
                                <td>
                                    <?php
                                    $actionLabels = [
                                        'demande' => '📝 Demande',
                                        'validation' => '✅ Validation',
                                        'refus' => '❌ Refus',
                                        'retour_conge' => '🔄 Retour de congé'
                                    ];
                                    $badgeColors = [
                                        'demande' => 'bg-warning text-dark',
                                        'validation' => 'bg-success',
                                        'refus' => 'bg-danger',
                                        'retour_conge' => 'bg-info'
                                    ];
                                    ?>
                                    <span class="badge <?= $badgeColors[$h['action']] ?? 'bg-secondary' ?>">
                                        <?= $actionLabels[$h['action']] ?? $h['action'] ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($h['date_action']) ?></td>
                                <td><?= htmlspecialchars($h['commentaire'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bouton pour retour de congé automatique -->
            <div class="mt-3">
                <a href="index.php?action=retour_conge_auto" class="btn btn-info">
                    <i class="bi bi-arrow-repeat"></i> Vérifier les retours de congé automatiques
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>