<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Township;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionandTownshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'Yangon' => ['Dagon', 'Kamayut', 'Lanmadaw', 'Mayangone'],
            'Mandalay' => ['Chanayetharzan', 'Pyigyidagun', 'Aungmyethazan'],
            'Naypyidaw' => ['Ottara', 'Dekkhina', 'Pyinmana'],
            'Bago' => ['Taungoo', 'Pyay', 'Bago'],
            'Sagaing' => ['Shwebo', 'Monywa', 'Katha'],
            'Magway' => ['Pakokku', 'Chauk', 'Magway'],
            'Ayeyarwady' => ['Pathein', 'Hinthada', 'Maubin'],
            'Tanintharyi' => ['Dawei', 'Myeik', 'Palaw'],
            'Kayin' => ['Hpa-An', 'Myawaddy', 'Kawkareik'],
            'Kayah' => ['Loikaw', 'Demoso', 'Hpruso'],
            'Mon' => ['Mawlamyine', 'Thaton', 'Kyaikto'],
            'Rakhine' => ['Sittwe', 'Thandwe', 'Mrauk U'],
            'Chin' => ['Hakha', 'Falam', 'Mindat']
        ];

        foreach($locations as $divisionName => $townships){
            $division = Division::create(['name' => $divisionName]);
            foreach($townships as $town){
                Township::create([
                    'division_id' => $division->id,
                    'name' => $town
                ]);
            }
        }
    }
}
