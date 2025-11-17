<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = ['Piece', 'Pack', 'Box', 'Kg', 'Liter', 'Meter'];

        foreach ($units as $u) {
            Unit::create([
                'unit_name' => $u,
            ]);
        }
    }
}
