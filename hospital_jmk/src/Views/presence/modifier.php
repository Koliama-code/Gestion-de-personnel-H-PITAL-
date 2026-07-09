<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier une présence</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if (isset($presence)): ?>
                        <form method="POST" action="index.php?action=presence_modifier&id=<?= $presence['id_presence'] ?>">
                            <div class="mb-3">
                                <label>Employé</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($presence['prenom'] . ' ' . $presence['nom']) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Date</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($presence['date_presence']) ?>" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Heure arrivée</label>
                                    <input type="time" name="heure_arrivee" class="form-control" value="<?= htmlspecialchars($presence['heure_arrivee'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Heure départ</label>
                                    <input type="time" name="heure_depart" class="form-control" value="<?= htmlspecialchars($presence['heure_depart'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Statut</label>
                                <select name="statut" class="form-select">
                                    <option value="Présent" <?= ($presence['statut'] == 'Présent') ? 'selected' : '' ?>>Présent</option>
                                    <option value="Absent" <?= ($presence['statut'] == 'Absent') ? 'selected' : '' ?>>Absent</option>
                                    <option value="Retard" <?= ($presence['statut'] == 'Retard') ? 'selected' : '' ?>>Retard</option>
                                    <option value="Congé" <?= ($presence['statut'] == 'Congé') ? 'selected' : '' ?>>Congé</option>
                                    <option value="Maladie" <?= ($presence['statut'] == 'Maladie') ? 'selected' : '' ?>>Maladie</option>
                                    <option value="Formation" <?= ($presence['statut'] == 'Formation') ? 'selected' : '' ?>>Formation</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Commentaire</label>
                                <textarea name="commentaire" class="form-control" rows="2"><?= htmlspecialchars($presence['commentaire'] ?? '') ?></textarea>
                            </div>
                            <div class="mt-4 text-end">
                                <a href="index.php?action=presence_dashboard" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">Présence introuvable.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>