<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            die('Email y contraseña son obligatorios');
        }

        $user = User::login($email, $password);
        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /tu-espacio.php');
            exit;
        } else {
            die('Credenciales inválidas');
        }
    }

    public function showRegisterForm()
    {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function register()
    {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $username = strip_tags($_POST['username']);

        if (!preg_match('/^[a-zA-Z0-9_ ]+$/', $username)) {
            die('Nombre inválido');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Correo inválido');
        }

        if (strlen($password) < 6) {
            die('La contraseña debe tener al menos 6 caracteres');
        }

        if (User::exists($email)) {
            die('Este correo ya está registrado');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (User::create($username, $email, $hashedPassword)) {
            header('Location: /login');
            exit;
        } else {
            die('Error al registrar el usuario');
        }
    }
}
