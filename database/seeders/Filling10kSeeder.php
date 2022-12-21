<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Filling10kSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Read filepaths from subfolders
        $fileRoutes = [];
        $di = new RecursiveDirectoryIterator(storage_path('fillings'));
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            if (str_contains($filename, "_10K_")) {
                $fileRoutes[] = $file->getRealPath();
            }
        }

        //Separate tables needed
        for ($j = 0; $j < count($fileRoutes); $j++) {
            $extractedTables = extractExcelTables($fileRoutes[1]);
            dd($extractedTables);
            $tempArray = [];

            //TODO: DECIDE WHAT TABLES ARE NEEDED
            // Check for the tables you need and send them to extractedTables;
            for ($i = 0; $i < count($extractedTables); $i++)
            {
                switch (true) {
                    case (str_contains($extractedTables[$i], "consolidated_statements_of_income")
                        and
                        !preg_match('/condensed|derivative|additional|components|parenthetical|suplementary/', $extractedTables[$i])
                    ):
                        $tempArray[] = $extractedTables[$i];
                        break;
                    case (str_contains($extractedTables[$i], "statements_of_operations")
                        and
                        !preg_match('/derivative|condensed|realized|dte|wes|issued|(details)|(detail)|parenthetical|restatement/i', $extractedTables[$i])
                    ):
                        $tempArray[] = $extractedTables[$i];
                        break;
                    case (str_contains($extractedTables[$i], "statements_of_cash_flows")
                        and
                        !preg_match('/condensed|restatement|restated|parenthetical|(detail)|(details)|supplemental|dte|wes|reconciliation/', $extractedTables[$i])
                    ):
                        $tempArray[] = $extractedTables[$i];
                        break;
                    case (str_contains($extractedTables[$i], "balance_sheets")
                        and
                        !preg_match('/parenthetical|(detail)|(details)|wes|components/', $extractedTables[$i])
                    ):
                        $tempArray[] = $extractedTables[$i];
                        break;
                    case (str_contains($extractedTables[$i], "_shareholders'_equity")
                        and
                        !preg_match('/parenthetical/i', $extractedTables[$i])
                    ):
                        $tempArray[] = $extractedTables[$i];
                        break;
                }
            }

            $selectedTables[] = $tempArray;

            if ($j == 500) {
                break;
            }
        }

        //Push desired tables to database
    }
}
