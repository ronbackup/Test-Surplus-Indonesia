<?php

namespace Database\Seeders;

use App\Models\Image;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5 ; $i++) { 
            Image::create([
                'name' => 'Image '.$i,
                'file' => 'file-ke-'.$i,
                'enable' => true,
            ]);
        }
    }
}
