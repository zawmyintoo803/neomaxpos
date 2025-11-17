<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach($products as $prod){
            ProductImage::create([
                'product_id' => $prod->id,
                'image_url' => 'images/product'.$prod->id.'.jpg',
            ]);
        }
    }
}
