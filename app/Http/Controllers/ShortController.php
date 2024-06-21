<?php

namespace App\Http\Controllers;

use App\Classes\Help;
use App\Models\Short;
use App\Jobs\VisitCountry;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use Illuminate\Validation\Rule;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ShortController extends Controller
{
    public function details(Request $request, ?Short $short = null){
		return view('components.backoffice.modals.short-data', ["short" => $short]);
    }
    
    // get details
    public function get_details(Request $request, Short $short){
        return Help::fragment("backoffice.short", "short-details", ["short" => $short]);
    }
    
    // Create short
    public function create(Request $request){
        return Short::createFromRequest($request);
    }
    
    // Update short
    public function update(Request $request, Short $short){
        return $short->updateFromRequest($request);
    }
    
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
                'referrer' => $request_data['referrer'],
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
    
    public function qrcode(Request $request, Short $short){
        $options = new QROptions;
        $options->quietzoneSize = 1;
        $contents = (new QRCode($options))->render($short->getLink());
        $path =  $short->code.".svg";

        //store file temporarily
        file_put_contents($path, $contents);

        //download file and delete it
        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function add_url_modal(Request $request){
        return view('components.backoffice.modals.short-add-url', ["urls" => $request->urls ?? []]);
    }
    
    public function add_url(Request $request){
        $validator = Validator::make($request->all(), [
            'language' => ['required', Rule::in(array_keys(__("languages")))],
        ]);
        
        if($validator->fails()){
            return view("components.alert", ["type" => "danger", "message" => $validator->errors()->first()]);
        }
        
        return '
            <div class="url-language col-12">
                <div class="input-group">
                    <span class="input-group-text p-0 overflow-hidden">
                        <img class="url-flag" title="'.__("languages.{$request->language}").'" alt="'.__("languages.{$request->language}").'" src="'.asset("images/lang/{$request->language}.svg").'">
                    </span>
                    <input type="text" class="form-control" id="short-defult_url" name="urls['.$request->language.']">
                    <span class="input-group-text px-2 bg-danger text-white overflow-hidden" role="button" onclick="$(this).closest(`.url-language`).remove();">
                        <i class="fa-solid fa-times"></i>
                    </span>
                </div>
            </div>
            <script>
                modal_2.hide();
            </script>
        ';
    }
}