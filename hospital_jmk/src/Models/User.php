<?php
require_once __DIR__ . '/../../config/database.php';

class User
{

    // === AUTHENTIFICATION ===
    public static function authenticate($username, $password)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            unset($user['mot_de_passe']);
            return $user;
        }
        return false;
    }

    public static function findById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // === GESTION DES UTILISATEURS (pour webmaster) ===

    // Récupérer les comptes admin et RH
    public static function getAdminAndRh()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM utilisateurs WHERE role IN ('admin', 'rh') ORDER BY role");
        return $stmt->fetchAll();
    }

    // Créer un compte (admin ou rh)
    public static function create($username, $password, $role)
    {
        global $pdo;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, mot_de_passe, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $role]);
    }

    // Mettre à jour un compte (mot de passe ou rôle)
    public static function update($id, $username, $password = null, $role = null)
    {
        global $pdo;
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET username = ?, mot_de_passe = ?, role = ? WHERE id_utilisateur = ?");
            return $stmt->execute([$username, $hash, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET username = ?, role = ? WHERE id_utilisateur = ?");
            return $stmt->execute([$username, $role, $id]);
        }
    }

    // Supprimer un compte (sécurité : empêcher la suppression du dernier admin)
    public static function delete($id)
    {
        global $pdo;
        // Vérifier qu'il reste au moins un admin
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'admin'");
        $count = $stmt->fetch()['total'];
        $user = self::findById($id);
        if ($user['role'] == 'admin' && $count <= 1) {
            return false; // Empêcher la suppression du dernier admin
        }
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        return $stmt->execute([$id]);
    }

    // Vérifier si un username existe déjà
    public static function exists($username, $excludeId = null)
    {
        global $pdo;
        if ($excludeId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM utilisateurs WHERE username = ? AND id_utilisateur != ?");
            $stmt->execute([$username, $excludeId]);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM utilisateurs WHERE username = ?");
            $stmt->execute([$username]);
        }
        return $stmt->fetch()['total'] > 0;
    }
}
