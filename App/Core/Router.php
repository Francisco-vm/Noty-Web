<?php

namespace App\Core;

class Router
{
    protected static array $routes = [];

    // Registrar ruta GET
    public static function get(string $uri, array $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    // Registrar ruta POST
    public static function post(string $uri, array $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    // Ejecutar la ruta correspondiente
    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $action = self::$routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 - Ruta no encontrada";
            return;
        }

        [$controller, $method] = $action;
        (new $controller())->$method();
    }
}
