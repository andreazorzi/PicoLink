<?php

namespace App\Http\Controllers;

use App\Classes\Help;
use App\Models\Short;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShortController extends Controller
{
    public function short(Request $request, $code, $test = false){
        $short = Short::where('code', $code)->first();
        
        if(is_null($short)){
            abort(404);
        }
        
        $browser_language = Help::preferred_language();
        $url = $short->getUrl($browser_language);
        
        if(config('app.env') == 'local'){
            return response()->json([
                'short' => $code,
                'browser_language' => $browser_language,
                'url' => $url,
                'urls' => $short->getUrls()
            ]);
        }
        
        if(!$test){
            // add new short view
        }
        
        return redirect($url);
    }
    
    public function short_test(Request $request, $code){
        return $this->short($request, $code, true);
    }
}