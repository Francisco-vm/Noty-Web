<?php

namespace App\Controllers;

use App\Models\Note;
use App\Helpers\Auth;

class NoteController
{
    public function getNotesByNotebook()
    {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $notebookId = $_GET['notebook_id'] ?? null;

        if (!$notebookId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de cuaderno no proporcionado']);
            return;
        }

        $Notas = Note::getByNotebookId($notebookId, $userId);
        header('Content-Type: application/json');
        echo json_encode($Notas);
    }

    public function getNoteContent()
    {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $noteId = $_GET['note_id'] ?? null;

        if (!$noteId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de nota no proporcionado']);
            return;
        }

        $nota = Note::getById($noteId, $userId);
        if ($nota) {
            header('Content-Type: application/json');
            echo json_encode($nota);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Nota no encontrada']);
        }
    }

    public function saveNoteContent()
    {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $input = json_decode(file_get_contents('php://input'), true);

        $noteId = $input['note_id'] ?? null;
        $content = $input['content'] ?? null;

        if (!$noteId || !$content) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }

        $success = Note::updateContent($noteId, $userId, $content);

        echo json_encode(['success' => $success]);
    }

    public function createNote()
    {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $notebookId = $_POST['notebook_id'] ?? null;
        $title = 'Nueva nota';

        if (!$notebookId) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }

        $noteId = Note::create($userId, $notebookId, $title);

        if ($noteId) {
            echo json_encode([
                'success' => true,
                'note' => [
                    'id' => $noteId,
                    'title' => 'Nueva nota'
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear la nota']);
        }
    }
}