<?php
require_once __DIR__ . '/../Models/Planning.php';
require_once __DIR__ . '/../Models/Employe.php';
require_once __DIR__ . '/AuthController.php';

class PlanningController
{

    // ============================================
    // LISTE DES PLANNINGS
    // ============================================
    public function listAction()
    {
        $user = AuthController::checkAuth();
        // Admin et RH peuvent voir tous les plannings ; Chef service peut voir son service
        $id_service = null;
        if ($user['role'] == 'chef_service') {
            $employe = Employe::getById($user['id_employe']);
            $id_service = $employe['id_service'] ?? null;
        }

        $mois = isset($_GET['mois']) ? $_GET['mois'] : date('m');
        $annee = isset($_GET['annee']) ? $_GET['annee'] : date('Y');

        if ($user['role'] == 'admin' || $user['role'] == 'rh') {
            $plannings = Planning::getAll(); // on filtrera peut-être après
        } else {
            $plannings = Planning::getByService($id_service, $mois, $annee);
        }

        // Récupérer les services pour le filtre
        $services = Planning::getServices();

        include __DIR__ . '/../Views/planning/liste.php';
    }

    // ============================================
    // AJOUTER UN PLANNING
    // ============================================
    public function addAction()
    {
        $user = AuthController::checkAuth();
        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_service' => $_POST['id_service'],
                'date_planning' => $_POST['date_planning'],
                'type_garde' => $_POST['type_garde'],
                'heure_debut' => $_POST['heure_debut'],
                'heure_fin' => $_POST['heure_fin']
            ];
            if (Planning::create($data)) {
                header('Location: index.php?action=plannings&success=1');
                exit;
            } else {
                $error = "Erreur lors de l'ajout du planning.";
            }
        }

        $services = Planning::getServices();
        include __DIR__ . '/../Views/planning/ajouter.php';
    }

    // ============================================
    // MODIFIER UN PLANNING
    // ============================================
    public function editAction()
    {
        $user = AuthController::checkAuth();
        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=plannings');
            exit;
        }
        $planning = Planning::getById($id);
        if (!$planning) {
            header('Location: index.php?action=plannings');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_service' => $_POST['id_service'],
                'date_planning' => $_POST['date_planning'],
                'type_garde' => $_POST['type_garde'],
                'heure_debut' => $_POST['heure_debut'],
                'heure_fin' => $_POST['heure_fin']
            ];
            if (Planning::update($id, $data)) {
                header('Location: index.php?action=plannings&success=1');
                exit;
            } else {
                $error = "Erreur lors de la modification.";
            }
        }

        $services = Planning::getServices();
        include __DIR__ . '/../Views/planning/modifier.php';
    }

    // ============================================
    // SUPPRIMER UN PLANNING
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
            Planning::delete($id);
        }
        header('Location: index.php?action=plannings&success=1');
        exit;
    }

    // ============================================
    // AFFECTER UN EMPLOYÉ À UN PLANNING
    // ============================================
    public function affecterAction()
    {
        $user = AuthController::checkAuth();
        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_planning = $_GET['id'] ?? null;
        if (!$id_planning) {
            header('Location: index.php?action=plannings');
            exit;
        }

        // Récupérer tous les employés (pour la liste)
        $employes = Employe::getAllForSelect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_employe = $_POST['id_employe'];
            if ($id_employe) {
                $result = Planning::affecter($id_planning, $id_employe);
                if ($result) {
                    header('Location: index.php?action=planning_affecter&id=' . $id_planning . '&success=1');
                    exit;
                } else {
                    $error = "Erreur : cet employé est déjà affecté à ce planning.";
                }
            } else {
                $error = "Veuillez sélectionner un employé.";
            }
        }

        // Récupérer les affectations existantes
        $affectations = Planning::getAffectations($id_planning);
        $planning = Planning::getById($id_planning);
        include __DIR__ . '/../Views/planning/affecter.php';
    }

    // ============================================
    // DÉSAFFECTER UN EMPLOYÉ
    // ============================================
    public function desaffecterAction()
    {
        $user = AuthController::checkAuth();
        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_planning = $_GET['id_planning'] ?? null;
        $id_employe = $_GET['id_employe'] ?? null;
        if ($id_planning && $id_employe) {
            Planning::desaffecter($id_planning, $id_employe);
        }
        header('Location: index.php?action=planning_affecter&id=' . $id_planning . '&success=1');
        exit;
    }
}
