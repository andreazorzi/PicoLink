<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Url;
use App\Models\Short;
use App\Models\Visit;
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
                $urls = [];
                
                foreach([null, 'en'] as $language){
                    $urls[] = Url::factory(1)->recycle($short)->create(is_null($language) ? ['language' => $language] : []);
                }
                
                $visits = Visit::factory(rand(5, 20))->recycle($short)->recycle(fake()->randomElement($urls))->create();
            }
        }
    }
}
