<?php

use Core\Infrastructure\Adapters\Http\Controllers\AuthController;
use Core\Infrastructure\Adapters\Http\Controllers\EmployeeController;
use Core\Infrastructure\Adapters\Http\Controllers\PointController;
use Core\Infrastructure\Adapters\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth.jwt'])->group(function () {

    Route::prefix('/users')->group(function () {
        Route::post('', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware(['auth.jwt', 'role:admin'])->prefix('/employees')->group(function () {
        Route::post('', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('', [EmployeeController::class, 'index'])->name('employees.index');
        Route::put('/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    Route::middleware(['auth.jwt', 'role:admin'])->prefix('/points')->group(function () {
        Route::get('/report', [PointController::class, 'report'])->name('points.report');
    });

    Route::prefix('/points')->group(function () {
        Route::post('', [PointController::class, 'register']);
        Route::get('', [PointController::class, 'index'])->name('points.index');
        Route::post('', [PointController::class, 'store'])->name('points.store');

        Route::put('/{id}', [PointController::class, 'update'])->name('points.update');

        Route::delete('/{id}', [PointController::class, 'destroy'])->name('points.destroy');
    });


    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
});
