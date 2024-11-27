<?php

use Core\Infrastructure\Adapters\Http\Controllers\AuthController;
use Core\Infrastructure\Adapters\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth.jwt'])->group(function () {
    Route::post('/points', [PointController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
