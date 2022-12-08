<?php 

require_once app_path('Services/6.getIndustryInformation.php');
ini_set('max_execution_time', 8000);


$url = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=0001679788&type=&dateb=&owner=include&start=0&count=10";


// Apple    320193
// AMD      002488
// PFE      078003
$cikNumber = 78003;

//Gracias82@
// dd(getSICNumberbyCompany($cikNumber));


function getCIKCodes()
{
    return DB::select('SELECT cik_number FROM companies');
}
$cikCodes = getCIKCodes();

$sicCodes = [];

for ($i = 0; $i < 11642; $i++)
{
    $sicCodes[] = getSICNumberbyCompany($cikCodes[$i]->cik_number);
}

// // foreach ($cikCodes as $cik)
// // {
// //     $sicCodes[] = getSICNumberbyCompany($cikNumber);
// // }

file_put_contents('cik_sic_data.json', json_encode($sicCodes));
echo "Job done succesfully";





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
