<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- <div class="col-md-2 sidebar p-3">
            <h5 class="text-white">🏥 Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php?action=dashboard" class="nav-link">📊 Dashboard</a></li>
                <li class="nav-item"><a href="index.php?action=employes" class="nav-link active">👥 Personnel</a></li>
                <li class="nav-item"><a href="index.php?action=logout" class="nav-link text-danger">🚪 Déconnexion</a></li>
            </ul>
        </div> -->

        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Modifier un Employé</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=employe_edit&id=<?= $employe['id_employe'] ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">🆔 Identité</h5>
                                <div class="mb-3">
                                    <label>Matricule <span class="text-danger">*</span></label>
                                    <input type="text" name="matricule" class="form-control" value="<?= htmlspecialchars($employe['matricule']) ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($employe['nom']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Prénom <span class="text-danger">*</span></label>
                                        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($employe['prenom']) ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Date de Naissance</label>
                                        <input type="date" name="date_naissance" class="form-control" value="<?= htmlspecialchars($employe['date_naissance']) ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Sexe</label>
                                        <select name="sexe" class="form-select">
                                            <option value="M" <?= ($employe['sexe'] == 'M') ? 'selected' : '' ?>>Masculin</option>
                                            <option value="F" <?= ($employe['sexe'] == 'F') ? 'selected' : '' ?>>Féminin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employe['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Téléphone</label>
                                    <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($employe['telephone']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Adresse</label>
                                    <textarea name="adresse" class="form-control" rows="2"><?= htmlspecialchars($employe['adresse']) ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">🩺 Données Professionnelles</h5>
                                <div class="mb-3">
                                    <label>Numéro d'Ordre</label>
                                    <input type="text" name="numero_ordre" class="form-control" value="<?= htmlspecialchars($employe['numero_ordre']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Date d'Embauche <span class="text-danger">*</span></label>
                                    <input type="date" name="date_embauche" class="form-control" value="<?= htmlspecialchars($employe['date_embauche']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Poste Occupé <span class="text-danger">*</span></label>
                                    <input type="text" name="poste_occupe" class="form-control" value="<?= htmlspecialchars($employe['poste_occupe']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Catégorie</label>
                                    <select name="id_categorie" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_categorie'] ?>" <?= ($employe['id_categorie'] == $cat['id_categorie']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['nom_categorie']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Spécialité</label>
                                    <select name="id_specialite" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($specialites as $spec): ?>
                                            <option value="<?= $spec['id_specialite'] ?>" <?= ($employe['id_specialite'] == $spec['id_specialite']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($spec['nom_specialite']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Service</label>
                                    <select name="id_service" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($services as $serv): ?>
                                            <option value="<?= $serv['id_service'] ?>" <?= ($employe['id_service'] == $serv['id_service']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($serv['nom_service']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Statut</label>
                                        <select name="statut_employe" class="form-select">
                                            <option value="Actif" <?= ($employe['statut_employe'] == 'Actif') ? 'selected' : '' ?>>Actif</option>
                                            <option value="En Congé" <?= ($employe['statut_employe'] == 'En Congé') ? 'selected' : '' ?>>En Congé</option>
                                            <option value="En Arrêt Maladie" <?= ($employe['statut_employe'] == 'En Arrêt Maladie') ? 'selected' : '' ?>>Arrêt Maladie</option>
                                            <option value="Suspendu" <?= ($employe['statut_employe'] == 'Suspendu') ? 'selected' : '' ?>>Suspendu</option>
                                            <option value="Démissionné" <?= ($employe['statut_employe'] == 'Démissionné') ? 'selected' : '' ?>>Démissionné</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Salaire Base (USD)</label>
                                        <input type="number" step="0.01" name="salaire_base" class="form-control" value="<?= htmlspecialchars($employe['salaire_base']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">📸 Photo actuelle</h5>
                                <div class="mb-3 d-flex align-items-center">
                                    <?php if ($employe['photo'] && file_exists('public/uploads/employes/' . $employe['photo'])): ?>
                                        <img src="public/uploads/employes/<?= htmlspecialchars($employe['photo']) ?>"
                                            alt="Photo" width="80" height="80" class="rounded-circle border me-3">
                                    <?php else: ?>
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($employe['prenom'] . '+' . $employe['nom']) ?>&background=0d6efd&color=fff&size=80"
                                            alt="Avatar" width="80" height="80" class="rounded-circle me-3">
                                    <?php endif; ?>
                                    <div>
                                        <label>Changer la photo (optionnel)</label>
                                        <input type="file" name="photo" class="form-control" accept="image/*">
                                        <small class="text-muted">Laissez vide pour conserver l'image actuelle.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="index.php?action=employes" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>