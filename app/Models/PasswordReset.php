<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordReset extends Model
{
    protected $primaryKey = 'token';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded  = ['no_key'];
    
    public static function makeResetLink(User $user):string{
        self::disableUserActiveLink($user);
		
		$expiration = date("Y-m-d H:i:s", strtotime("+1 day"));
		$token = self::generateToken($user, $expiration);
        
        $reset_link = self::create([
            "token" => $token,
            "user" => $user->username,
            "expiration" => $expiration
        ]);
		
		return route("web-auth.reset-password", [$reset_link]);
	}
    
    public function changePassword(Request $request):View{
        if(!$this->isValid()){
			return view("components.alert", ["status" => "danger", "message" => "Il link non è valido o è scaduto", "callback" => 'modal.hide(); htmx.trigger("#page", "change");']);
		}
		
		Validator::make($request->all(), [
			"password" => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
		])->validateWithBag('reset_password');
		
		$user = User::find($this->user);
		
		if(!is_null($user)){
			$user->password = Hash::make($request->password);
			$user->save();
		}
		
		self::disableUserActiveLink($user);
		
		return view("components.alert", ["status" => "success", "message" => "Password aggiornata", "callback" => 'window.location.href = "'.route("backoffice.index").'";']);
    }
    
    public function isValid():bool{
        return strtotime($this->expiration) > time();
    }
    
    private static function generateToken(User $user, string $expiration):string{
        return hash_hmac("sha256", $user->username.$expiration, config("app.key"));
    }
    
    private static function disableUserActiveLink(User $user):void{
        self::where("user", $user->username)->where("expiration", ">=", date("Y-m-d H:i:s"))->update(["expiration" => date("Y-m-d H:i:s")]);
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
