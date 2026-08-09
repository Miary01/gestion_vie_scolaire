<?php

namespace App\Core;

/**
 * Routeur minimaliste : associe une méthode HTTP + un chemin à
 * [Contrôleur, action]. Remplace l'ancien "routage par fichier"
 * (app/client/home.php, app/client/logout.php, ...).
 */
class Router
{
    /** @var array<string, array<string, array{0: class-string, 1: string}>> */
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->routes['GET'][$path] = [$controller, $action];
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->routes['POST'][$path] = [$controller, $action];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            http_response_code(404);
            $notFound = APP_ROOT . '/app/Views/errors/404.php';
            if (is_file($notFound)) {
                require $notFound;
            } else {
                echo '404 - Page introuvable';
            }
            return;
        }

        [$controllerClass, $action] = $route;
        $controller = new $controllerClass();
        $controller->$action();
    }
}
