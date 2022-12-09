<?php

namespace Database\Seeders;

use App\Models\CompanyIndustry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyIndustrySeeder extends Seeder
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
                    $classificationQuery = DB::select("SELECT id FROM classifications WHERE sic_code = $sic");

                    foreach ($classificationQuery as $id) {
                        foreach ($id as $key => $value) {
                            $classificationId = $value;
                        }
                    }

                    CompanyIndustry::query()->updateOrCreate([
                        'classification_id' => $classificationId,
                        'cik_number' => $cik,
                    ]);
                } else {
                    continue;
                }
            }
        }
    }
}
