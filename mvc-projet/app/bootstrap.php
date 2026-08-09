<?php
/**
 * Bootstrap : autoload + démarrage de la session applicative.
 */

define('APP_ROOT', dirname(__DIR__));

// Autoload très simple basé sur les namespaces App\Xxx -> app/Xxx
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = APP_ROOT . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$GLOBALS['config'] = require APP_ROOT . '/config/config.php';

use App\Core\Database;
use App\Core\Auth;

// Connexion + session base de données démarrées une seule fois, pour toute
// requête (remplace la répétition PDO+SessionHandlerDB présente en tête de
// chaque ancien fichier app/<role>/home.php).
Database::pdo();
Auth::startSession();
