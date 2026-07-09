<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🩺 Gestion des Spécialités</h2>
                <a href="index.php?action=specialite_add" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle Spécialité
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'used'): ?>
                <div class="alert alert-danger">
                    ⛔ Impossible de supprimer cette spécialité car elle est attribuée à un ou plusieurs employés.
                </div>
            <?php endif; ?>

            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($specialites)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucune spécialité enregistrée.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($specialites as $spec): ?>
                            <tr>
                                <td><?= htmlspecialchars($spec['id_specialite']) ?></td>
                                <td><strong><?= htmlspecialchars($spec['nom_specialite']) ?></strong></td>
                                <td><?= htmlspecialchars($spec['nom_categorie'] ?? 'Non catégorisée') ?></td>
                                <td>
                                    <a href="index.php?action=specialite_edit&id=<?= $spec['id_specialite'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="index.php?action=specialite_delete&id=<?= $spec['id_specialite'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Confirmer la suppression de cette spécialité ?')">
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>