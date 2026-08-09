<?php

namespace App\Core;

use PDO;

/**
 * Modèle de base : donne accès à la connexion PDO partagée.
 * Chaque modèle métier (Utilisateur, Client, Exercice, ...) étend cette
 * classe et expose des méthodes explicites (findByLogin, create, ...)
 * au lieu de requêtes SQL éparpillées dans les vues/contrôleurs.
 */
abstract class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }
}
