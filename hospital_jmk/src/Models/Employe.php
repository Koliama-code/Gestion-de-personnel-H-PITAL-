<?php
require_once __DIR__ . '/../../config/database.php';

class Employe
{

    public static function getAll()
    {
        global $pdo;
        $sql = "SELECT e.*, 
                       s.nom_service, 
                       c.nom_categorie, 
                       sp.nom_specialite 
                FROM employes e
                LEFT JOIN services s ON e.id_service = s.id_service
                LEFT JOIN categories_personnel c ON e.id_categorie = c.id_categorie
                LEFT JOIN specialites sp ON e.id_specialite = sp.id_specialite
                ORDER BY e.nom ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM employes WHERE id_employe = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        global $pdo;
        $sql = "INSERT INTO employes 
                (matricule, nom, prenom, date_naissance, sexe, email, telephone, adresse, 
                 numero_ordre, date_embauche, poste_occupe, 
                 id_service, id_categorie, id_specialite, statut_employe, salaire_base, photo)
                VALUES 
                (:matricule, :nom, :prenom, :date_naissance, :sexe, :email, :telephone, :adresse,
                 :numero_ordre, :date_embauche, :poste_occupe, 
                 :id_service, :id_categorie, :id_specialite, :statut_employe, :salaire_base, :photo)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public static function update($id, $data)
    {
        global $pdo;
        $sql = "UPDATE employes SET 
                    matricule = :matricule, nom = :nom, prenom = :prenom, 
                    date_naissance = :date_naissance, sexe = :sexe, email = :email, 
                    telephone = :telephone, adresse = :adresse, numero_ordre = :numero_ordre,
                    date_embauche = :date_embauche, poste_occupe = :poste_occupe,
                    id_service = :id_service, id_categorie = :id_categorie, 
                    id_specialite = :id_specialite, statut_employe = :statut_employe,
                    salaire_base = :salaire_base, photo = :photo
                WHERE id_employe = :id";
        $stmt = $pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM employes WHERE id_employe = ?");
        return $stmt->execute([$id]);
    }

    // --- Fonctions pour les listes déroulantes ---
    public static function getServices()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM services ORDER BY nom_service")->fetchAll();
    }

    public static function getCategories()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM categories_personnel ORDER BY nom_categorie")->fetchAll();
    }

    public static function getSpecialites()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM specialites ORDER BY nom_specialite")->fetchAll();
    }
}
