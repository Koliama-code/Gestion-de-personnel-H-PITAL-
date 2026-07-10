<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🔐 Gestion des Comptes (Admin & RH)</h2>
                <a href="index.php?action=webmaster_add" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau compte
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Opération effectuée avec succès !</div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'last_admin'): ?>
                <div class="alert alert-danger">
                    ⛔ Impossible de supprimer le dernier compte administrateur.
                </div>
            <?php endif; ?>

            <div class="table-responsive card shadow-sm p-3">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucun compte admin ou RH trouvé.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['id_utilisateur']) ?></td>
                                <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                                <td>
                                    <span class="badge <?= ($u['role'] == 'admin') ? 'bg-danger' : 'bg-primary' ?>">
                                        <?= strtoupper($u['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?action=webmaster_edit&id=<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="index.php?action=webmaster_delete&id=<?= $u['id_utilisateur'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Confirmer la suppression de ce compte ?')">
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