<?php

namespace App;

class Router
{

    private const ALLOWED = [
        'home', 'item', 'cart', 'order', 'user', 'admin', 'api-item', 'api-category', 'api-rarity', 'api-color'
    ];

    public function dispatch(string $url): void
    {
        $parts = explode('/', trim($url, '/'));

        $segment = $parts[0] ?? '';
        if($segment !== '' && !in_array($segment, self::ALLOWED)){
            http_response_code(404);
            echo "Page introuvable";
            return;
        }

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
