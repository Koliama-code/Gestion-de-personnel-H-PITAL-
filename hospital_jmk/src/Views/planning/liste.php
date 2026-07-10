<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📅 Gestion des Plannings</h2>
                <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
                    <a href="index.php?action=planning_add" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nouveau Planning
                    </a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="plannings">
                        <div class="col-md-3">
                            <label>Service</label>
                            <select name="id_service" class="form-select">
                                <option value="">-- Tous --</option>
                                <?php foreach ($services as $s): ?>
                                    <option value="<?= $s['id_service'] ?>" <?= (isset($_GET['id_service']) && $_GET['id_service'] == $s['id_service']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['nom_service']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Mois</label>
                            <select name="mois" class="form-select">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= (isset($_GET['mois']) && $_GET['mois'] == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
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

            <!-- Tableau -->
            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Type</th>
                            <th>Heure début</th>
                            <th>Heure fin</th>
                            <th>Affectations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($plannings)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun planning trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($plannings as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['date_planning']) ?></td>
                                    <td><?= htmlspecialchars($p['nom_service'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge <?= ($p['type_garde'] == 'Nuit') ? 'bg-dark' : 'bg-info' ?>">
                                            <?= htmlspecialchars($p['type_garde']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($p['heure_debut'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($p['heure_fin'] ?? '-') ?></td>
                                    <td>
                                        <a href="index.php?action=planning_affecter&id=<?= $p['id_planning'] ?>" class="btn btn-sm btn-secondary">
                                            <i class="bi bi-people"></i> Voir
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
                                            <a href="index.php?action=planning_edit&id=<?= $p['id_planning'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="index.php?action=planning_delete&id=<?= $p['id_planning'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce planning ?')">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        <?php endif; ?>
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