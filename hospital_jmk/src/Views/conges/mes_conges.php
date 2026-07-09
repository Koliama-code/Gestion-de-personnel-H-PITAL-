<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📋 Mes Congés</h2>
                <a href="index.php?action=demander_conge" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle Demande
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Demande envoyée avec succès !</div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Motif</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($conges)): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <?= isset($error) ? $error : 'Aucune demande de congé.' ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($conges as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['id_conge']) ?></td>
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
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>