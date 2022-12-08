<?php 

require_once app_path('Services/6.getIndustryInformation.php');
ini_set('max_execution_time', 15000);


$url = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=0001679788&type=&dateb=&owner=include&start=0&count=10";


// Apple    320193
// AMD      002488
// PFE      078003
$cikNumber = 78003;





$cikCodes = getCIKCodesfromDB();


function getSICCodes()
{
$cikCodes = getCIKCodesfromDB();

$sicCodes = [];


for ($i = 0; $i < count($cikCodes); $i++)
{
    $sicCodes[] = getSICNumberbyCompany($cikCodes[$i]->cik_number);
    break;
}

file_put_contents('cik_sic_data.json', json_encode($sicCodes));
echo "SIC codes extraction succesful";

return $sicCodes;
}


dd(getSICCodes());





?>

{{-- <!DOCTYPE html>

<title>Document</title>
<link rel="stylesheet" href="/app.css">

<body>

    <form action="/allFillings" method="GET">
        <label for="search">Search</label>
        <input type="text" id="search" name="search">
    </form>
    <div style="overflow-y:auto; height: 450px; width: 600px;overflow-wrap: break-word;">
        <table>
            <ol> --}}

<?php
                    //dd(route('allFillings'));
                    
                    // for ($i = 0; $i < count($allFillings); $i++)
                    // {
                    //     echo "<li>$allFillings[$i][0]</li>";
                    // }
                    
                ?>

{{-- @foreach ($allFillings as $filling)
                {
                    <li href="$filling[0]">{{ $filling[1] }}</li>;
}
@endforeach --}}
{{-- </ol>
        </table>
    </div>


</body> --}}
