<?php

namespace App\Core;

class Routeur
{
    private array $routes = [];

    private const ADMIN_URL = '/admin';
    private const ADMIN_REDIRECT_LOGIN_URL = '/login';

    public function addRoute(array $route): self
    {
        $this->routes[] = $route;

        return $this;
    }

    public function handleRequest(string $url, string $method): void
    {
        if (preg_match('~^' . self::ADMIN_URL . '~', $url)) {
            if (empty($_SESSION['user']) || !in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
                http_response_code(403);

                $_SESSION['flash']['danger'] = "Vous devez être connecté pour accéder à cette page";
                http_response_code(302);
                header('Location: ' . self::ADMIN_REDIRECT_LOGIN_URL);
                exit();
            }
        }

        foreach ($this->routes as $route) {
            if (preg_match("#^$route[url]$#", $url, $matches) && \in_array($method, $route['methods'])) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                $controller = new $controllerName();

                $params = array_slice($matches, 1);

                $controller->$actionName(...$params);

                return;
            }
        }

        http_response_code(404);
        echo "<h1>404 - Not Found</h1>";
    }
}