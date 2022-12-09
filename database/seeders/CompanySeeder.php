<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        foreach ($companies as $company)
        {
            $cik = $company['cik_str'];
            $industryQuery = DB::select("SELECT id FROM company_industries WHERE cik_number = $cik");

            foreach ($industryQuery as $id) {
                foreach ($id as $key => $value) {
                    $industryId = $value;
                }
            }

            Company::query()->updateOrCreate([
                'company_industry_id' => $industryId,
                'stock_symbol' => $company['ticker'],
                'company_title' => $company['title']

            ]);
        }
    }
}
