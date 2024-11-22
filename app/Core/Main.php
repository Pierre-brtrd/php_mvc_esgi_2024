<?php

namespace App\Core;

class Main
{
    public function __construct(
        private Routeur $routeur = new Routeur()
    ) {
    }

    public function start(): void
    {
        session_start();

        $uri = $_SERVER['REQUEST_URI'];

        if (!empty($uri) && $uri !== '/' && $uri[-1] === '/') {
            $uri = substr($uri, 0, -1);

            http_response_code(301);
            header("Location: $uri");
            exit;
        }

        $this->initRouter();

        $this->routeur->handleRequest($uri, $_SERVER['REQUEST_METHOD']);
    }

    private function initRouter(): void
    {
        $files = glob(ROOT . '/Controller/*.php');
        $files = array_merge_recursive($files, glob(ROOT . '/Controller/**/*.php'));

        foreach ($files as $controllerPath) {
            $class = $this->convertPathToNamespace($controllerPath);

            if (class_exists($class)) {
                $methods = get_class_methods($class);

                foreach ($methods as $method) {
                    $attributes = (new \ReflectionMethod($class, $method))->getAttributes(Route::class);

                    foreach ($attributes as $route) {
                        $route = $route->newInstance();

                        $route
                            ->setController($class)
                            ->setAction($method);

                        $this->routeur->addRoute([
                            'url' => $route->getUrl(),
                            'methods' => $route->getMethods(),
                            'controller' => $route->getController(),
                            'action' => $route->getAction(),
                            'name' => $route->getName(),
                        ]);
                    }
                }
            }
        }
    }

    private function convertPathToNamespace(string $path): string
    {
        $path = substr($path, 1, -4);
        $path = str_replace('/', '\\', $path);
        $path = ucfirst($path);

        return $path;
    }
}