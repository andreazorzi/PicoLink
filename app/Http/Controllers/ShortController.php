<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Tag;
use App\Classes\Help;
use App\Models\Short;
use App\Jobs\VisitCountry;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use Illuminate\Validation\Rule;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Common\EccLevel;
use Illuminate\Support\Facades\Validator;
use Brunoinds\LinkPreviewDetector\LinkPreviewDetector;

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
        
        if(!$test && !LinkPreviewDetector::isForLinkPreview()){
            $request_data = Help::getRequestData();
            
            $visit = $short->visits()->create([
                'url_id' => $url->id,
                'language' => $request_data['language'],
                'device' => $request_data['device_type'],
                'referrer' => $request_data['referrer'],
            ]);
            
            config("app.env") == 'local' ? $visit->getCountry($request_data['ip']) : VisitCountry::dispatch($visit, $request_data['ip']);;
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
        
        return redirect(route("backoffice.short", $short));
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
            return view("components.alert", ["status" => "danger", "message" => $validator->errors()->first()]);
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
    
    public function upload_csv(Request $request){
        $validator = Validator::make($request->all(), [
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);
        
        if($validator->fails()){
            return view("components.alert", ["status" => "danger", "message" => $validator->errors()->first()]);
        }
        
        $header = [];
        $errors = [];
        $csv = fopen($request->csv, "r");
        
        while (($data = fgetcsv($csv, 0, ";")) !== FALSE) {
            if(count($header) < 1){
                $header = array_flip($data);
                continue;
            }
            
            if(Short::where('code', $data[$header['code']])->exists()){
                $errors[] = $data[$header['code']];
                continue;
            }
            
            $short = Short::create([
                'code' => $data[$header['code']],
                'description' => $data[$header['description'] ?? ''],
            ]);
            
            $short->urls()->create([
                'url' => $data[$header['url']],
            ]);
            
            if(!empty($data[$header['tags'] ?? ''])){
                foreach(explode(",", $data[$header['tags']]) as $tag){
                    $tag = Tag::where('name', $tag)->first();
                    
                    if(is_null($tag)) continue;
                    
                    $short->tags()->attach($tag->id);
                }
            }
            
        }
        
        return view("components.alert", ["status" => count($errors) > 0 ? "warning" : "success", "duration" => count($errors) > 0 ? -1 : 3000, "message" => __("app.pages.upload-csv.upload_statuses.".(count($errors) > 0 ? "warning" : "success"), ["errors" => count($errors), "codes" => implode(", ", $errors)]), "beforeshow" => '$("form")[0].reset()']);
        
        // $file = $request->file('file');
        // $contents = $file->get();
        // $lines = explode("\n", $contents);
        
        // return view("components.backoffice.modals.short-upload-csv", ["lines" => $lines]);
    }
    
    public function multiple_download(Request $request){
        $shorts = Short::filter([
            "query" => $request->filter ?? null,
            "advanced_search" => $request->advanced_search ?? null,
        ])->get();
        
        $time = time();
        $zip = new ZipArchive;
        $zipFileName = "tmp/qrcode/".$time.".zip";
        $zip->open(public_path($zipFileName), ZipArchive::CREATE);
        
        foreach($shorts as $short){
            $options = new QROptions;
            $options->quietzoneSize = 1;
        
            if(!empty($request->logo)){
                $options->eccLevel= EccLevel::H;
                $options->addLogoSpace = true;
                $options->logoSpaceWidth = 10;
                $options->logoSpaceHeight = 10;
                $options->keepAsSquare        = [
                    QRMatrix::M_FINDER_DARK,
                    QRMatrix::M_FINDER_DOT,
                    QRMatrix::M_ALIGNMENT_DARK,
                ];
            }
            
            $contents = (new QRCode($options))->render($short->getLink());
            
            $zip->addFromString(Str::slug($short->description).".svg", base64_decode(str_replace("data:image/svg+xml;base64,", "", $contents)));
        }
        
        $zip->close();
        
        return response(headers: ["HX-Redirect" => asset($zipFileName)]);
    }
}