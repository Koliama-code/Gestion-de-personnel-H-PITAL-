<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- ===== SIDEBAR ===== -->
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <!-- ===== CONTENU ===== -->
        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>👥 Gestion du Personnel</h2>
                <a href="index.php?action=employe_add" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel Employé
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Photo</th>
                            <th>Matricule</th>
                            <th>Nom & Prénom</th>
                            <th>Catégorie</th>
                            <th>Spécialité</th>
                            <th>Service</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($employes)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Aucun employé enregistré.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($employes as $emp): ?>
                            <tr>
                                <td>
                                    <?php if ($emp['photo'] && file_exists('public/uploads/employes/' . $emp['photo'])): ?>
                                        <img src="public/uploads/employes/<?= htmlspecialchars($emp['photo']) ?>"
                                            alt="Photo" width="45" height="45" class="rounded-circle border">
                                    <?php else: ?>
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($emp['prenom'] . '+' . $emp['nom']) ?>&background=0d6efd&color=fff&size=45"
                                            alt="Avatar" width="45" height="45" class="rounded-circle">
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($emp['matricule']) ?></strong></td>
                                <td><?= htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($emp['nom_categorie'] ?? 'N/C') ?></span></td>
                                <td><?= htmlspecialchars($emp['nom_specialite'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($emp['nom_service'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $statut = $emp['statut_employe'];
                                    $badgeClass = 'bg-success';
                                    if ($statut == 'En Congé') $badgeClass = 'bg-warning text-dark';
                                    elseif ($statut == 'En Arrêt Maladie') $badgeClass = 'bg-danger';
                                    elseif ($statut == 'Suspendu') $badgeClass = 'bg-dark';
                                    elseif ($statut == 'Démissionné') $badgeClass = 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statut) ?></span>
                                </td>
                                <td>
                                    <a href="index.php?action=employe_edit&id=<?= $emp['id_employe'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="index.php?action=employe_delete&id=<?= $emp['id_employe'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="bi bi-trash3"></i>
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

<?php include __DIR__ . '/layouts/footer.php'; ?>