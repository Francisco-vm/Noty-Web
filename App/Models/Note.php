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

    public static function getById($noteId, $userId)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("
        SELECT 
            notes.*, 
            notebooks.title AS notebook_title 
        FROM notes
        LEFT JOIN notebooks ON notes.notebook_id = notebooks.id
        WHERE notes.id = :id AND notes.user_id = :user_id
    ");
        $stmt->bindParam(':id', $noteId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function updateContent($noteId, $userId, $content)
    {
        $db = (new Database())->Connect();
        $stmt = $db->prepare("UPDATE notes SET content = :content, updated_at = NOW() WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $noteId);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    public static function create($userId, $notebookId, $title)
    {
        $db = (new Database())->Connect();

        $defaultContent = '{"blocks":[],"version":"2.31.0"}';

        $stmt = $db->prepare("INSERT INTO notes (user_id, notebook_id, title, content, created_at, updated_at) VALUES (:user_id, :notebook_id, :title, :content, NOW(), NOW())");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':notebook_id', $notebookId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $defaultContent);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }
}