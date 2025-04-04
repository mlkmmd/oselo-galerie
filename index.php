<?php
// Point d'entrée de l'application
session_start();

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'database.php';
require_once 'models.php';
require_once 'controllers.php';

// Router simple
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Sécurité basique
$page = preg_replace('/[^a-zA-Z]/', '', $page);
$action = preg_replace('/[^a-zA-Z]/', '', $action);

// Rediriger vers la bonne page
switch ($page) {
    case 'dashboard':
        $controller = new DashboardController();
        $controller->$action();
        break;
    case 'artwork':
        $controller = new ArtworkController();
        $controller->$action();
        break;
    case 'warehouse':
        $controller = new WarehouseController();
        $controller->$action();
        break;
    default:
        // Page 404
        include 'views/404.php';
        break;
}