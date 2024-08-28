<?php

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShortController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\SearchTableController;

Route::prefix("backoffice")->group(function(){
	Route::middleware(['auth'])->group(function () {
		// Change user password
		Route::put('user/reset-password', [UserController::class, 'change_password'])->name('user.change-password');
		
		// Shorts
		Route::prefix("shorts")->group(function(){
			Route::put('create', [ShortController::class, 'create'])->name('short.create');
			Route::post('list', [SearchTableController::class, 'list'])->name('shorts.list')->defaults('model', new App\Models\Short);
			Route::post('details/{short?}', [ShortController::class, 'details'])->name('short.details');
			Route::post('add-url-modal', [ShortController::class, 'add_url_modal'])->name('short.add-url-modal');
			Route::post('add-url', [ShortController::class, 'add_url'])->name('short.add-url');
			Route::put('upload-csv', [ShortController::class, 'upload_csv'])->name('short.upload-csv');
			Route::get('multiple-download', [ShortController::class, 'multiple_download'])->name('short.multiple-download');
			
			Route::prefix("{short}")->group(function(){
				Route::put('update', [ShortController::class, 'update'])->name('short.update');
				Route::delete('delete', [ShortController::class, 'delete'])->name('short.delete');
				Route::post('get-details', [ShortController::class, 'get_details'])->name('short.get-details');
				Route::post('get-timeline-data', [ShortController::class, 'get_timeline_data'])->name('short.get-timeline-data');
				Route::post('share', [ShortController::class, 'share'])->name('short.share');
				Route::post('qrcode', [ShortController::class, 'qrcode'])->name('short.qrcode');
			});
		});
		
		// Tags
		Route::prefix("tags")->group(function(){
			Route::put('create', [TagController::class, 'create'])->name('tag.create');
			Route::post('list', [TagController::class, 'list'])->name('tags.list');
			Route::post('details/{tag?}', [TagController::class, 'details'])->name('tag.details');
			
			Route::prefix("{tag}")->group(function(){
				Route::put('update', [TagController::class, 'update'])->name('tag.update');
				Route::delete('delete', [TagController::class, 'delete'])->name('tag.delete');
			});
		});
	});
});