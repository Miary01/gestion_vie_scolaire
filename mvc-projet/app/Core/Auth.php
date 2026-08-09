<?php

namespace App\Core;

/**
 * Aide à la gestion de la session utilisateur : démarrage, identité
 * courante, garde d'accès par rôle. Centralise ce qui était répété
 * (isset($_SESSION['id_utilisateur']) + header('Location: ...')) en tête
 * de chaque ancien fichier app/<role>/home.php.
 */
class Auth
{
    // Rôles tels que définis dans la table `role` de la base
    public const ROLE_ADMIN         = 1;
    public const ROLE_CLIENT        = 2;
    public const ROLE_PROFESSEUR    = 3;
    public const ROLE_ETABLISSEMENT = 4;
    public const ROLE_ORGANISATION  = 5;

    private static bool $started = false;

    public static function startSession(): void
    {
        if (self::$started) {
            return;
        }

        $handler = new SessionHandlerDB(Database::pdo());
        session_set_save_handler($handler, true);
        session_start();
        self::$started = true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['id_utilisateur']);
    }

    public static function id(): ?int
    {
        return isset($_SESSION['id_utilisateur']) ? (int) $_SESSION['id_utilisateur'] : null;
    }

    public static function login(string $loginName, int $idUtilisateur): void
    {
        $_SESSION['login'] = $loginName;
        $_SESSION['id_utilisateur'] = $idUtilisateur;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    /**
     * Redirige vers la page de connexion si l'utilisateur n'est pas
     * authentifié. À appeler en première ligne de chaque action protégée.
     */
    public static function requireLogin(string $redirectTo = '/'): void
    {
        if (!self::check()) {
            Response::redirect($redirectTo);
        }
    }
}
