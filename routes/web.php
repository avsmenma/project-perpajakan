<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NpwpSearchController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth.custom')->group(function () {
    Route::get('/', [NpwpSearchController::class, 'index'])->name('home');
    Route::get('/search', [NpwpSearchController::class, 'search'])->name('search');
    Route::get('/download-pdf/{path}', [NpwpSearchController::class, 'downloadPdf'])->where('path', '.*')->name('download.pdf');
});
