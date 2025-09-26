<?php

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\NoteController;
use App\Controllers\NotebookController;

// Rutas GET
Router::get('/', [HomeController::class, 'index']);
Router::get('/login', [AuthController::class, 'showLoginForm']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/register', [AuthController::class, 'showRegisterForm']);
Router::post('/register', [AuthController::class, 'register']);

Router::get('/tu-espacio', [HomeController::class, 'tuEspacio']);

Router::get('/about', [HomeController::class, 'about']);

Router::get('/get-notes', [NoteController::class, 'getNotesByNotebook']);

Router::get('/get-note-content', [NoteController::class, 'getNoteContent']);

Router::post('/save-note-content', [NoteController::class, 'saveNoteContent']);

Router::post('/create-notebook', [NotebookController::class, 'createNotebook']);

Router::post('/create-note', [NoteController::class, 'createNote']);
// Ejecutar el router
Router::dispatch();
