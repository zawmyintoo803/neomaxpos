<?php

namespace Database\Seeders;

use App\Models\SupplierType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SupplierTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Wholesaler', 'description' => 'Supplies products in bulk quantities.'],
            ['name' => 'Retailer', 'description' => 'Sells directly to end customers.'],
            ['name' => 'Distributor', 'description' => 'Distributes goods between suppliers and retailers.'],
            ['name' => 'Manufacturer', 'description' => 'Produces goods or raw materials.'],
            ['name' => 'Importer', 'description' => 'Imports goods from foreign markets.'],
            ['name' => 'Exporter', 'description' => 'Exports products to other countries.'],
        ];

        foreach ($types as $type) {
            SupplierType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
