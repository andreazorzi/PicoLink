<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add admin user
        if(is_null(config("auth.admin.username"))){
            abort(500, "Admin user not set");
        }
        
        $admin = User::create([
            'username' => config("auth.admin.username"),
            'password' => Hash::make(config("auth.admin.password")),
            'email' => config("auth.admin.email"),
            'name' => config("auth.admin.name"),
            'groups' => explode(",", config("auth.admin.groups")),
            'type' => 'local',
            'enabled' => false,
        ]);
    }
}
