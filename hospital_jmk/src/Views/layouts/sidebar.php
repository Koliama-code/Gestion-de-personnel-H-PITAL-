<?php
// $user est défini par le contrôleur
?>

<div class="col-md-2 sidebar p-3">
    <h5 class="text-white">🏥 Menu</h5>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="index.php?action=dashboard"
                class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'dashboard') ? 'active' : '' ?>">
                📊 Dashboard
            </a>
        </li>

        <!-- Personnel : Admin et RH -->
        <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
            <li class="nav-item">
                <a href="index.php?action=employes"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'employes') ? 'active' : '' ?>">
                    👥 Personnel
                </a>
            </li>
        <?php endif; ?>


        <!-- Présence : Admin, RH, Chef service -->
        <?php if (in_array($user['role'], ['admin', 'rh', 'chef_service'])): ?>
            <li class="nav-item">
                <a href="index.php?action=presence_dashboard"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'presence_dashboard') ? 'active' : '' ?>">
                    📊 Présence
                </a>
            </li>
        <?php endif; ?>

        <!-- Congés : Admin et RH -->
        <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
            <li class="nav-item">
                <a href="index.php?action=conges"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'conges') ? 'active' : '' ?>">
                    📅 Congés
                </a>
            </li>
        <?php endif; ?>

        <!-- Espace RH pour demander des congés -->
        <?php if ($user['role'] == 'rh'): ?>
            <!-- <li class="nav-item">
                <a href="index.php?action=mes_conges"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'mes_conges') ? 'active' : '' ?>">
                    📋 Mes Congés
                </a>
            </li> -->
            <li class="nav-item">
                <a href="index.php?action=demander_conge"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'demander_conge') ? 'active' : '' ?>">
                    ➕ Demander un Congé
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array($user['role'], ['admin', 'rh'])): ?>
            <li class="nav-item">
                <a href="index.php?action=historique_conges"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'historique_conges') ? 'active' : '' ?>">
                    📜 Historique Congés
                </a>
            </li>
        <?php endif; ?>

        <!-- Admin uniquement : Référentiels -->
        <?php if ($user['role'] == 'admin'): ?>
            <li class="nav-item">
                <a href="index.php?action=services"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'services') ? 'active' : '' ?>">
                    🏢 Services
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=categories"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'categories') ? 'active' : '' ?>">
                    📂 Catégories
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=specialites"
                    class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'specialites') ? 'active' : '' ?>">
                    🩺 Spécialités
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item mt-4">
            <a href="index.php?action=logout" class="nav-link text-danger">🚪 Déconnexion</a>
        </li>
    </ul>
</div>