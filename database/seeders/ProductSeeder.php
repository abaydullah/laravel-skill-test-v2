<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      for($i = 1; $i <= 10; $i++){
          $product = Product::create([
              'product_name' => 'Product '.$i,
              'price_per_item' => rand(50, 100),
              'quantity_in_stock' => rand(1,50),
          ]);
      }
    }
}
