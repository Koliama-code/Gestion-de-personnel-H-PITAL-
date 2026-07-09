<?php
require_once __DIR__ . '/../../config/database.php';

class Specialite
{

    public static function getAll()
    {
        global $pdo;
        $sql = "SELECT s.*, c.nom_categorie 
                FROM specialites s
                LEFT JOIN categories_personnel c ON s.id_categorie = c.id_categorie
                ORDER BY s.nom_specialite ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM specialites WHERE id_specialite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($nom, $id_categorie)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO specialites (nom_specialite, id_categorie) VALUES (?, ?)");
        return $stmt->execute([$nom, $id_categorie]);
    }

    public static function update($id, $nom, $id_categorie)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE specialites SET nom_specialite = ?, id_categorie = ? WHERE id_specialite = ?");
        return $stmt->execute([$nom, $id_categorie, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM specialites WHERE id_specialite = ?");
        return $stmt->execute([$id]);
    }

    public static function isUsed($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM employes WHERE id_specialite = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    public static function getCategories()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM categories_personnel ORDER BY nom_categorie")->fetchAll();
    }
}
