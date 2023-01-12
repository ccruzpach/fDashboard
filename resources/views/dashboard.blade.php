<?php 

require_once public_path('/services/CompanyInfoQueries.php');
require_once public_path('/services/4.extractEdgarFillingsUrls.php');
require_once public_path('/services/XlsxFillingProcessor.php');
require_once public_path('/services/getSECFillings.php');
require_once public_path('/services/FillingListByCompany.php');


//BOBA 4UT (slightly hard, Deep sound, less clacky)
//Holy Pandas (Softer, more clacky)
use App\Models\Filling10k;
use App\Models\Cik;
use App\Models\Sic;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Company;
//"320193" 
$cikNumber = getCompanyCIK('AAPL')->cik_number;
// dd(getFillingDocument($cikNumber, '10-K', 20050101));
// dd(getFillingDatesFromFillingsList($cikNumber, '10-K', 20050101));
// dd(getXlsFillings($cikNumber, '10-K', 20050101));

$cikData = DB::select("SELECT id, cik_number FROM ciks");
$cikNumbers = [];

foreach ($cikData as $cik) 
{

    // dd(gettype($cik->id), gettype($cik->cik_number));
    $cikNumbers[] = $cik->cik_number;
}

file_put_contents('cikNumbers.json', json_encode($cikNumbers));
echo "All Done"




//TODO: Separate Processes for 10KFillings Table:
// 1. Grab all links;
// 2. Grab all content from links;
// 3. Push content to DB;


// Database\Seeders\SectorSeeder ............................................................................................................ RUNNING  
// Database\Seeders\SectorSeeder ...................................................................................................... 83.89 ms DONE  

// Database\Seeders\IndustrySeeder .......................................................................................................... RUNNING  
// Database\Seeders\IndustrySeeder ................................................................................................. 1,180.56 ms DONE  

// Database\Seeders\SicSeeder ............................................................................................................... RUNNING  
// Database\Seeders\SicSeeder ...................................................................................................... 3,260.46 ms DONE  

// Database\Seeders\CikSeeder ............................................................................................................... RUNNING  
// Database\Seeders\CikSeeder ..................................................................................................... 24,404.33 ms DONE  

// Database\Seeders\CompanySeeder ........................................................................................................... RUNNING  
// Database\Seeders\CompanySeeder ................................................................................................. 50,902.79 ms DONE  

//ONLY 10 companies used, fillings dating from 2005 => 1.5MB vs 423MB.
// Database\Seeders\Filling10kSeeder ........................................................................................................ RUNNING  
// Database\Seeders\Filling10kSeeder ............................................................................................. 279,995.69 ms DONE


//ONLY 10 companies, fillings dating from 2015;
// Database\Seeders\Filling10kSeeder ........................................................................................................ RUNNING  
// Database\Seeders\Filling10kSeeder ............................................................................................. 371,705.47 ms DONE  





?>



{{--

<!DOCTYPE html>

<title>Document</title>
<link rel="stylesheet" href="/app.css">

<body>

</body>


<body>
    <form action="" method="get" style="margin: 30px auto; width: 310px">
        <label for="search" style="margin-right: 2px;">Search Company</label>
        <input type="text" id="search" name="search" placeholder="Symbol">
    </form>

    <?php
        
        $companySymbol = strtoupper($_GET['search'] ?? null);
        
        if (empty($companySymbol))
        {
            $companySymbol = 'AAPL';
        }

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

    <div id="company_financials" style="width: 750px; height: 500px; border: 1px solid black; padding: 10px; border-radius: 15px; overflow-y: auto;">
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
