<?php 

require_once app_path('Services/6.getIndustryInformation.php');
class CompanyClassificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(storage_path('cik_sic_data.json'));
        $companies = json_decode($json, true);

        foreach ($companies as $company)
        {
            CompanyClassification::query()->updateOrCreate([
                'sic_number' => $company['cik_str'],
                'cik_number' => $company['cik_number']
            ]);
        }
    }
}






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
