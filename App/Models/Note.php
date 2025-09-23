<?php

namespace App\Models;
use App\Config\Database;
use PDO;

class Note
{
    public static function getByUserId($userId)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY updated_at DESC");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByNotebookId($notebookId, $userId)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("SELECT * FROM notes WHERE notebook_id = :notebook_id AND user_id = :user_id ORDER BY updated_at DESC");
        $stmt->bindParam(':notebook_id', $notebookId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}