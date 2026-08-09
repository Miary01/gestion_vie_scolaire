<?php

namespace App\Core;

/**
 * Contrôleur de base : fournit le rendu de vues (le "V" du MVC).
 * Chaque contrôleur métier étend cette classe.
 */
abstract class Controller
{
    /**
     * Rend une vue PHP en lui passant des données sous forme de variables
     * locales (extract), sans mélanger requêtes SQL et HTML comme dans
     * l'ancien code.
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = APP_ROOT . '/app/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            die("Vue introuvable : {$view}");
        }

        require $viewFile;
    }

    protected function json(array $data, int $status = 200): never
    {
        Response::json($data, $status);
    }

    protected function redirect(string $path): never
    {
        Response::redirect($path);
    }
}
