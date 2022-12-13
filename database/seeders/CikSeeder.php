<?php

namespace Database\Seeders;

use App\Models\Cik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sicJson = file_get_contents(storage_path('cik_sic_data.json'));
        $cikIndustries = json_decode($sicJson, true);

        foreach ($cikIndustries as $cikInds) {
            foreach ($cikInds as $cik => $sic) {
                (preg_match("([a-z])i", $sic)) ? $sic = null : $sic;

                if ($sic != null) {
                    $sicQuery = DB::select("SELECT id FROM sics WHERE sic_code = $sic");

                    foreach ($sicQuery as $id) {
                        foreach ($id as $key => $value) {
                            $sicId = $value;
                        }
                    }

                    Cik::query()->updateOrCreate([
                        'sic_id' => $sicId,
                        'cik_number' => $cik,
                    ]);
                } else {
                    continue;
                }
            }
        }
    }
}
