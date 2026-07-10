<?php
// Ce fichier est inclus dans toutes les vues qui ont besoin du menu
// Il suppose que la variable $user (contenant les infos de session) est déjà définie
?>

<div class="col-md-2 sidebar p-3">
    <h5 class="text-white"><i class="bi bi-hospital"></i> Hôpital JMK</h5>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="index.php?action=dashboard"
                class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <!-- Webmaster : Gestion des comptes -->
        <?php if ($user['role'] == 'webmaster'): ?>
            <li class="nav-item">
                <a href="index.php?action=webmaster_dashboard"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'webmaster_dashboard') ? 'active' : '' ?>">
                    🔐 Gestion comptes
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array($user['role'], ['admin', 'rh', 'directeur'])): ?>
            <li class="nav-item">
                <a href="index.php?action=employes"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'employes') ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Personnel
                </a>
            </li>
        <?php endif; ?>


        <?php if (in_array($user['role'], ['admin', 'rh', 'chef_service'])): ?>
            <li class="nav-item">
                <a href="index.php?action=presence_dashboard"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'presence_dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i> Présence
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array($user['role'], ['admin', 'rh', 'chef_service'])): ?>
            <li class="nav-item">
                <a href="index.php?action=conges"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'conges') ? 'active' : '' ?>">
                    <i class="bi bi-calendar-check"></i> Congés
                </a>
            </li>
        <?php endif; ?>



        <?php if (in_array($user['role'], ['admin', 'rh', 'chef_service'])): ?>
            <li class="nav-item">
                <a href="index.php?action=plannings"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'plannings') ? 'active' : '' ?>">
                    <i class="bi bi-calendar-week"></i> Plannings
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array($user['role'], ['employe', 'chef_service', 'rh'])): ?>
            <li class="nav-item">
                <a href="index.php?action=mes_conges"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'mes_conges') ? 'active' : '' ?>">
                    <i class="bi bi-person-vcard"></i> Mes Congés
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array($user['role'], ['admin', 'rh', 'chef_service', 'employe'])): ?>
            <li class="nav-item">
                <a href="index.php?action=historique_conges"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'historique_conges') ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i> Historique
                </a>
            </li>
        <?php endif; ?>

        <?php if ($user['role'] == 'admin'): ?>
            <li class="nav-item">
                <a href="index.php?action=services"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'services') ? 'active' : '' ?>">
                    <i class="bi bi-building"></i> Services
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=categories"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'categories') ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i> Catégories
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=specialites"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'specialites') ? 'active' : '' ?>">
                    <i class="bi bi-star"></i> Spécialités
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item mt-4">
            <a href="index.php?action=logout" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </li>
    </ul>
</div>