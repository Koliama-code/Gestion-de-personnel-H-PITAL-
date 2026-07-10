<?php
require_once __DIR__ . '/../Models/Employe.php';
require_once __DIR__ . '/../Models/Presence.php';
require_once __DIR__ . '/../Models/Conge.php';
require_once __DIR__ . '/AuthController.php';

class DashboardController
{

    // ============================================
    // PAGE DU DASHBOARD
    // ============================================
    public function indexAction()
    {
        $user = AuthController::checkAuth();
        include __DIR__ . '/../Views/dashboard/index.php';
    }

    // ============================================
    // RÉCUPÉRER LES DONNÉES POUR LES GRAPHIQUES (AJAX)
    // ============================================
    public function dataAction()
    {
        $user = AuthController::checkAuth();
        header('Content-Type: application/json');

        // Récupérer les paramètres de filtrage (mois/année)
        $mois = isset($_GET['mois']) ? $_GET['mois'] : date('m');
        $annee = isset($_GET['annee']) ? $_GET['annee'] : date('Y');

        // --- 1. Statistiques générales ---
        global $pdo;

        // Total employés
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM employes");
        $totalEmployes = $stmt->fetch()['total'];

        // Présents aujourd'hui
        $date = date('Y-m-d');
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM presence WHERE date_presence = ? AND statut = 'Présent'");
        $stmt->execute([$date]);
        $presentAujourdhui = $stmt->fetch()['total'];

        // Congés en attente
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM conges WHERE statut = 'En attente'");
        $congesEnAttente = $stmt->fetch()['total'];

        // Total services
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM services");
        $totalServices = $stmt->fetch()['total'];

        // --- 2. Répartition par service ---
        $stmt = $pdo->query("SELECT s.nom_service, COUNT(e.id_employe) as total 
                             FROM services s
                             LEFT JOIN employes e ON s.id_service = e.id_service
                             GROUP BY s.id_service
                             ORDER BY total DESC");
        $repartitionService = $stmt->fetchAll();

        // --- 3. Statut des employés ---
        $stmt = $pdo->query("SELECT statut_employe, COUNT(*) as total 
                             FROM employes 
                             GROUP BY statut_employe");
        $statutEmployes = $stmt->fetchAll();

        // --- 4. Présence du jour (statuts) ---
        $stmt = $pdo->prepare("SELECT statut, COUNT(*) as total 
                               FROM presence 
                               WHERE date_presence = ? 
                               GROUP BY statut");
        $stmt->execute([$date]);
        $presenceJour = $stmt->fetchAll();

        // --- 5. Évolution des congés validés par mois (12 derniers mois) ---
        $evolutionConges = [];
        for ($i = 0; $i < 12; $i++) {
            $m = date('m', strtotime("-$i months"));
            $a = date('Y', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM conges 
                                   WHERE statut = 'Validé' 
                                   AND MONTH(date_demande) = ? 
                                   AND YEAR(date_demande) = ?");
            $stmt->execute([$m, $a]);
            $evolutionConges[] = [
                'mois' => $label,
                'total' => (int)$stmt->fetch()['total']
            ];
        }
        $evolutionConges = array_reverse($evolutionConges);

        // --- 6. Données pour la présence mensuelle (en cours) ---
        // Compter les jours de présence par statut pour le mois sélectionné
        $statsMois = Presence::getStatsForAll($mois, $annee);
        // On aggrège les statuts pour le graphique
        $statutsMois = ['Présent' => 0, 'Absent' => 0, 'Retard' => 0, 'Congé' => 0, 'Maladie' => 0, 'Formation' => 0];
        foreach ($statsMois as $id => $data) {
            foreach ($data['statuts'] as $statut => $total) {
                if (isset($statutsMois[$statut])) {
                    $statutsMois[$statut] += $total;
                }
            }
        }

        // --- Envoyer la réponse JSON ---
        echo json_encode([
            'totalEmployes' => $totalEmployes,
            'presentAujourdhui' => $presentAujourdhui,
            'congesEnAttente' => $congesEnAttente,
            'totalServices' => $totalServices,
            'repartitionService' => $repartitionService,
            'statutEmployes' => $statutEmployes,
            'presenceJour' => $presenceJour,
            'evolutionConges' => $evolutionConges,
            'statutsMois' => $statutsMois,
            'moisActuel' => date('F', mktime(0, 0, 0, $mois, 1))
        ]);
    }
}
