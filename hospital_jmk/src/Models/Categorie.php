<?php
require_once __DIR__ . '/../../config/database.php';

class Categorie
{

    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM categories_personnel ORDER BY nom_categorie ASC");
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM categories_personnel WHERE id_categorie = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($nom, $description = null)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO categories_personnel (nom_categorie, description) VALUES (?, ?)");
        return $stmt->execute([$nom, $description]);
    }

    public static function update($id, $nom, $description = null)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE categories_personnel SET nom_categorie = ?, description = ? WHERE id_categorie = ?");
        return $stmt->execute([$nom, $description, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM categories_personnel WHERE id_categorie = ?");
        return $stmt->execute([$id]);
    }

    public static function isUsed($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM employes WHERE id_categorie = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
