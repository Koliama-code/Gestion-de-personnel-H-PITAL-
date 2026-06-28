<?php
require_once __DIR__ . '/../../config/database.php';

class Service
{

    // Récupérer tous les services
    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM services ORDER BY nom_service ASC");
        return $stmt->fetchAll();
    }

    // Récupérer un service par son ID
    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id_service = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Ajouter un service
    public static function create($nom)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO services (nom_service) VALUES (?)");
        return $stmt->execute([$nom]);
    }

    // Modifier un service
    public static function update($id, $nom)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE services SET nom_service = ? WHERE id_service = ?");
        return $stmt->execute([$nom, $id]);
    }

    // Supprimer un service
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM services WHERE id_service = ?");
        return $stmt->execute([$id]);
    }

    // Vérifier si un service est utilisé par des employés (pour éviter de supprimer un service attaché à un employé)
    public static function isUsed($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM employes WHERE id_service = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
