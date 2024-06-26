<?php

use App\Models\Short;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('shorts')->group(function () {
    Route::put('create', function(Request $request){
        $validator = Validator::make($request->all(), [
            'shorts' => ['required'],
            'shorts.*.code' => ['required', 'max:50', 'unique:shorts,code'],
            'shorts.*.description' => ['nullable', 'max:255'],
            'shorts.*.url' => ['required', 'max:255', 'url:http,https'],
            'shorts.*.languages.*.url' => ['required_with:shorts.*.languages.*.language', 'max:255', 'url:http,https'],
            'shorts.*.languages.*.language' => ['required_with:shorts.*.languages.*.url', Rule::in(array_keys(__("languages")))],
        ], [
            'shorts.required' => 'The short link is required',
            'shorts.*.code.unique' => 'The following short links already exist: :input',
            'shorts.*.languages.*.url.required_with' => 'The language URL is required when the language is present.',
            'shorts.*.languages.*.url.max' => 'The language URL must not be greater than 255 characters.',
            'shorts.*.languages.*.url.url' => 'The language URL format is invalid.',
            'shorts.*.languages.*.language.required_with' => 'The language is required when the language URL is present.',
            'shorts.*.languages.*.language.in' => 'The language ":input" is invalid.',
        ]);
        
        if($validator->fails()){
            return response()->json(['status' => 'danger', 'message' => $validator->errors()->all()]);
        }
        
        foreach($request->shorts as $short){
            $short_obj = Short::create([
                'code' => $short['code'],
                'description' => $short['description'] ?? $short['url'],
            ]);
            
            $short_obj->urls()->create([
                'url' => $short['url'],
            ]);
            
            if(!empty($short['languages'])){
                foreach($short['languages'] as $language){
                    if($short_obj->urls()->where('language', $language['language'])->exists()) continue;
                    
                    $short_obj->urls()->create([
                        'url' => $language['url'],
                        'language' => $language['language'],
                    ]);
                }
            }
            
            if(!empty($short['tags'])){
                foreach($short['tags'] as $tag){
                    $tag = Tag::where('name', $tag)->first();
                    
                    if(is_null($tag)) continue;
                    
                    $short_obj->tags()->attach($tag->id);
                }
            }
        }
        
        return response()->json(['status' => 'success', 'message' => 'Short links created successfully.']);
    })->name('short.create');
});
