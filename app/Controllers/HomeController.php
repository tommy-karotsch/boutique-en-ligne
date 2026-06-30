<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\ColorModel;
use App\Models\RarityModel;

class HomeController
{
    public function index()
    {
        $nbItems        = count((new ItemModel())->findAll());
        $nbCategories   = count((new CategoryModel())->findAll());
        $nbColors       = count((new ColorModel())->findAll());
        $nbRarities     = count((new RarityModel())->findAll());

        require_once __DIR__ . '/../Views/home/index.php';
    }
}
