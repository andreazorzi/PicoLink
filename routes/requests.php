<?php

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShortController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\SearchTableController;

Route::prefix("backoffice")->group(function(){
	Route::middleware(['role:'.config("auth.authentik.administrators")])->group(function () {
		// Shorts
		Route::prefix("shorts")->group(function(){
			Route::put('create', [ShortController::class, 'create'])->name('short.create');
			Route::post('list', [SearchTableController::class, 'list'])->name('shorts.list')->defaults('model', new App\Models\Short);
			Route::post('details/{short?}', [ShortController::class, 'details'])->name('short.details');
			
			Route::prefix("{short}")->group(function(){
				Route::put('update', [ShortController::class, 'update'])->name('short.update');
				Route::delete('delete', [ShortController::class, 'delete'])->name('short.delete');
				Route::post('send-reset-password', [ShortController::class, 'send_reset_password'])->name('short.send-reset-password');
			});
		});
	});
});

// Change user password
// Route::put('user/reset-password/{reset_link}', [UserController::class, 'change_password'])->name('user.change-password');
// Route::post('user/send-reset-password/{user?}', [UserController::class, 'send_reset_password'])->name('user.send-reset-password-user');