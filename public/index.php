<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$url = $_GET['url'] ?? '';

$router = new App\Router();
$router->dispatch($url);
