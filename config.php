<?php
// Configuration de l'application

// Informations de base
define('APP_NAME', 'Galerie Oselo');
define('APP_VERSION', '1.0.0');

// Chemins
define('BASE_URL', 'http://localhost/galerie-oselo');
define('ROOT_PATH', dirname(__DIR__));

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

