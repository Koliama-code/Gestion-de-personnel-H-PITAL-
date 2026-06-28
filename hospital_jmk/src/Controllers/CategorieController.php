<?php
require_once __DIR__ . '/../Models/Categorie.php';
require_once __DIR__ . '/AuthController.php';

class CategorieController
{

    public function listAction()
    {
        $user = AuthController::checkRole('admin');
        $categories = Categorie::getAll();
        include __DIR__ . '/../Views/categories/liste.php';
    }

    public function addAction()
    {
        $user = AuthController::checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_categorie']));
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));

            if (!empty($nom)) {
                if (Categorie::create($nom, $description)) {
                    header('Location: index.php?action=categories&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de l'ajout de la catégorie.";
                }
            } else {
                $error = "Le nom de la catégorie est obligatoire.";
            }
        }

        include __DIR__ . '/../Views/categories/ajouter.php';
    }

    public function editAction()
    {
        $user = AuthController::checkRole('admin');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?action=categories');
            exit;
        }

        $categorie = Categorie::getById($id);
        if (!$categorie) {
            header('Location: index.php?action=categories');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_categorie']));
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));

            if (!empty($nom)) {
                if (Categorie::update($id, $nom, $description)) {
                    header('Location: index.php?action=categories&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } else {
                $error = "Le nom de la catégorie est obligatoire.";
            }
        }

        include __DIR__ . '/../Views/categories/modifier.php';
    }

    public function deleteAction()
    {
        $user = AuthController::checkRole('admin');
        $id = $_GET['id'] ?? null;

        if ($id) {
            if (Categorie::isUsed($id)) {
                header('Location: index.php?action=categories&error=used');
                exit;
            }
            Categorie::delete($id);
        }
        header('Location: index.php?action=categories&success=1');
        exit;
    }
}
