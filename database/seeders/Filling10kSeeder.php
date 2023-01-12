<?php

namespace Database\Seeders;

use App\Models\Filling10k;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // $cikQuery = DB::select("SELECT id, cik_number FROM ciks");
        $json = file_get_contents('cikNumbers.json');
        $ciks = json_decode($json, true);

        foreach ($ciks as $cik) {
            $cikId = 1;
            $cikNumber = $cik;

            $fillings = getFillingDocument($cikNumber, '10-K', 20150101);

            foreach ($fillings as $content => $date) {
                $fillingContent = $content;
                $fillingContent = getHtmlContent($fillingContent);
                $fillingDate = $date;
    
                Filling10k::query()->updateOrCreate([
                    'cik_id' => $cikId,
                    'filling_date' => $fillingDate,
                    'filling_content' => $fillingContent
                ]);
            }
            $cikId++;
        }
    }

    // public function run()
    // {

    //     $cikValues = DB::select("SELECT id, cik_number FROM ciks");

    //     for ($i=0; $i < count($cikValues); $i++) { 
    //         $cik = $cikValues[$i];

    //         $fillingUrls = getFillingDocument($cik->cik_number, '10-K', 20150101);

    //         for ($j=0; $j < count($fillingUrls); $j++) { 
    //             $fillinContent = parseFillingUrl($fillingUrls[$j]);

    //             Filling10k::query()->updateOrCreate([
    //                 'cik_id' => $cik->id,
    //                 'filling_date' => $fillinContent['date'],
    //                 'filling_content' => $fillinContent['content']
    //             ]);
    //         }

    //     }
    // }
}

// function parseFillingUrl($fillingUrlDate) {
//     $url = $fillingUrlDate[0];
//     $date = $fillingUrlDate[1];
//     $content = getHtmlContent($url);

//     return [
//         'content' => $content,
//         'date' => $date
//     ];
// }