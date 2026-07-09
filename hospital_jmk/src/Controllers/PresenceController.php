<?php
require_once __DIR__ . '/../Models/Presence.php';
require_once __DIR__ . '/../Models/Employe.php';
require_once __DIR__ . '/AuthController.php';

class PresenceController
{

    // ============================================
    // TABLEAU DE BORD PRÉSENCE
    // ============================================
    public function dashboardAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh', 'chef_service'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_service = null;
        if ($user['role'] == 'chef_service') {
            $employe = Employe::getById($user['id_employe']);
            $id_service = $employe['id_service'] ?? null;
        }

        $presences = Presence::getToday($id_service);
        $total = count($presences);
        $present = 0;
        $absent = 0;

        foreach ($presences as $p) {
            if ($p['statut'] == 'Présent') $present++;
            else $absent++;
        }

        include __DIR__ . '/../Views/presence/dashboard.php';
    }

    // ============================================
    // POINTER (Arrivée / Départ)
    // ============================================
    public function pointerAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $employes = Employe::getAllForSelect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_employe = $_POST['id_employe'];
            $type = $_POST['type']; // arrivee ou depart
            $commentaire = htmlspecialchars(trim($_POST['commentaire'] ?? ''));

            if ($id_employe && $type) {
                $result = Presence::pointer($id_employe, $type, $commentaire);
                if ($result) {
                    header('Location: index.php?action=presence_dashboard&success=1');
                    exit;
                } else {
                    $error = "Erreur lors du pointage. Vérifiez que l'employé n'a pas déjà pointé aujourd'hui.";
                }
            } else {
                $error = "Veuillez sélectionner un employé et un type de pointage.";
            }
        }

        include __DIR__ . '/../Views/presence/pointer.php';
    }

    // ============================================
    // HISTORIQUE DES PRÉSENCES
    // ============================================
    public function historiqueAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_employe = $_GET['id_employe'] ?? null;
        $mois = $_GET['mois'] ?? date('m');
        $annee = $_GET['annee'] ?? date('Y');

        $historique = [];
        $stats = [];
        $employes = Employe::getAllForSelect();

        if ($id_employe) {
            $historique = Presence::getHistorique($id_employe, $mois, $annee);
            $stats = Presence::getStats($id_employe, $mois, $annee);
        }

        include __DIR__ . '/../Views/presence/historique.php';
    }

    // ============================================
    // MODIFIER UNE PRÉSENCE (RH/Admin)
    // ============================================
    public function modifierAction()
    {
        $user = AuthController::checkAuth();

        if (!in_array($user['role'], ['admin', 'rh'])) {
            header('Location: index.php?action=dashboard&error=acces_refuse');
            exit;
        }

        $id_presence = $_GET['id'] ?? null;
        if (!$id_presence) {
            header('Location: index.php?action=presence_dashboard');
            exit;
        }

        // Récupérer la présence
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, e.nom, e.prenom FROM presence p 
                               JOIN employes e ON p.id_employe = e.id_employe 
                               WHERE p.id_presence = ?");
        $stmt->execute([$id_presence]);
        $presence = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['statut'];
            $commentaire = htmlspecialchars(trim($_POST['commentaire'] ?? ''));
            $heure_arrivee = $_POST['heure_arrivee'] ?? null;
            $heure_depart = $_POST['heure_depart'] ?? null;

            // Mise à jour complète
            $update = $pdo->prepare("UPDATE presence SET 
                                     heure_arrivee = ?, 
                                     heure_depart = ?, 
                                     statut = ?, 
                                     commentaire = CONCAT(commentaire, ' ', ?)
                                     WHERE id_presence = ?");
            $update->execute([$heure_arrivee, $heure_depart, $statut, $commentaire, $id_presence]);

            header('Location: index.php?action=presence_dashboard&success=1');
            exit;
        }

        include __DIR__ . '/../Views/presence/modifier.php';
    }
}
