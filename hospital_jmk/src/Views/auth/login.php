<?php
// Démarrer la session pour afficher les erreurs si besoin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Hôpital JMK</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ========================================
               GLOBAL
            ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f0fe 0%, #d4e4f7 50%, #b8d4f0 100%);
            background-attachment: fixed;
            padding: 20px;
        }

        /* ========================================
               CARTE DE CONNEXION
            ======================================== */
        .login-wrapper {
            width: 100%;
            max-width: 480px;
            animation: fadeInUp 0.8s ease forwards;
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 20px 60px rgba(13, 110, 253, 0.15), 0 8px 24px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 70px rgba(13, 110, 253, 0.20), 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        /* ========================================
               EN-TÊTE
            ======================================== */
        .login-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #042d6c, #04275c);
            border-radius: 18px;
            color: #fff;
            font-size: 32px;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.25);
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .login-header p {
            font-size: 15px;
            color: #6c757d;
            font-weight: 400;
            margin: 0;
        }

        /* ========================================
               MESSAGE D'ERREUR
            ======================================== */
        .alert-custom {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 12px;
            color: #b91c1c;
            padding: 12px 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            animation: shake 0.4s ease;
        }

        .alert-custom i {
            font-size: 18px;
            color: #dc2626;
        }

        /* ========================================
               FORMULAIRE
            ======================================== */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            color: #9ca3af;
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 14px 14px 46px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #fafbfc;
            color: #1a1a2e;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: #0a316d;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.10);
        }

        .input-wrapper input:focus+.input-icon,
        .input-wrapper input:focus~.input-icon {
            color: #042557;
        }

        .input-wrapper input::placeholder {
            color: #b0b8c4;
            font-weight: 400;
        }

        /* ========================================
               BOUTON DE CONNEXION
            ======================================== */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #073b8a, #021e49);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-right: 10px;
        }

        /* ========================================
               PIED DE PAGE
            ======================================== */
        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .login-footer p {
            font-size: 13px;
            color: #9ca3af;
            margin: 0;
        }

        .login-footer .badge-version {
            display: inline-block;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        /* ========================================
               SHOW PASSWORD TOGGLE
            ======================================== */
        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #0d6efd;
        }

        /* ========================================
               ANIMATIONS
            ======================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(8px);
            }

            60% {
                transform: translateX(-4px);
            }

            80% {
                transform: translateX(4px);
            }
        }

        /* ========================================
               RESPONSIVE
            ======================================== */
        @media (max-width: 576px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 20px;
            }

            .login-logo {
                width: 60px;
                height: 60px;
                font-size: 26px;
            }

            .login-header h1 {
                font-size: 20px;
            }

            .input-wrapper input {
                padding: 12px 12px 12px 42px;
                font-size: 14px;
            }

            .btn-login {
                padding: 12px;
                font-size: 15px;
            }
        }

        @media (max-width: 400px) {
            .login-card {
                padding: 24px 16px;
            }
        }

        /* ========================================
               DESIGN OPTIONNEL - DÉCORATIONS
            ======================================== */
        .login-decoration {
            position: fixed;
            border-radius: 50%;
            opacity: 0.10;
            pointer-events: none;
            z-index: 0;
        }

        .login-decoration.d1 {
            width: 300px;
            height: 300px;
            background: #0d6efd;
            top: -100px;
            right: -80px;
        }

        .login-decoration.d2 {
            width: 200px;
            height: 200px;
            background: #0a58ca;
            bottom: -60px;
            left: -60px;
        }

        @media (max-width: 576px) {
            .login-decoration {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Décoration d'arrière-plan -->
    <div class="login-decoration d1"></div>
    <div class="login-decoration d2"></div>

    <!-- ============================================= -->
    <!-- CARTE DE CONNEXION                           -->
    <!-- ============================================= -->
    <div class="login-wrapper">

        <div class="login-card">

            <!-- Logo et titre -->
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-hospital"></i>
                </div>
                <h1>🏥 Hôpital JMK</h1>
                <p>Gestion du personnel médical</p>
            </div>

            <!-- Message d'erreur -->
            <?php if (isset($error)): ?>
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form method="POST" action="index.php?action=login" autocomplete="off">

                <!-- Champ Utilisateur -->
                <div class="form-group">
                    <label for="username"><i class="fas fa-user me-1"></i> Nom d'utilisateur</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Entrez votre nom d'utilisateur"
                            value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                            required
                            autofocus>
                    </div>
                </div>

                <!-- Champ Mot de passe -->
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock me-1"></i> Mot de passe</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-key"></i></span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Entrez votre mot de passe"
                            required>
                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword()"
                            aria-label="Afficher/masquer le mot de passe">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Bouton de connexion -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>

            </form>

            <!-- Pied de page -->
            <div class="login-footer">
                <p>
                    <span class="badge-version">
                        <i class="fas fa-code-branch me-1"></i> v1.0
                    </span>
                </p>
                <p class="mt-2">
                    <i class="fas fa-shield-alt me-1"></i>
                    Sécurisé • Connexion réservée au personnel autorisé
                </p>
            </div>

        </div>
    </div>

    <!-- ============================================= -->
    <!-- SCRIPTS                                       -->
    <!-- ============================================= -->
    <script>
        // === Toggle Password ===
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // === Focus automatique sur le premier champ ===
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                usernameInput.focus();
            }
        });

        // === Soumettre avec la touche Entrée ===
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const form = document.querySelector('form');
                if (form) {
                    form.submit();
                }
            }
        });
    </script>

</body>

</html>