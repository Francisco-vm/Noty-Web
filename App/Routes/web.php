<?php

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

// Rutas GET
Router::get('/', [HomeController::class, 'index']);
Router::get('/login', [AuthController::class, 'showLoginForm']);
Router::post('/login', [AuthController::class, 'login']);

Router::get('/register', [AuthController::class, 'showRegisterForm']);
Router::post('/register', [AuthController::class, 'register']);

Router::get('/about', [HomeController::class, 'about']);


// Ejecutar el router
Router::dispatch();
