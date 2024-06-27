<?php

use App\Models\User;
use App\Classes\Help;
use App\Http\Controllers\ImageController;
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
            Route::prefix('qrcode')->group(function(){
                Route::get('', [ImageController::class, 'qrcode'])->name('backoffice.short.qrcode');
                Route::get('logo', [ImageController::class, 'qrcode'])->defaults('logo', true)->name('backoffice.short.qrcode-logo');
            });
        });

        Route::view('tags', 'backoffice.tags')->name('backoffice.tags');
        Route::view('upload-csv', 'backoffice.upload-csv')->name('backoffice.upload-csv');
        Route::view('change-password', 'backoffice.reset-password', ['change' => true])->name('backoffice.change-password');
        
        Route::get('csv-template', function(){
            return response()->download("template/short_import.csv");
        })->name('backoffice.csv-template');
    });
});

Route::middleware('dev-env')->group(function () {
    Route::get('test', function(){
        dd(Help::getRequestData());
    })->name('test');
});

// Shorts
Route::prefix('{short}')->group(function () {
    Route::get('/', [ShortController::class, 'short'])->name("short");
    Route::get('/info', [ShortController::class, 'short_info'])->name("short.info");
    Route::get('/test', [ShortController::class, 'short_test'])->name("short.test");
});

Route::view('/', 'index')->name('index');