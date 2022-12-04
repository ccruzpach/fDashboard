<?php

namespace Database\Seeders;

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
        
        $url = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list';


        $results = getSICData($url);

        foreach ($results as $result)
        {
            Company::query()->updateOrCreate([
                'sector' => $result['sector']                
            ]);
        }


    }
}
