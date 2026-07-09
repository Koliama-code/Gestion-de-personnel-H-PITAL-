<?php
require_once __DIR__ . '/../../config/database.php';

class Presence
{

    // ============================================
    // POINTER (Arrivée/Départ)
    // ============================================
    public static function pointer($id_employe, $type, $commentaire = '')
    {
        global $pdo;
        $date = date('Y-m-d');
        $heure = date('H:i:s');

        // Vérifier si l'employé a déjà pointé aujourd'hui
        $check = $pdo->prepare("SELECT * FROM presence WHERE id_employe = ? AND date_presence = ?");
        $check->execute([$id_employe, $date]);
        $existant = $check->fetch();

        if ($existant) {
            // Mettre à jour le départ
            if ($type == 'depart') {
                $stmt = $pdo->prepare("UPDATE presence SET heure_depart = ?, commentaire = CONCAT(commentaire, ' ', ?) 
                                       WHERE id_presence = ?");
                return $stmt->execute([$heure, $commentaire, $existant['id_presence']]);
            }
            return false; // Déjà pointé
        } else {
            // Nouveau pointage (arrivée)
            $stmt = $pdo->prepare("INSERT INTO presence (id_employe, date_presence, heure_arrivee, statut, commentaire) 
                                   VALUES (?, ?, ?, 'Présent', ?)");
            return $stmt->execute([$id_employe, $date, $heure, $commentaire]);
        }
    }

    // ============================================
    // RÉCUPÉRER LA PRÉSENCE DU JOUR
    // ============================================
    public static function getToday($id_service = null)
    {
        global $pdo;
        $date = date('Y-m-d');
        $sql = "SELECT p.*, e.nom, e.prenom, e.matricule, s.nom_service 
                FROM presence p
                JOIN employes e ON p.id_employe = e.id_employe
                LEFT JOIN services s ON e.id_service = s.id_service
                WHERE p.date_presence = ?";
        $params = [$date];

        if ($id_service) {
            $sql .= " AND e.id_service = ?";
            $params[] = $id_service;
        }

        $sql .= " ORDER BY e.nom ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ============================================
    // RÉCUPÉRER L'HISTORIQUE D'UN EMPLOYÉ
    // ============================================
    public static function getHistorique($id_employe, $mois = null, $annee = null)
    {
        global $pdo;
        if (!$mois) $mois = date('m');
        if (!$annee) $annee = date('Y');

        $sql = "SELECT * FROM presence 
                WHERE id_employe = ? 
                AND YEAR(date_presence) = ? 
                AND MONTH(date_presence) = ?
                ORDER BY date_presence DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_employe, $annee, $mois]);
        return $stmt->fetchAll();
    }

    // ============================================
    // MODIFIER MANUELLEMENT UNE PRÉSENCE (RH/Admin)
    // ============================================
    public static function updateStatut($id_presence, $statut, $commentaire = '')
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE presence SET statut = ?, commentaire = CONCAT(commentaire, ' ', ?) 
                               WHERE id_presence = ?");
        return $stmt->execute([$statut, $commentaire, $id_presence]);
    }

    // ============================================
    // MARQUER UN EMPLOYÉ ABSENT AUTOMATIQUEMENT (si congé validé)
    // ============================================
    public static function marquerAbsent($id_employe, $date_debut, $date_fin, $motif)
    {
        global $pdo;
        $date = $date_debut;
        while ($date <= $date_fin) {
            $stmt = $pdo->prepare("INSERT INTO presence (id_employe, date_presence, statut, commentaire) 
                                   VALUES (?, ?, 'Congé', ?)
                                   ON DUPLICATE KEY UPDATE statut = 'Congé', commentaire = ?");
            $stmt->execute([$id_employe, $date, $motif, $motif]);
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        return true;
    }

    // ============================================
    // STATISTIQUES PRÉSENCE
    // ============================================
    public static function getStats($id_employe, $mois, $annee)
    {
        global $pdo;
        $sql = "SELECT statut, COUNT(*) as total 
                FROM presence 
                WHERE id_employe = ? 
                AND YEAR(date_presence) = ? 
                AND MONTH(date_presence) = ?
                GROUP BY statut";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_employe, $annee, $mois]);
        return $stmt->fetchAll();
    }
}
