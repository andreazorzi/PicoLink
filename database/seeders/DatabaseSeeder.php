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
            $shorts = Short::factory(10)->create();
            
            foreach($shorts as $short){
                $urls = [];
                
                foreach([null, 'en', 'de'] as $language){
                    $urls[] = Url::factory(1)->recycle($short)->create(['language' => $language]);
                }
                
                $visits = Visit::factory(rand(20,30))->recycle($short)->recycle($urls[0])->create();
                $visits = Visit::factory(rand(20,30))->recycle($short)->recycle($urls[1])->create();
                $visits = Visit::factory(rand(20,30))->recycle($short)->recycle($urls[2])->create();
            }
        }
    }
}
