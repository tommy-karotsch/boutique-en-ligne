<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/layout/header.php';
        echo "<div style='text-align: center; margin-top: 50px;'>";
        echo "<h1>Bienvenue sur la boutique Rocket League !</h1>";
        echo "<a href='/boutique-rl/public/item/index' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px;'>Voir les objets</a>";
        echo "</div>";
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
