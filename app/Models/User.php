<?php

namespace App\Models;

use App\Mail\SendEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Routing\Route;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'username';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded  = ['no_key'];
    
    public static function getTableFields():array{
        return [
            "username" => [
                "filter" => true,
                "sort" => "asc"
            ],
            "name" => [
                "filter" => true
            ],
            "email" => [
                "filter" => true
            ],
            "type" => [
                "filter" => true,
                "custom-label" => "Tipo",
            ],
            "status" => [
                "filter" => true,
                "custom-value" => "getStatusText",
                "custom-filter" => "CASE WHEN enabled = 1 THEN 'attivo' ELSE 'disattivo' END"
            ],
        ];
    }
    
    public function getTableActions($model_name, $model_key, $key):array{
        return [
            // Default action
            [
                "custom-attributes" => 'data-id="'.$key.'" hx-post="'.route($model_name.".details", [$this]).'" hx-target="#modal .modal-content"',
                "icon" => '<i class="table-search-preview fa-solid fa-pen"></i>'
            ]
        ];
    }
    
    public function getStatusText():string{
        return '<span class="text-'.($this->enabled ? "success" : "danger").'">'.($this->enabled ? "Attivo" : "Disattivo").'</span>';
    }
    
    public static function current():?User{
        return Auth::user();
    }
    
    public function password_resets(){
        return $this->hasMany(PasswordReset::class, "user");
    }
    
    public function hasRole($roles):bool{
        $inter = array_intersect($this->groups, $roles);
        
        return count($inter) > 0;
    }
    
    public function canAccessRoute(Route $route):bool{
        $middlewares = null;
        
        foreach($route->action["middleware"] ?? [] as $role){
            if(strpos($role, "role:") === false) continue;
            
            $middlewares = explode(",", explode(":", $role)[1]);
        }
        
        return !(!is_null($middlewares) && !User::current()->hasRole($middlewares));
    }
    
    public function isAdmin():bool{
        return $this->hasRole(explode(",", config("auth.authentik.administrators")));
    }
    
    public function checkAuthGroups():bool{
        // If no auth groups sets, anyone can access
        if(config("auth.authentik.groups") == ""){
            return true;
        }
        
        // Get all auth groups and add administrator group
        $auth_groups = array_merge(explode(",", config("auth.authentik.groups")), explode(",", config("auth.authentik.administrators")));
        
        return $this->hasRole($auth_groups);
    }
    
    public function sendResetPasswordEmail($force = false):bool{
        if($this->enabled == 0) return false;
        
        if(($this->password_resets()->orderBy("created", "desc")->first()?->isValid() ?? false) && !$force) return false;
        
        $mail_data = [
            "sender" => [
                "email" => config("mail.from.address"),
                "name" => config("mail.from.name")
            ],
            "receivers" => [
                [
                    "email" => App::environment('local') ? config("mail.mail-test") : $this->email,
                ]
            ],
            "subject" => config("app.name").": resetta la password dell'account",
            "body" => [
                "view" => "emails.reset-password",
                "parameters" => [
                    "user" => $this,
                    "reset-link" => PasswordReset::makeResetLink($this)
                ]
            ],
        ];
        
        Mail::send(new SendEmail($mail_data));
        
        return true;
    }
    
    public static function createFromRequest(Request $request):View{
        return (new self)->updateFromRequest($request, false);
    }
    
    public function updateFromRequest(Request $request, bool $update = true):?View{
        $validation = self::validate($request, $update);
        if ($validation["status"] == "danger") {
            return view("components.alert", ["status" => "danger", "message" => $validation["message"]]);
        }
        
        if(!$this->incrementing && !$update){
            $request->merge(['groups' => ["local"]]);
        }
        
        $key = array_intersect_key($request->all(), array_flip([self::getModelKey()]));
        
        $model = $this->updateOrCreate(
            $key,
            array_diff_assoc($request->all(), $key)
        );
        
        if(!$update){
            $model->sendResetPasswordEmail();
        }
        
        return view("components.alert", ["status" => "success", "message" => "User salvato", "callback" => 'modal.hide(); htmx.trigger("#page", "change");']);
    }
    
    public static function validate(Request $request, bool $update):array{
        $validator = Validator::make($request->all(), [
            self::getModelKey() => ['required', ($update ? "exists" : "unique").":App\Models\\".class_basename(new self).",".self::getModelKey()],
			"enabled" => ['required'],
			"name" => ['required'],
			"email" => ['required', 'email:rfc,dns'],
		]);
        
        if ($validator->fails()) {
			return ["status" => "danger", "message" => implode("\\n", $validator->errors()->all())];
		}
        
        return ["status" => "success"];
    }
    
    // Ovverride for Authenticatable: necessary to provide the correct password key
    // Only for website authentication
    public function getAuthPassword(){
        return $this->password;
    }
    
    public static function getModelAttributes():array{
        return Schema::getColumnListing((new self)->getTable());
    }
    
    public static function getModelKey():string{
        return (new self)->getKeyName();
    }

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'groups' => 'array',
        'enabled' => 'boolean',
    ];
}
