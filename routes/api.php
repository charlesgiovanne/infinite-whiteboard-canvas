<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;

Route::get('/boards', [BoardController::class, 'index']);
Route::post('/boards', [BoardController::class, 'store']);
Route::get('/boards/{id}', [BoardController::class, 'show']);
Route::put('/boards/{id}', [BoardController::class, 'update']);
Route::delete('/boards/{id}', [BoardController::class, 'destroy']);
