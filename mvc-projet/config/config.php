<?php
/**
 * Configuration centrale de l'application.
 *
 * Toutes les valeurs sensibles peuvent être surchargées via un fichier
 * .env (à la racine du projet, non versionné) ou des variables
 * d'environnement système. Des valeurs par défaut (identiques à celles
 * du projet d'origine) sont utilisées si rien n'est défini.
 */

// Charge un éventuel fichier .env situé à la racine du projet
$envPath = dirname(__DIR__) . '/.env';
$env = is_file($envPath) ? parse_ini_file($envPath) : [];

function config_get(array $env, string $key, string $default = ''): string
{
    return $env[$key] ?? getenv($key) ?: $default;
}

return [
    'db' => [
        'host'     => config_get($env, 'DB_HOST', 'localhost'),
        'name'     => config_get($env, 'DB_NAME', 'projet'),
        'user'     => config_get($env, 'DB_USER', 'admin'),
        'password' => config_get($env, 'DB_PASSWORD', 'admin123'),
        'charset'  => 'utf8',
    ],
    'admin_key' => config_get($env, 'ADMIN_KEY', ''),
    'paths' => [
        'base'    => dirname(__DIR__),
        'uploads' => dirname(__DIR__) . '/storage/uploads/exercices',
    ],
];
