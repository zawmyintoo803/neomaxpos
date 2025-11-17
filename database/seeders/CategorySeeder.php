<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name'=>'Electronics'],
            ['category_name'=>'Clothing'],
            ['category_name'=>'Accessories'],
            ['category_name'=>'Home & Kitchen'],
        ];

        foreach($categories as $cat) {
            Category::create($cat);
        }
    }
}
