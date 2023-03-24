<?php

namespace Database\Seeders;

use App\Models\ProductImage;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3 ; $i++) { 
            ProductImage::create([
                'product_id' => $i,
                'image_id' => $i
            ]);
        }
    }
}
