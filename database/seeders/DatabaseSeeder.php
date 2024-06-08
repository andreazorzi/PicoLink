<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Url;
use App\Models\Short;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
        
        if(config("app.env") == "local"){
            $shorts = Short::factory(50)->create();
            
            foreach($shorts as $short){
                foreach([null, 'en'] as $language){
                    $url = Url::factory(1)->recycle($short)->create(is_null($language) ? ['language' => $language] : []);
                }
            }
        }
    }
}
