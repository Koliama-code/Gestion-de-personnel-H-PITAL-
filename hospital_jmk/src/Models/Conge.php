<?php
require_once __DIR__ . '/../../config/database.php';

class Conge
{

    // Récupérer tous les congés avec infos employé
    public static function getAll()
    {
        global $pdo;
        $sql = "SELECT c.*, e.nom, e.prenom, e.id_service 
                FROM conges c
                JOIN employes e ON c.id_employe = e.id_employe
                ORDER BY c.date_demande DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Récupérer les congés d'un employé
    public static function getByEmploye($id_employe)
    {
        global $pdo;
        $sql = "SELECT c.*, e.nom, e.prenom 
                FROM conges c
                JOIN employes e ON c.id_employe = e.id_employe
                WHERE c.id_employe = ?
                ORDER BY c.date_demande DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_employe]);
        return $stmt->fetchAll();
    }

    // Récupérer les congés d'un service
    public static function getByService($id_service)
    {
        global $pdo;
        $sql = "SELECT c.*, e.nom, e.prenom 
                FROM conges c
                JOIN employes e ON c.id_employe = e.id_employe
                WHERE e.id_service = ?
                ORDER BY c.date_demande DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_service]);
        return $stmt->fetchAll();
    }

    // Récupérer un congé par son ID
    public static function getById($id)
    {
        global $pdo;
        $sql = "SELECT c.*, e.nom, e.prenom, e.id_service 
                FROM conges c
                JOIN employes e ON c.id_employe = e.id_employe
                WHERE c.id_conge = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Demander un congé
    public static function demander($id_employe, $date_debut, $date_fin, $motif)
    {
        global $pdo;

        // Vérifier si l'employé n'a pas déjà un congé en attente ou validé qui se chevauche
        $check = $pdo->prepare("SELECT COUNT(*) as total FROM conges 
                                WHERE id_employe = ? 
                                AND statut != 'Refusé'
                                AND (
                                    (date_debut <= ? AND date_fin >= ?) OR
                                    (date_debut <= ? AND date_fin >= ?) OR
                                    (date_debut >= ? AND date_fin <= ?)
                                )");
        $check->execute([$id_employe, $date_debut, $date_debut, $date_fin, $date_fin, $date_debut, $date_fin]);
        $result = $check->fetch();

        if ($result['total'] > 0) {
            return false; // Chevauchement de congés
        }

        // Insérer la demande
        $sql = "INSERT INTO conges (id_employe, date_debut, date_fin, motif, statut) 
                VALUES (?, ?, ?, ?, 'En attente')";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$id_employe, $date_debut, $date_fin, $motif]);

        if ($result) {
            $id_conge = $pdo->lastInsertId();
            // Ajouter à l'historique
            self::ajouterHistorique($id_conge, $id_employe, 'demande', 'Demande de congé soumise');
        }

        return $result;
    }

    // Mettre à jour le statut d'un congé (avec gestion du statut employé)
    public static function updateStatut($id, $statut)
    {
        global $pdo;

        // Récupérer le congé et l'employé associé
        $conge = self::getById($id);
        if (!$conge) {
            return false;
        }

        $oldStatut = $conge['statut'];
        $id_employe = $conge['id_employe'];

        // Mettre à jour le statut du congé
        $sql = "UPDATE conges SET statut = ? WHERE id_conge = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$statut, $id]);

        if (!$result) {
            return false;
        }

        // Mettre à jour le statut de l'employé et gérer l'historique
        if ($statut == 'Validé' && $oldStatut != 'Validé') {
            // L'employé est en congé
            $updateEmp = $pdo->prepare("UPDATE employes SET en_conge = 1 WHERE id_employe = ?");
            $updateEmp->execute([$id_employe]);
            self::ajouterHistorique($id, $id_employe, 'validation', 'Congé validé - Employé en congé');

            // Ajouter un retour automatique après la date de fin
            // (Optionnel : on pourrait planifier une tâche, mais pour l'instant on le fera manuellement)

        } elseif ($statut == 'Refusé' && $oldStatut != 'Refusé') {
            self::ajouterHistorique($id, $id_employe, 'refus', 'Demande de congé refusée');
        }

        return true;
    }

    // Retour de congé (remettre l'employé en statut actif)
    public static function retourCongé($id_employe)
    {
        global $pdo;

        // Vérifier que l'employé a un congé validé en cours
        $check = $pdo->prepare("SELECT id_conge FROM conges 
                                WHERE id_employe = ? 
                                AND statut = 'Validé'
                                AND date_fin < NOW()
                                AND date_fin >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                ORDER BY date_fin DESC LIMIT 1");
        $check->execute([$id_employe]);
        $conge = $check->fetch();

        if ($conge) {
            // Remettre l'employé en actif
            $updateEmp = $pdo->prepare("UPDATE employes SET en_conge = 0 WHERE id_employe = ?");
            $updateEmp->execute([$id_employe]);

            self::ajouterHistorique($conge['id_conge'], $id_employe, 'retour_conge', 'Retour de congé - Employé actif');
            return true;
        }

        return false;
    }

    // Ajouter une entrée dans l'historique
    public static function ajouterHistorique($id_conge, $id_employe, $action, $commentaire = '')
    {
        global $pdo;
        $sql = "INSERT INTO historique_conges (id_conge, id_employe, action, commentaire) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id_conge, $id_employe, $action, $commentaire]);
    }

    // Récupérer l'historique des congés
    public static function getHistorique($id_employe = null)
    {
        global $pdo;
        if ($id_employe) {
            $sql = "SELECT h.*, e.nom, e.prenom 
                    FROM historique_conges h
                    JOIN employes e ON h.id_employe = e.id_employe
                    WHERE h.id_employe = ?
                    ORDER BY h.date_action DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_employe]);
        } else {
            $sql = "SELECT h.*, e.nom, e.prenom 
                    FROM historique_conges h
                    JOIN employes e ON h.id_employe = e.id_employe
                    ORDER BY h.date_action DESC";
            $stmt = $pdo->query($sql);
        }
        return $stmt->fetchAll();
    }

    // Supprimer un congé
    public static function delete($id)
    {
        global $pdo;
        $conge = self::getById($id);
        if ($conge && $conge['statut'] == 'Validé') {
            // Si le congé est validé, remettre l'employé en actif
            $updateEmp = $pdo->prepare("UPDATE employes SET en_conge = 0 WHERE id_employe = ?");
            $updateEmp->execute([$conge['id_employe']]);
        }
        $stmt = $pdo->prepare("DELETE FROM conges WHERE id_conge = ?");
        return $stmt->execute([$id]);
    }
}
