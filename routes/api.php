<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RecordatorioController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/solicitar-reset', [AuthController::class, 'solicitarReset']);
Route::post('/confirmar-reset', [AuthController::class, 'confirmarReset']);

Route::get('/recordatorios/{user_id}', [RecordatorioController::class, 'index']);
Route::post('/recordatorios', [RecordatorioController::class, 'store']);
Route::delete('/recordatorios/{id}', [RecordatorioController::class, 'destroy']);
Route::patch('/recordatorios/{id}/toggle', [RecordatorioController::class, 'toggle']);