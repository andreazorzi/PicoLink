<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // User details
    public function details(Request $request, ?User $user = null){
		return view('components.backoffice.modals.user-data', ["user" => $user, "type" => $request->type]);
    }
    
    // Create user
    public function create(Request $request){
        return User::createFromRequest($request);
    }
    
    // Update user
    public function update(Request $request, User $user){
        return $user->updateFromRequest($request);
    }
    
    // Delete user
    public function delete(Request $request, User $user){
        $username = $user->username;
        $user->delete();
        return view("components.alert", ["status" => "success", "message" => "Utente ".$username." eliminato", "callback" => 'modal.hide(); htmx.trigger("#page", "change");']);
    }
    
    // Send user reset password
    public function send_reset_password(Request $request, $user = null){
        $user = User::find($user);
        $admin_request = !is_null(User::current()) && User::current()->isAdmin();
        
        if(!is_null($user)){
            $user->sendResetPasswordEmail($admin_request);
        }
        
        return view("components.alert", ["status" => "success", "message" => $admin_request ? "Email inviata" : "Riceverai a breve un'email per resettare la password"]);
    }
    
    // User change password
    public function change_password(Request $request){
        $user = User::current();
        
        if(!Hash::check($request->current_password, $user->password)){
            return view("components.alert", ["status" => "danger", "message" => __("auth.password", ["attribute" => __("validation.attributes.current_password")])]);
        }
        
        $validator = Validator::make($request->all(), [
            "password" => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
        ]);
        
        if($validator->fails()){
            return view("components.alert", ["status" => "danger", "message" => implode('\\n', $validator->errors()->all())]);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return view("components.alert", ["status" => "success", "message" => "Password aggiornata", "callback" => 'window.location.href = "'.route("backoffice.index").'";']);
    }
}
