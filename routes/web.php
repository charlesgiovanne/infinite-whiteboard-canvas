<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;

Route::get('/', [BoardController::class, 'home'])->name('home');
Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
Route::get('/boards/{id}', [BoardController::class, 'show'])->name('boards.show');
