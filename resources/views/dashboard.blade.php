<?php 

require public_path('/services/6.getIndustryInformation.php');

use App\Models\CompanyIndustry;
use App\Models\Company;

// $cIndustry = CompanyIndustry::where('cik_number', 34088)->get();

$company = Company::where('stock_symbol', 'AMD')->get();
// $cIndustry = CompanyIndustry::where('id', $company->company_industry_id)->get();

dd($company->company_industry_id);



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
