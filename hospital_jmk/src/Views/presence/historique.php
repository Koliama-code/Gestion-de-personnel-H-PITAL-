<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📜 Historique des Présences</h2>
                <a href="index.php?action=presence_dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <!-- Formulaire de filtre -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="presence_historique">
                        <div class="col-md-4">
                            <label>Employé</label>
                            <select name="id_employe" class="form-select">
                                <option value="">-- Tous --</option>
                                <?php foreach ($employes as $emp): ?>
                                    <option value="<?= $emp['id_employe'] ?>" <?= (isset($_GET['id_employe']) && $_GET['id_employe'] == $emp['id_employe']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['matricule'] . ' - ' . $emp['prenom'] . ' ' . $emp['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Mois</label>
                            <select name="mois" class="form-select">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= (isset($_GET['mois']) && $_GET['mois'] == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Année</label>
                            <select name="annee" class="form-select">
                                <?php for ($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                                    <option value="<?= $y ?>" <?= (isset($_GET['annee']) && $_GET['annee'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
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
                            <th>Date</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                            <th>Statut</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historique)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucune donnée pour cette période.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historique as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['date_presence']) ?></td>
                                    <td><?= htmlspecialchars($h['heure_arrivee'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($h['heure_depart'] ?? '-') ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'bg-success';
                                        if ($h['statut'] == 'Absent') $badgeClass = 'bg-danger';
                                        elseif ($h['statut'] == 'Retard') $badgeClass = 'bg-warning text-dark';
                                        elseif ($h['statut'] == 'Congé') $badgeClass = 'bg-info';
                                        elseif ($h['statut'] == 'Maladie') $badgeClass = 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($h['statut']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($h['commentaire'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Statistiques -->
            <?php if (!empty($stats)): ?>
                <div class="row g-4 mt-3">
                    <?php foreach ($stats as $s): ?>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body">
                                    <h5><?= htmlspecialchars($s['statut']) ?></h5>
                                    <h2 class="display-6"><?= $s['total'] ?></h2>
                                    <small class="text-muted">jours</small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>