<?php

namespace App\Models;
use App\Config\Database;
use PDO;

class Notebook
{
    public static function getByUserId($userId)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT * FROM notebooks WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener solo cuadernos principales (no subcuadernos)
    public static function getMainNotebooks($userId)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT * FROM notebooks WHERE user_id = :user_id AND notebook_id IS NULL ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener subcuadernos de un cuaderno específico
    public static function getSubNotebooks($notebookId, $userId)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT * FROM notebooks WHERE notebook_id = :notebook_id AND user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(':notebook_id', $notebookId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}