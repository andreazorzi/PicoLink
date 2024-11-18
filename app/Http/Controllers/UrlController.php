<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    public function modal(Request $request, Url $url){
        return view('components.backoffice.modals.url-update', ["url" => $url]);
    }
    
    public function update(Request $request, Url $url){
        $validator = Validator::make($request->all(), [
            'url' => ['required', 'url:http,https'],
        ]);
        
        if($validator->fails()){
            return view("components.alert", ["status" => "danger", "message" => implode('\\n', $validator->errors()->all())]);
        }
        
        $url->update([
            'url' => $request->url,
        ]);
        
        return view("components.alert", ["status" => "success", "message" => __('app.pages.short.updated'), 'beforeshow' => 'modal.hide(); $(".language[data-lang='.($url->language ?? 'default').'] .url").text("'.$request->url.'");']);
    }
}