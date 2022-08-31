<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 5; $i++) { 
            $catalog = new Catalog;

            $catalog->name = $faker->randomElement(['buku anak', 'buku dewasa','cerita rakyat','buku sejarah','buku pelajaran']);

            $catalog->save();
        }    
    }
}
