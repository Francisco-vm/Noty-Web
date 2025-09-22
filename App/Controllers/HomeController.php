<?php

namespace App\Controllers;

use App\Helpers\Auth;

class HomeController
{
    public function index()
    {
        if (Auth::check()) {
            header('Location: /tu-espacio');
            exit;
        }

        require_once __DIR__ . '/../Views/home.php';
    }

    public function about(){
        require_once __DIR__ . '/../Views/about.php';
    }

    public function tuEspacio()
    {
        Auth::requireLogin();
        require_once __DIR__ . '/../Views/tu-espacio.php';
    }
}
