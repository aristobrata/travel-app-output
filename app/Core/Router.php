<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = ['GET', $path, $handler, $middleware];
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = ['POST', $path, $handler, $middleware];
    }

    public function dispatch(string $method, string $uri): void
    {
        // Strip query string and normalize slashes
        $uri = strtok($uri, '?');
        $uri = '/' . trim($uri, '/');
        if ($uri === '') $uri = '/';

        foreach ($this->routes as [$routeMethod, $routePath, $handler, $middlewares]) {
            if ($routeMethod !== $method) continue;

            // Build regex from route pattern
            $pattern = '#^' . preg_replace('#\{(\w+)\}#', '([^/]+)', $routePath) . '$#';
            if (!preg_match($pattern, $uri, $matches)) continue;

            array_shift($matches);

            // Run middleware chain
            foreach ($middlewares as $mw) {
                (new $mw())->handle();
            }

            // Call controller
            [$class, $action] = $handler;
            (new $class())->$action(...$matches);
            return;
        }

        http_response_code(404);
        require BASE_PATH . '/views/errors/404.php';
    }
}
