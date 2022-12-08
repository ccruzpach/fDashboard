<?php

namespace Database\Seeders;

use App\Models\FinancialSector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialSectorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(storage_path('SICData.json'));
        $sectors = json_decode($json, true);

        foreach ($sectors as $sector)
        {
            FinancialSector::query()->updateOrCreate([
                'sector' => str_replace("Office of ", "", $sector['sector'])
            ]);
        }
    }
}
