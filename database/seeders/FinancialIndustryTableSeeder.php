<?php

namespace Database\Seeders;

use App\Models\FinancialIndustry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialIndustryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(public_path('SICData.json'));
        $industries = json_decode($json, true);

        $sector = 0;

        foreach ($industries as $industry)
        {
            if (str_contains($industry['sector'], "Industrial"))
            {
                $sector = 1;
            } elseif (str_contains($industry['sector'], "Energy"))
            {
                $sector = 2;
            } elseif (str_contains($industry['sector'], "Estate"))
            {
                $sector = 3;
            }
            elseif (str_contains($industry['sector'], "Manufacturing"))
            {
                $sector = 4;
            }
            elseif (str_contains($industry['sector'], "Life"))
            {
                $sector = 5;
            }
            elseif (str_contains($industry['sector'], "Technology"))
            {
                $sector = 6;
            }
            elseif (str_contains($industry['sector'], "Trade"))
            {
                $sector = 7;
            }
            elseif (str_contains($industry['sector'], "Finance"))
            {
                $sector = 8;
            }
            elseif (str_contains($industry['sector'], "Structured"))
            {
                $sector = 9;
            }
            elseif (str_contains($industry['sector'], "International"))
            {
                $sector = 10;
            }


            //TODO: FIGURE OUT FOREIGN KEY CONNECTION
            FinancialIndustry::query()->updateOrCreate([
                'sic_code' => $industry['sic_code'],                
                'id' => $sector,
                'industry' => str_replace("Office of ", "", $industry['industry'])
            ]);
        }
    }
}
