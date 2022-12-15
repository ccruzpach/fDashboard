<?php 

require public_path('/services/CompanyInfoQueries.php');
require public_path('/services/4.extractEdgarFillingsUrls.php');
require public_path('/services/XlsxFillings.php');

use App\Models\Cik;
use App\Models\Sic;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Company;

set_time_limit(90000);



// $path1 = storage_path('/fillings/320193/10K/320193_10K_20221028.xlsx');
// $tables = extractExcelTables($path1);
// dd($tables);

$time_start = microtime(true);
$ciks = DB::select("SELECT cik_number FROM ciks");

foreach ($ciks as $cik)
{
    getXlsFillings($cik->cik_number, '10-K', 20050101);
};
$time_end = microtime(true);
$time = $time_end - $time_start;

echo " Operational Time: $time"




//Consolidated Statements of Operations

//Consolidated Statements of Comprehensive Income
//Consolidated Statements of Cash Flows
//Consolidated Balance Sheet

//Consolidated Stateemnt of Shareholder's Equity


?>


{{-- 
<!DOCTYPE html>

<title>Document</title>
<link rel="stylesheet" href="/app.css">

<body>
    <form action="" method="get" style="margin: 30px auto; width: 310px">
        <label for="search" style="margin-right: 2px;">Search Company</label>
        <input type="text" id="search" name="search" placeholder="Symbol">
    </form>

    <?php
        $companySymbol = strtoupper($_GET['search']);
        $companyCik = getCompanyCIK(strtoupper($companySymbol))->cik_number;
    ?>

    <div id="company_highlights" style="width: 1000px; margin: 30px auto ; border: 1px solid black; padding: 0px; border-radius: 15px;">
        <div style="display:flex; justify-content: space-between; padding: 0px 10px 0 10px;">
            <div>
                <p>Company: <strong>{{ getCompanyName($companySymbol)->company_title }}</strong> <sup>({{ $companySymbol }})</sup></p>
            </div>
            <div>
                <p>Industry/Sector:
                    <strong>{{ ucwords(strtolower(getCompanyIndustry($companySymbol)->industry_name)) }} | {{ getCompanySector($companySymbol)->sector_name }}
                    </strong>
                </p>
            </div>
        </div>
        <div style="padding: 0px 10px 0 10px;">
            <h4>Highlights</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Vulputate odio ut enim blandit volutpat maecenas volutpat. Vestibulum rhoncus est pellentesque elit. Suspendisse sed nisi lacus sed viverra tellus in hac habitasse. Fringilla urna porttitor rhoncus dolor. In aliquam sem fringilla ut morbi tincidunt augue interdum velit. A iaculis at erat pellentesque adipiscing commodo. Lacus luctus accumsan tortor posuere ac ut. In eu mi bibendum neque egestas. Mauris sit amet massa vitae tortor condimentum.</p>
            <p>In metus vulputate eu scelerisque felis. Eu feugiat pretium nibh ipsum consequat nisl vel. Accumsan tortor posuere ac ut consequat semper viverra. Volutpat lacus laoreet non curabitur gravida arcu. In pellentesque massa placerat duis ultricies lacus sed. Orci dapibus ultrices in iaculis nunc sed. Pellentesque massa placerat duis ultricies lacus sed turpis. Felis bibendum ut tristique et egestas quis. Egestas maecenas pharetra convallis posuere morbi. Et malesuada fames ac turpis egestas sed tempus urna et. Aliquam ut porttitor leo a diam sollicitudin tempor. Magna fringilla urna porttitor rhoncus dolor.</p>
        </div>
    </div>

    <div style="display: flex; width: 1000px; justify-content: space-between; margin: 0 auto; padding: 10px;">

        <div id = "company_financials" style="width: 750px; height: 500px; border: 1px solid black; padding: 10px; border-radius: 15px; overflow-y: auto;">
            <h4>Finances</h4>
        </div>
    
        <div id="company_fillings" style="width: 200px; height: 500px; border: 1px solid black; padding: 10px; border-radius: 15px;overflow-y:auto">
            <h4>Recent fillings</h4>
            <?php
                $results = getAllFillingsListByCompany($companyCik, 20050101);
            ?>

            <ul>
                <?php
                    foreach ( $results as $key => $value )
                    {
                    echo "<li><a href='$value'>$key</a></li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</body>

 --}}
