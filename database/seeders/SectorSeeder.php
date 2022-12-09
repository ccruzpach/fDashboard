<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
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

        foreach ($sectors as $sector) {
            Sector::query()->updateOrCreate([
                'sector_name' => str_replace("Office of ", "", $sector['sector'])
            ]);
        }
    }
}
