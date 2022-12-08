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
        $cikURL = "https://www.sec.gov/files/company_tickers.json";
        $cikJson = file_get_contents($cikURL);
        $companies = json_decode($cikJson, true);

        $sicJson = file_get_contents(storage_path('cik_sic_data.json'));
        $sicNumbers = json_decode($sicJson, true);

        foreach ($companies as $company) 
        {
            foreach ($sicNumbers as $numbers)
            {
                foreach ($numbers as $cik => $sic)
                {
                    if (strval($cik) == $company['cik_str'])
                    {
                        return $sic;
                    }
                }
            }

            Company::query()->updateOrCreate([
                'cik_number' => $company['cik_str'],
                'sic_number' => $sic,
                'stock_symbol' => $company['ticker'],
                'company_title' => $company['title']
            ]);
        }
    }
}
