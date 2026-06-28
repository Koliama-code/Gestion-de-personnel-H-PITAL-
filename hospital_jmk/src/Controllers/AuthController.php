<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController
{

    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                $user = User::authenticate($username, $password);
                if ($user) {
                    $_SESSION['user'] = $user;
                    header('Location: index.php?action=dashboard');
                    exit;
                } else {
                    $error = "Identifiants incorrects.";
                }
            }
        }
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public static function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
        return $_SESSION['user'];
    }

    public static function checkRole($requiredRole)
    {
        $user = self::checkAuth();
        $rolesHierarchy = ['employe' => 1, 'chef_service' => 2, 'rh' => 3, 'directeur' => 4, 'admin' => 5];

        if ($requiredRole === 'admin' && $user['role'] !== 'admin') {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        if (isset($rolesHierarchy[$requiredRole]) && isset($rolesHierarchy[$user['role']])) {
            if ($rolesHierarchy[$user['role']] < $rolesHierarchy[$requiredRole]) {
                header('Location: index.php?action=dashboard&error=acces_refuse');
                exit;
            }
        }
        return $user;
    }
}
