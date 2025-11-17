<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('suppliers')->insert([
            [
                'name'             => 'ABC Trading Co.',
                'phone'            => '09123456789',
                'email'            => 'info@abctrading.com',
                'contact_person'   => 'U Aung Kyaw',
                'address'          => 'No.12, Insein Road, Yangon',
                'supplier_type_id' => 1,
                'division_id'      => '01',  // Yangon
                'township_id'      => '0101', // Insein
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'name'             => 'Golden Supplier Ltd.',
                'phone'            => '09988776655',
                'email'            => 'sales@goldensupplier.com',
                'contact_person'   => 'Daw Mya Mya',
                'address'          => 'Mandalay Downtown',
                'supplier_type_id' => 2,
                'division_id'      => '02',  // Mandalay
                'township_id'      => '0203', // Chanayethazan
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'name'             => 'Universe Distribution',
                'phone'            => '09777788899',
                'email'            => 'contact@universe.com',
                'contact_person'   => 'U Kyaw Zin',
                'address'          => 'Nay Pyi Taw',
                'supplier_type_id' => 1,
                'division_id'      => '03',  // Nay Pyi Taw
                'township_id'      => '0302', // Dekkhina Thiri
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
        ]);
    }
}
