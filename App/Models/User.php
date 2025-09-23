<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    public static function create($user, $email, $hashedPassword)
    {
        $db = (new Database())->Connect();

        $date = date('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO users (email, username, created_at, updated_at) VALUES (:email, :username, :created_at, :updated_at)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':created_at', $date);
        $stmt->bindParam(':updated_at', $date);

        if (!$stmt->execute()) {
            return false; // Falló el primer insert
        }

        $userId = $db->lastInsertId();

        $stmt = $db->prepare("INSERT INTO passwords (user_id, hash) VALUES (:user_id, :hash)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':hash', $hashedPassword);

        if (!$stmt->execute()) {
            return false;
        }

        $defaultTitle = 'Mi primer cuaderno';
        $defaultColor = '#FFD700';

        $stmt = $db->prepare("INSERT INTO notebooks (title, color, notebook_id, user_id, created_at, updated_at) VALUES (:title, :color, NULL, :user_id, :created_at, :updated_at)");
        $stmt->bindParam(':title', $defaultTitle);
        $stmt->bindParam(':color', $defaultColor);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':created_at', $date);
        $stmt->bindParam(':updated_at', $date);

        return $stmt->execute();
    }


    public static function exists($email)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public static function login($email, $password)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT u.id, u.username, p.hash FROM users u JOIN passwords p ON u.id = p.user_id WHERE u.email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['hash'])) {
            return ['id' => $user['id'], 'username' => $user['username']];
        }

        return false;
    }
}
