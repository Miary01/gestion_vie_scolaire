<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Fournit une unique connexion PDO partagée par toute l'application.
 * Remplace le `new PDO(...)` recopié dans chaque ancien fichier.
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $conf = $GLOBALS['config']['db'];
            try {
                self::$pdo = new PDO(
                    "mysql:host={$conf['host']};dbname={$conf['name']};charset={$conf['charset']}",
                    $conf['user'],
                    $conf['password']
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                http_response_code(500);
                die('Connexion à la base de données impossible : ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
