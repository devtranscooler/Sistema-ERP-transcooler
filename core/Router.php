<?php

declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $action): void
    {
        $this->add('GET', $uri, $action);
    }

    public function post(string $uri, callable $action): void
    {
        $this->add('POST', $uri, $action);
    }

    public function put(string $uri, callable $action): void
    {
        $this->add('PUT', $uri, $action);
    }

    public function delete(string $uri, callable $action): void
    {
        $this->add('DELETE', $uri, $action);
    }

    private function add(string $method, string $uri, callable $action): void
    {
        $pattern = preg_replace('#\{[\w]+\}#', '([\w-]+)', $uri);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'pattern' => $pattern,
            'action' => $action
        ];
    }

    public function dispatch(string $requestUri, string $requestMethod)
    {
        foreach ($this->routes as $route) {

            if ($route['method'] !== $requestMethod) {
                continue;
            }

            if (preg_match($route['pattern'], $requestUri, $matches)) {

                array_shift($matches);

                return call_user_func_array($route['action'], $matches);
            }
        }

        http_response_code(404);
        return [
            "status" => false,
            "message" => "Ruta no encontrada"
        ];
    }
}