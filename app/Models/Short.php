<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

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
            "created_at" => [
                "sort" => "asc",
            ],
        ];
    }
    
    public function getTableActions($model_name,$model_key, $key):array{
        return [
            // Default action
            [
                "custom-attributes" => 'data-id="'.$key.'" hx-post="'.route($model_name.".details", [$this]).'"',
                "icon" => '<i class="table-search-preview fa-solid fa-pen"></i>'
            ]
        ];
    }
    
    public function urls(){
        return $this->hasMany(Url::class);
    }
    
    public function getUrl($language = null){
        $url = $this->urls()->where("language", $language)->first();
        
        if(is_null($url)){
            $url = $this->urls()->where("language", null)->first();
        }
        
        return $url->url;
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
			"name" => ['required']
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