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
}