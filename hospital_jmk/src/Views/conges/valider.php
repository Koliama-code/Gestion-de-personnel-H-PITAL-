<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <h2>📅 Détail de la demande</h2>

            <?php if (isset($conge)): ?>
                <div class="card">
                    <div class="card-body">
                        <h5>Demande de <?= htmlspecialchars($conge['prenom'] . ' ' . $conge['nom']) ?></h5>
                        <p><strong>Du :</strong> <?= htmlspecialchars($conge['date_debut']) ?></p>
                        <p><strong>Au :</strong> <?= htmlspecialchars($conge['date_fin']) ?></p>
                        <p><strong>Motif :</strong> <?= htmlspecialchars($conge['motif'] ?? 'Non précisé') ?></p>
                        <p><strong>Statut actuel :</strong> <?= htmlspecialchars($conge['statut']) ?></p>

                        <?php if ($conge['statut'] == 'En attente'): ?>
                            <a href="index.php?action=valider_conge&id=<?= $conge['id_conge'] ?>&statut=valide" class="btn btn-success">✅ Valider</a>
                            <a href="index.php?action=valider_conge&id=<?= $conge['id_conge'] ?>&statut=refuse" class="btn btn-danger">❌ Refuser</a>
                        <?php endif; ?>
                        <a href="index.php?action=conges" class="btn btn-secondary">⬅ Retour</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Demande introuvable.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>