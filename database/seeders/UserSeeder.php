<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('123456'),
            'role_id' => '1',
        ]);

        User::create([
            'name' => 'Cashier One',
            'email' => 'cashier@pos.com',
            'password' => Hash::make('123456'),
            'role_id' => '4',
        ]);
    }
}
