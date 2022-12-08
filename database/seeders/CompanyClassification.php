<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyClassification;


class CompanyClassificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(storage_path('SICData.json'));
        $companies = json_decode($json, true);

        foreach ($companies as $company)
        {
            CompanyClassification::query()->updateOrCreate([
                'sic_number' => $company['cik_str'],
                'cik_number' => $company['cik_number']
            ]);
        }
    }
}
