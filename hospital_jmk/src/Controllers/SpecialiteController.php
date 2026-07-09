<?php
require_once __DIR__ . '/../Models/Specialite.php';
require_once __DIR__ . '/AuthController.php';

class SpecialiteController
{

    public function listAction()
    {
        $user = AuthController::checkRole('admin');
        $specialites = Specialite::getAll();
        include __DIR__ . '/../Views/specialites/liste.php';
    }

    public function addAction()
    {
        $user = AuthController::checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_specialite']));
            $id_categorie = $_POST['id_categorie'] ?: null;

            if (!empty($nom)) {
                if (Specialite::create($nom, $id_categorie)) {
                    header('Location: index.php?action=specialites&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de l'ajout de la spécialité.";
                }
            } else {
                $error = "Le nom de la spécialité est obligatoire.";
            }
        }

        $categories = Specialite::getCategories();
        include __DIR__ . '/../Views/specialites/ajouter.php';
    }

    public function editAction()
    {
        $user = AuthController::checkRole('admin');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?action=specialites');
            exit;
        }

        $specialite = Specialite::getById($id);
        if (!$specialite) {
            header('Location: index.php?action=specialites');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_specialite']));
            $id_categorie = $_POST['id_categorie'] ?: null;

            if (!empty($nom)) {
                if (Specialite::update($id, $nom, $id_categorie)) {
                    header('Location: index.php?action=specialites&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } else {
                $error = "Le nom de la spécialité est obligatoire.";
            }
        }

        $categories = Specialite::getCategories();
        include __DIR__ . '/../Views/specialites/modifier.php';
    }

    public function deleteAction()
    {
        $user = AuthController::checkRole('admin');
        $id = $_GET['id'] ?? null;

        if ($id) {
            if (Specialite::isUsed($id)) {
                header('Location: index.php?action=specialites&error=used');
                exit;
            }
            Specialite::delete($id);
        }
        header('Location: index.php?action=specialites&success=1');
        exit;
    }
}
