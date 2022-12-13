<?php

namespace Database\Seeders;

use App\Models\Sic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SicSeeder extends Seeder
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

                if ($sic != null)
                {
                    $indIdQuery = DB::select("SELECT id FROM industries WHERE sic_code = $sic");
                    
                    foreach ($indIdQuery as $id) {
                        foreach ($id as $key => $value) {
                            $industryId = $value;
                        }
                    }
                    Sic::query()->updateOrCreate([
                        'sic_code' => $sic,
                        'industry_id' => $industryId
                    ]);
                } else {
                    continue;
                }
            }
        }
    }
}
