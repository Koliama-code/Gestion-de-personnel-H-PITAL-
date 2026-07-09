<?php
require_once __DIR__ . '/../../config/database.php';

class Employe
{

    /**
     * Récupère tous les employés avec leurs relations (service, catégorie, spécialité)
     */
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

    /**
     * Récupère un employé par son ID
     */
    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM employes WHERE id_employe = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }




    /**
     * Crée un nouvel employé
     */
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

    // Ajoute cette méthode dans Employe.php

    public static function getAllForSelect()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT id_employe, nom, prenom, matricule FROM employes ORDER BY nom");
        return $stmt->fetchAll();
    }



    /**
     * Met à jour un employé
     */
    public static function update($id, $data)
    {
        global $pdo;
        $sql = "UPDATE employes SET 
                    matricule = :matricule, 
                    nom = :nom, 
                    prenom = :prenom, 
                    date_naissance = :date_naissance, 
                    sexe = :sexe, 
                    email = :email, 
                    telephone = :telephone, 
                    adresse = :adresse, 
                    numero_ordre = :numero_ordre,
                    date_embauche = :date_embauche, 
                    poste_occupe = :poste_occupe,
                    id_service = :id_service, 
                    id_categorie = :id_categorie, 
                    id_specialite = :id_specialite, 
                    statut_employe = :statut_employe,
                    salaire_base = :salaire_base, 
                    photo = :photo
                WHERE id_employe = :id";
        $stmt = $pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Supprime un employé (et optionnellement sa photo)
     */
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM employes WHERE id_employe = ?");
        return $stmt->execute([$id]);
    }

    // ========================================================
    // MÉTHODES POUR LES LISTES DÉROULANTES (Référentiels)
    // ========================================================

    /**
     * Récupère tous les services
     */
    public static function getServices()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM services ORDER BY nom_service")->fetchAll();
    }

    /**
     * Récupère toutes les catégories
     */
    public static function getCategories()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM categories_personnel ORDER BY nom_categorie")->fetchAll();
    }

    /**
     * Récupère toutes les spécialités
     */
    public static function getSpecialites()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM specialites ORDER BY nom_specialite")->fetchAll();
    }

    /**
     * Récupère les spécialités d'une catégorie donnée (utile pour le filtrage)
     */
    public static function getSpecialitesByCategorie($categorieId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM specialites WHERE id_categorie = ? ORDER BY nom_specialite");
        $stmt->execute([$categorieId]);
        return $stmt->fetchAll();
    }
}
