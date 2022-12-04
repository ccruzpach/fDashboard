<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = "https://www.sec.gov/files/company_tickers.json";
        $json = file_get_contents($url);
        $companies = json_decode($json, true);

        foreach ($companies as $company)
        {
            Company::query()->updateOrCreate([
                'cik_number' => $company['cik_str'],
                'stock_symbol' => $company ['ticker'],
                'company_title' => $company['title']

            ]);
        }
    }
}
