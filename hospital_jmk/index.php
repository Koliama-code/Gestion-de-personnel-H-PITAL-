<?php
session_start();

require_once 'src/Controllers/AuthController.php';
require_once 'src/Controllers/EmployeController.php';
require_once 'src/Controllers/ServiceController.php';
require_once 'src/Controllers/CategorieController.php';

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

    default:
        header('Location: index.php?action=login');
        exit;
}
