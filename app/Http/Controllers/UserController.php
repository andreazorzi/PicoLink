<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordReset;

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
    public function change_password(Request $request, PasswordReset $reset_link){
        return $reset_link->changePassword($request);
    }
}
