<?php

namespace App\Http\Controllers;

use App\Classes\Help;
use App\Jobs\VisitCountry;
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
        
        if(!$test){
            $request_data = Help::getRequestData();
            
            $visit = $short->visits()->create([
                'url_id' => $url->id,
                'language' => $request_data['language'],
                'device' => $request_data['device_type'],
                'referer' => $request_data['referer'],
            ]);
            
            VisitCountry::dispatch($visit, $request_data['ip']);
        }
        
        if(config('app.env') == 'local'){
            return response()->json([
                'short' => $code,
                'browser_language' => $browser_language,
                'url' => $url->url,
                'urls' => $short->getUrls()
            ]);
        }
        
        return redirect($url->url);
    }
    
    public function short_test(Request $request, $code){
        return $this->short($request, $code, true);
    }
    
    public function short_info(Request $request, $code){
        $short = Short::where('code', $code)->first();
        
        if(is_null($short)){
            abort(404);
        }
        
        // redirect to short page
    }
    
    public function short_preview(Request $request, Short $short){
        return view('backoffice.short', ['short' => $short]);
    }
    
    public function get_timeline_data(Request $request, Short $short){
        $range = explode(" - ", $request->range);
        
        $from = Help::convert_date($range[0]);
        $to = Help::convert_date($range[1] ?? $range[0]);
        
        return Help::fragment("backoffice.short", "timeline", ["short" => $short, "from" => $from, "to" => $to]);
    }
    
    public function share(Request $request, Short $short){
        return view('components.backoffice.modals.short-share', ['short' => $short]);
    }
}