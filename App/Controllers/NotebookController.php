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
        $color = $_POST['color'] ?? 'rgb(242, 242, 242)';
        $color = trim(strip_tags($color));

        // Validaciones
        if (!preg_match('/^[\p{L}0-9_ ]+$/u', $name)) {
            $this->jsonResponse(false, 'Nombre inválido');
            return;
        }
        
        if (!preg_match('/^rgb\\s*\\(\\s*(\\d{1,3})\\s*,\\s*(\\d{1,3})\\s*,\\s*(\\d{1,3})\\s*\\)$/i', $color)) {
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