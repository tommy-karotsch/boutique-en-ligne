<?php

namespace App;

class Router
{
    public function dispatch(string $url): void
    {
        $parts = explode('/', trim($url, '/'));

        $controllerName = isset($parts[0]) && $parts[0] !== ''
            ? str_replace(' ', '', ucwords(str_replace('-', ' ', $parts[0]))). 'Controller'
            : 'HomeController';

        $methodName = isset($parts[1]) && $parts[1] !== ''
            ? $parts[1]
            : 'index';

        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)){
            http_response_code(404);
            echo "Page introuvable";
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)){
            http_response_code(404);
            echo "Action introuvable";
            return;
        }

        $controller->$methodName();
    }
}
