<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\ProfissionalController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::get('niveis', [NivelController::class, 'index']);
    Route::get('niveis/{id}', [NivelController::class, 'show']);
    Route::get('profissionais', [ProfissionalController::class, 'index']);
    Route::get('profissionais/{id}', [ProfissionalController::class, 'show']);

    Route::middleware('role:ADMIN')->group(function () {
        Route::post('niveis', [NivelController::class, 'store']);
        Route::put('niveis/{id}', [NivelController::class, 'update']);
        Route::delete('niveis/{id}', [NivelController::class, 'destroy']);

        Route::post('profissionais', [ProfissionalController::class, 'store']);
        Route::put('profissionais/{id}', [ProfissionalController::class, 'update']);
        Route::delete('profissionais/{id}', [ProfissionalController::class, 'destroy']);
    });
});
