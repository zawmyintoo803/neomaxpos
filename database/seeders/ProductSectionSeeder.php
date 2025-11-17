<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSection;

class ProductSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['name'=>'Hot Deals','description'=>'Best discounted products for you'],
            ['name'=>'Featured Products','description'=>'Top products featured in our store'],
            ['name'=>'New Arrivals','description'=>'Check out the latest products'],
        ];

        foreach($sections as $sec) {
            ProductSection::create($sec);
        }
    }
}
