<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📅 Gestion des Congés</h2>
                <?php if ($user['role'] == 'rh'): ?>
                    <a href="index.php?action=demander_conge" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Demander un Congé
                    </a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ✅ Opération effectuée avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'update_failed'): ?>
                <div class="alert alert-danger">⛔ Erreur lors de la mise à jour du statut.</div>
            <?php endif; ?>

            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Employé</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($conges)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucune demande de congé.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($conges as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['id_conge']) ?></td>
                                <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                                <td><?= htmlspecialchars($c['date_debut']) ?></td>
                                <td><?= htmlspecialchars($c['date_fin']) ?></td>
                                <td><?= htmlspecialchars($c['motif'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-warning text-dark';
                                    if ($c['statut'] == 'Validé') $badgeClass = 'bg-success';
                                    elseif ($c['statut'] == 'Refusé') $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($c['statut']) ?></span>
                                </td>
                                <td>
                                    <!-- Validation : Admin et RH -->
                                    <?php if (in_array($user['role'], ['admin', 'rh']) && $c['statut'] == 'En attente'): ?>
                                        <a href="index.php?action=valider_conge&id=<?= $c['id_conge'] ?>&statut=valide"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Valider cette demande ?')">
                                            ✅
                                        </a>
                                        <a href="index.php?action=valider_conge&id=<?= $c['id_conge'] ?>&statut=refuse"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Refuser cette demande ?')">
                                            ❌
                                        </a>
                                    <?php endif; ?>

                                    <!-- Suppression : Admin et RH -->
                                    <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
                                        <a href="index.php?action=supprimer_conge&id=<?= $c['id_conge'] ?>"
                                            class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Supprimer cette demande ?')">
                                            🗑️
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>