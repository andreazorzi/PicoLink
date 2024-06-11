<?php

use App\Models\User;
use App\Classes\Help;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ShortController;

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
Route::prefix('backoffice')->group(function () {
    Route::get('/', [RouteController::class, 'index', [User::current()]])->name('backoffice.index');
    
    Route::middleware(['auth'])->group(function () {
        Route::prefix('short/{short:code}')->group(function(){
            Route::get('/', [ShortController::class, 'short_preview'])->name('backoffice.short');
            Route::get('/qrcode', [ShortController::class, 'short_preview'])->name('backoffice.short.qrcode-download');
        });
    });
});

Route::middleware('dev-env')->group(function () {
    Route::get('test', function(){
        echo '
            <img src="'.asset("images/lang/default.svg").'" title="Default"> Default<br>
        ';
        foreach(__("languages") as $key => $value){
            echo '
                <img src="'.asset("images/lang/".$key.".svg").'" title="'.$value.'"> '.$value.'<br>
            ';
        }
    })->name('test');
});

// Shorts
Route::prefix('{short}')->group(function () {
    Route::get('/', [ShortController::class, 'short'])->name("short");
    Route::get('/info', [ShortController::class, 'short_info']);
    Route::get('/test', [ShortController::class, 'short_test']);
});