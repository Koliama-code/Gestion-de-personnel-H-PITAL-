<?php
require_once __DIR__ . '/../Models/Service.php';
require_once __DIR__ . '/AuthController.php';

class ServiceController
{

    // Liste des services
    public function listAction()
    {
        // Seul l'admin peut gérer les services
        $user = AuthController::checkRole('admin');
        $services = Service::getAll();
        include __DIR__ . '/../Views/services/liste.php';
    }

    // Ajouter un service
    public function addAction()
    {
        $user = AuthController::checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_service']));
            if (!empty($nom)) {
                if (Service::create($nom)) {
                    header('Location: index.php?action=services&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de l'ajout du service.";
                }
            } else {
                $error = "Le nom du service est obligatoire.";
            }
        }

        include __DIR__ . '/../Views/services/ajouter.php';
    }

    // Modifier un service
    public function editAction()
    {
        $user = AuthController::checkRole('admin');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?action=services');
            exit;
        }

        $service = Service::getById($id);
        if (!$service) {
            header('Location: index.php?action=services');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars(trim($_POST['nom_service']));
            if (!empty($nom)) {
                if (Service::update($id, $nom)) {
                    header('Location: index.php?action=services&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } else {
                $error = "Le nom du service est obligatoire.";
            }
        }

        include __DIR__ . '/../Views/services/modifier.php';
    }

    // Supprimer un service
    public function deleteAction()
    {
        $user = AuthController::checkRole('admin');
        $id = $_GET['id'] ?? null;

        if ($id) {
            // Vérifier si le service est utilisé
            if (Service::isUsed($id)) {
                // Rediriger avec un message d'erreur
                header('Location: index.php?action=services&error=used');
                exit;
            }
            Service::delete($id);
        }
        header('Location: index.php?action=services&success=1');
        exit;
    }
}
