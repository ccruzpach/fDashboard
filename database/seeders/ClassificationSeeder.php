<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(storage_path('cik_sic_data.json'));
        $companies = json_decode($json, true);

        foreach ($companies as $company)
        {
            Classification::query()->updateOrCreate([
                'sic_number' => $company['cik_str'],
                'cik_number' => $company['cik_number']
            ]);
        }
    }
}
