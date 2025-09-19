<?php

namespace App\Controllers;

class AuthController
{
    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        echo "Procesando login...";
    }

    public function showRegisterForm()
    {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function register()
    {
        echo "Procesando registro...";
    }
}
