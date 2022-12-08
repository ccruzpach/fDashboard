<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
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

        foreach ($companies as $company) {
            Company::query()->updateOrCreate([
                'cik_number' => $company['cik_str'],
                'stock_symbol' => $company['ticker'],
                'company_title' => $company['title']

            ]);
        }
    }
}
