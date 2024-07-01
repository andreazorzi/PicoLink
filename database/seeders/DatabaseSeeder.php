<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tag;
use App\Models\Url;
use App\Models\Short;
use App\Models\Visit;
use App\Models\TagCategory;
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
            $tag_categories = TagCategory::factory(3)->create();
            $tags = Tag::factory(15)->recycle($tag_categories)->create();
            
            $shorts = Short::factory(10)->create();
            
            foreach($shorts as $short){
                $urls = [];
                
                foreach([null, 'en', 'de'] as $language){
                    $urls[] = Url::factory(1)->recycle($short)->create(['language' => $language]);
                }
                
                $visits = Visit::factory(rand(5,10))->recycle($short)->recycle($urls[0])->create();
                $visits = Visit::factory(rand(5,10))->recycle($short)->recycle($urls[1])->create();
                $visits = Visit::factory(rand(5,10))->recycle($short)->recycle($urls[2])->create();
                
                $short->tags()->sync($tags->random(rand(1,3))->pluck('id')->toArray());
            }
        }
    }
}
