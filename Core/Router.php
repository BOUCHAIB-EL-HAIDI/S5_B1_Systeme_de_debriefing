<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action){
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, string $action){
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestMethod][$uri])) {
            [$controllerName, $methodName] = explode('@', $this->routes[$requestMethod][$uri]);

            $controllerClass = "App\\Controllers\\$controllerName";
            $controller = new $controllerClass();
            $controller->$methodName();
            return;
        }

        $controllerClass = "App\\Controllers\\NotFoundController";
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            $controller->index();
        } else {
            http_response_code(404);
            echo "404 - Page NOT Found";
        }
    }
}