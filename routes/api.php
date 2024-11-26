<?php

use Core\Infrastructure\Adapters\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;

Route::post('/points', [PointController::class, 'register']);
