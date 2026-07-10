<?php
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../Models/User.php';

class WebmasterController
{

    // Vérifier que l'utilisateur est bien webmaster
    private function checkWebmaster()
    {
        $user = AuthController::checkAuth();
        if ($user['role'] !== 'webmaster') {
            header('Location: index.php?action=dashboard');
            exit;
        }
        return $user;
    }

    // Tableau de bord du webmaster
    public function dashboardAction()
    {
        $user = $this->checkWebmaster();
        global $pdo;

        // Récupérer tous les utilisateurs (admin, rh)
        $stmt = $pdo->query("SELECT id_utilisateur, username, role, id_employe FROM utilisateurs ORDER BY role");
        $users = $stmt->fetchAll();

        include __DIR__ . '/../Views/webmaster/dashboard.php';
    }

    // Ajouter un utilisateur (admin ou rh)
    public function addAction()
    {
        $user = $this->checkWebmaster();
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $role = $_POST['role']; // 'admin' ou 'rh'

            // Vérifier que le rôle est autorisé
            if (!in_array($role, ['admin', 'rh'])) {
                $error = "Rôle non autorisé.";
                include __DIR__ . '/../Views/webmaster/ajouter.php';
                return;
            }

            // Vérifier que l'username n'existe pas déjà
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Ce nom d'utilisateur est déjà pris.";
                include __DIR__ . '/../Views/webmaster/ajouter.php';
                return;
            }

            // Hash du mot de passe
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertion
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, mot_de_passe, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $role]);

            header('Location: index.php?action=webmaster_dashboard&success=1');
            exit;
        }

        include __DIR__ . '/../Views/webmaster/ajouter.php';
    }

    // Modifier un utilisateur (changer le mot de passe ou le rôle)
    public function editAction()
    {
        $user = $this->checkWebmaster();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=webmaster_dashboard');
            exit;
        }

        // Récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        $editUser = $stmt->fetch();
        if (!$editUser) {
            header('Location: index.php?action=webmaster_dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = trim($_POST['password'] ?? '');
            $newRole = $_POST['role'] ?? $editUser['role'];

            $success = true;

            // Mettre à jour le mot de passe si fourni
            if (!empty($newPassword)) {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id_utilisateur = ?");
                if (!$stmt->execute([$hash, $id])) {
                    $success = false;
                }
            }

            // Mettre à jour le rôle si changé
            if ($newRole !== $editUser['role']) {
                if (in_array($newRole, ['admin', 'rh'])) {
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id_utilisateur = ?");
                    if (!$stmt->execute([$newRole, $id])) {
                        $success = false;
                    }
                }
            }

            if ($success) {
                header('Location: index.php?action=webmaster_dashboard&success=1');
                exit;
            } else {
                $error = "Erreur lors de la mise à jour du compte.";
            }
        }

        include __DIR__ . '/../Views/webmaster/modifier.php';
    }

    // Supprimer un utilisateur (sauf soi-même)
    public function deleteAction()
    {
        $user = $this->checkWebmaster();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if ($id && $id != $user['id_utilisateur']) {
            $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
            $stmt->execute([$id]);
        }
        header('Location: index.php?action=webmaster_dashboard&success=1');
        exit;
    }
}
