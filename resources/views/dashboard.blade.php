<?php 

$cikJson = file_get_contents(storage_path('cik_sic_data.json'));
$sicNumbers = json_decode($cikJson, true);

foreach ($sicNumbers as $numbers)
{
    foreach ($numbers as $cik => $sic)
    {
        dd($cik, $sic);
    }
}

// dd($sicNumbers[0]);







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
