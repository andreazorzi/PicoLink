<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Backoffice
Route::get('/', [RouteController::class, 'index', [User::current()]])->name('backoffice.index');

Route::middleware(['role:'.config("auth.authentik.administrators")])->group(function () {
    // Route::view('users', 'backoffice.users', headers: ["menu" => true])->name('backoffice.users');
});

Route::middleware('dev-env')->group(function () {
    Route::get('test', function(){
        echo "test";
    })->name('test');
});