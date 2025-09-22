<?php
namespace App\Helpers;

class Auth
{
    protected static function ensureSessionStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check(): bool
    {
        self::ensureSessionStarted();
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }
}