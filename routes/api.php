<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/solicitar-reset', [AuthController::class, 'solicitarReset']);
Route::post('/confirmar-reset', [AuthController::class, 'confirmarReset']);