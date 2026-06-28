<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">


        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🏢 Gestion des Services</h2>
                <a href="index.php?action=service_add" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Service
                </a>
            </div>

            <!-- Messages de succès / erreur -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'used'): ?>
                <div class="alert alert-danger">
                    ⛔ Impossible de supprimer ce service car il est actuellement attribué à un ou plusieurs employés.
                </div>
            <?php endif; ?>

            <!-- Tableau des services -->
            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nom du Service</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                            <tr>
                                <td colspan="3" class="text-center">Aucun service enregistré.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($services as $serv): ?>
                            <tr>
                                <td><?= htmlspecialchars($serv['id_service']) ?></td>
                                <td><strong><?= htmlspecialchars($serv['nom_service']) ?></strong></td>
                                <td>
                                    <a href="index.php?action=service_edit&id=<?= $serv['id_service'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="index.php?action=service_delete&id=<?= $serv['id_service'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Confirmer la suppression de ce service ?')">
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