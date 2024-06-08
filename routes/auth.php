<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebAuthController;

// Authentik Login
Route::prefix('authentik')->group(function () {
	Route::get('/', [AuthController::class, 'submit']);
	Route::get('/login', [AuthController::class, 'submit'])->name('auth.login');
	Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
	Route::get('/submit', [AuthController::class, 'submit']);
	Route::get('/callback', [AuthController::class, 'callback'])->name('auth.callback');
	Route::get('/user', [AuthController::class, 'userProfile'])->name('auth.user');
});

// Web Login
Route::prefix('web')->group(function () {
	Route::post('/login', [WebAuthController::class, 'authenticate'])->name('web-auth.login');
	Route::get('/logout', [WebAuthController::class, 'logout'])->name('web-auth.logout');
	Route::get('/reset-password/{reset_link}', [WebAuthController::class, 'reset_password'])->name('web-auth.reset-password');
});