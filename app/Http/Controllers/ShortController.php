<?php

namespace App\Http\Controllers;

use App\Classes\Help;
use App\Models\Short;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShortController extends Controller
{
    public function short(Request $request, $code){
        $short = Short::where('code', $code)->first();
        
        if(is_null($short)){
            abort(404);
        }
        
        return redirect($short->getUrl(Help::preferred_language()));
    }
}