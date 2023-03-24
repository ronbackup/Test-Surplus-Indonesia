<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3 ; $i++) { 
            CategoryProduct::create([
                'product_id' => $i,
                'category_id' => $i
            ]);
        }
    }
}
