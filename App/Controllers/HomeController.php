<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Models\Note;
use App\Models\Notebook;

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

    public function about()
    {
        require_once __DIR__ . '/../Views/about.php';
    }

    public function tuEspacio()
    {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $mainNotebooks = Notebook::getMainNotebooks($userId);
        $standaloneNotes = Note::getByUserId($userId);

        require_once __DIR__ . '/../Views/tu-espacio.php';
    }

}
