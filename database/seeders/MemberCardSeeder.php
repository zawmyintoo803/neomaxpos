<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MemberCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cards = [];
        $date = now()->format('ymd'); // Example: 251018 for Oct 18, 2025

        for ($i = 1; $i <= 10; $i++) {
            $cards[] = [
                'card_number' => 'MC' . $date . str_pad($i, 4, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('member_cards')->insert($cards);
    }
}
