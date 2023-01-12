<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
require_once public_path('/services/CompanyInfoQueries.php');
require_once public_path('/services/4.extractEdgarFillingsUrls.php');
require_once public_path('/services/XlsxFillingProcessor.php');
require_once public_path('/services/getSECFillings.php');
require_once public_path('/services/FillingListByCompany.php');


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SectorSeeder::class,
            IndustrySeeder::class,
            SicSeeder::class,
            CikSeeder::class,
            CompanySeeder::class,
            Filling10kSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
