<?php

namespace Database\Seeders;

use App\Models\CompanyClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyClassification extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [];

        foreach ($companies as $company)
        {
            CompanyClassification::query()->updateOrCreate([
                'sic_number' => $company['cik_str'],


            ]);
        }
    }
}
