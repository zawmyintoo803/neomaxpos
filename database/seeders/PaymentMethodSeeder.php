<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            ['payment_name' => 'Cash', 'created_at'=>now(), 'updated_at'=>now()],
            ['payment_name' => 'Credit Card', 'created_at'=>now(), 'updated_at'=>now()],
            ['payment_name' => 'Mobile Payment', 'created_at'=>now(), 'updated_at'=>now()],
        ]);
    }
}
