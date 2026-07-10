<?php
require_once __DIR__ . '/../../config/database.php';

class Planning
{

    // Récupérer tous les plannings avec le nom du service
    public static function getAll()
    {
        global $pdo;
        $sql = "SELECT p.*, s.nom_service 
                FROM plannings p
                JOIN services s ON p.id_service = s.id_service
                ORDER BY p.date_planning DESC, p.id_service";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Récupérer un planning par ID
    public static function getById($id)
    {
        global $pdo;
        $sql = "SELECT p.*, s.nom_service 
                FROM plannings p
                JOIN services s ON p.id_service = s.id_service
                WHERE p.id_planning = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Récupérer les plannings d'un service
    public static function getByService($id_service, $mois = null, $annee = null)
    {
        global $pdo;
        $sql = "SELECT p.*, s.nom_service 
                FROM plannings p
                JOIN services s ON p.id_service = s.id_service
                WHERE p.id_service = ?";
        $params = [$id_service];
        if ($mois && $annee) {
            $sql .= " AND MONTH(p.date_planning) = ? AND YEAR(p.date_planning) = ?";
            $params[] = $mois;
            $params[] = $annee;
        }
        $sql .= " ORDER BY p.date_planning ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Créer un planning
    public static function create($data)
    {
        global $pdo;
        $sql = "INSERT INTO plannings (id_service, date_planning, type_garde, heure_debut, heure_fin) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['id_service'],
            $data['date_planning'],
            $data['type_garde'],
            $data['heure_debut'] ?: null,
            $data['heure_fin'] ?: null
        ]);
    }

    // Mettre à jour un planning
    public static function update($id, $data)
    {
        global $pdo;
        $sql = "UPDATE plannings SET 
                    id_service = ?, 
                    date_planning = ?, 
                    type_garde = ?, 
                    heure_debut = ?, 
                    heure_fin = ? 
                WHERE id_planning = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['id_service'],
            $data['date_planning'],
            $data['type_garde'],
            $data['heure_debut'] ?: null,
            $data['heure_fin'] ?: null,
            $id
        ]);
    }

    // Supprimer un planning (cascade supprime les affectations)
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM plannings WHERE id_planning = ?");
        return $stmt->execute([$id]);
    }

    // Récupérer les services (pour liste déroulante)
    public static function getServices()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM services ORDER BY nom_service")->fetchAll();
    }

    // Récupérer les affectations d'un planning (employés affectés)
    public static function getAffectations($id_planning)
    {
        global $pdo;
        $sql = "SELECT a.*, e.nom, e.prenom, e.matricule 
                FROM affectations_planning a
                JOIN employes e ON a.id_employe = e.id_employe
                WHERE a.id_planning = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_planning]);
        return $stmt->fetchAll();
    }

    // Vérifier si un employé est déjà affecté à un planning d'un certain type pour une date donnée
    public static function verifierAffectationUnique($id_employe, $date_planning, $type_garde)
    {
        global $pdo;
        $sql = "SELECT COUNT(*) as total 
            FROM affectations_planning a
            JOIN plannings p ON a.id_planning = p.id_planning
            WHERE a.id_employe = ? 
              AND p.date_planning = ?
              AND p.type_garde = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_employe, $date_planning, $type_garde]);
        $result = $stmt->fetch();
        return $result['total'] > 0; // true si déjà affecté
    }

    // Affecter un employé à un planning
    public static function affecter($id_planning, $id_employe)
    {
        global $pdo;
        // Récupérer les infos du planning pour la vérification
        $planning = self::getById($id_planning);
        if (!$planning) return false;

        $date_planning = $planning['date_planning'];
        $type_garde = $planning['type_garde'];

        // Vérifier si l'employé n'est pas déjà affecté ce jour pour ce type
        if (self::verifierAffectationUnique($id_employe, $date_planning, $type_garde)) {
            return false; // déjà affecté
        }

        $sql = "INSERT INTO affectations_planning (id_planning, id_employe) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            return $stmt->execute([$id_planning, $id_employe]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Désaffecter un employé d'un planning
    public static function desaffecter($id_planning, $id_employe)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM affectations_planning WHERE id_planning = ? AND id_employe = ?");
        return $stmt->execute([$id_planning, $id_employe]);
    }
}
