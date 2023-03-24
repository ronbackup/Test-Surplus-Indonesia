<?php

namespace Database\Seeders;

use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5 ; $i++) { 
            Product::create([
                'name' => 'Product '.$i,
                'description' => 'Description '.$i,
                'enable' => true
            ]);
        }
    }
}
