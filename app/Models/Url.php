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

class Url extends Model
{
	use HasFactory;
    
    public $timestamps = false;

    protected $guarded  = ['no_key']; // set guarded columns, set to no_key to avoid problems
    
    public function visits(){
        return $this->hasMany(Visit::class);
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