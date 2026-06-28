<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
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
}
