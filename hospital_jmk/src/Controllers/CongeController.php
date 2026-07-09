<?php
require_once __DIR__ . '/../Models/Conge.php';
require_once __DIR__ . '/../Models/Employe.php';
require_once __DIR__ . '/AuthController.php';

class CongeController
{

    // ============================================
    // 1. LISTE DES CONGÉS
    // ============================================
    public function listAction()
    {
        $user = AuthController::checkAuth();

        if ($user['role'] == 'admin' || $user['role'] == 'rh') {
            $conges = Conge::getAll();
        } elseif ($user['role'] == 'chef_service') {
            $employe = Employe::getById($user['id_employe']);
            $id_service = $employe['id_service'] ?? null;
            $conges = Conge::getByService($id_service);
        } else {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $success = isset($_GET['success']) ? true : false;
        $error = isset($_GET['error']) ? $_GET['error'] : null;

        include __DIR__ . '/../Views/conges/liste.php';
    }

    // ============================================
    // 2. DEMANDER UN CONGÉ
    // ============================================
    public function demanderAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['employe', 'chef_service', 'rh', 'admin'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $employes = [];
        if (in_array($user['role'], ['rh', 'admin'])) {
            $employes = Employe::getAllForSelect();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (in_array($user['role'], ['rh', 'admin'])) {
                $id_employe = $_POST['id_employe'] ?? null;
                if (!$id_employe) {
                    $error = "Veuillez sélectionner un employé.";
                    include __DIR__ . '/../Views/conges/demander.php';
                    return;
                }
            } else {
                $id_employe = $user['id_employe'];
                if (!$id_employe) {
                    $error = "Vous n'êtes pas associé à un employé.";
                    include __DIR__ . '/../Views/conges/demander.php';
                    return;
                }
            }

            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $motif = htmlspecialchars(trim($_POST['motif']));

            if (strtotime($date_debut) > strtotime($date_fin)) {
                $error = "La date de fin doit être postérieure à la date de début.";
            } elseif (Conge::demander($id_employe, $date_debut, $date_fin, $motif)) {
                header('Location: index.php?action=conges&success=1');
                exit;
            } else {
                $error = "Erreur lors de la demande. Vérifiez qu'il n'y a pas de chevauchement avec un autre congé.";
            }
        }

        include __DIR__ . '/../Views/conges/demander.php';
    }

    // ============================================
    // 3. MES CONGÉS
    // ============================================
    public function mesCongesAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['employe', 'chef_service', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        if (!$user['id_employe']) {
            $conges = [];
            $error = "Vous n'êtes pas associé à un employé.";
            include __DIR__ . '/../Views/conges/mes_conges.php';
            return;
        }

        $conges = Conge::getByEmploye($user['id_employe']);
        include __DIR__ . '/../Views/conges/mes_conges.php';
    }

    // ============================================
    // 4. VALIDER UN CONGÉ
    // ============================================
    public function validerAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh', 'chef_service'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $statut = $_GET['statut'] ?? null;

        if ($id && in_array($statut, ['valide', 'refuse'])) {
            $nouveauStatut = ($statut == 'valide') ? 'Validé' : 'Refusé';
            $result = Conge::updateStatut($id, $nouveauStatut);

            if ($result) {
                header('Location: index.php?action=conges&success=1');
                exit;
            } else {
                header('Location: index.php?action=conges&error=update_failed');
                exit;
            }
        }

        if ($id) {
            $conge = Conge::getById($id);
            if (!$conge) {
                header('Location: index.php?action=conges');
                exit;
            }
            include __DIR__ . '/../Views/conges/valider.php';
        } else {
            header('Location: index.php?action=conges');
            exit;
        }
    }

    // ============================================
    // 5. SUPPRIMER UN CONGÉ
    // ============================================
    public function deleteAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            Conge::delete($id);
        }

        header('Location: index.php?action=conges&success=1');
        exit;
    }

    // ============================================
    // 6. RETOUR DE CONGÉ (RH ou Admin)
    // ============================================
    public function retourAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_employe = $_GET['id_employe'] ?? null;
        if ($id_employe) {
            Conge::retourCongé($id_employe);
        }

        header('Location: index.php?action=historique_conges&success=1');
        exit;
    }

    // ============================================
    // 7. HISTORIQUE DES CONGÉS (Admin, RH)
    // ============================================
    public function historiqueAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_employe = $_GET['id_employe'] ?? null;
        $historique = Conge::getHistorique($id_employe);

        // Récupérer tous les employés pour le filtre
        $employes = Employe::getAllForSelect();

        include __DIR__ . '/../Views/conges/historique.php';
    }
}
