<?php
// Ne pas oublier que $user doit être disponible (défini dans chaque vue)
?>
<div class="col-md-2 sidebar p-3">
    <h5 class="text-white">🏥 Menu</h5>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="index.php?action=dashboard" class="nav-link <?= ($_GET['action'] == 'dashboard') ? 'active' : '' ?>">📊 Dashboard</a></li>
        <?php if ($user['role'] == 'admin' || $user['role'] == 'rh' || $user['role'] == 'directeur'): ?>
            <li class="nav-item"><a href="index.php?action=employes" class="nav-link <?= ($_GET['action'] == 'employes') ? 'active' : '' ?>">👥 Personnel</a></li>
        <?php endif; ?>
        <?php if ($user['role'] == 'admin'): ?>
            <li class="nav-item"><a href="index.php?action=services" class="nav-link <?= ($_GET['action'] == 'services') ? 'active' : '' ?>">🏢 Services</a></li>
        <?php endif; ?>
        <li class="nav-item mt-4"><a href="index.php?action=logout" class="nav-link text-danger">🚪 Déconnexion</a></li>
    </ul>
</div>