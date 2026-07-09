<?php
session_start();

require_once 'src/Controllers/AuthController.php';
require_once 'src/Controllers/EmployeController.php';
require_once 'src/Controllers/ServiceController.php';
require_once 'src/Controllers/CategorieController.php';
require_once 'src/Controllers/SpecialiteController.php';
require_once 'src/Controllers/CongeController.php';
require_once 'src/Controllers/PresenceController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    // Authentification
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'dashboard':
        include 'src/Views/dashboard.php';
        break;

    // Gestion des employés
    case 'employes':
        (new EmployeController())->listAction();
        break;
    case 'employe_add':
        (new EmployeController())->addAction();
        break;
    case 'employe_edit':
        (new EmployeController())->editAction();
        break;
    case 'employe_delete':
        (new EmployeController())->deleteAction();
        break;

    // Gestion des services (NOUVEAU)
    case 'services':
        (new ServiceController())->listAction();
        break;
    case 'service_add':
        (new ServiceController())->addAction();
        break;
    case 'service_edit':
        (new ServiceController())->editAction();
        break;
    case 'service_delete':
        (new ServiceController())->deleteAction();
        break;

    // Gestion des catégories 
    case 'categories':
        (new CategorieController())->listAction();
        break;
    case 'categorie_add':
        (new CategorieController())->addAction();
        break;
    case 'categorie_edit':
        (new CategorieController())->editAction();
        break;
    case 'categorie_delete':
        (new CategorieController())->deleteAction();
        break;

    // Gestion des spécialités
    case 'specialites':
        (new SpecialiteController())->listAction();
        break;
    case 'specialite_add':
        (new SpecialiteController())->addAction();
        break;
    case 'specialite_edit':
        (new SpecialiteController())->editAction();
        break;
    case 'specialite_delete':
        (new SpecialiteController())->deleteAction();
        break;


    // Gestion des congés
    case 'conges':
        (new CongeController())->listAction();
        break;
    case 'demander_conge':
        (new CongeController())->demanderAction();
        break;
    case 'mes_conges':
        (new CongeController())->mesCongesAction();
        break;
    case 'valider_conge':
        (new CongeController())->validerAction();
        break;
    case 'supprimer_conge':
        (new CongeController())->deleteAction();
        break;

    case 'historique_conges':
        (new CongeController())->historiqueAction();
        break;
    case 'retour_conge':
        (new CongeController())->retourAction();
        break;

    // Gestion des présences

    case 'presence_dashboard':
        (new PresenceController())->dashboardAction();
        break;
    case 'presence_pointer':
        (new PresenceController())->pointerAction();
        break;
    case 'presence_historique':
        (new PresenceController())->historiqueAction();
        break;
    case 'presence_modifier':
        (new PresenceController())->modifierAction();
        break;

    default:
        header('Location: index.php?action=login');
        exit;
}
