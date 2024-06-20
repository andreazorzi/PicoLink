<?php

namespace App\Models;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Short extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $guarded  = ['no_key']; // set guarded columns, set to no_key to avoid problems
    
    public static function getTableFields():array{
        return [
            "code" => [
                "filter" => true,
            ],
            "description" => [
                "filter" => true,
            ],
            "visits" => [
                "custom-value" => "getVisits",
            ],
            "created_at" => [
                "sort" => "desc",
                "custom-value" => "getCreatedAtText",
            ],
            "created_at" => [
                "sort" => "desc",
                "custom-value" => "getCreatedAtText",
            ],
        ];
    }
    
    public function getTableActions($model_name,$model_key, $key):array{
        return [
            [
                "custom-attributes" => 'onclick="navigator.clipboard.writeText(`'.(!empty($key) ? $this->getLink() : "").'`); Toastify({text: `'.__("app.pages.index.link_copied").'!`, duration: `1400`, className: `success`, gravity: `bottom`, position: `center`, close: true}).showToast();"',
                "icon" => '<i class="fa-solid fa-clipboard text-primary"></i>'
            ],
            
            // Default action
            [
                "url" => (!empty($key) ? route("backoffice.short", ["short" => $this->code ?? ""]) : ""),
                "icon" => '<i class="table-search-preview fa-solid fa-pen"></i>'
            ]
        ];
    }
    
    public function urls(){
        return $this->hasMany(Url::class);
    }
    
    public function visits(){
        return $this->hasMany(Visit::class);
    }
    
    public function getUrl($language = null){
        $url = $this->urls()->where("language", $language)->first();
        
        if(is_null($url)){
            $url = $this->urls()->where("language", null)->first();
        }
        
        return $url;
    }
    
    public function getUrls(){
        $urls = [];
        
        foreach($this->urls()->orderBy("language")->get() as $url){
            $urls[$url->language ?? "_default"] = $url->url;
        }
        
        return $urls;
    }
    
    public function getLink(){
        return route("short", $this->code);
    }
    
    public function getCreatedAtText(){
        return date("d/m/Y H:i", strtotime($this->created_at));
    }
    
    public function getVisits(){
        return $this->visits()->count();
    }
    
    public function getTimeline($from = null, $to = null){
        $from = new DateTime($from ?? date("Y-m-d", strtotime("-7 days")));
        $to = new DateTime($to ?? date("Y-m-d"));
        
        $data = [];
        
        for($i = $from; $i <= $to; $i->modify('+1 day')){
            $day = [
                "date" => date("d/m/Y", strtotime($i->format("Y-m-d"))),
                "total_visits" => $this->visits()->whereDate("created_at", $i->format("Y-m-d"))->count(),
                "visits" => [],
            ];
            
            foreach($this->urls()->orderBy("language")->get() as $url){
                $day["visits"][__("languages.".($url->language ?? "default"))] = $url->visits()->whereDate("created_at", $i->format("Y-m-d"))->count();
            }
            
            $data[] = $day;
        }
        
        return $data;
    }
    
    public function getDevices($from = null, $to = null){
        $from = $from ?? date("Y-m-d", strtotime("-7 days"));
        $to = ($to ?? date("Y-m-d"))." 23:59:59";
        
        $data = [];
        
        foreach(Visit::selectRaw("visits.device, COUNT(*) AS count")->where("short_id", $this->id)->whereBetween("created_at", [$from, $to])->groupBy("device")->get() as $visit){
            $data[$visit->device] = $visit->count;
        }
        
        $data = [
            "labels" => array_keys($data),
            "data" => array_values($data),
        ];
        
        return $data;
    }
    
    public function getReferrers($from = null, $to = null){
        $from = $from ?? date("Y-m-d", strtotime("-7 days"));
        $to = ($to ?? date("Y-m-d"))." 23:59:59";
        
        $html = '';
        
        foreach(Visit::selectRaw("visits.referrer, COUNT(*) AS count")->where("short_id", $this->id)->whereBetween("created_at", [$from, $to])->groupBy("referrer")->orderBy("count", "desc")->orderBy("referrer")->get() as $visit){
            try {
                $favicon = base64_encode(file_get_contents("https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=http://{$visit->referrer}&size=16"));
            } catch (\Throwable $th) {
                $favicon = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAAEUlEQVR42mNkIAAYRxWMJAUAE5gAEdz4t9QAAAAASUVORK5CYII=";
            }
            $html .= '
                <tr>
                    <td>
                        <img class="me-2" src="data:image/png;base64,'.$favicon.'">
                        '.$visit->referrer.'
                    </td>
                    <td class="text-end">'.$visit->count.'</td>
                </tr>
            ';
        }
        
        return $html;
    }
    
    public function getMaps($from = null, $to = null){
        $from = $from ?? date("Y-m-d", strtotime("-7 days"));
        $to = ($to ?? date("Y-m-d"))." 23:59:59";
        
        $data = [
            ["country", "Click"]
        ];
        
        foreach(Visit::select(DB::raw("visits.country, COUNT(*) AS count"))->where("short_id", $this->id)->whereBetween("created_at", [$from, $to])->groupBy("country")->get() as $visit){
            $data[] = [config("countries.{$visit->country}"), $visit->count];
        }
        
        return $data;
    }
    
    public static function createFromRequest(Request $request):View{
        return (new self)->updateFromRequest($request, false);
    }
    
    public function updateFromRequest(Request $request, bool $update = true):?View{
        $validation = self::validate($request, $update);
        if ($validation["status"] == "danger") {
            return view("components.alert", ["status" => "danger", "message" => $validation["message"]]);
        }
        
        // // Custom key value for model without incrementing
        // if(!$this->incrementing && !$update){
        //     // $request->merge([self::getModelKey() => self::modelCustomKeyGeneration()]);
        // }
        
        // Fill the model with the request
        $this->fill($request->all());
        
        // If the model is dirty, save it
        if($this->isDirty()){
            $this->save();
        }
        
        return view("components.alert", ["status" => "success", "message" => "Dipinto salvato", "callback" => 'modal.hide(); htmx.trigger("#page", "change");']);
    }
    
    public static function validate(Request $request, bool $update):array{
        $validator = Validator::make($request->all(), [
            self::getModelKey() => [$update ? "exists:App\Models\\".class_basename(new self).",".self::getModelKey() : "prohibited"],
            // self::getModelKey() => ['required', ($update ? "exists" : "unique").":App\Models\\".class_basename(new self).",".self::getModelKey()], // for non incrementing keys
			"description" => ['required']
		]);
        
        if ($validator->fails()) {
			return ["status" => "danger", "message" => implode("\\n", $validator->errors()->all())];
		}
        
        return ["status" => "success"];
    }
    
    public static function getModelAttributes():array{
        return Schema::getColumnListing((new self)->getTable());
    }
    
    public static function getModelKey():string{
        return (new self)->getKeyName();
    }
    
    protected $casts = [
		
	];
}