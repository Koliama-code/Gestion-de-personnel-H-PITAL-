<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📊 Présence du Jour</h2>
                <div>
                    <a href="index.php?action=presence_pointer" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Pointer
                    </a>
                    <a href="index.php?action=presence_historique" class="btn btn-info">
                        <i class="bi bi-clock-history"></i> Historique
                    </a>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <!-- Statistiques rapides -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary shadow">
                        <div class="card-body text-center">
                            <h2 class="display-6"><?= $total ?? 0 ?></h2>
                            <p>Total</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success shadow">
                        <div class="card-body text-center">
                            <h2 class="display-6"><?= $present ?? 0 ?></h2>
                            <p>Présents</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger shadow">
                        <div class="card-body text-center">
                            <h2 class="display-6"><?= $absent ?? 0 ?></h2>
                            <p>Absents</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des présences -->
            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Employé</th>
                            <th>Service</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($presences)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucun pointage pour aujourd'hui.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($presences as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></td>
                                <td><?= htmlspecialchars($p['nom_service'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($p['heure_arrivee'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($p['heure_depart'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-success';
                                    if ($p['statut'] == 'Absent') $badgeClass = 'bg-danger';
                                    elseif ($p['statut'] == 'Retard') $badgeClass = 'bg-warning text-dark';
                                    elseif ($p['statut'] == 'Congé') $badgeClass = 'bg-info';
                                    elseif ($p['statut'] == 'Maladie') $badgeClass = 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($p['statut']) ?></span>
                                </td>
                                <td>
                                    <a href="index.php?action=presence_modifier&id=<?= $p['id_presence'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
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