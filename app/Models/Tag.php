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

class Tag extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $guarded  = ['no_key']; // set guarded columns, set to no_key to avoid problems
    
    public static function getTableFields():array{
        return [
            "name" => [
                "sort" => "asc",
                "filter" => true,
            ],
        ];
    }
    
    public function getTableActions($model_name,$model_key, $key):array{
        return [
            // Default action
            [
                "url" => (!empty($key) ? route("backoffice.short", ["short" => $this->code ?? ""]) : ""),
                "icon" => '<i class="table-search-preview fa-solid fa-pen"></i>'
            ]
        ];
    }
    
    public static function createFromRequest(Request $request):View{
        return (new self)->updateFromRequest($request, false);
    }
    
    public function updateFromRequest(Request $request, bool $update = true):?View{
        $validation = self::validate($request, $update);
        if ($validation["status"] == "danger") {
            return view("components.alert", ["status" => "danger", "message" => $validation["message"]]);
        }
        
        // Custom key value for model without incrementing
        $tag_category = TagCategory::where("name", $request->tag_category)->first();
        
        if(is_null($tag_category)){
            $tag_category = TagCategory::create(["name" => $request->tag_category]);
        }
        
        $request->merge(["tag_category_id" => $tag_category->id]);
        
        // Fill the model with the request
        $this->fill($request->all());
        
        // If the model is dirty, save it
        if($this->isDirty()){
            $this->save();
        }
        
        return view("components.alert", ["status" => "success", "message" => "Tag salvato", "beforeshow" => 'modal.hide(); htmx.ajax("post", "'.route("tags.list").'", "#tags")']);
    }
    
    public static function validate(Request $request, bool $update):array{
        $validator = Validator::make($request->all(), [
            self::getModelKey() => [$update ? "exists:App\Models\\".class_basename(new self).",".self::getModelKey() : "prohibited"],
			"name" => ['required'],
			"tag_category" => ['required'],
			"background_color" => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
			"text_color" => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
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