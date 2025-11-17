<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Cash'],
            ['name' => 'Credit'],
            ['name' => 'Member'],
            ['name' => 'VIP'],
            ['name' => 'Dealer'],
            ['name' => 'Employee'],
            ['name' => 'Online'],
        ];

        DB::table('customer_types')->insert($types);
    }
}
