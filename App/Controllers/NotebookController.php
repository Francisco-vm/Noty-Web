<?php

namespace App\Controllers;

use App\Models\Notebook;
use App\Helpers\Auth;

class NotebookController
{
    public function createNotebook()
    {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $name = strip_tags($_POST['name']);
        $color = isset($_POST['color']) ? filter_var($_POST['color'], FILTER_SANITIZE_STRING) : '#F2F2F2';

        // Validaciones
        if (!preg_match('/^[a-zA-Z0-9_ ]+$/', $name)) {
            $this->jsonResponse(false, 'Nombre inválido');
            return;
        }

        if (!preg_match('/^#([0-9a-fA-F]{3}){1,2}$/', $color)) {
            $this->jsonResponse(false, 'Color inválido');
            return;
        }

        // Crear cuaderno
        $newId = Notebook::create($name, $color, $userId);

        if ($newId) {
            $this->jsonResponse(true, null, [
                'id' => $newId,
                'title' => $name,
                'color' => $color
            ]);
        } else {
            $this->jsonResponse(false, 'Error al crear el cuaderno');
        }
    }

    private function jsonResponse($success, $error = null, $notebook = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'error' => $error,
            'notebook' => $notebook
        ]);
        exit;
    }
}