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

            <!-- Formulaire de sélection du mois/année -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="presence_historique">
                        <div class="col-md-3">
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
                        <div class="col-md-5 d-flex align-items-end">
                            <div class="w-100">
                                <label>Recherche</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom, prénom ou matricule...">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau des statistiques -->
            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover" id="statsTable">
                    <thead class="table-primary">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom & Prénom</th>
                            <th>Présent</th>
                            <th>Absent</th>
                            <th>Retard</th>
                            <th>Congé</th>
                            <th>Maladie</th>
                            <th>Formation</th>
                            <th><strong>Total jours</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats)): ?>
                            <tr>
                                <td colspan="9" class="text-center">Aucune donnée pour cette période.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stats as $id => $data): ?>
                                <tr class="stats-row">
                                    <td><?= htmlspecialchars($data['matricule']) ?></td>
                                    <td><?= htmlspecialchars($data['prenom'] . ' ' . $data['nom']) ?></td>
                                    <?php
                                    // Compter les jours pour chaque statut
                                    $present = $data['statuts']['Présent'] ?? 0;
                                    $absent = $data['statuts']['Absent'] ?? 0;
                                    $retard = $data['statuts']['Retard'] ?? 0;
                                    $conge = $data['statuts']['Congé'] ?? 0;
                                    $maladie = $data['statuts']['Maladie'] ?? 0;
                                    $formation = $data['statuts']['Formation'] ?? 0;
                                    // Total des jours = somme de tous les statuts (hors Absent car absent ≠ présent)
                                    // On va afficher le nombre de jours total travaillés = présent + retard + formation ? 
                                    // Mais on affichera la somme de tous les statuts sauf Absent, ou bien on affiche le total des jours pointés (Présent + Retard + Formation + Congé + Maladie)
                                    // Pour "Nombre de jours" on prend le total des jours où l'employé a eu un statut autre que 'Absent' ou on compte tous les jours du mois?
                                    // Je propose d'afficher le nombre de jours où l'employé a été présent (Présent + Retard + Formation) car ce sont des jours de travail effectif.
                                    // Mais la demande est "nombre de jours effectuer" → je vais afficher le total de jours pointés (Présent + Retard + Formation + Congé + Maladie)
                                    $totalJours = $present + $retard + $conge + $maladie + $formation;
                                    ?>
                                    <td><?= $present ?></td>
                                    <td><?= $absent ?></td>
                                    <td><?= $retard ?></td>
                                    <td><?= $conge ?></td>
                                    <td><?= $maladie ?></td>
                                    <td><?= $formation ?></td>
                                    <td><strong><?= $totalJours ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script de recherche instantanée -->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var input = this.value.toLowerCase();
        var rows = document.querySelectorAll('.stats-row');
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>