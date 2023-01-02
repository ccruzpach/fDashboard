<?php 

require public_path('/services/CompanyInfoQueries.php');
require public_path('/services/4.extractEdgarFillingsUrls.php');
require public_path('/services/XlsxFillingProcessor.php');
require public_path('/services/XlsxFillings.php');

use App\Models\Cik;
use App\Models\Sic;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Company;

// set_time_limit(15000);
// $cikNumber = '320193';
// $cikNumber = '2488';
// $cikNumber = '78003';

$cikNumber = getCompanyCIK('F')->cik_number;

$content = extractDOM(getHtmlContent(createSearchUrl($cikNumber, '10-K', 20050101)));
$results = extracHtmlByTag($content, 'a');

$links = [];

for ($i = 0; $i < count($results); $i++)
{    
    $link = $results[$i]->getAttribute('href');
    if (str_contains($link, '/Archives/edgar'))
    {
        $link = 'https://www.sec.gov' . $link;
        $link = extractDOM(getHtmlContent($link));
        $link = extracHtmlByTag($link, 'a');

        $links[] = $link;
    }
}

$newLinks = [];

for ($j = 0; $j < count($links); $j++)
{
    $tempArray = [];
    $companySymbol = strtolower(getCompanySymbol($cikNumber));

    for ($k = 0; $k < count ($links[$j]); $k++)
    {
        //TODO: Remove the 'ix?doc=' to get a non-dynamic version of the filling.
        //TODO: Make this function work for other fillings, and not just 10ks   
        $l = $links[$j][$k]->getAttribute('href');

        if (str_contains($l, '/Archives/edgar/'))
        {
            if (str_contains($l, $companySymbol)
            or
            (str_contains($l, '10k'))
            or
            (str_contains($l, '10-k'))
            or
            (str_contains($l, '10k')))
            {
                $tempArray[] = 'https://www.sec.gov' . $l;
                break;
            }
        }        
    }
    $newLinks[] = $tempArray;
}

dd($newLinks);

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
