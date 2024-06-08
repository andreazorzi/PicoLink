<?php

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\SearchTableController;

Route::prefix("backoffice")->group(function(){
	Route::middleware(['role:'.config("auth.authentik.administrators")])->group(function () {
		// Users
		// Route::prefix("users")->group(function(){
		// 	Route::put('create', [UserController::class, 'create'])->name('user.create');
		// 	Route::post('list', [SearchTableController::class, 'list'])->name('users.list')->defaults('model', new App\Models\User);
		// 	Route::post('details/{user?}', [UserController::class, 'details'])->name('user.details');
			
		// 	Route::prefix("{user}")->group(function(){
		// 		Route::put('update', [UserController::class, 'update'])->name('user.update');
		// 		Route::delete('delete', [UserController::class, 'delete'])->name('user.delete');
		// 		Route::post('send-reset-password', [UserController::class, 'send_reset_password'])->name('user.send-reset-password');
		// 	});
		// });
	});
});

// Change user password
// Route::put('user/reset-password/{reset_link}', [UserController::class, 'change_password'])->name('user.change-password');
// Route::post('user/send-reset-password/{user?}', [UserController::class, 'send_reset_password'])->name('user.send-reset-password-user');