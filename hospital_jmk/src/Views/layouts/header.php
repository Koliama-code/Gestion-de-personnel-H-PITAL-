<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Personnel - Hôpital JMK</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts (Segoe UI déjà présent) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Style personnalisé -->
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>

<body>


    <!-- Barre d'info utilisateur -->
    <div class="container-fluid bg-light border-bottom py-2">
        <div class="row align-items-center">
            <div class="col-6">
                <span class="text-muted small">🏥 Hôpital JMK</span>
            </div>
            <div class="col-6 text-end">
                <?php if (isset($_SESSION['user'])): ?>
                    <i class="bi bi-person-circle text-primary"></i>
                    <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
                    <span class="badge bg-secondary ms-1"><?= htmlspecialchars($_SESSION['user']['role']) ?></span>
                    <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm ms-2">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <style>
        /* ---- SIDEBAR FIXE ---- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            /* Largeur fixe (ajuste selon ton design) */
            height: 100vh;
            /* Hauteur totale de l'écran */
            overflow-y: auto;
            /* Scroll si le menu est trop long */
            z-index: 1000;
            /* Pour passer au-dessus du contenu */
            background-color: #0d6efd;
            /* Couleur de fond (modifiable) */
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* ---- CONTENU PRINCIPAL ---- */
        .main-content {
            margin-left: 250px;
            /* Décale pour ne pas être sous la sidebar */
            padding: 20px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
    </style>