<?php
require_once __DIR__ . '/../Controllers/AuthController.php';
$user = AuthController::checkAuth();
include __DIR__ . '/layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- ===== SIDEBAR ===== -->
        <?php include __DIR__ . '/layouts/sidebar.php'; ?>


        <!-- ===== CONTENU ===== -->
        <div class="col-md-10 main-content">
            <h1>Bonjour, <?= htmlspecialchars($user['username']) ?> !</h1>
            <p>Rôle : <strong><?= htmlspecialchars($user['role']) ?></strong></p>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Tableau de bord simplifié. Les statistiques arriveront bientôt !
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>